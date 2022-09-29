<?php

namespace App\Http\Services;


use DB;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;
use GuzzleHttp\Client;




class OfferLogixService
{
    //private $_salespeople_cache_identifier = 'apistore.salespeople_';

    private $redis_vehicle;
    private $api_endpoints;
    private $api_payment_url;
    private $credit_scores = array('v_poor' => 400, 'poor' => 550, 'fair' => 630, 'good' => 750, 'excellent' => 820 );
    private $source = 16044;
    private $email = 'buildabrand@offerlogix.com';
    private $password = '16044bab';

    public function __construct()
    {
        $this->redis_vehicle = Redis::connection('vehicle');
        $this->api_endpoints = config('app.api_endpoints')[config('app.env')];
        $this->api_payment_url = 'https://stage.ttl.offerlogix.net/api/payments?Source='.$this->source.'&CDSID=412977&Vehicle.TransactionType=All&sxml=3&vehicle.dealerRebate=0&vehicle.manufacturerRebate=500';
        $this->api_rebates_url = 'https://www.offerlogix.net/quote/Offer_CSR.php?login_email='.$this->email.'&login_password='.$this->password;
    }

    private function getProviderId($d_id){
        $vclient = new Client(['verify' =>false]);
        $vresponse = $vclient->request('GET', $this->api_endpoints['azurewebsites'].'/api/inventorywidgets/get-dealer-inventory-settings/' . $d_id, [
            'headers' => [
                'Accept' => 'text/plain',
                'Content-Type' => 'application/json-patch+json'
            ],
            'body' => '{}'
        ]);

        $provider = json_decode($vresponse->getBody());
        if(!isset($provider->model->invetoryProviderType))return false;
        $provider_id = $provider->model->invetoryProviderType;
        return $provider_id;
    }

    private function findVehicleRedis($d_id, $v_id){

        $provider_id = $this->getProviderId($d_id);

        $cache_identifier = "vehicle_filters_".$d_id."_".$provider_id;


        if($this->redis_vehicle->exists($cache_identifier)){
            $return_format =  json_decode($this->redis_vehicle->get($cache_identifier), true);
            $response = false;
            foreach($return_format['vehicles'] as $index => $arr){
                if($arr['id'] == $v_id){
                    $response = $arr;
                    break;
                }
            }


            return $response;
        }

        return false;

    }

    public function getRebateByProgramId($program_id){
        $vclient = new Client(['verify' =>false]);

        $rebate = false;

        try{

            $response = $vclient->request('GET', $this->api_rebates_url.'&savefile=GetProgramByID_CA&Ver=v2.6&API_Data=GetProgramByID/'.$program_id.'?format=json', ['stream' => true]);
            // Read bytes off of the stream until the end of the stream is reached
            $body = $response->getBody();
            $str='';
            while (!$body->eof()) {
                $str .= $body->read(1024);
            }
            $rebate = json_decode($str, true);
            if(!is_array($rebate) || empty($rebate) || !isset($rebate['response']) || empty($rebate['response'])){
                $rebate = false;
            }

        }catch(\Exception $e){
            $rebate = false;
        }

        return $rebate;

    }

    public function getRebates($zip, $vin){
        $vclient = new Client(['verify' =>false]);

        $rebates = false;

        try{

            $response = $vclient->request('GET', $this->api_rebates_url.'&savefile=GetProgramByID_CA&Ver=v2.6&API_Data=findvehiclegroupsbyvehicleandpostalcode/1N4AL4CV2MN413675/38654/1,2,8?format=json', ['stream' => true]);
            // Read bytes off of the stream until the end of the stream is reached
            $body = $response->getBody();
            $str='';
            while (!$body->eof()) {
                $str .= $body->read(1024);
            }
            $rebates = json_decode($str, true);
            if(!is_array($rebates) || empty($rebates) || !isset($rebates['response']) || empty($rebates['response'])){
                $rebates = false;
            }

        }catch(\Exception $e){
            $rebates = false;
        }

        return $rebates;
    }

    public function getFinanceLease($parameters_array){
        $finance_added = false;
        $lease_added = false;

        $vehicle_array = $this->findVehicleRedis($parameters_array['d_id'], $parameters_array['vehicle_id']);
        if(!is_array($vehicle_array))return false;

        if(empty($vehicle_array['price']) || $vehicle_array['price'] < 1)return false;

        $vehicle_array['price'] = $vehicle_array['price'] + $parameters_array['payoff'];

        $vclient = new Client(['verify' =>false]);

        if($vehicle_array['mileage'] < 1 || empty($vehicle_array['mileage']))$vehicle_array['mileage']=1;

        $query = array();
        $query['Vin'] = $vehicle_array['vin'];
        $query['Odometer'] = $vehicle_array['mileage'];
        $query['Purchase'] = $vehicle_array['price'];
        $query['MSRP'] = $vehicle_array['price'];

        if( $parameters_array['downpayment'] == null ){
            $parameters_array['downpayment'] = number_format($vehicle_array['price'] * .10, 0, "","");
        }else if(!isset($parameters_array['downpayment']) || empty($parameters_array['downpayment'])){
            $parameters_array['downpayment'] = 0;
        }


        // $query = array(
        //     'Vin' => '1N4AL4CV2MN413675&CDSID',
        //     'Odometer' => 1,
        //     'Purchase' => 32615,
        //     'MSRP' => 33115,
        // );
        $query['Beacon'] = $this->credit_scores[$parameters_array['credit_score']];

        if(isset($parameters_array['zip_location']) && !empty($parameters_array['zip_location'])){
            $query['state'] = $parameters_array['zip_location']['state'];
            $query['city'] = $parameters_array['zip_location']['city'];
            $query['county'] = $parameters_array['zip_location']['county'];
        }else{
            $query['ZipCode'] = $parameters_array['zip'];
        }


        $query['vehicle.tradeInAmount'] = $parameters_array['tradein_amount'];

        $query['finance_term'] = (isset($parameters_array['finance_term']) && !empty($parameters_array['finance_term'])) ? $parameters_array['finance_term'] : null;
        $query['lease_term'] = (isset($parameters_array['lease_term']) && !empty($parameters_array['lease_term'])) ? $parameters_array['lease_term'] : null;
        $query['lease_miles'] = (isset($parameters_array['lease_miles']) && !empty($parameters_array['lease_miles'])) ? $parameters_array['lease_miles'] : null;
        $query['leaseDP'] = $parameters_array['downpayment'];
        $query['loanDP'] = $parameters_array['downpayment'];


        $rebates = $this->getRebates($parameters_array['zip'],$query['Vin']);
        $rebates_array = array();

        if($rebates){
            foreach($rebates['response'] as $rebate_index => $rebate_arr){
                if(isset($rebate_arr['cashDealScenarios']) && !empty($rebate_arr['cashDealScenarios'])){
                    foreach($rebate_arr['cashDealScenarios'] as $cash_index => $cash_arr){
                        $scenario_id = $cash_arr['dealScenarioTypeID'];
                        if(isset($cash_arr['consumerCash']) && !empty($cash_arr['consumerCash'])){
                            if(isset($cash_arr['consumerCash']['cashPrograms']) && !empty($cash_arr['consumerCash']['cashPrograms'])){

                                foreach($cash_arr['consumerCash']['cashPrograms'] as $cp_index => $cp_arr){

                                    if($cp_arr['financialInstitution'] == 'Open' && in_array($scenario_id,[1,2,8])){
                                        $rebate_cash = number_format($cp_arr['cash'],0,"","");
                                        $program_id = $cp_arr['cashProgramID'];
                                        $name = $cp_arr['programName'];
                                        $expiration = $cp_arr['stopDate'];
                                        $expiration_format = explode("T", $expiration);
                                        $ts = strtotime($expiration_format[0]);
                                        $rebate_info = array(
                                            'cash' => $rebate_cash,
                                            'program_id' => $program_id,
                                            'name' => $name,
                                            'expiration' => $ts

                                        );
                                        if($scenario_id == 1){
                                            $rebate_info['type'] = 'cash';
                                        }
                                        if($scenario_id == 2){
                                            $rebate_info['type'] = 'financing';
                                            $query['CustRebates'] = $rebate_cash;
                                        }
                                        if($scenario_id == 8){
                                            $rebate_info['type'] = 'leasing';
                                            $query['CustLeaseRebates'] = $rebate_cash;
                                        }

                                        $program_info = $this->getRebateByProgramId($program_id);
                                        if(isset($program_info['response']) && !empty($program_info['response'])){
                                            $rebate_info['disclaimer'] = $program_info['response']['consumer'];
                                            $rebate_info['category'] = $program_info['response']['category'];
                                            $rebate_info['title'] = $program_info['response']['title'];
                                        }


                                        $rebates_array[] = $rebate_info;

                                    }
                                }
                            }

                        }

                    }

                }

            }
        }




        $str = '';
        foreach($query as $field => $val){
            $str .= $field.'='.$val.'&';
        }
        $str = substr($str, 0, -1);
        $vresponse = $vclient->request('GET', $this->api_payment_url .'&'.$str, [
            'headers' => [
                'Accept' => 'text/plain'
                //'Content-Type' => 'application/json-patch+json'
            ]
        ]);
        // echo $this->api_payment_url .'&'.$str;
        $response = json_decode($vresponse->getBody()->getContents(), true);
        if(!isset($response['payments']) && !empty($response['payments']) ) return false;
        //dd($response);
        $finance_array = array();
        $leasing_array = array();
        $lease_terms = array();
        $finance_terms = array();
        $lease_miles = array();
        foreach($response['payments'] as $index => $arr){
            $type = $arr['type'];
            $payment = $arr['monthlyPayment'];



            if($type == "Loan"){

                if(isset($arr['monthlyPayment'])){
                    if(!in_array($arr['term'], $finance_terms)){
                        $finance_array['terms'][] = $arr['term'];
                        $finance_terms[] = $arr['term'];
                    }

                    if(!empty($query['finance_term'])){
                        if($query['finance_term'] != $arr['term']){
                            continue;
                        }
                    }

                    if( (isset($finance_array['monthlyPayment']) && $finance_array['monthlyPayment'] > $payment) || !isset($finance_array['monthlyPayment'])){
                        $finance_added = true;
                        $finance_array['monthlyPayment'] = $payment;
                        $finance_array['interestRate'] = $arr['interestRate'];
                        $finance_array['upfront'] = $arr['upfront'];
                        $finance_array['downPayment'] = $arr['downPayment'];
                        $finance_array['term'] = $arr['term'];
                        $finance_array['expirationDate'] = $arr['expirationDate'];
                        $finance_array['taxes'] = 0;
                        $finance_array['fees'] = 0;

                        if(isset($arr['atcFeeDetails']['feeDetails'])){
                            foreach($arr['atcFeeDetails']['feeDetails'] as $cost_index => $cost_arr){
                                if(preg_match("/Tax/i", $cost_arr['feeName'])){
                                    $tax_amount = $cost_arr['amount'];
                                    if($tax_amount > 0){
                                        $finance_array['taxes'] += $tax_amount;
                                    }

                                }
                                if(preg_match("/Fees/i", $cost_arr['feeName'])){
                                    $fee_amount = $cost_arr['amount'];
                                    if($fee_amount > 0){
                                        $finance_array['fees'] += $fee_amount;
                                    }

                                }
                            }

                        }


                    }
                }
            }else{
                if(!in_array($arr['term'], $lease_terms)){
                    $leasing_array['terms'][] = $arr['term'];
                    $lease_terms[] = $arr['term'];
                }

                if(!in_array($arr['mileageAllowed'], $lease_miles)){
                    $leasing_array['miles'][] = $arr['mileageAllowed'];
                    $lease_miles[] = $arr['mileageAllowed'];
                }



                if(!empty($query['lease_term'])){
                    if($query['lease_term'] != $arr['term']){
                        continue;
                    }
                }

                if(!empty($query['lease_miles'])){
                    if($query['lease_miles'] != $arr['mileageAllowed']){
                        continue;
                    }
                }

                if( (isset($leasing_array['monthlyPayment']) && $leasing_array['monthlyPayment'] > $payment) || !isset($leasing_array['monthlyPayment'])){
                    $lease_added = true;
                    $leasing_array['monthlyPayment'] = $payment;
                    $leasing_array['interestRate'] = $arr['interestRate'];
                    $leasing_array['upfront'] = $arr['upfront'];
                    $leasing_array['downPayment'] = $arr['downPayment'];
                    $leasing_array['term'] = $arr['term'];
                    $leasing_array['expirationDate'] = $arr['expirationDate'];
                    $leasing_array['mileageAllowed'] = $arr['mileageAllowed'];
                    $leasing_array['taxes'] = 0;
                    $leasing_array['fees'] = 0;

                    if(isset($arr['atcFeeDetails']['feeDetails'])){
                        foreach($arr['atcFeeDetails']['feeDetails'] as $cost_index => $cost_arr){
                            if(preg_match("/Tax/i", $cost_arr['feeName'])){
                                $tax_amount = $cost_arr['amount'];
                                if($tax_amount > 0){
                                    $leasing_array['taxes'] += $tax_amount;
                                }

                            }
                            if(preg_match("/Fees/i", $cost_arr['feeName'])){
                                $fee_amount = $cost_arr['amount'];
                                if($fee_amount > 0){
                                    $leasing_array['fees'] += $fee_amount;
                                }

                            }
                        }
                    }


                }
            }






        }
        //dd([$finance_array, $leasing_array]);

        if(!$lease_added || !$finance_added){
            return false;
        }



        $offerLogixData = array('financing' => $finance_array, 'leasing' => $leasing_array, 'rebates' => $rebates_array);

        $offerLogixData['zip_location'] = (isset($parameters_array['zip_location']) && !empty($parameters_array['zip_location'])) ? $parameters_array['zip_location'] : [];
        $offerLogixData['downpayment'] = $parameters_array['downpayment'];
        $offerLogixData['selected'] = $parameters_array['selected'];
        $offerLogixData['credit_score'] = ($parameters_array['selected'] == 'leasing') ? $parameters_array['lease_credit'] : ($parameters_array['selected'] == 'financing' ? $parameters_array['finance_credit'] : '' );
        $offerLogixData['lease_term'] = $parameters_array['lease_term'];
        $offerLogixData['payoff'] = $parameters_array['payoff'];
        $offerLogixData['lease_miles'] = $parameters_array['lease_miles'];
        $offerLogixData['finance_term'] = $parameters_array['finance_term'];
        $offerLogixData['zip'] = $parameters_array['zip'];
        $offerLogixData['zip_string'] = $parameters_array['zip_string'];

        //dd($offerLogixData);
       // ZipCode=33483&vehicle.transactionType=All&vehicle.dealerRebate=0&vehicle.manufacturerRebate=500&vehicle.tradeInAmount=2000
        return $offerLogixData;

    }




}
