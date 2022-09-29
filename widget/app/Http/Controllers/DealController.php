<?php

namespace App\Http\Controllers;


use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;
use App\Jobs\EventsTracking;
use Illuminate\Support\Facades\View;
use App\Http\Services\OfferLogixService;
use Illuminate\Support\Str;
use App\Http\Services\UtilsService;





use DB;
use App\Models\Deal;
use App\Models\Tracking;
use App\Models\Track;

use Carbon\Carbon;
use Illuminate\Support\Facades\Redirect;
use Validator;

use function PHPUnit\Framework\isJson;

class DealController extends Controller
{
    private $d_id = null;
    private $s_id = null;
    private $c_id = null;
    private $a_id = null;
    private $v_id = null;
    private $vin = null;
    private $deal_id = null;
    private $from = null;
    private $user_token = null; //token generated for each session/user when starting


    private $accessToken = null; //token for api client
    private $request;

    private $deal = null;
    private $dealer = null;
    private $salesperson = null;
    private $salespeople = null;
    private $vehicleFilters = null;
    private $filters = null;
    private $dealerTrendFilters = [];
    private $currentPage = '';
    private $percentage = 0;
    private $minSaved = 0;
    private $hours = null;
    private $daysClosed = null;
    private $beverages = null;
    private $entry = null;
    private $provider_id = null; //[Inventory Providers] 1 = dealertrend -  2 = vauto
    private $pagination_items_total = 18;
    private $pagination_page = 1;
    private $pagination_total_pages = null;
    private $temp_vehicles = [];

    private $selectedVehicle = null;
    private $vehicles = null;
    private $vehicle = null;
    private $contactCheck = false;
    private $tradeCheck = false;
    private $vehicleCheck = false;
    private $appointmentCheck = false;
    private $preapprovedCheck = false;
    private $paymentCheck = false;
    private $paCheckOne = false;
    private $paCheckTwo = false;
    private $api_endpoints = null;// local, beta, production
    private $debug_api = true;
    private $redis_salespeople;
    private $redis_salesperson;
    private $redis_queue;
    private $redis_vehicle;
    private $offer_logix;
    private $env;
    private $agent;
    private $is_mobile;
    private $utils_service;


    public function __construct(Request $request, UtilsService $utilsService){

        $this->request = $request;
        if($request->has('user_token'))$this->user_token = $request->user_token;
        if($request->has('d_id'))$this->d_id = $request->d_id;
        if($request->has('accessToken'))$this->accessToken = $request->accessToken;
        if($request->has('s_id'))$this->s_id = $request->s_id;
        if($request->has('c_id'))$this->c_id = $request->c_id;
        if($request->has('a_id'))$this->a_id = $request->a_id;
        if($request->has('v_id'))$this->v_id = $request->v_id;
        if($request->has('deal_id'))$this->deal_id = $request->deal_id;
        if($request->has('entry'))$this->entry = $request->entry;
        if($request->has('from'))$this->from = $request->from;
        if($request->has('vin'))$this->vin = $request->vin;
        $this->env = config('app.env');
        $this->api_endpoints = config('app.api_endpoints')[config('app.env')];
        $this->redis_salespeople = Redis::connection('salespeople');
        $this->redis_salesperson = Redis::connection('salesperson');
        $this->redis_queue = Redis::connection('queue');
        $this->redis_vehicle = Redis::connection('vehicle');
        $this->agent = new \Jenssegers\Agent\Agent;
        $this->is_mobile = $this->agent->isMobile();
        $this->utils_service = $utilsService;
        $this->filters = (object)[];


//        $l='[{"ipv4Prefix":"157.55.39.0/24"},{"ipv4Prefix":"207.46.13.0/24"},{"ipv4Prefix":"40.77.167.0/24"},{"ipv4Prefix":"13.66.139.0/24"},{"ipv4Prefix":"13.66.144.0/24"},{"ipv4Prefix":"52.167.144.0/24"},{"ipv4Prefix":"13.67.10.16/28"},{"ipv4Prefix":"13.69.66.240/28"},{"ipv4Prefix":"13.71.172.224/28"},{"ipv4Prefix":"139.217.52.0/28"},{"ipv4Prefix":"191.233.204.224/28"},{"ipv4Prefix":"20.36.108.32/28"},{"ipv4Prefix":"20.43.120.16/28"},{"ipv4Prefix":"40.79.131.208/28"},{"ipv4Prefix":"40.79.186.176/28"},{"ipv4Prefix":"52.231.148.0/28"},{"ipv4Prefix":"51.8.235.176/28"},{"ipv4Prefix":"51.105.67.0/28"}]';
//        $s = json_decode($l, true);
//        $f =[];
//        foreach($s as $i=>$arr){
//            if(isset($arr['ipv4Prefix'])){
//                $f[] = $arr['ipv4Prefix'];
//            }
//        }
//        echo json_encode($f);exit;

        // $params = array(
        //     'beacon' => 750,
        //     'zip' => 33483,
        //     'tradein_amount' => 3000,
        //     'vehicle_id' => 16220302,
        //     'd_id' => $this->d_id
        // );
        // $v_array =  $this->offer_logix->getFinanceLease($params);
        // dd($v_array);
        // exit;


       // EventsTracking::dispatch(array('message'=>'blue'));
       // exit;
      // dd($this->generateSasToken('https://beta-service-bus-main.servicebus.windows.net/','RootManageSharedAccessKey','8r8OWRTNu9+msARQlwwfEOfTcVJpyGs5m3EiqIe8/kY='));


        //dd(json_encode($f));
    }


    public function getStartSalesPersonFlow()
    {

        //Change the landing page coming for salescard widget to land on details page if mobile
        if($this->is_mobile && empty($this->entry) && $this->request->source == "MainWebSite" && !empty($this->s_id) ){

            return $this->getSalespersonDetail();
            exit;
        }

        $this->currentPage = 'sales_start';
        $deal = $this->_getDeal();

        //In salesperson flow, they don't have access to changing salesperson
        if($deal->entry != null && $deal->entry == 'salesperson' && !empty($this->s_id)){
            $this->_queueTracking("stop_flow_redirect","salesperson flow, sales_start no entry", $this->currentPage);
            return redirect('/salesperson-detail?user_token=' . $this->user_token . "&s_id=". $this->s_id);
        }


        $this->_getDealer();
        $this->_getSalesperson();
        $this->_getVehicle();
        $this->_getSalespeople();

        $this->_appointmentCheck();
        $this->_tradeCheck();
        $this->_paCheck();
        $this->_paymentCheck();
        $this->_contactCheck();


        //dd($this->salespeople);
        $salesperson = (!empty($this->salesperson) ? $this->salesperson->salesperson : "");
        if($salesperson == "" && isset($this->salespeople->salespeople[0]->s_id)){
            $this->_queueTracking("stop_flow_redirect","no s_id, picking top ranked, redirect with s_id placed", $this->currentPage);
            return redirect('/start-sales-person?user_token=' . $this->user_token . "&s_id=". $this->salespeople->salespeople[0]->s_id);

        }


        $salesperson_photos = (!empty($this->salesperson->photos) ? $this->salesperson->photos : []);
        $salesperson_reviews = (!empty($this->salesperson->reviews) ? $this->salesperson->reviews : []);
        $vehicle = (!empty($this->vehicle) ? $this->vehicle->model : "");
        $salesperson_available = ($salesperson == "") ? "false" : "true";

        $extraViewData = ['salesperson' => $salesperson,'salespeople' => $this->salespeople->salespeople,'vehicle' => $vehicle,
                            'sp_photos' => $salesperson_photos, 'sp_reviews' => $salesperson_reviews, 'salesperson_available'=>$salesperson_available];
        $this->_trackView();
        $this->_queueTracking("page_entry","showing page", $this->currentPage);
        return view('wizard.pages.start-sales-person')->with(array_merge($this->_getViewData(), $extraViewData));
    }




    public function skipSalesPersonStep(Request $request)
    {
        $deal = $this->_getDeal();
        $this->_queueTracking("page_skipped","skip page", 'Salesperson');
        return redirect('/vehicle-select?user_token=' . $this->user_token);

    }

    public function selectedSalesPerson(Request $request)
    {
        $deal = $this->_getDeal();
        $s_id = $this->request->s_id;

        $deal->s_id = $s_id;
        $deal->save();
        $this->_trackSubmission('salesperson', $s_id);
        $this->_queueTracking("salesperson_selected","selected salesperson");

        $this->_setSelectedVehicle();

        if($deal->vehicle_id == null){
            $this->_queueTracking("redirect","redirect page", "vehicle-select");
            return redirect('/vehicle-select?s_id=' . $s_id . '&user_token=' . $this->user_token);

        }else{
            $this->_queueTracking("redirect","redirect page", "schedule-appointment");
            return redirect('/schedule-appointment?user_token=' . $this->user_token);

        }

    }

    public function getSalespersonDetail()
    {
        $this->currentPage = 'sales';
        $this->_getDeal();
        $this->_getDealer();
        $this->_getSalesperson();
        $this->_getVehicle();
        //$this->_getSalespeople();

        $this->_appointmentCheck();
        $this->_tradeCheck();
        $this->_paCheck();
        $this->_paymentCheck();

        $salesperson = (!empty($this->salesperson) ? $this->salesperson->salesperson : "");
        $vehicle = (!empty($this->vehicle) ? $this->vehicle->model : "");
        $extraViewData = ['salesperson' => $salesperson,  'vehicle' => $vehicle,
                            'sp_photos' => $this->salesperson->photos, 'sp_reviews' => $this->salesperson->reviews];

        //dd($this->salesperson);
        $this->_trackView();
        $this->_queueTracking("page_entry","showing page", $this->currentPage);
        return view('wizard.pages.salesperson-detail')->with(array_merge($this->_getViewData(), $extraViewData));

    }

    public function getWelcomePage(Request $request)
    {


        $this->currentPage = 'welcome';
        $this->_getDeal();

        $this->_getDealer();

        $this->_getSalesperson();

        $this->_getVehicle();

        //$this->_getSalespeople();

        $this->_appointmentCheck();
        $this->_tradeCheck();
        $this->_paCheck();
        $this->_paymentCheck();
        $this->_contactCheck();

        $salesperson = (!empty($this->salesperson) ? $this->salesperson->salesperson : "");
        $vehicle = (!empty($this->vehicle) ? $this->vehicle->model : "");

        $welcome_info = $this->_welcomeInfo();

        $extraViewData = ['salesperson' => $salesperson, 'vehicle' => $vehicle, 'welcomeInfo' => $welcome_info];
        //session()->flash('message', 'Post successfully updated.');

        $this->_trackView();
        $this->_queueTracking("page_entry","showing page", $this->currentPage);
        return view('wizard.pages.navigation')->with(array_merge($this->_getViewData(), $extraViewData));
    }

    // Contact Information Steps
    public function getContactInformation(Request $request)
    {

        if($this->request->has('redirect'))
        {
            $previousPage = $request->redirect;
        }
        else
        {
            $previousPage = 'value-trade';
        }
        $this->currentPage = 'contact';
        $this->_getDeal();

        if($this->deal->s_id == null){
            $this->_queueTracking("stop_flow_redirect","missing s_id", $this->currentPage);
            return redirect('/start-sales-person?user_token=' . $this->user_token);
        }


        $this->_getDealer();
        $this->_getSalesperson();
        $this->_getVehicle();
        //$this->_getSalespeople();

        $this->_appointmentCheck();
        $this->_tradeCheck();
        $this->_paCheck();
        $this->_paymentCheck();
        $this->_contactCheck();



        $salesperson = (!empty($this->salesperson) ? $this->salesperson->salesperson : "");
        $vehicle = (!empty($this->vehicle) ? $this->vehicle->model : "");
        $extraViewData = ['salesperson' => $salesperson, 'vehicle' => $vehicle,  'previousPage' => $previousPage];

        $saved_time = 10;
        $extraViewData['saved_time'] = $saved_time;
        $extraViewData['progress_header'] = "You've completed contact information!";
        $extraViewData['progress_message1'] = "So there's a chance!";
        $extraViewData['progress_message2'] = "Much Appreciated.";
        $extraViewData['progress_text2'] = "Now let's get your Trade-In...";
        $extraViewData['progress_show'] = true;

        $this->_trackView();
        $this->_queueTracking("page_entry","showing page", $this->currentPage);
        return view('wizard.pages.contact-information')->with(array_merge($this->_getViewData(), $extraViewData));


    }

    public function submitContactInformation(Request $request)
    {
        $return = $this->_submitContract();
        $deal = $return[0];
        $bodyFilters = $return[1];


        $client = new Client(['verify' =>false,'base_uri' => $this->api_endpoints['toolkit']."/api/"]);
        $headers = ['Accept' => 'text/plain', 'Content-Type' => 'application/json-patch+json'];
        $body = "{" . rtrim($bodyFilters, ',') . "}";

        $this->_queueTracking("api_submit","contact_api", "contact processing");

        $response = $client->request('POST', 'ThirdPartyWidgets/createContact', [
            'headers' => $headers,
            'body' => $body
        ]);

        $return_array = [];
        if($this->debug_api){
            $return_array['body'] = $body;
        }

        if($response->getStatusCode() != 200)
        {
            $this->_queueTracking("api_submit_error","contact api error", "contact processing");
            //return response(['status' => 'Error When Passing Data'], 400);
            return $this->_400($return_array);
        }

        $data = json_decode($response->getBody());

        //return response([$data],400);

        if(!$data->success){
           // return response(['status' => 'Error Creating Data'], 400);
           $this->_queueTracking("api_submit_error","contact api response error", "contact processing");
           return $this->_400($return_array);

        }

        $deal->contact_id = $data->contactId;

        if(!$deal->save())
        {
            //return response(['status' => 'Error Saving Data'],400);
            $this->_queueTracking("api_database_error","contact database save error", "contact processing");
            return $this->_400($return_array);
        }

       $selected_vehicle =  $this->_setSelectedVehicle();

       if($this->debug_api){
        $return_array['selected_vehicle'] = $selected_vehicle;
       }

        $return_array['previousPage'] = $request->previousPage;
        $this->_trackSubmission('contact');
        $this->_queueTracking("api_submit_success","contact submit finished", "contact set");
        return $this->_ok($return_array);

    }

    // Value Trade Step
    public function getValueTrade()
    {

        $this->currentPage = 'trade';
        $this->_getDeal();
        $this->_getDealer();
        $this->_getSalesperson();
        $this->_getVehicle();
        //$this->_getSalespeople();

        $this->_appointmentCheck();
        $this->_tradeCheck();
        $this->_paCheck();
        $this->_paymentCheck();
        $this->_contactCheck();

        if($this->salesperson == ""){
            $this->_queueTracking("redirect","no salesperson", "value_trade");
            return redirect('/start-sales-person?user_token=' . $this->user_token );
        }

        if(!$this->contactCheck){
           # $this->_queueTracking("redirect","no contact", "value_trade");
           # return redirect('/contact-information?user_token=' . $this->user_token . '&previousPage=value-trade'.'&redirect=value-trade');
        }

        $salesperson = (!empty($this->salesperson) ? $this->salesperson->salesperson : "");
        $vehicle = (!empty($this->vehicle) ? $this->vehicle->model : "");
        $extraViewData = ['salesperson' => $salesperson, 'vehicle' => $vehicle];

        $saved_time = 20;
        $extraViewData['saved_time'] = $saved_time;
        $extraViewData['progress_header'] = "You've completed value trade!";
        $extraViewData['progress_message1'] = "Parting is such sweet sorrow!";
        $extraViewData['progress_message2'] = "";
        $extraViewData['progress_text2'] = "Personalized Payments - next stop!";
        $extraViewData['progress_show'] = true;

        $this->_trackView();
        $this->_queueTracking("page_entry","showing page", $this->currentPage);

        return view('wizard.pages.value-trade')->with(array_merge($this->_getViewData(), $extraViewData));
    }

    public function submitValueTrade(Request $request)
    {
        $return = $this->_submitTrade();
        $deal = $return[0];
        $bodyFilters = $return[1];

        $client = new Client(['verify' =>false,'base_uri' => $this->api_endpoints['testdrive'].'/api/']);
        $headers = ['Accept' => 'text/plain', 'Content-Type' => 'application/json-patch+json'];
        $body = json_encode($bodyFilters);
        //return response($body,400);
            $response = $client->request('POST', 'TradeIn/create', [
                'headers' => $headers,
                'body' => $body
            ]);

        $return_array = [];
        if($this->debug_api){
            $return_array['body'] = $body;
        }


        if($response->getStatusCode() != 200)
        {
            $this->_queueTracking("api_submit_error","value trade error", "value trade processing");
            return $this->_400($return_array);
        }
        $results = json_decode($response->getBody());
        if(isset($results->id)){
            $deal->trade_id = $results->id;
        }
        if(!$deal->save())
        {
            $this->_queueTracking("api_database_error","value trade database save error", "value trade processing");
            return $this->_400($return_array);
        }

        $this->_trackSubmission('trade');
        $this->_queueTracking("api_submit_success","value trade submit finished", "value trade set");
        return $this->_ok($return_array);
    }

    // Pre Approved Basic Step
    public function getPreApprovedBasic()
    {
        $this->currentPage = 'preapproved';
        $this->_getDeal();
        $this->_getDealer();
        $this->_getSalesperson();
        $this->_getVehicle();
        //$this->_getSalespeople();

        $this->_appointmentCheck();
        $this->_tradeCheck();
        $this->_paCheck();
        $this->_paymentCheck();
        $this->_contactCheck();

        if(!$this->contactCheck){
            #$this->_queueTracking("redirect","no contact", "preapproved_contact");
            #return redirect('/contact-information?user_token=' . $this->user_token . '&previousPage=appointment'.'&redirect=pre-approved');
        }

        if($this->salesperson == ""){
            $this->_queueTracking("redirect","no salesperson", "preapproved_contact");
            return redirect('/start-sales-person?user_token=' . $this->user_token );
        }


        $salesperson = (!empty($this->salesperson) ? $this->salesperson->salesperson : "");
        $vehicle = (!empty($this->vehicle) ? $this->vehicle->model : "");
        $extraViewData = ['salesperson' => $salesperson, 'vehicle' => $vehicle, 'paCheckOne' => $this->paCheckOne, 'paCheckTwo' => $this->paCheckTwo];

        $this->_trackView();
        $this->_queueTracking("page_entry","showing page", $this->currentPage);
        return view('wizard.pages.pre-approved-basic')->with(array_merge($this->_getViewData(), $extraViewData));

    }

    public function submitPreApprovedBasic(Request $request)
    {
        $deal = $this->_getDeal();
        $this->_contactCheck();

        $rules = array(
            'fname' => 'required',
            'lname' => 'required',
            'email' => 'required',
            'phone' => 'required',
            'birthday' => 'required',
            'address' => 'required',
            'city' => 'required',
            'state' => 'required',
            'zipcode' => 'required'
        );

        $validator = Validator::make($request->all(), $rules);

        if($validator->fails())
        {
            $this->_queueTracking("api_validation_error","preapproved contact", "preapproved contact processing");
            return response(['status' => 'Missing one or more required values'], 400);
        }

        $deal->fname = $request->fname;
        $deal->lname = $request->lname;
        $deal->mname = $request->mname;
        $deal->phone = $request->phone;
        $deal->email = $request->email;
        $deal->birthday = $request->birthday;
        $deal->address = $request->address;
        $deal->apt_unit = $request->apt_unit;
        $deal->city = $request->city;
        $deal->state = $request->state;
        $deal->zipcode = $request->zipcode;

        if(!$deal->save())
        {
            $this->_queueTracking("api_database_error","preapproved contact error", "preapproved contact processing");
            return response(['status' => 'Error Saving Data'],400);
        }

        $this->_queueTracking("api_submit_success","preapproved contact finished", "preapproved contact set");

        if($this->contactCheck == false){
            $this->_queueTracking("api_contact_process","preapproved creating contact", "preapproved contact set");
            $this->request->text_opt_in = 1;
            $this->submitContactInformation($this->request);
        }



        return response(['status' => 'Success'], 200);
    }

    // Pre Approved Basic Step

    public function getPreApprovedEmployee()
    {

        $this->currentPage = 'preapproved';
        $this->_getDeal();
        $this->_getDealer();
        $this->_getSalesperson();
        $this->_getVehicle();
        //$this->_getSalespeople();

        $this->_appointmentCheck();
        $this->_tradeCheck();
        $this->_paCheck();
        $this->_paymentCheck();
        $this->_contactCheck();

        $salesperson = (!empty($this->salesperson) ? $this->salesperson->salesperson : "");
        $vehicle = (!empty($this->vehicle) ? $this->vehicle->model : "");
        $extraViewData = ['salesperson' => $salesperson, 'vehicle' => $vehicle, 'paCheckOne' => $this->paCheckOne, 'paCheckTwo' => $this->paCheckTwo];

        $saved_time = 40;
        $extraViewData['saved_time'] = $saved_time;
        $extraViewData['progress_header'] = "You've completed your pre-approval!";
        $extraViewData['progress_message1'] = "I'll make him a deal he can't refuse!";
        $extraViewData['progress_message2'] = "";
        $extraViewData['progress_text2'] = "You are virtually driving this home today!";
        $extraViewData['progress_show'] = true;
        $this->_queueTracking("page_entry","showing page", $this->currentPage.' employment');
        $this->_trackView();
        return view('wizard.pages.pre-approved-employment')->with(array_merge($this->_getViewData(), $extraViewData));

    }

    public function submitPreApprovedEmployee(Request $request)
    {


        $rules = array(
            'employment_status' => 'required',
            'job_title' => 'required',
            'company_name' => 'required',
            'company_address' => 'required',
            'company_phone' => 'required',
            'years_company' => 'required',
            'months_company' => 'required',
            'income' => 'required',
            'rent_own' => 'required',
            'rent_own_amount' => 'required',
            'years_address' => 'required',
            'months_address' => 'required'
            //'dl_no' => 'required',
            //'dl_state' => 'required',
            //'ssn' => 'required'
        );

        $validator = Validator::make($request->all(), $rules);

        if($validator->fails())
        {
            $this->_queueTracking("api_validation_error","preapproved employment", "preapproved employment processing");
            return response(['status' => 'Missing one or more required values','error' => $validator->errors()], 400);
        }

        $deal = $this->_getDeal();
        $deal->employment_status = $request->employment_status;
        $deal->job_title = $request->job_title;
        $deal->company_name = $request->company_name;
        $deal->company_address = $request->company_address;
        $deal->company_phone = $request->company_phone;
        $deal->years_company = $request->years_company;
        $deal->months_company = $request->months_company;
        $deal->income = $request->income;
        $deal->rent_own = $request->rent_own;
        $deal->rent_own_amount = $request->rent_own_amount;
        $deal->years_address = $request->years_address;
        $deal->months_address = $request->months_address;

        $return = $this->_submitApprovalEmployee($deal);
        $deal = $return[0];
        $bodyFilters = $return[1];



        $client = new Client(['verify' =>false,'base_uri' => $this->api_endpoints['testdrive'].'/api/']);
        $headers = ['Accept' => 'text/plain', 'Content-Type' => 'application/json-patch+json'];
        $body = "{" . rtrim($bodyFilters, ',') . "}";

        //return response([$body], 400);


        $response = $client->request('POST', 'PreApproved/create', [
            'headers' => $headers,
            'body' => $body
        ]);

        $return_array = [];
        if($this->debug_api){
            $return_array['body'] = $body;
        }


        if($response->getStatusCode() != 200)
        {
            $this->_queueTracking("api_submit_error","preapproved employment error", "preapproved employment processing");

            return $this->_400($return_array);
        }

        $rsp = json_decode($response->getBody());
        $deal->preapproved_id = $rsp->id;
        if(!$deal->save())
        {
            $this->_queueTracking("api_database_error","preapproved employent error", "preapproved employment processing");

            return $this->_400($return_array);
        }

        $this->_queueTracking("api_submit_success","preapproved employment success", "preapproved employment success");
        $this->_trackSubmission('credit');

        return $this->_ok($return_array);
    }

    public function getAppointment()
    {

        $this->currentPage = 'appointment';
        $this->_getDeal();
         $this->_contactCheck();

         if(!$this->contactCheck){
            #$this->_queueTracking("redirect","no contact", "appointment_page");

            #return redirect('/contact-information?user_token=' . $this->user_token . '&previousPage=appointment'.'&redirect=schedule-appointment');
        }

        if($this->deal->s_id == null){
            $this->_queueTracking("redirect","no salesperson", "appointment_page");

            return redirect('/start-sales-person?user_token=' . $this->user_token);
        }


        $this->_getDealer();
        $this->_getSalesperson();
        $this->_getVehicle();
        //$this->_getSalespeople();

        $this->_appointmentCheck();
        $this->_tradeCheck();
        $this->_paCheck();
        $this->_paymentCheck();




        $this->_getHours();
        $this->_getDaysClosed();
        $this->_getBeverages();

        $salesperson = (!empty($this->salesperson) ? $this->salesperson->salesperson : "");
        $vehicle = (!empty($this->vehicle) ? $this->vehicle->model : "");
        $extraViewData = ['salesperson' => $salesperson, 'vehicle' => $vehicle, 'hours' => $this->hours, 'daysClosed' => $this->daysClosed, 'beverages' => $this->beverages];

        $saved_time = 30;
        $extraViewData['saved_time'] = $saved_time;
        $extraViewData['progress_header'] = "You've completed your appointment!";
        $extraViewData['progress_message1'] = "Boo yah!";
        $extraViewData['progress_message2'] = "Let's drive!";
        $extraViewData['progress_text2'] = "I'm rolling out the red carpet!";
        $extraViewData['progress_show'] = true;

        $this->_trackView();
        $this->_queueTracking("page_entry","showing page", $this->currentPage);
        return view('wizard.pages.appointment')->with(array_merge($this->_getViewData(), $extraViewData));
    }

    public function submitAppointment(Request $request)
    {
        $return = $this->_submitAppointment();
        $deal = $return[0];
        $bodyFilters = $return[1];

        //return response('{'.$bodyFilters.'}', 400);
        $client = new Client(['base_uri' => $this->api_endpoints['testdrive'].'/api/','verify' =>false]);
        $headers = ['Accept' => 'text/plain', 'Content-Type' => 'application/json-patch+json'];
        $body = "{" . rtrim($bodyFilters, ',') . "}";


        $response = $client->request('POST', 'TestDrive/create', [
            'headers' => $headers,
            'body' => $body
        ]);

        $return_array = [];
        if($this->debug_api){
            $return_array['body'] = $body;
        }

        if($response->getStatusCode() != 200)
        {
            $this->_queueTracking("api_validation_error","appointment", "appointment processing");

            return $this->_400($return_array);
        }

        $body = json_decode($response->getBody());
        if(isset($body->id) && !empty($body->id)){
            $deal->td_id = $body->id;
        }

        if(!$deal->save())
        {
            return $this->_400($return_array);
        }

        $this->_trackSubmission('appointment');
        return $this->_ok($return_array);
    }

    public function getVehiclePage(Request $request)
    {

        $this->currentPage = 'vehicle';
        $this->_getDeal();
        $this->_getDealer();
        $this->_getSalesperson();
        $this->_getVehicle();
       // $this->_getSalespeople();

        $this->_appointmentCheck();
        $this->_tradeCheck();
        $this->_paCheck();
        $this->_paymentCheck();
        $this->_contactCheck();

        $this->_getVehicleFilters();
        $this->_getVehiclesCache();
        $pagination = $this->_getPagination();

        $salesperson = (!empty($this->salesperson) ? $this->salesperson->salesperson : "");
        $vehicle = (!empty($this->vehicle) ? $this->vehicle->model : "");
        $is_offerlogix = $this->_offerlogixActivated();
        $extraViewData = ['salesperson' => $salesperson, 'hideNav'=> 'vehicle_filter','is_offerlogix'=>$is_offerlogix, 'vehicle' => $vehicle, 'filters' => $this->filters, 'leasing_json'=>json_encode($this->filters->leasing), 'financing_json'=>json_encode($this->filters->financing), 'vehicles' => $this->vehicles, 'pagination' => $pagination,'p'=>$this->pagination_page, 'totalPages'=>$this->pagination_total_pages];

        $this->_trackView();
        $this->_queueTracking("page_entry","showing page", $this->currentPage);
        return view('wizard.pages.vehicle')->with(array_merge($this->_getViewData(), $extraViewData));

    }

    public function getVehicleDetailPage()
    {

        $this->currentPage = 'vehicle';

        $this->_getDeal();
        $this->_getDealer();
        $this->_getSalesperson();
        $this->_getVehicle();
        //$this->_getSalespeople();
        $this->_appointmentCheck();
        $this->_tradeCheck();
        $this->_paCheck();
        $this->_paymentCheck();
        $this->_contactCheck();

        $this->_getSelectedVehicle($this->request->vehicle_id);


        $salesperson = (!empty($this->salesperson) ? $this->salesperson->salesperson : "");
        $selectedVehicle = (!empty($this->selectedVehicle) ? $this->selectedVehicle->model : "");

        $categories = array();
        foreach($selectedVehicle->categorizedOptions as $op){

            $xplode = explode("@", $op);
            if(count($xplode) == 2){
                $categories[$xplode[0]][] = $xplode[1];
            }
        }


        $extraViewData = ['salesperson' => $salesperson, 'vehicle' => $selectedVehicle, 'categories' => $categories];
        $saved_time = 20;
        $extraViewData['saved_time'] = $saved_time;
        $extraViewData['progress_header'] = "You've completed customizing your payment!";
        $extraViewData['progress_message1'] = 'Awesome Sauce!';
        $extraViewData['progress_message2'] = "You're doing great.";
        $extraViewData['progress_text2'] = "You are really on top of this!";
        $extraViewData['progress_show'] = true;
        $offerlogix_array = false;
        $extraViewData['offerlogix_object_all'] = '{}';
        $extraViewData['offerlogix'] = $offerlogix_array;
        $extraViewData['offerlogix_settings'] = array();
        if($this->_offerlogixActivated()){
            $check_loan = false;
            $offerlogix_settings = array();
            $check_loan = json_decode($this->deal->offerlogix_json, true);

            if(!empty($check_loan) && is_array($check_loan) && isset($check_loan['selected']) && $this->deal->vehicle_id == $this->request->vehicle_id){
                $offerlogix_array =  $check_loan['offerlogix'];
                unset($check_loan['offerlogix']);
                $offerlogix_settings = $check_loan;
                $extraViewData['offerlogix_settings'] = $offerlogix_settings;
            }else{
                $offerlogix_array = false;
                $this->_setProviderId();
                $cache_identifier = "vehicle_filters_".$this->d_id."_".$this->provider_id;
                if($this->redis_vehicle->exists($cache_identifier)){
                    $return_format =  json_decode($this->redis_vehicle->get($cache_identifier), true);
                    foreach($return_format['vehicles'] as $index => $v_arr){
                        if($v_arr['id'] == $this->request->vehicle_id){
                            if(isset($v_arr['offerlogix']) && !empty($v_arr['offerlogix'])){
                                $offerlogix_array =  $v_arr['offerlogix'];
                            }
                        }
                    }
                }

                $this->deal->offerlogix_json = '{}';
                $this->deal->save();
            }

            if(is_array($offerlogix_array) && !empty($offerlogix_array)){

                $offerlogix_object_all = json_encode($offerlogix_array);
                $extraViewData['offerlogix_object_all'] = $offerlogix_object_all;
                $credit_selected = false;
                $available_credit_types = array();
                if(!empty($offerlogix_array['poor']))$available_credit_types[] = 'poor';
                if(!empty($offerlogix_array['fair']))$available_credit_types[] = 'fair';
                if(!empty($offerlogix_array['good']))$available_credit_types[] = 'good';
                if(!empty($offerlogix_array['excellent']))$available_credit_types[] = 'excellent';
                $extraViewData['available_credit_types'] = $available_credit_types;

                if(!empty($offerlogix_settings)){
                    if($offerlogix_settings['credit_selected'] == 'good'){
                        $extraViewData['offerlogix'] = $offerlogix_array['good'];
                        $offerlogix_object = json_encode($offerlogix_array['good']);
                        $extraViewData['offerlogix_object'] = $offerlogix_object;
                        $credit_selected = 'good';

                    }else if($offerlogix_settings['credit_selected'] == 'excellent'){
                        $extraViewData['offerlogix'] = $offerlogix_array['excellent'];
                        $offerlogix_object = json_encode($offerlogix_array['excellent']);
                        $extraViewData['offerlogix_object'] = $offerlogix_object;
                        $credit_selected = 'excellent';
                    }else if($offerlogix_settings['credit_selected'] == 'fair'){
                        $extraViewData['offerlogix'] = $offerlogix_array['fair'];
                        $offerlogix_object = json_encode($offerlogix_array['fair']);
                        $extraViewData['offerlogix_object'] = $offerlogix_object;
                        $credit_selected = 'fair';
                    }else if($offerlogix_settings['credit_selected'] == 'poor'){
                        $extraViewData['offerlogix'] = $offerlogix_array['poor'];
                        $offerlogix_object = json_encode($offerlogix_array['poor']);
                        $extraViewData['offerlogix_object'] = $offerlogix_object;
                        $credit_selected = 'poor';
                    }else{

                    }
                    $offerlogix_settings['type'] = 'set';
                }else{
                    if(!empty($offerlogix_array['good'])){
                        $extraViewData['offerlogix'] = $offerlogix_array['good'];
                        $offerlogix_object = json_encode($offerlogix_array['good']);
                        $extraViewData['offerlogix_object'] = $offerlogix_object;
                        $credit_selected = 'good';
                    }else if(!empty($offerlogix_array['excellent'])){
                        $extraViewData['offerlogix'] = $offerlogix_array['excellent'];
                        $offerlogix_object = json_encode($offerlogix_array['excellent']);
                        $extraViewData['offerlogix_object'] = $offerlogix_object;
                        $credit_selected = 'excellent';
                    }else if(!empty($offerlogix_array['fair'])){
                        $extraViewData['offerlogix'] = $offerlogix_array['fair'];
                        $offerlogix_object = json_encode($offerlogix_array['fair']);
                        $extraViewData['offerlogix_object'] = $offerlogix_object;
                        $credit_selected = 'fair';
                    }else if(!empty($offerlogix_array['poor'])){
                        $extraViewData['offerlogix'] = $offerlogix_array['poor'];
                        $offerlogix_object = json_encode($offerlogix_array['poor']);
                        $extraViewData['offerlogix_object'] = $offerlogix_object;
                        $credit_selected = 'poor';
                    }else{

                    }

                    $items = $extraViewData['offerlogix'];
                    $offerlogix_settings['selected'] = 'finance';
                    $offerlogix_settings['credit_selected'] = $items['credit_score'];
                    $offerlogix_settings['downpayment'] = $items['financing']['downPayment'];
                    $offerlogix_settings['payoff'] = "false";
                    $offerlogix_settings['tradein'] = "false";
                    $offerlogix_settings['tradein_options'] = "false";
                    $offerlogix_settings['finance_term'] = $items['financing']['term'];
                    $offerlogix_settings['type'] = 'unset';



                }
                $extraViewData['offerlogix_settings'] = $offerlogix_settings;
                $extraViewData['credit_selected'] = $credit_selected;
                $extraViewData['offerlogix_settings_json'] = json_encode($offerlogix_settings);
                if($credit_selected){
                    sort($extraViewData['offerlogix']['financing']['terms']);
                    if(isset($extraViewData['offerlogix']['leasing']['terms'])){
                      sort($extraViewData['offerlogix']['leasing']['terms']);
                    }


                }

                $this->_queueTracking("offerlogix_payments","showing page", $this->currentPage);
                $this->_trackView();
                $this->_queueTracking("page_entry","showing page", $this->currentPage);
                return view('wizard.pages.vehicle-detail')->with(array_merge($this->_getViewData(), $extraViewData));


            }


        }

        $this->_trackView();
        $this->_queueTracking("page_entry","showing page", $this->currentPage);
        return view('wizard.pages.vehicle-detail2')->with(array_merge($this->_getViewData(), $extraViewData));


    }

    public function removeTrade(){
        $deal = $this->_getDeal();
        $deal->trade_payoff = 0;
        $deal->trade_value = 0;
        $offerlogix = json_decode($this->deal->offerlogix_json,true);
        $offerlogix['tradein_options']['payoff'] = null;
        $offerlogix['tradein_options']['tradein'] = null;
        $deal->offerlogix_json = json_encode($offerlogix);

        if(!$deal->save())
        {

            return $this->_400();
        }

        return $this->_ok();

    }


    public function getVehicleDetailPageBk()
    {

        $this->currentPage = 'vehicle';
        $this->_getDeal();
        $this->_getDealer();
        $this->_getSalesperson();
        $this->_getVehicle();
       // $this->_getSalespeople();

        $this->_appointmentCheck();
        $this->_tradeCheck();
        $this->_paCheck();
        $this->_paymentCheck();
        $this->_contactCheck();

        $this->_getSelectedVehicle($this->request->vehicle_id);

        $salesperson = (!empty($this->salesperson) ? $this->salesperson->salesperson : "");
        $vehicle = (!empty($this->vehicle) ? $this->vehicle->model : "");

        $extraViewData = ['salesperson' => $salesperson, 'vehicle' => $vehicle, 'selectedVehicle' => $this->selectedVehicle];

        $saved_time = 10;
        $extraViewData['saved_time'] = $saved_time;
        $extraViewData['progress_header'] = "You've completed picking your vehicle!";
        $extraViewData['progress_message1'] = 'Nice choice!';
        $extraViewData['progress_message2'] = "That's one fine ride.";
        $extraViewData['progress_text2'] = "Now let's get some info...";
        $extraViewData['progress_show'] = true;
        $extraViewData['offerlogix'] = false;


        if($this->_offerlogixActivated()){
            $v_id = $this->request->vehicle_id;
            $check_loan = json_decode($this->deal->offerlogix_json, true);


            if(!empty($check_loan) && is_array($check_loan) && isset($check_loan['selected']) && $v_id == $check_loan['vehicle_id']){
                $offerlogix_array =  $check_loan;
                $extraViewData['offerlogix'] = $offerlogix_array;
            }else{
                $offerlogix_array = false;
                $this->_setProviderId();
                $cache_identifier = "vehicle_filters_".$this->d_id."_".$this->provider_id;
                if($this->redis_vehicle->exists($cache_identifier)){
                    $return_format =  json_decode($this->redis_vehicle->get($cache_identifier), true);
                    foreach($return_format['vehicles'] as $index => $v_arr){
                        if($v_arr['id'] == $v_id){
                            if(isset($v_arr['offerlogix']) && !empty($v_arr['offerlogix'])){
                                $offerlogix_array =  $v_arr['offerlogix'];
                                $extraViewData['offerlogix'] = $offerlogix_array;
                                $this->_queueTracking("offerlogix_vehicle","showing page", $this->currentPage);

                            }
                        }
                    }
                }
            }
        }


        $this->_trackView();
        $this->_queueTracking("page_entry","showing page", $this->currentPage);
        return view('wizard.pages.vehicle-detail')->with(array_merge($this->_getViewData(), $extraViewData));
    }

    public function submitVehicle(Request $request)
    {
        $deal = $this->_getDeal();
        $data = $request->post();
        $offerlogix = array();
        $deal->vehicle_id = $data['v_id'];
        $deal->vehicle_stock = $data['v_stock'];
        $tradein = (!empty($data['tradein']) && $data['tradein'] > 0) ? $data['tradein'] : null;
        $payoff = (!empty($data['payoff']) && $data['payoff'] > 0) ? $data['payoff'] : null;

        $selected = $data['selected'];
        if($selected == 'finance' || $selected == 'lease'){
            $deal->trade_value = $tradein;
            $deal->trade_payoff = $payoff;
            $offerlogix['vehicle_id'] = $data['v_id'];
            $offerlogix['offerlogix'] = json_decode($data['offerlogix'], true);
            $offerlogix['selected'] = $selected;
            $offerlogix['credit_selected'] = $data['credit_selected'];
            $offerlogix['zip'] = $data['zip'];
            $offerlogix['downpayment'] = $data['downpayment'];
            $offerlogix['tradein_options'] = json_decode($data['tradein_options'], true);

            if ($selected == 'finance') {
                $offerlogix['finance_term'] = $data['finance_term'];
            }
            if ($selected == 'lease') {
                $offerlogix['lease_term'] = $data['lease_term'];
                $offerlogix['lease_miles'] = $data['lease_miles'];
            }
        }

        $error = false;

        if(!empty($offerlogix)){
            try{
                $json = json_encode($offerlogix);
            }catch(\Exception $e){
                $error = true;
            }
        }else{
            $json = '{}';
        }



        if($error){
            $this->_queueTracking("api_offerlogix_error","saving offerlogix", "offerlogix processing");

            return response('failure', 400);
        }else{
            $this->deal->offerlogix_json = $json;
        }

        $return_array = [];


        if(!$deal->save())
        {
            $this->_queueTracking("api_database_error","vehicle", "vehicle processing");

            return $this->_400($return_array);
        }

        $selected_vehicle = $this->_setSelectedVehicle();

        if($this->debug_api){
            $return_array['body'] =  $selected_vehicle;
        }


        return $this->_ok($return_array);
    }

    public function test(){
        return view('wizard.pages.test');

    }

    public function getPaymentsPage()
    {

        $this->currentPage = 'payments';

        $this->_getDeal();
        $this->_getDealer();
        $this->_getSalesperson();
        $this->_getVehicle();
        //$this->_getSalespeople();

        $this->_appointmentCheck();
        $this->_tradeCheck();
        $this->_paCheck();
        $this->_paymentCheck();
        $this->_contactCheck();

         if(!$this->contactCheck){
           # $this->_queueTracking("redirect","no contact", "payments page");

           # return redirect('/contact-information?user_token=' . $this->user_token . '&previousPage=payments'.'&redirect=payments');
        }

        //  if($this->vehicle == ""){
        //      return redirect('/vehicle-select?accessToken=' . $this->accessToken . '&d_id=' . $this->d_id.'&deal_id='.$this->deal_id );
        //  }

        //dd($this->deal);

        $salesperson = (!empty($this->salesperson) ? $this->salesperson->salesperson : "");
        $vehicle = (!empty($this->vehicle) ? $this->vehicle->model : "");
        $extraViewData = ['salesperson' => $salesperson, 'vehicle' => $vehicle];
        $saved_time = 20;
        $extraViewData['saved_time'] = $saved_time;
        $extraViewData['progress_header'] = "You've completed customizing your payment!";
        $extraViewData['progress_message1'] = 'Awesome Sauce!';
        $extraViewData['progress_message2'] = "You're doing great.";
        $extraViewData['progress_text2'] = "You are really on top of this!";
        $extraViewData['progress_show'] = true;

        if($this->_offerlogixActivated()){
            $check_loan = json_decode($this->deal->offerlogix_json, true);


            if(!empty($check_loan) && is_array($check_loan) && isset($check_loan['selected']) && $this->deal->vehicle_id == $check_loan['vehicle_id']){
                $offerlogix_array =  $check_loan;
            }else{
                $offerlogix_array = false;
                $this->_setProviderId();
                $cache_identifier = "vehicle_filters_".$this->d_id."_".$this->provider_id;
                if($this->redis_vehicle->exists($cache_identifier)){
                    $return_format =  json_decode($this->redis_vehicle->get($cache_identifier), true);
                    foreach($return_format['vehicles'] as $index => $v_arr){
                        if($v_arr['id'] == $this->deal->vehicle_id){
                            if(isset($v_arr['offerlogix']) && !empty($v_arr['offerlogix'])){
                                $offerlogix_array =  $v_arr['offerlogix'];
                            }
                        }
                    }
                }

                $this->deal->offerlogix_json = '{}';
                $this->deal->save();

                if(empty($offerlogix_array)){
                    $trade_value = (!empty($this->deal->trade_value)) ? $this->deal->trade_value : 0;
                    $dealer_zip = $this->dealer->zip;
                    $downpayment = null; // number_format(32615 * .10,0,"","");
                    $params = array(
                        'credit_score' => 'good',
                        'zip' => $dealer_zip,
                        'zip_string' => '',
                        'tradein_amount' => $trade_value,
                        'vehicle_id' => $this->deal->vehicle_id,
                        'downpayment' => $downpayment,
                        'payoff' =>  0,
                        'selected' => 'financing',
                        'd_id' => $this->d_id,
                        'lease_term' => 0,
                        'finance_term' => 0,
                        'lease_miles' => 0,
                        'zip_location' => [],
                        'lease_credit' => 'good',
                        'finance_credit' => 'good',
                        'accessToken' => $this->accessToken
                    );


                    try{
                        $client = new Client(['verify'=>false,'base_uri' => $this->api_endpoints['buildabrand']]);
                        $response = $client->request('POST', '/getOfferlogixLoan', [
                            'headers' => [
                                'Accept' => 'text/plain',
                                'Content-Type' => 'application/json-patch+json'
                            ],
                            'body' => json_encode($params)
                        ]);

                        if($response->getStatusCode() == 200){
                            $offerlogix_array = json_decode($response->getBody(), true);

                            //$offerlogix_array_object = json_encode($offerlogix);
                            //return response($response->getBody(), 200);
                        }
                    }catch(\Exception $e){

                    }


                }
            }

            if(is_array($offerlogix_array) && !empty($offerlogix_array)){

                $offerlogix_object = json_encode($offerlogix_array);
                $extraViewData['offerlogix_object'] = $offerlogix_object;
                $extraViewData['offerlogix'] = $offerlogix_array;
                $this->_queueTracking("offerlogix_payments","showing page", $this->currentPage);

                $this->_trackView();
                $this->_queueTracking("page_entry","showing page", $this->currentPage);
                return view('wizard.pages.payments_offerlogix')->with(array_merge($this->_getViewData(), $extraViewData));


            }


        }

        $this->_trackView();
        $this->_queueTracking("page_entry","showing page", $this->currentPage);
        return view('wizard.pages.payments')->with(array_merge($this->_getViewData(), $extraViewData));


    }

    public function submitPaymentsPage(Request $request)
    {
        $deal = $this->_getDeal();



        $body = '{
            "ContactId": '.$deal->contact_id.',';
            if(!empty($deal->vehicle_id)){
                $body .= '"VehicleId": '.$deal->vehicle_id.',';
            }

        $body .= '"TradeInValue": '.$request->payment_trade_value.',
            "DownPayment": '.$request->payment_down_payment.',
            "InterestRate": '.$request->payment_interest_rate.',
            "Term": '.$request->payment_term.',
            "VehicleStockNumber": "'.$deal->vehicle_stock.'",
            "Price": '.$request->payment_price.',
            "MonthlyPayment": '.$request->payment_monthly.',
            "TotalFinanced": '.$request->payment_total.'
        }';

        $client = new Client(['verify' =>false,'base_uri' => $this->api_endpoints['testdrive'].'/api/']);
        $headers = ['Accept' => 'text/plain', 'Content-Type' => 'application/json-patch+json'];

        $response = $client->request('POST', 'PaymentCalculator/create', [
            'headers' => $headers,
            'body' => $body
        ]);

        $return_array = [];
        if($this->debug_api){
            $return_array['body'] = $body;
        }


        if($response->getStatusCode() != 200)
        {
            $this->_queueTracking("api_submit_error","payments", "payments processing");

            return $this->_400($return_array);
        }





        $deal->payment_monthly = $request->payment_monthly;
        $deal->payment_total = $request->payment_total;
        $deal->payment_down_payment = $request->payment_down_payment;
        $deal->payment_interest_rate = $request->payment_interest_rate;
        $deal->payment_trade_value = $request->payment_trade_value;
        $deal->payment_price = $request->payment_price;
        $deal->payment_term = $request->payment_term;
        $deal->trade_payoff = $request->payment_trade_payoff;

        if(!$deal->save())
        {
            $this->_queueTracking("api_database_error","payments", "payments processing");

            return $this->_400($return_array);
        }

        $this->_trackSubmission('payment');
        return $this->_ok($return_array);
    }


    public function getSummary()
    {
        $this->currentPage = 'summary';
        $this->_getDeal();
        $this->_getDealer();
        $this->_getSalesperson();
        $this->_getVehicle();
        //$this->_getSalespeople();

        $this->_appointmentCheck();
        $this->_tradeCheck();
        $this->_paCheck();
        $this->_paymentCheck();
        $this->_contactCheck();

        if(!$this->contactCheck){
            #$this->_queueTracking("redirect","no contact", "summary page");
            #return redirect('/contact-information?user_token=' . $this->user_token . '&previousPage=trade'.'&redirect=summary');
        }

        if(isset($this->vehicle->model->photos[0]->small)){
            $photo = $this->vehicle->model->photos[0]->small;
        }else{
            $photo = "";
        }

        if(isset($this->vehicle->model->price)){
            $display_price = number_format($this->vehicle->model->price, 2, '.', ',');
        }else{
            $display_price = 'N/A';
        }

        if($this->appointmentCheck){
            $date_display = date("l",strtotime($this->deal->td_date)).' '.str_replace('/','-', $this->deal->td_date).' at '.$this->deal->td_time.' '.strtoupper($this->deal->td_period);

        }else{
           $date_display = "N/A";
        }

        if($this->salesperson != ""){
            $sales_number_format = substr($this->salesperson->salesperson->IndividualPhoneNumber, -10);
            $number1 = substr($sales_number_format, 0, 3);
            $number2 = substr($sales_number_format, 3, 3);
            $number3 = substr($sales_number_format, 6, 4);
            $sales_number_display = "(".$number1.") ".$number2."-".$number3;
        }else{

            $sales_number_display = "N/A";
        }


        $salesperson = (!empty($this->salesperson) ? $this->salesperson->salesperson : "");
        $vehicle = (!empty($this->vehicle) ? $this->vehicle->model : "");
        $extraViewData = ['salesperson' => $salesperson, 'sales_number_display'=>$sales_number_display, 'date_display'=>$date_display, 'vehicle' => $vehicle, 'photo'=>$photo, 'display_price'=>$display_price];

        $this->_trackView();
        $this->_queueTracking("page_entry","showing page", $this->currentPage);
        return view('wizard.pages.summary')->with(array_merge($this->_getViewData(), $extraViewData));


    }

    public function submitSummary(){
        $this->_getDeal();
        $deal_id = $this->deal_id;
        $message_type = $this->request->message_type;

        $bodyFilters['ContactId'] = $this->deal->contact_id;
        $bodyFilters['DealId'] = $this->deal->id;
        $bodyFilters['VehicleStockNumber'] = $this->deal->vehicle_stock;
        $bodyFilters['MessageType'] = $message_type;
        $bodyFilters['TradeInId'] = $this->deal->trade_id;
        $bodyFilters['PaymentId'] = 0;
        $bodyFilters['PreApprovedId'] = $this->deal->preapproved_id;
        $bodyFilters['DealerId'] = $this->deal->d_id;
        $bodyFilters['DealId'] = $this->deal_id;
        if(!empty($this->deal->s_id)){
            $bodyFilters['SalespersonId'] = $this->deal->s_id;

        }
        if(!empty($this->deal->td_id)){
            $bodyFilters['AppointmentId'] = $this->deal->td_id;
        }


        //return response(json_encode($this->deal), 400);
        $client = new Client(['verify' =>false,'base_uri' => $this->api_endpoints['testdrive'].'/api/']);
        $headers = ['Accept' => 'text/plain', 'Content-Type' => 'application/json-patch+json'];

        $response = $client->request('POST', 'WidgetSummary/sent-notifications', [
            'headers' => $headers,
            'body' => json_encode($bodyFilters)
        ]);

        $return_array = [];
        if($this->debug_api){
            $return_array['body'] = json_encode($bodyFilters);
        }



        if($response->getStatusCode() != 200)
        {
            $this->_queueTracking("api_submit_error","summary", "summary processing");

            return $this->_400($return_array);
        }

        $this->_queueTracking("api_submit_success","summary", "summary set");

        return $this->_ok($return_array);
    }

    public function trackingClient(Request $request){
        if(!$request->has('data')){
            return response('failure', 400);
        }
        $data = $request->input('data');

        if(!is_array($data) || empty($data)){
            return response('failure', 400);
        }

        if(empty($data['page']))$data['page'] = 'not_set';
        $data['created'] = date("Y-m-d H:i:s");
        $deal = Deal::where('user_token', $this->user_token)->first();
        $data['d_id'] = $deal->d_id;
        $data['deal_id'] = $deal->id;
        EventsTracking::dispatch($data);

        return response('success', 200);


    }

    public function offerlogixCalculate2(Request $request){
        $data = $request->post();
        $this->_getDeal();
        $this->_setProviderId();

        $cache_identifier = "vehicle_filters_".$this->d_id."_".$this->provider_id;
        $v_id = $this->deal->vehicle_id;
        $chrome_id = null;
        if($this->redis_vehicle->exists($cache_identifier)){
            $return_format =  json_decode($this->redis_vehicle->get($cache_identifier), true);
            foreach($return_format['vehicles'] as $index => $v_arr){
                if($v_arr['id'] == $v_id){
                    if(isset($v_arr['csid']) && !empty($v_arr['csid'])){
                        $chrome_id =  $v_arr['csid'];
                        break;
                    }
                }
            }
        }



        $trade_value = (!empty($this->deal->trade_value)) ? $this->deal->trade_value : 0;
        $zip_location = (isset($data['zip_location']) && !empty($data['zip_location'])) ? $data['zip_location'] : [];
        $credit_score = (!empty($data['lease_credit'])) ? $data['lease_credit'] : $data['finance_credit'];
        $params = array(
            'credit_score' => $credit_score,
            'zip' => $data['zip'],
            'zip_string' => '',
            'tradein_amount' => $trade_value,
            'vehicle_id' => $v_id,
            'downpayment' => $data['downpayment'],
            'payoff' => $data['payoff'],
            'selected' => $data['selected'],
            'd_id' => $this->d_id,
            'lease_term' => (empty($data['lease_term']) ? 60 : $data['lease_term']),
            'finance_term' => (empty($data['finance_term']) ? 60 : $data['finance_term']),
            'lease_miles' => (empty($data['lease_miles']) ? 12000 : $data['lease_miles']),
            'zip_location' => [],
            'lease_credit' => $credit_score,
            'finance_credit' => $credit_score,
            'accessToken' => $this->accessToken,
            'chrome_id' => $chrome_id
        );


        $client = new Client(['verify'=>false,'base_uri' => $this->api_endpoints['buildabrand']]);
        $response = $client->request('POST', '/getOfferlogixLoan', [
            'headers' => [
                'Accept' => 'text/plain',
                'Content-Type' => 'application/json-patch+json'
            ],
            'body' => json_encode($params)
        ]);


        if($response->getStatusCode() == 200){
            $offerlogix = json_decode($response->getBody(), true);
            $offerlogix_array_object = json_encode($offerlogix);
            $this->_queueTracking("api_offerlogix_recalculate","recalculate offerlogix", "offerlogix processing");

            return response($response->getBody(), 200);
        }

        $this->_queueTracking("api_offerlogix_error","recalculate offerlogix", "offerlogix processing");

        return response('failure', 400);


    }


    public function offerlogixCalculate(Request $request){
        $data = $request->post();
        $this->_getDeal();
        $this->_setProviderId();

        $cache_identifier = "vehicle_filters_".$this->d_id."_".$this->provider_id;
        $v_id = $data['vehicle_id'];
        $chrome_id = null;
        if($this->redis_vehicle->exists($cache_identifier)){
            $return_format =  json_decode($this->redis_vehicle->get($cache_identifier), true);
            foreach($return_format['vehicles'] as $index => $v_arr){
                if($v_arr['id'] == $v_id){
                    if(isset($v_arr['csid']) && !empty($v_arr['csid'])){
                        $chrome_id =  $v_arr['csid'];
                        break;
                    }
                }
            }
        }



        $trade_value = (!empty($data['tradein'])) ? $data['tradein'] : 0;
        $zip_location = [];
        $credit_score = $data['credit_selected'];
        $params = array(
            'credit_score' => $credit_score,
            'zip' => $data['zip'],
            'zip_string' => '',
            'tradein_amount' => $trade_value,
            'vehicle_id' => $v_id,
            'downpayment' => $data['downpayment'],
            'payoff' => $data['payoff'],
            'selected' => ($data['selected'] == 'lease') ? 'leasing' : 'financing',
            'd_id' => $this->d_id,
            'lease_term' => null,
            'finance_term' => null,
            'lease_miles' => (empty($data['lease_miles']) ? 12000 : $data['lease_miles']),
            'zip_location' => $zip_location,
            'lease_credit' => $credit_score,
            'finance_credit' => $credit_score,
            'accessToken' => $this->accessToken,
            'chrome_id' => $chrome_id
        );

       $credit_types = array('poor', 'fair', 'good', 'excellent');

        $return_array =  array();

        foreach($credit_types as $c_type){
            $params['credit_score'] = $c_type;
            $params['lease_credit'] = $c_type;
            $params['finance_credit'] = $c_type;

            $client = new Client(['verify'=>false,'base_uri' => $this->api_endpoints['buildabrand']]);
            $response = $client->request('POST', '/getOfferlogixLoan', [
                'headers' => [
                    'Accept' => 'text/plain',
                    'Content-Type' => 'application/json-patch+json'
                ],
                'body' => json_encode($params)
            ]);


            if($response->getStatusCode() == 200){
                $offerlogix = json_decode($response->getBody(), true);
                $offerlogix_array_object = json_encode($offerlogix);

                $return_array[$c_type] = json_decode($response->getBody(), true);
            }

        }

        if(!empty($return_array)){
            return response($return_array, 200);
        }


        return response('failure', 400);


    }

    public function offerlogixSave(Request $request){
        $data = $request->post();
        $this->_getDeal();
        $data['tradein_amount'] = (!empty($this->deal->trade_value)) ? $this->deal->trade_value : 0;
        $data['zip_location'] = (isset($data['zip_location']) && !empty($data['zip_location'])) ? $data['zip_location'] : [];
        $credit_score = (!empty($data['lease_credit'])) ? $data['lease_credit'] : $data['finance_credit'];
        $data['credit_score'] = $credit_score;
        $data['vehicle_id'] = $this->deal->vehicle_id;

        $error = false;

        if(is_array($data) && (isset($data['selected']) && !empty($data['selected']) )){

            try{
                $json = json_encode($data);
            }catch(\Exception $e){
                $error = true;
            }
        }else{
            $error = true;
        }


        if($error){
            $this->_queueTracking("api_offerlogix_error","saving offerlogix", "offerlogix processing");

            return response('failure', 400);
        }else{
            $this->deal->offerlogix_json = $json;
            if($this->deal->save()){
                $this->_queueTracking("api_offerlogix_save","offerlogix saved", "offerlogix saved");
                return response('success', 200);
            }
        }

        $this->_queueTracking("api_offerlogix_error","saving offerlogix", "offerlogix processing");
        $this->_trackSubmission('payment');
        return response('failure', 400);
    }

    public function offerlogixZipLookup(Request $request){
        if(!$request->has('zip')){
            return response('failure', 400);
        }
        $zip = $request->input('zip');

        $deal = Deal::where('user_token', $this->user_token)->first();
        $d_id = $deal->d_id;
        $access_token = $deal->access_token;
        $params = array(
            'zip' => $zip,
            'd_id' => $d_id,
            'accessToken' => $access_token
        );


        $client = new Client(['verify'=>false,'base_uri' => $this->api_endpoints['buildabrand']]);
        $response = $client->request('POST', '/getOfferlogixZip', [
            'headers' => [
                'Accept' => 'text/plain',
                'Content-Type' => 'application/json-patch+json'
            ],
            'body' => json_encode($params)
        ]);


        if($response->getStatusCode() == 200){
            $this->_queueTracking("api_offerlogix_success","zip change", "offerlogix zip change");

            $info = json_decode($response->getBody(), true);
            return response(['data' => $info], 200);

        }

        $this->_queueTracking("api_offerlogix_error","zip change", "offerlogix processing");

        return response(['message','We were unable to find results for give zip code. Please try another.'], 400);



    }

    public function salespersonQuestion(Request $request)
    {
        $data = $request->post();
        $this->_getDeal();
        $this->_getSalesperson();
        $salesperson = (!empty($this->salesperson) ? $this->salesperson->salesperson : "");
        $s_phone = $salesperson->IndividualPhoneNumber;
        $personal_phone = substr($salesperson->phone, -10);
        $s_phone = substr($s_phone,-10);
        if($salesperson == "" || empty($s_phone) || strlen($s_phone) != 10){
            return response(['message','Unable to process request.'], 400);
        }

        $fname = $data['fname'];
        $lname = $data['lname'];
        $phone = $data['phone'];
        $question = urlencode("A question from ".$fname." ".$lname." - ".$phone." \n\n". $data['question']);

        $data_array = array(
            'Body' => $question,
            'From' => $s_phone,
            'To' => $personal_phone,
            'MediaUrls' => null,
            'SalespeopleID' => $this->deal->s_id,
            'DealerID' => $this->deal->d_id,
            'SmsType' => 0,
            'ContactID' => $this->deal->contact_id,
            'AuthorSid' => null

        );

        $json = json_encode($data_array);

        $r = $this->_sendServiceBusQueue($json);
        return response([$r],200);
        if($this->_sendServiceBusQueue($json)){
            return response(['message','success'], 200);
        }

        return response(['message','Unable to complete request.'], 400);


    }

    public function filterVehicle(){
        $this->_getDeal();
        $this->_getVehicleFilters();
        $this->_getVehiclesCache();
        $data = array('filters' => $this->filters, 'leasing_json'=>json_encode($this->filters->leasing), 'financing_json'=>json_encode($this->filters->financing));

        return response(['data' => $data], 200);
    }

    public function getVehiclesHtml(){

        $only_need_vehicles = true;
        $this->_getDeal();
        $this->_getVehicleFilters($only_need_vehicles);
        $this->_getVehiclesCache();
        return View::make('wizard.pages.partials.vehicles', ['vehicles' => $this->vehicles, 'user_token'=>$this->user_token, 'accessToken'=>$this->accessToken,'d_id'=>$this->d_id,'deal'=>$this->deal, 'totalPages'=> $this->pagination_total_pages,'p'=>$this->pagination_page ]);
    }



//Private Functions

    private function _generateSasToken()
    {
        $uri = $this->api_endpoints['sasuri'];
        $sasKeyName = $this->api_endpoints['saskeyname'];
        $sasKeyValue = $this->api_endpoints['saskeyvalue'];

        $targetUri = strtolower(rawurlencode(strtolower($uri)));
        $expires = time();
        $expiresInMins = 60;
        $week = 60*60*24*7;
        $expires = $expires + $week;
        $toSign = $targetUri . "\n" . $expires;
        $signature = rawurlencode(base64_encode(hash_hmac('sha256',
            $toSign, $sasKeyValue, TRUE)));

        $token = "SharedAccessSignature sr=" . $targetUri . "&sig=" . $signature . "&se=" . $expires .         "&skn=" . $sasKeyName;
        return $token;
    }

    private function _sendServiceBusQueue($json){
        $client = new Client(['verify' =>false,'base_uri' => $this->api_endpoints['servicebusmessageurl']]);
        $headers = ['Host' => str_replace(array('https://','/'),'',$this->api_endpoints['sasuri']),
            'Content-Type' => 'application/atom+xml;type=entry;charset=utf-8',
            'x-ms-retrypolicy' => 'NoRetry',
            'Authorization' => $this->_generateSasToken(),
            'Content-Length' => strlen($json),
            'BrokerProperties' => $json
        ];

        $this->_queueTracking("api_submit","sending twillio text message", "ask a question");

        $response = $client->request('POST', '', [
            'headers' => $headers,
            'body' => $json
        ]);


        if($response->getStatusCode() == 201){
            $this->_queueTracking("api_submit_success","sending twillio text message", "ask a question");
            return true;
        }

        $this->_queueTracking("api_submit_failure","sending twillio text message", "ask a question");
        return false;
    }



    private function _generateUserToken(){
        $token = null;
        for ($x = 0; $x < 10; $x ++) {
            $token = Str::random(32);
            try {
                $deal_ct = Deal::where('user_token', $token)->count();
                if ($deal_ct == 0) {
                    break;
                }
            }catch (\Exception $e) {
                $token = null;
            }
        }

        return $token;
    }

    private function _offerlogixActivated(){
        if(($this->env == 'local' || $this->env == 'beta' ) && $this->d_id == 19){
            return true;
        }

        return false;
    }



    private function _trackSubmission($type, $data=''){
        $track = Track::where('deal_id', $this->deal_id)->first();
        if(!$track)return;

        if($type == "contact"){
            $track->contact = 1;
        }elseif($type == "trade"){
            $track->trade = 1;
        }elseif($type == "appointment"){
            $track->appointment = 1;
        }elseif($type == "payment"){
            $track->payment = 1;
        }elseif($type == "credit"){
            $track->credit = 1;
        }elseif($type == "salesperson"){
            $track->s_id = $data;
        }

        $track->save();
    }

    private function _trackView(){

        DB::table('track')->where('deal_id', $this->deal_id)->increment('views');


    }


    private function _queueTracking($type=null, $info=null, $page=null){

        $c_page = null;
        $created = date("Y-m-d H:i:s");
        if($page != null)$c_page = $page;
        if($this->currentPage != null)$c_page = $this->currentPage;

        if($c_page == null)$c_page = 'not_set';
        if($info == null)$info='not_set';

        $data = array(
            'deal_id' => $this->deal_id,
            'd_id' => $this->d_id,
            's_id' => $this->s_id,
            'page' => $c_page,
            'env' => 'server',
            'type' => $type,
            'info' => $info,
            'created' => $created
        );

        EventsTracking::dispatch($data);
    }

    private function _saveTracking(Array $data){
        return true;

    }

    private function _redisGet($key, $connection){
       // dd($this->redis->get($key));
        $return = false;
        $select = "redis_".$connection;
        if($this->$select->exists($key)){
            $return = json_decode($this->$select->get($key));
        }
        return $return;
    }



    private function _400($extraData=null){
        if($extraData == null || empty($extraData)){
            return response(['status'=>'Error Processing Request'], 400)->header('Content-Type', 'application/json');
        }else{
            $rsp = array_merge(['status'=>'Error Processing Request'], $extraData);
            return response($rsp, 400)->header('Content-Type', 'application/json');
        }
    }

    private function _ok($extraData = null){
        if($extraData == null || empty($extraData)){
            return response(['status'=>'Success'], 200)->header('Content-Type', 'application/json');
        }else{
            $rsp = array_merge(['status'=>'Success'], $extraData);
            return response($rsp, 200)->header('Content-Type', 'application/json');
        }

    }

    private function _flowConditions(){
        $this->request->session()->flush();

        if($this->request->has('source') && $this->request->source == "MainWebSite" ){
            $this->source = 'salesperson_website';
        }elseif($this->request->has('source') && strtolower($this->request->source) == "facebook"  ){
            $this->source = 'facebook';
        }elseif($this->request->has('source') && strtolower($this->request->source) == "digitalbusinesscard"  ){
            $this->source = 'salesperson_website';
        }elseif($this->request->has('source') && strtolower($this->request->source) == "dealer"  ){
            $this->source = 'dealer_website';
        }elseif($this->request->has('source') && strtolower($this->request->source) == "vdp"  ){
            $this->source = 'dealer_website_vdp';
        }else{
            $this->source = 'toolkit';

        }
        $this->_getSalespeople();
        if(isset($this->salespeople->salespeople)){
            $sids = $this->salespeople->sids; //flat array of all saleperson ids for checking later on

            //salesperson id passed in must be one of the salespeople pulled from api call
            if(!empty($this->s_id)){
                if(!in_array($this->s_id, $sids)){
                    //if we can't find salesperson id in flat array then unset entry and passed in s_id

                    $this->s_id = $sids[0];
                    if($this->entry == 'salesperson')$this->entry=null;
                }
            }else{
                $this->s_id = $sids[0];
                if($this->entry == 'salesperson')$this->entry=null;
            }
        }

    }

    private function _getDeal(){


        if($this->user_token && $this->from != 'toolkit')
        {

            $deal = Deal::where('user_token', $this->user_token)->first();
            $this->deal_id = $deal->id;
            //Set client credentials
            $this->accessToken = $deal->access_token;
            $this->d_id = $deal->d_id;
        }
        else
        {

            $this->_flowConditions();


            if($this->from == 'toolkit' && $this->d_id != null){
                $deal = Deal::where('d_id', $this->d_id)->first();
                $deal->user_ip = $this->request->ip();
                $deal->d_id = $this->d_id;
                $deal->s_id = $this->s_id;
                $deal->entry = $this->entry;
                $deal->save();
                //Set client credentials
                $this->accessToken = $deal->access_token;
                $this->d_id = $deal->d_id;
                $this->user_token = $deal->user_token;
                $this->_queueTracking("deal_continued_toolkit","user sent to app", "deal continued");
            }else{
                $this->user_token = $this->_generateUserToken();

                $deal = Deal::create([
                    'user_ip' => $this->request->ip(),
                    'd_id' => $this->d_id,
                    's_id' => $this->s_id,
                    'entry' => $this->entry,
                    'user_token' => $this->user_token,
                    'access_token' => $this->accessToken

                ]);


                $this->deal_id = $deal->id;

                if(!$this->utils_service->isCrawlerCheck($this->request->ip())) {

                    $this->_queueTracking("new_deal_started", "new unique deal_id started", "deal created");

                    $track = Track::create([
                        'deal_id' => $this->deal_id,
                        'd_id' => $this->d_id,
                        'source' => $this->source,
                        's_id' => $this->s_id,
                        'ip' => $this->request->ip(),
                        'is_mobile' => ($this->is_mobile == "true") ? 1 : 0

                    ]);

                    $track->save();
                }
                //DB::table('tracking')->where('deal_id', $deal->id)->increment('trade');





            }





            if(!empty($deal->user_token)){
                $this->deal_id = $deal->id;
                $deal = Deal::where('user_token', $this->user_token)->first();


                if($this->v_id){
                    $this->_setVehicle($deal);
                    $this->_queueTracking("deal_prepopulation","v_id present from toolkit", "deal created");
                }

                if($this->a_id){
                    $this->_setAppointment($deal);
                    $this->_queueTracking("deal_prepopulation","a_id present from toolkit", "deal created");
                }

                if($this->c_id){
                    $this->_setContact($deal);
                    $this->_queueTracking("deal_prepopulation","c_id present from toolkit", "deal created");
                }

                if($this->vin){
                    $vehicle_id = null;
                    $this->_setProviderId();
                    $cache_identifier = "vehicle_filters_".$this->d_id."_".$this->provider_id;
                    $redis_vehicles = json_decode($this->redis_vehicle->get($cache_identifier),true);
                    if(is_array($redis_vehicles) && !empty($redis_vehicles)){
                        foreach($redis_vehicles['vehicles'] as $index => $arr){
                            if($arr['vin'] == $this->vin){
                                $vehicle_id = $arr['id'];
                                break;
                            }
                        }
                    }
                    if($vehicle_id){
                        $this->v_id = $vehicle_id;
                        $this->request->vehicle_id = $vehicle_id;
                        $this->_setVehicle($deal);
                        $this->_queueTracking("deal_prepopulation","vin set facebook ad", "vehicle prepop");
                    }

                }

                $deal->refresh();
            }

        }

        $this->deal = $deal;
            //dd($deal);
        return $deal;

    }

    private function _getDealer(){

        if($this->request->session()->has('dealer') && $this->request->session()->get('dealer')->d_id == $this->d_id) {

            $dealer = $this->request->session()->get('dealer');



        }else{

            $this->request->session()->forget('dealer');
            $dclient = new Client(['base_uri' => $this->api_endpoints['buildabrand'],'verify' =>false]);
            $dresponse = $dclient->request('GET', '/getDealerInfo?accessToken=' . $this->accessToken . '&d_id=' . $this->d_id);

            $dealer = json_decode($dresponse->getBody());

            $dealer = $dealer->dealer;

            $dlclient = new Client(['verify' =>false]);
            $dlresponse = $dlclient->request('GET', $this->api_endpoints['toolkit']."/api/dealer/" . $this->d_id . "/media", [
                'headers' => [
                    'Accept' => 'text/plain',
                    'Content-Type' => 'application/json-patch+json'
                ],
            ]);

            $dlresponse = json_decode($dlresponse->getBody());
            if(isset($dlresponse->model->backgroungImage->path)){
                $dealer->brandImage = $dlresponse->model->backgroungImage->path;
            }else{
                $dealer->brandImage = "";
            }

            if(isset($dlresponse->model->dealershipLogo->path)){
                $dealer->brandLogo = $dlresponse->model->dealershipLogo->path;
            }else{
                if(isset($dlresponse->model->backgroungImage->path)){
                    $dealer->brandLogo = $dlresponse->model->backgroungImage->path;
                }else{
                    $dealer->brandLogo = "";
                }
            }


            $this->request->session()->put('dealer', $dealer);
        }

        $this->dealer = $dealer;
    }

    private function _getSalesperson(){
        $cache_identifier_salespeople = 'apistore.salespeople'.$this->d_id;
        $client = new Client(['base_uri' => $this->api_endpoints['buildabrand'],'verify' =>false]);


        if(!$this->s_id){
            if(isset($this->deal->s_id) && !empty($this->deal->s_id)){
                $this->s_id = $this->deal->s_id;
            }
        }


        if($this->s_id)
        {
            //User has switched salesperson but not selected them yet.
            //So show the temp salesperson and let it go to session but the real salesperson is saved in database until user selects a new user.
            if(isset($this->request->s_id) && $this->s_id != $this->deal->s_id){

                $this->request->session()->forget('salesperson');
                //$salesperson = session('salesperson');

            }

            //Condition where salesman was switched but not selected.  User just went to a new page.  clear session and use s_id from database. Pull s_id info again.
            if(isset($this->request->session()->get('salesperson')->salesperson->s_id) && $this->request->session()->get('salesperson')->salesperson->s_id != $this->deal->s_id){
                $this->request->session()->forget('salesperson');
            }



            if($this->request->session()->exists('salesperson')) {
                $salesperson = $this->request->session()->get('salesperson');


            }else{
                $cache_identifier = 'apistore.salesperson_'.$this->s_id;
                $s_id = $this->s_id;

                if ($salesperson = $this->_redisGet($cache_identifier, "salesperson")) {
                    $this->request->session()->put('salesperson',$salesperson);


                }else{

                    $response = $client->request('GET', '/getSalesPersonByID?accessToken=' . $this->accessToken . '&s_id=' . $this->s_id . '&d_id=' . $this->d_id);

                    $salesperson = json_decode($response->getBody());



                    $this->request->session()->put('salesperson',$salesperson);



                }


            }






        }
        else
        {
            $salesperson = '';
            $this->request->session()->forget('salesperson');
        }

        if($salesperson != ''){
            $this->percentage+= 10;
            $this->minSaved+= 20;
        }

        $this->salesperson = $salesperson;
    }

    private function _welcomeInfo(){
        $contact_str = "";
        if($this->deal->contact_id != null && is_numeric($this->deal->contact_id)){
            $contact_str = "&contactId=".$this->deal->contact_id;
        }
        $dlclient = new Client(['verify' =>false]);
        $dlresponse = $dlclient->request('GET', $this->api_endpoints['toolkit']."/api/ExpressBuying/get-by-salepersonid?salesPersonId=".$this->s_id."&dealerId=".$this->d_id.$contact_str, [
            'headers' => [
                'Accept' => 'text/plain',
                'Content-Type' => 'application/json-patch+json',
                'X-Api-Key' => 'Gds3zmv59redtBu0pXNlbTS4iVbMcjZe'
            ],
        ]);

        $dldata = json_decode($dlresponse->getBody());
        if(isset($dldata->model)){
           // $dldata->model->custom->message = str_replace(array("\r\n"),array("<br>"), $dldata->model->custom->message);
            return $dldata->model->custom;
        }

        return [];
    }

    private function _getVehicle(){
        $vehicle = "";
        $found = false;

        if(isset($this->deal->id) && $this->deal->vehicle_id != null && $this->deal->vehicle_stock != null)
        {

            if(isset($this->request->session()->get('vehicle')->model->id) &&  $this->request->session()->get('vehicle')->model->id == $this->deal->vehicle_id){
                $vehicle = $this->request->session()->get('vehicle');
                $found = true;
            }else{

                $vclient = new Client(['verify' =>false]);
                $vresponse = $vclient->request('GET', $this->api_endpoints['azurewebsites'].'/api/inventorywidgets/getbyid?id=' . $this->deal->vehicle_id . '&dealerId=' . $this->deal->d_id, [
                    'headers' => [
                        'Accept' => 'text/plain',
                        'Content-Type' => 'application/json-patch+json'
                    ],
                    'body' => '{}'
                ]);
                $vehicle_rsp = json_decode($vresponse->getBody());

                if(isset($vehicle_rsp->model->id)){
                    $vehicle = $vehicle_rsp;
                    $found = true;
                    $this->request->session()->put('vehicle', $vehicle);
                }

            }


            if($found){
                $this->vehicleCheck = true;
                $this->percentage+= 25;
                $this->minSaved+= 30;
            }
        }



        $this->vehicle = $vehicle;
    }

    private function _setVehicle($deal){

        $vclient = new Client(['verify' =>false]);
        $vresponse = $vclient->request('GET', $this->api_endpoints['azurewebsites'].'/api/inventorywidgets/getbyid?id=' . $this->v_id . '&dealerId=' . $deal->d_id, [
            'headers' => [
                'Accept' => 'text/plain',
                'Content-Type' => 'application/json-patch+json'
            ],
            'body' => '{}'
        ]);
        $vehicle_rsp = json_decode($vresponse->getBody());
        if(isset($vehicle_rsp->model) && !empty($vehicle_rsp->model)){
            $deal->vehicle_id = $vehicle_rsp->model->id;
            $deal->vehicle_stock = $vehicle_rsp->model->stockNumber;
            $deal->save();

        }

    }

    private function _setAppointment($deal){
        $hclient = new Client(['verify' =>false]);
        $hresponse = $hclient->request('GET', $this->api_endpoints['testdrive'].'/api/TestDrive/' . $this->a_id);
        $response = json_decode($hresponse->getBody());

        if(isset($response->dateRequested) && !empty($response->dateRequested)){

            $date = substr($response->dateRequested, 0, 10); //Y-m-d
            $time = substr($response->dateRequested,11,5);
            $hour = substr($response->dateRequested,11,2);

            if($hour > 12){
                //$hour = $hour - 12;
                $period = 'pm';
            }else if($hour < 12){
                $period = 'am';
                $hour = str_replace('0','',$hour);
            }else if($hour == 12){
                $period = 'pm';
            }
            $hour = $hour.":00";
            $info = json_decode($response->content);
            $beverage_id = null;
            $beverage_title = null;
            $beverage_comment = null;

            if(isset($info->beverage->id) && !empty($info->beverage->id)){
                $beverage_id = $info->beverage->id;
                $beverage_title = $info->beverage->title;
                $beverage_comment = $info->beverage->comments;
            }


            $date_mod = explode("-", $date);
            $format_date = $date_mod[1].'/'.$date_mod[2].'/'.$date_mod[0]; // m/d/Y

            $deal->td_id = $response->appointmentId;
            $deal->td_date = $format_date;
            $deal->td_time = $hour;
            $deal->td_period = $period;
            $deal->td_beverage = $beverage_title;
            $deal->td_beverage_id = $beverage_id;
            $deal->td_comments = $beverage_comment;
            $deal->save();

        }
    }

    private function _setContact($deal){

        $client = new Client(['verify' =>false]);
        $response = $client->request('GET', $this->api_endpoints['toolkit']."/api/ThirdPartyWidgets/GetContact?contactId=".$this->c_id, [
            'headers' => [
                'X-Api-Key'=> 'Gds3zmv59redtBu0pXNlbTS4iVbMcjZe'

            ],
        ]);

        $body = json_decode($response->getBody());

        if(isset($body->contactId) && !empty($body->contactId)){
            $deal->contact_id = $body->contactId;
            $deal->fname = $body->firstName;
            $deal->lname = $body->lastName;
            $deal->email = $body->emailAddress;
            $deal->phone = $body->phoneNumber;
            if(isset($body->address) && !empty($body->address))$deal->address = $body->address;
            if(isset($body->postalCode) && !empty($body->postalCode))$deal->zipcode = $body->postalCode;
            if(isset($body->city) && !empty($body->city))$deal->city = $body->city;
            if(isset($body->state) && !empty($body->state))$deal->state = $body->state;
            if(isset($body->birthDate) && !empty($body->birthDate))$deal->birthday = $body->birthDate;
            $deal->save();

        }
    }

    private function _setSelectedVehicle(){
        $deal = $this->_getDeal();
        $body = null;

        if($deal->vehicle_id != null && $deal->vehicle_stock != null && $deal->s_id != null && $deal->contact_id != null){

            $this->_getSelectedVehicle($deal->vehicle_id);
            if(isset($this->selectedVehicle->model->vin)){
                $client = new Client(['verify' =>false,'base_uri' => $this->api_endpoints['toolkit']."/api/"]);
                $headers = ['Accept' => 'text/plain', 'Content-Type' => 'application/json-patch+json','X-Api-Key' => 'Gds3zmv59redtBu0pXNlbTS4iVbMcjZe'];
                $body = array(
                    "ContactId" => $deal->contact_id,
                    "Make" => $this->selectedVehicle->model->make,
                    "Model" => $this->selectedVehicle->model->modelName,
                    "Year" => $this->selectedVehicle->model->year,
                    "StockNumber" => $deal->vehicle_stock,
                    "SalesPersonId" => $deal->s_id
                );


                $response = $client->request('POST', 'ThirdPartyWidgets/vehicle-selected', [
                    'headers' => $headers,
                    'body' => json_encode($body)
                ]);

                if($response->getStatusCode() == 200){
                    $body['status_code'] = $response->getStatusCode();
                }
                return json_encode($body);


            }


        }

        return $body;

    }

    private function _getSelectedVehicle($vehicle_id){
        $vclient = new Client(['verify' =>false]);
        $vresponse = $vclient->request('GET', $this->api_endpoints['azurewebsites'].'/api/inventorywidgets/getbyid?id=' . $vehicle_id . '&dealerId=' . $this->d_id, [
            'headers' => [
                'Accept' => 'text/plain',
                'Content-Type' => 'application/json-patch+json'
            ],
            'body' => '{}'
        ]);



        $this->selectedVehicle = json_decode($vresponse->getBody());

    }

    private function _getVehicles(){
        $cache_identifier = 'apistore.vehicles'.$this->d_id;
        $vf = json_decode(json_encode($this->vehicleFilters),true);
        //dd($this->vehicleFilters);
        //dd(json_encode($this->vehicleFilters));
        if(0){
        //if (Cache::has($cache_identifier) && empty($vf)) {
            $this->vehicles = Cache::get($cache_identifier);

        }else{

            $vclient = new Client(['verify'=>false]);
            $vresponse = $vclient->request('POST', $this->api_endpoints['azurewebsites'].'/api/inventorywidgets/get-vehicles?dealerId='.$this->d_id, [
                'headers' => [
                    'Accept' => 'text/plain',
                    'Content-Type' => 'application/json-patch+json'
                ],
                'body' => json_encode($this->vehicleFilters)
            ]);

            //dd(json_encode($this->vehicleFilters));

            $vehicles = json_decode($vresponse->getBody());
            //dd($vehicles);
            if(isset($vehicles->totalPages)){
                $this->pagination_total_pages = $vehicles->totalPages;
            }


            $this->vehicles = $vehicles;

            if(empty($vf) && isset($vehicles->items)){
                //Cache::put($cache_identifier, $vehicles, now()->addMinutes(180));
            }

        }

        if($this->provider_id == 1){
           // $this->_getFiltersDealerTrendBuild($this->vehicles);
            //$this->filters = (object) $this->dealerTrendFilters;
        }


    }

    private function _getVehiclesCache(){
        $vehicle = array();
        $items = array();
        $records = count($this->temp_vehicles);
        $per_page = $this->vehicleFilters->perPage;
        $page = $this->vehicleFilters->page;
        $total_pages = ceil($records / $per_page);
        $vehicle['pageNumber'] = $page;
        $vehicle['totalPages'] = $total_pages;
        $end = ($page * $per_page) - 1;
        $start = $end - 17;

        if($start > $records)$start=$records;
        for($i=$start; count($items) != $per_page; $i++){
            if(!isset($this->temp_vehicles[$i]))break;

            $temp = $this->temp_vehicles[$i];
            $temp['odometer'] = $temp['mileage'];
            $temp['driveTrain'] = $temp['drivetrain'];
            $temp['stockNumber'] = $temp['stock_number'];
            $temp['saleClass'] = $temp['condition'];

            $items[] = $temp;

        }


        $vehicle['items'] = $items;

        $json = json_encode($vehicle);
        $this->vehicles = json_decode($json);
        $this->pagination_total_pages = $total_pages;


    }


    private function _getPagination(){

    }

    private function _getFiltersDealerTrendBuild($vehicles){

        if(isset($vehicles->items) && !empty($vehicles->items)){

            $max_price = max(array_column($vehicles->items, 'price'));
            $min_price = min(array_column($vehicles->items, 'price'));

            $max_year = max(array_column($vehicles->items, 'year'));
            $min_year = min(array_column($vehicles->items, 'year'));

            $max_odometer = max(array_column($vehicles->items, 'odometer'));
            $min_odometer = min(array_column($vehicles->items, 'odometer'));

            $this->dealerTrendFilters['priceRanges'][] = (object) array('from'=> $min_price, 'to'=> $max_price);
            $this->dealerTrendFilters['years'] = array($max_year, $min_year);
            $this->dealerTrendFilters['miles'] = array($max_odometer, $min_odometer);
            $this->filters = (object) $this->dealerTrendFilters;
        }else{
            $this->dealerTrendFilters = json_decode('{"priceRanges":[{"from":0,"to":0}],"years":[0,0],"miles":[0,0]}');
            $this->filters = json_decode('{"priceRanges":[{"from":0,"to":0}],"years":[0,0],"miles":[0,0]}');

        }


    }

    private function _getFiltersDealerTrend(){

        $cache_identifier = "vehicle_filters_".$this->d_id."_".$this->provider_id;
        $makes_cache = $cache_identifier."_makes";
        $vehicleFilters = [];
        $vehicleFilters['perPage'] = $this->pagination_items_total;
        $vehicleFilters['page'] = $this->pagination_page;

        $this->filters = json_decode('{}');


        $vclient = new Client(['verify' =>false]);
        if (Cache::has($makes_cache)) {
            $makes = Cache::get($makes_cache);

        }else{




            $response_makes = $vclient->request('GET', $this->api_endpoints['azurewebsites'].'/api/inventorywidgets/get-dealer-trend-makes/' . $this->d_id, [
                'headers' => [
                    'Accept' => 'text/plain',
                    'Content-Type' => 'application/json-patch+json'
                ],
                'body' => '{}'
            ]);
            $makes = json_decode($response_makes->getBody());
            Cache::put($makes_cache, $makes, now()->addMinutes(180));

        }

        $models_url_string = '';
        foreach($makes as $make_id=>$make_name){
            if($this->request->has('v_make') && $this->request->v_make == $make_id && $this->request->v_make != 'all'){
                $vehicleFilters['BrandName'] = $make_name;
                $models_url_string .= '?make='.$make_name;
            }
            $this->filters->brands[] = (object) array('id'=>$make_id, 'name'=>$make_name);
        }


        $response_models = $vclient->request('GET', $this->api_endpoints['azurewebsites'].'/api/inventorywidgets/get-dealer-trend-models/' . $this->d_id.$models_url_string, [
            'headers' => [
                'Accept' => 'text/plain',
                'Content-Type' => 'application/json-patch+json'
            ],
            'body' => '{}'
        ]);
        $models = json_decode($response_models->getBody());

        foreach($models as $model_id=>$model_name){
            if($this->request->has('v_model') && $this->request->v_model == $model_id && $this->request->v_model != 'all'){
                $vehicleFilters['ModelName'] = $model_name;
            }
            $this->filters->models[] = (object) array('id'=>$model_id, 'name'=>$model_name);
        }





        if($this->request->has('vehicleSearch'))
        {
            $vehicleFilters['SearchText'] = $this->request->vehicleSearch;
        }

        if($this->request->has('sortBy'))
        {
            $vehicleFilters['SortBy'] = $this->request->sortBy;
        }

        if($this->request->has('v_condition') && $this->request->v_condition != 'all')
        {
            $v_condition = $this->request->v_condition;

            if($v_condition == 'new')
            {
                $v_condition = false;
            } else
            {
                $v_condition = true;
            }
            $vehicleFilters['IsUsed'] = $v_condition;
        }

        if($this->request->has('v_year') && $this->request->v_year != '' && $this->request->v_year != 'all')
        {
            $vehicleFilters['YearTo'] = $this->request->v_year_to;
            $vehicleFilters['YearFrom'] = $this->request->v_year_from;
        }

        if($this->request->has('v_price_range') && $this->request->v_price_range != '' && $this->request->v_price_range != 'all')
        {
            $explode_price = explode("-",$this->request->v_price_range );
            $vehicleFilters['PriceFrom'] = $explode_price[0];
            $vehicleFilters['PriceTo'] = $explode_price[1];
        }

        if($this->request->has('v_miles_range') && $this->request->v_miles_range != '' && $this->request->v_miles_range != 'all')
        {
            $explode_miles = explode("-",$this->request->v_miles_range );
            $vehicleFilters['OdometerFrom'] = $explode_miles[0];
            $vehicleFilters['OdometerTo'] = $explode_miles[1];
        }


        $this->vehicleFilters = (object) $vehicleFilters;

        //pagination event. no need for filters below. Get vehicles
        if(!empty($this->pagination_page) && $this->pagination_page != 1){
           // return;
        }


        $redis_cache = json_decode($this->redis_vehicle->get($cache_identifier),true);


        // $this->_filterConditionsVehicles($redis_cache);
        // $this->_filterCacheConditions($redis_cache); //map cache to filter
        // $this->_filterCacheMakes($redis_cache); //map cache to filter
        // $this->_filterCacheModels($redis_cache); //map cache to filter
        // $this->_filterCacheYears($redis_cache); //map cache to filter
        // $this->_filterCachePriceRanges($redis_cache); //map cache to filter
        // $this->_filterCacheMileage($redis_cache); //map cache to filter

        $this->_filterCacheConditions($redis_cache); //map cache to filter
        $this->_filterAll($redis_cache); //map cache to filter



    }

    private function _getVehicleFilters($only_need_vehicles=false){
        $vehicleFilters = [];

        $vclient = new Client(['verify' =>false]);
        $vresponse = $vclient->request('GET', $this->api_endpoints['azurewebsites'].'/api/inventorywidgets/get-dealer-inventory-settings/' . $this->d_id, [
            'headers' => [
                'Accept' => 'text/plain',
                'Content-Type' => 'application/json-patch+json'
            ],
            'body' => '{}'
        ]);

        $provider = json_decode($vresponse->getBody());

        $this->provider_id = $provider->model->invetoryProviderType;

        if($this->request->has('p') && $this->request->p != ''){
            $this->pagination_page = $this->request->p;
        }

        if($this->provider_id == 1){
            $this->_getFiltersDealerTrend();
            return;
        }


        $cache_identifier = "vehicle_filters_".$this->d_id."_".$this->provider_id;
        $vehicleFilters['perPage'] = $this->pagination_items_total;
        $vehicleFilters['page'] = $this->pagination_page;


        if($this->request->has('p') && !empty($this->request->p) && is_numeric($this->request->p)){
            $vehicleFilters['page'] = $this->request->p;
        }


        $vehicleFilters = (object) $vehicleFilters;
        $this->vehicleFilters = $vehicleFilters;

        $redis_cache = json_decode($this->redis_vehicle->get($cache_identifier),true);

        $this->_filterCacheConditions($redis_cache); //map cache to filter
        $this->_filterAll($redis_cache, $only_need_vehicles); //map cache to filter


    }



    private function _filterAll($cache, $only_need_vehicles = false){
        $filter_cache = $cache['filters'];
        $vehicles_cache = $cache['vehicles'];
        $makes_cache = $cache['makes'];
        $models_cache = $cache['models'];
        $loan_condition = $this->request->loan_condition;
        $loan_payment = $this->request->loan_payment;

        $tradein = (is_numeric($this->deal->trade_value) && $this->deal->trade_value > 0) ? (int) $this->deal->trade_value : null;
        $payoff = (is_numeric($this->deal->trade_payoff) && $this->deal->trade_payoff > 0) ? (int) $this->deal->trade_payoff : null;
        $check_offer = json_decode($this->deal->offerlogix_json, true);
        $downpayment = null;
        if(isset($check_offer['downpayment']) && $check_offer['downpayment'] > 0){
          $downpayment = $check_offer['downpayment'];
        }

        if(!empty($this->request->v_price_range)){
            $price_range_request = $this->request->v_price_range;
            $xplode_price_range = explode('-', $price_range_request);
            $start_price = $xplode_price_range[0];
            $end_price = $xplode_price_range[1];
        }

        if(!empty($this->request->v_miles_range)){
            $mileage_range_request = $this->request->v_miles_range;
            $xplode_mileage_range = explode('-', $mileage_range_request);
            $start_mileage = $xplode_mileage_range[0];
            $end_mileage = $xplode_mileage_range[1];
        }

        $included_makes_temp = [];
        $included_makes = [];
        $included_makes_ids = [];
        $included_makes_names_id = [];
        foreach($makes_cache as $id => $name){
            $included_makes_temp[$id] = $name;
        }
        if(!empty($this->request->v_make)){
            foreach($this->request->v_make as $m_id){
                $included_makes_ids[] = $m_id;
                $included_makes_names_id[$included_makes_temp[$m_id]] = $m_id;
            }
        }


        $makes_filter = [];
        $models_filter = [];
        $year_filter = [];
        $price_filter = [];
        $mileage_filter = [];
        $leasing_filter = [];
        $financing_filter = [];
        $price_high = [];
        $year_high = [];
        $mileage_high = [];
        $makes_added = [];
        $engine_filter = [];
        $transmission_filter = [];
        $drivetrain_filter = [];

        $i=0;
        foreach($vehicles_cache as $v_index => $v_arr){
            $make = $v_arr['make'];
            $condition = strtolower($v_arr['condition']);
            $model = $v_arr['model'];
            $year = $v_arr['year'];
            $price = $v_arr['price'];
            $price_range = $v_arr['price_range'];
            $mileage = $v_arr['mileage'];
            $mileage_range = $v_arr['mileage_range'];
            $financing_range = $v_arr['financing_range'];
            $leasing_range = $v_arr['leasing_range'];
            $certified = $v_arr['certified'];
            $keywords_array = $v_arr['vehicle_search'];
            $engine = $v_arr['engine'];
            $transmission = $v_arr['transmission'];
            $drivetrain = $v_arr['drivetrain'];
            $offerlogix = $v_arr['offerlogix'];
            $alt_monthly_payment = null;
            if(!empty($offerlogix)){
                $m_payment = $this->_getFinancingPayment($offerlogix, $price, $tradein, $payoff, $downpayment);
                if($m_payment){
                    $financing_range = $m_payment." - ".$m_payment;
                    $v_arr['financing_range'] = $financing_range;
                    $alt_monthly_payment = $m_payment;
                }
            }
            $v_arr['alt_monthly_payment'] = $alt_monthly_payment;

            $make_id = null;

            if(empty($year))continue;

            if($this->request->has('v_search') && !empty($this->request->v_search)){
                $word_found = false;
                $keywords_explode = explode(" ", $this->request->v_search);
                foreach($keywords_explode as $word){
                    if(in_array(strtolower($word), $keywords_array) ){
                        $word_found = true;
                        break;
                    }
                }

                if(!$word_found){
                    continue;
                }
            }



                //Conditions Dropdown
            if($this->request->has('v_condition') && !empty($this->request->v_condition)){

                //if($this->request->v_condition != 'all' && $this->request->v_condition != $condition){
                if(is_array($this->request->v_condition) && !in_array($condition, $this->request->v_condition)){
                    if(in_array('certified', $this->request->v_condition)){
                        if($certified != "true"){
                            continue;
                        }
                    }else{
                        continue;
                    }
                }
            }

            //finance dropdown filter
            if($this->request->has('loan_condition') && $this->request->loan_condition == "finance"){

                if(empty($loan_payment))continue;
                if(empty($financing_range))continue;

                $xplode_financing_range = explode('-', $financing_range);
                if(count($xplode_financing_range) != 2)continue;
                if((int)trim($xplode_financing_range[1]) > $loan_payment)continue;
                // if($loan_payment < $xplode_financing_range[0])continue;
                //if($loan_payment > $xplode_financing_range[1])continue;

            }

            //finance dropdown filter
            if($this->request->has('loan_condition') && $this->request->loan_condition == "lease" ){

                if(empty($loan_payment))continue;
                if(empty($leasing_range))continue;

                $xplode_leasing_range = explode('-', $leasing_range);
                if(count($xplode_leasing_range) != 2)continue;
                if((int)trim($xplode_leasing_range[1]) > $loan_payment)continue;


                //if($loan_payment < $xplode_leasing_range[0])continue;
                //if($loan_payment > $xplode_leasing_range[1])continue;
            }



            //Want to always show all makes that pass conditions filter
            if (array_key_exists($make, $makes_filter)) {
                $makes_filter[$make]['count'] = $makes_filter[$make]['count'] + 1;
            }else{
                $makes_filter[$make] = array(
                    'count' => 1,
                    'id' => array_search($make, $makes_cache)
                );
            }



            //Makes dropdown filter vehicle
            if($this->request->has('v_make') && !empty($this->request->v_make) ){
                $make_id = array_search($make, $makes_cache);
                if(!in_array($make_id, $this->request->v_make)){
                    $add_make = false;
                    if(!empty($this->request->v_model) ){
                        foreach($this->request->v_model as $model_id){
                            $key_exists = array_key_exists($model_id, $models_cache[$make]);
                            $index = array_search($model, $models_cache[$make]);
                            if(!$key_exists){

                                continue;
                            }else{
                                if($index != $model_id){
                                    continue;
                                }
                            }
                            $add_make = true;
                            break;
                        }
                    }

                    if(!$add_make){
                        continue;
                    }

                }
            }

            //dd(array($makes_cache,$models_cache));

            //Models dropdown filter
            if($this->request->has('v_model') && !empty($this->request->v_model) ){
                $add_model = false;

                foreach($this->request->v_model as $model_id){
                    $key_exists = array_key_exists($model_id, $models_cache[$make]);
                    $index = array_search($model, $models_cache[$make]);
                    if(!$key_exists){

                        continue;
                    }else{
                        if($index != $model_id){
                            continue;
                        }
                    }
                    $add_model = true;
                    break;
                }

                if(!$add_model){
                    if(!$only_need_vehicles && isset($included_makes_names_id[$make]) && in_array($included_makes_names_id[$make], $included_makes_ids)){

                    }else{
                        if($only_need_vehicles && isset($included_makes_names_id[$make]) && in_array($included_makes_names_id[$make], $included_makes_ids)){
                            continue; //don't pull vehicles if model not selected for vehicles on search page
                        }else{
                            continue;
                        }

                    }

                }
            }

            //engine drop down
            if($this->request->has('v_engine') && !empty($this->request->v_engine)  ){
                if(!in_array($engine, $this->request->v_engine)){
                    continue;
                }

            }

            //transmission drop down
            if($this->request->has('v_transmission') && !empty($this->request->v_transmission)  ){
                if(!in_array($transmission, $this->request->v_transmission)){
                    continue;
                }

            }

            //drivetrain drop down
            if($this->request->has('v_drivetrain') && !empty($this->request->v_drivetrain)  ){
                if(!in_array($drivetrain, $this->request->v_drivetrain)){
                    continue;
                }

            }


            //Year dropdown filter
            if($this->request->has('v_year_from') && !empty($this->request->v_year_from) && $this->request->has('v_year_to') && !empty($this->request->v_year_to) ){
                if($year < $this->request->v_year_from || $year > $this->request->v_year_to)continue;

            }

            //Price dropdown filter
            if($this->request->has('v_price_range') && !empty($this->request->v_price_range) && strlen($this->request->v_price_range) > 0 && $loan_condition == 'cash' ){
                if($price < $start_price || $price > $end_price){
                    continue;
                }

            }

            //mileage dropdown filter
            if($this->request->has('v_miles_range') && !empty($this->request->v_miles_range) && strlen($this->request->v_miles_range) > 0  ){

                if($mileage < $start_mileage || $mileage > $end_mileage){
                    continue;
                }
            }



            //only models that pass above filters
            if (array_key_exists($model, $models_filter)) {
                $models_filter[$model]['count'] = $models_filter[$model]['count'] + 1;
            }else{
                $models_filter[$model] = array(
                    'count' => 1,
                    'id' => array_search($model, $models_cache[$make]),
                    'make' => $make
                );
            }




            //only years that pass above filters
            if (array_key_exists($year, $year_filter)) {
                $year_filter[$year]['count'] = $year_filter[$year]['count'] + 1;
            }else{
                $year_filter[$year] = array(
                    'count' => 1
                );

                $year_high[] = $year;
            }

            if (array_key_exists($engine, $engine_filter)) {
                $engine_filter[$engine]['count'] = $engine_filter[$engine]['count'] + 1;
            }else{
                $engine_filter[$engine] = array(
                    'count' => 1
                );

            }

            if (array_key_exists($transmission, $transmission_filter)) {
                $transmission_filter[$transmission]['count'] = $transmission_filter[$transmission]['count'] + 1;
            }else{
                $transmission_filter[$transmission] = array(
                    'count' => 1
                );

            }

            if (array_key_exists($drivetrain, $drivetrain_filter)) {
                $drivetrain_filter[$drivetrain]['count'] = $drivetrain_filter[$drivetrain]['count'] + 1;
            }else{
                $drivetrain_filter[$drivetrain] = array(
                    'count' => 1
                );

            }


            //only prices that pass above filters
            if (array_key_exists($price_range, $price_filter)) {
                $price_filter[$price_range]['count'] = $price_filter[$price_range]['count'] + 1;
            }else{
                $format_price = explode("-", $price_range);
                $price_filter[$price_range] = array(
                    'count' => 1,
                    'low' => '$'.number_format($format_price[0],0,"",","),
                    'high' => '$'.number_format($format_price[1],0,"",",")
                );

                $price_high[] = $format_price[0];
                $price_high[] = $format_price[1];
            }

            //only mileage that pass above filters
            if (array_key_exists($mileage_range, $mileage_filter)) {
                $mileage_filter[$mileage_range]['count'] = $mileage_filter[$mileage_range]['count'] + 1;
            }else{
                $format_mileage = explode("-", $mileage_range);
                $mileage_filter[$mileage_range] = array(
                    'count' => 1,
                    'low' => number_format($format_mileage[0],0,"",","),
                    'high' => number_format($format_mileage[1],0,"",",")
                );

                $mileage_high[] = $format_mileage[0];
                $mileage_high[] = $format_mileage[1];

            }

            if (array_key_exists($financing_range, $financing_filter)) {
                $financing_filter[$financing_range]['count'] = $financing_filter[$financing_range]['count'] + 1;
            }else{
                if(!empty($financing_range)){
                    $financing_filter[$financing_range] = array(
                        'count' => 1
                    );
                }
            }

            if (array_key_exists($leasing_range, $leasing_filter)) {
                $leasing_filter[$leasing_range]['count'] = $leasing_filter[$leasing_range]['count'] + 1;
            }else{
                if(!empty($leasing_range)){
                    $leasing_filter[$leasing_range] = array(
                        'count' => 1
                    );
                }
            }

            $this->temp_vehicles[] = $v_arr;

        }

        $this->filters->brands = $makes_filter;
        $this->filters->models = $models_filter;
        $this->filters->engines = $engine_filter;
        $this->filters->transmissions = $transmission_filter;
        $this->filters->drivetrains = $drivetrain_filter;
        foreach($this->filters->models as $index => $item){
           if(is_numeric($index)){
               $new_index = $item['make'].' '.$index;
               unset($this->filters->models[$index]);
               $this->filters->models[$new_index] = $item;
           }
        }

        $name=array_column($this->filters->models,"make");
        array_multisort($name, SORT_ASC, SORT_STRING, $this->filters->models);


        $this->filters->years = $year_filter;
        krsort($this->filters->years,SORT_NUMERIC);
        $this->filters->priceRanges = $price_filter;
        ksort($this->filters->years,SORT_NUMERIC);
        ksort($this->filters->engines,SORT_NUMERIC);

        $this->filters->miles = $mileage_filter;
        $this->filters->financing = $financing_filter;
        $this->filters->leasing = $leasing_filter;
        $this->filters->loanCondition = $loan_condition;
        $this->_filterSort($this->request->sortBy);
        if(!empty($price_high)){
            rsort($price_high);
            $this->filters->high_price = $price_high[0];
            $this->filters->low_price = $price_high[count($price_high)-1];
        }else{
            $this->filters->high_price = 0;
            $this->filters->low_price = 0;
        }

        if(!empty($year_high)){
            rsort($year_high);
            $this->filters->high_year = $year_high[0];
            $this->filters->low_year = $year_high[count($year_high)-1];
        }else{
            $this->filters->high_year = 2000;
            $this->filters->low_year = 2000;
        }

        if(!empty($mileage_high)){
            rsort($mileage_high);
            $this->filters->high_mileage = $mileage_high[0];
            $this->filters->low_mileage = $mileage_high[count($mileage_high)-1];
        }else{
            $this->filters->high_mileage = 2000;
            $this->filters->low_mileage = 2000;
        }

        $this->filters->only_need_vehicles = $only_need_vehicles;
        $this->filters->total_vehicles = count($this->temp_vehicles);
    }

    private function _getFinancingPayment($offerlogix, $price, $tradein, $payoff, $downpayment){
        $ol = false;
        $y = $price;
        if(!empty($offerlogix['good'])){
            $ol = $offerlogix['good'];
        }elseif(!empty($offerlogix['excellent'])){
            $ol = $offerlogix['excellent'];
        }elseif(!empty($offerlogix['fair'])){
            $ol = $offerlogix['fair'];
        }elseif(!empty($offerlogix['poor'])){
            $ol = $offerlogix['poor'];
        }
        if($ol == false)return false;
        if(empty($ol['financing']))return false;

        $rebates = 0;
        $doc_fee = (int) $ol['doc_fee'];
        $term = (int) $ol['financing']['term'];
        if(!$downpayment){
            $downpayment = (int) $ol['financing']['downPayment'];
        }

        foreach($ol['rebates'] as $r_i => $r_arr){
            if($r_arr['type'] == 'financing'){
                $rebates += (int) $r_arr['cash'];
            }
        }

        if(empty($ol['financing']['options'][$term]))return false;
        $item = $ol['financing']['options'][$term];
        $sales_tax = $item['salesTaxPct'];
        $ir = $item['interestRate'];
        $_ir = $item['interestRate'] / 100 / 12;
        $fees = 0;
        foreach($item['fees_detail'] as $f_i => $f_arr){
            if($f_arr['feeType'] == 'Registration'){
                $fees += (int) $f_arr['amount'];
            }
        }
        $price += $doc_fee;
        $price -= $tradein;
        //if($y == 51441)dd($ir);
        $price += $payoff;
        $price += $fees;
        //if($y == 51441)dd($price);
        $taxes = $price * $sales_tax;
       // if($y == 51441)dd($sales_tax);
        $price += $taxes;
        $price -= $rebates;
        $price -= (int) $downpayment;
        //if($y == 51441)dd($rebates);
        $_x = pow(1 + $_ir, $term);
        //if($y == 51441)dd($_x);
        $payment = (int) ($price * $_x * $_ir)/($_x-1);
        //if($y == 51441)dd($payment);

        return (int) $payment;
    }

    private function _filterSort($sort){
        if($sort == 'make_asc'){
            $make = array_column($this->temp_vehicles, 'make');
            array_multisort($make, SORT_ASC, $this->temp_vehicles);
        }elseif($sort == 'make_desc'){
            $make = array_column($this->temp_vehicles, 'make');
            array_multisort($make, SORT_DESC, $this->temp_vehicles);
        }elseif($sort == 'price_desc'){
            $price = array_column($this->temp_vehicles, 'price');
            array_multisort($price, SORT_DESC, $this->temp_vehicles);
        }elseif($sort == 'price_asc'){
            $price = array_column($this->temp_vehicles, 'price');
            array_multisort($price, SORT_ASC, $this->temp_vehicles);
        }elseif($sort == 'year_desc'){
            $year = array_column($this->temp_vehicles, 'year');
            array_multisort($year, SORT_DESC, $this->temp_vehicles);
        }elseif($sort == 'year_asc'){
            $year = array_column($this->temp_vehicles, 'year');
            array_multisort($year, SORT_ASC, $this->temp_vehicles);
        }

    }



    private function _filterConditionsVehicles($cache){
        $filter_cache = $cache['filters'];
        $vehicles_cache = $cache['vehicles'];
        $makes_cache = $cache['makes'];
        $models_cache = $cache['models'];


        $makes_filter = [];
        $makes_added = [];
        $i=0;
        foreach($vehicles_cache as $v_index => $v_arr){

            $make = $v_arr['make'];
            $condition = strtolower($v_arr['condition']);
            $model = $v_arr['model'];
            $year = $v_arr['year'];
            $price = $v_arr['price'];
            $mileage = $v_arr['mileage'];
            $make_id = null;

            //Conditions Dropdown
            if($this->request->has('v_condition') && !empty($this->request->v_condition)){

                if($this->request->v_condition != 'all' && $this->request->v_condition != $condition){

                    continue;
                }
            }

            //Makes dropdown
            if($this->request->has('v_make') && strlen($this->request->v_make) > 0 ){
                $make_id = array_search($make, $makes_cache);
                if($this->request->v_make != 'all' && $this->request->v_make != $make_id){

                    continue;
                }
            }

            //Models dropdown
            if($this->request->has('v_model') && strlen($this->request->v_model) > 0 && $this->request->v_model != 'all' ){
                if(isset($models_cache[$make])){
                    $model_id = $this->request->v_model;
                    $key_exists = array_key_exists($model_id, $models_cache[$make]);
                    if(!$key_exists){

                            continue;
                    }

                }
            }

            //Year dropdown
            if($this->request->has('v_year') && !empty($this->request->v_year) && strlen($this->request->v_year) > 0 && $this->request->v_year != 'all' ){
                    if($this->request->v_year != $year){

                            continue;
                    }

            }

            //Price dropdown
            if($this->request->has('v_price_range') && !empty($this->request->v_price_range) && strlen($this->request->v_price_range) > 0 && $this->request->v_price_range != 'all' ){
                $price_explode = explode("-", $this->request->v_price_range);

                if( !($price >= $price_explode[0]  && $price <= $price_explode[1])){

                        continue;
                }

            }

            //Mileage dropdown
            if($this->request->has('v_miles_range') && !empty($this->request->v_miles_range) && strlen($this->request->v_miles_range) > 0 && $this->request->v_miles_range != 'all' ){
                $miles_explode = explode("-", $this->request->v_miles_range);

                if( $mileage >= $miles_explode[0]  && $mileage <= $miles_explode[1]){
                }else{
                    continue;
                }

            }

            $this->temp_vehicles[] = $v_arr;

        }

    }


    private function _filterCacheYears($cache){

        $filter_cache = $cache['filters'];
        $makes_cache = $cache['makes'];
        $models_cache = $cache['models'];

        $year_filter = [];

        foreach($this->temp_vehicles as $v_index => $v_arr){
            $year = $v_arr['year'];
            $condition = strtolower($v_arr['condition']);
            $make = $v_arr['make'];
            $model = $v_arr['model'];
            if(empty($year))continue;
            if($this->request->has('v_condition') && !empty($this->request->v_condition)){

                if($this->request->v_condition != 'all' && $this->request->v_condition != $condition){

                    continue;
                }
            }

            if($this->request->has('v_make') && strlen($this->request->v_make) > 0 ){
                $make_id = array_search($make, $makes_cache);
                if($this->request->v_make != 'all' && $this->request->v_make != $make_id){

                    continue;
                }
            }

            //Models dropdown
            if($this->request->has('v_model') && strlen($this->request->v_model) > 0 && $this->request->v_model != 'all' ){
                if(isset($models_cache[$make])){
                    $model_id = $this->request->v_model;
                    $key_exists = array_key_exists($model_id, $models_cache[$make]);
                    $index = array_search($model, $models_cache[$make]);
                    if($key_exists){
                        if($index != $model_id){
                            continue;
                        }
                    }

                }
            }


            if (array_key_exists($year, $year_filter)) {
                $year_filter[$year]['count'] = $year_filter[$year]['count'] + 1;
            }else{
                $year_filter[$year] = array(
                    'count' => 1
                );
            }

        }

        $this->filters->years = $year_filter;

    }

    private function _filterCachePriceRanges($cache){

        $filter_cache = $cache['filters'];
        $makes_cache = $cache['makes'];
        $models_cache = $cache['models'];

        $price_filter = [];

        foreach($this->temp_vehicles as $v_index => $v_arr){
            $price_range = $v_arr['price_range'];
            $year = $v_arr['year'];
            $condition = strtolower($v_arr['condition']);
            $make = $v_arr['make'];
            $model = $v_arr['model'];

            if($this->request->has('v_condition') && !empty($this->request->v_condition)){

                if($this->request->v_condition != 'all' && $this->request->v_condition != $condition){

                    continue;
                }
            }

            if($this->request->has('v_make') && strlen($this->request->v_make) > 0 ){
                $make_id = array_search($make, $makes_cache);
                if($this->request->v_make != 'all' && $this->request->v_make != $make_id){

                    continue;
                }
            }

            //Models dropdown
            if($this->request->has('v_model') && strlen($this->request->v_model) > 0 && $this->request->v_model != 'all' ){
                if(isset($models_cache[$make])){
                    $model_id = $this->request->v_model;
                    $key_exists = array_key_exists($model_id, $models_cache[$make]);
                    $index = array_search($model, $models_cache[$make]);
                    if($key_exists){
                        if($index != $model_id){
                            continue;
                        }
                    }

                }
            }


            if($this->request->has('v_price_range') && !empty($this->request->v_price_range) && strlen($this->request->v_price_range) > 0 && $this->request->v_price_range != 'all' ){
                if($this->request->v_price_range != $price_range)continue;
            }

            if (array_key_exists($price_range, $price_filter)) {
                $price_filter[$price_range]['count'] = $price_filter[$price_range]['count'] + 1;
            }else{
                $format_price = explode("-", $price_range);
                $price_filter[$price_range] = array(
                    'count' => 1,
                    'low' => '$'.number_format($format_price[0],0,"",","),
                    'high' => '$'.number_format($format_price[1],0,"",",")
                );
            }
        }


        $this->filters->priceRanges = $price_filter;


    }

    private function _filterCacheMileage($cache){

        $filter_cache = $cache['filters'];
        $makes_cache = $cache['makes'];
        $models_cache = $cache['models'];

        $mileage_filter = [];
        foreach($this->temp_vehicles as $v_index => $v_arr){
            $mileage = $v_arr['mileage_range'];
            $price_range = $v_arr['price_range'];
            $year = $v_arr['year'];
            $condition = strtolower($v_arr['condition']);
            $make = $v_arr['make'];
            $model = $v_arr['model'];

            if($this->request->has('v_condition') && !empty($this->request->v_condition)){

                if($this->request->v_condition != 'all' && $this->request->v_condition != $condition){

                    continue;
                }
            }

            if($this->request->has('v_make') && strlen($this->request->v_make) > 0 ){
                $make_id = array_search($make, $makes_cache);
                if($this->request->v_make != 'all' && $this->request->v_make != $make_id){

                    continue;
                }
            }

            //Models dropdown
            if($this->request->has('v_model') && strlen($this->request->v_model) > 0 && $this->request->v_model != 'all' ){
                if(isset($models_cache[$make])){
                    $model_id = $this->request->v_model;
                    $key_exists = array_key_exists($model_id, $models_cache[$make]);
                    $index = array_search($model, $models_cache[$make]);
                    if($key_exists){
                        if($index != $model_id){
                            continue;
                        }
                    }

                }
            }

            if($this->request->has('v_price_range') && !empty($this->request->v_price_range) && strlen($this->request->v_price_range) > 0 && $this->request->v_price_range != 'all' ){
                if($this->request->v_price_range != $price_range)continue;
            }

            if($this->request->has('v_miles_range') && !empty($this->request->v_miles_range) && strlen($this->request->v_miles_range) > 0 && $this->request->v_miles_range != 'all' ){

                if($mileage != $this->request->v_miles_range)continue;


            }


            if (array_key_exists($mileage, $mileage_filter)) {
                $mileage_filter[$mileage]['count'] = $mileage_filter[$mileage]['count'] + 1;
            }else{
                $format_mileage = explode("-", $mileage);
                $mileage_filter[$mileage] = array(
                    'count' => 1,
                    'low' => number_format($format_mileage[0],0,"",","),
                    'high' => number_format($format_mileage[1],0,"",",")
                );
            }
        }

        $this->filters->miles = $mileage_filter;

    }

    private function _filterCacheMakes($cache){

        $filter_cache = $cache['filters'];
        $vehicles_cache = $cache['vehicles'];
        $makes_cache = $cache['makes'];

        $makes_filter = [];
        $makes_added = [];

        foreach($vehicles_cache as $v_index => $v_arr){
            $make = $v_arr['make'];
            $condition = strtolower($v_arr['condition']);

            if($this->request->has('v_condition') && !empty($this->request->v_condition)){

                if($this->request->v_condition != 'all' && $this->request->v_condition != $condition){

                    continue;
                }
            }

            if (array_key_exists($make, $makes_filter)) {
                $makes_filter[$make]['count'] = $makes_filter[$make]['count'] + 1;
            }else{
                $makes_filter[$make] = array(
                    'count' => 1,
                    'id' => array_search($make, $makes_cache)
                );
            }

        }

        $this->filters->brands = $makes_filter;

    }

    private function _filterCacheModels($cache){

        $models_cache = $cache['models'];

        $models_filter = [];

            foreach($this->temp_vehicles as $v_index => $v_arr){
                $model = $v_arr['model'];
                $make = $v_arr['make'];
                $year = $v_arr['year'];

                if (array_key_exists($model, $models_filter)) {
                    $models_filter[$model]['count'] = $models_filter[$model]['count'] + 1;
                }else{
                    $models_filter[$model] = array(
                        'count' => 1,
                        'id' => array_search($model, $models_cache[$make])
                    );
                }


            }

        $this->filters->models = $models_filter;

    }

    private function _filterCacheConditions($cache){

        $filter_cache = $cache['filters'];

        $condition_filter = [];

        if(isset($filter_cache['total'])){
            $condition_filter['all'] = 'All ('.$filter_cache['total'].')';
        }else{
            $condition_filter['all'] = 'All';
        }

        if(isset($filter_cache['new'])){
            $condition_filter['new'] = 'NEW ('.$filter_cache['new'].')';
        }else{
            $condition_filter['new'] = 'NEW';
        }

        if(isset($filter_cache['used'])){
            $condition_filter['used'] = 'USED ('.$filter_cache['used'].')';
        }else{
            $condition_filter['used'] = 'USED';
        }

        if(isset($filter_cache['certified'])){
            $condition_filter['certified'] = 'CERTIFIED ('.$filter_cache['certified'].')';
        }else{
            $condition_filter['certified'] = 'CERTIFIED';
        }

        $this->filters->conditions =  $condition_filter;

    }



    private function _getSalespeople(){
        $cache_identifier = 'apistore.salespeople_'.$this->d_id;

        $salespeople = $this->_redisGet($cache_identifier, "salespeople");

        if(!$salespeople){
            $client = new Client(['base_uri' => $this->api_endpoints['buildabrand'],'verify'=>false]);
            $sresponse = $client->request('GET', '/getSalesPeople?accessToken=' . $this->accessToken . '&d_id=' . $this->d_id);

            $response = json_decode($sresponse->getBody());
            if(isset($response->salespeople)){
                $salespeople = $response->salespeople;
            }else{
                $salespeople = "";
            }
        }



        $this->salespeople = $salespeople;
    }

    private function _setProviderId(){
        $vclient = new Client(['verify' =>false]);
        $vresponse = $vclient->request('GET', $this->api_endpoints['azurewebsites'].'/api/inventorywidgets/get-dealer-inventory-settings/' . $this->d_id, [
            'headers' => [
                'Accept' => 'text/plain',
                'Content-Type' => 'application/json-patch+json'
            ],
            'body' => '{}'
        ]);

        $provider = json_decode($vresponse->getBody());

        $this->provider_id = $provider->model->invetoryProviderType;
    }

    private function _getHours(){
        $hclient = new Client(['verify' =>false]);
        $hresponse = $hclient->request('GET', $this->api_endpoints['testdrive'].'/dealer/' . $this->dealer->d_id .'/open-hours');
        $this->hours = json_decode($hresponse->getBody());
    }

    private function _getDaysClosed(){
        $daysClosed = array();

        foreach($this->hours as $hour)
        {
            if(!$hour->is_open)
            {
                if($hour->name == 'Sunday')
                {
                    $daysClosed[] = 0;
                } elseif($hour->name == 'Monday')
                {
                    $daysClosed[] = 1;
                } elseif($hour->name == 'Tuesday')
                {
                    $daysClosed[] = 2;
                } elseif($hour->name == 'Wednesday')
                {
                    $daysClosed[] = 3;
                } elseif($hour->name == 'Thursday')
                {
                    $daysClosed[] = 4;
                } elseif($hour->name == 'Friday')
                {
                    $daysClosed[] = 5;
                } elseif($hour->name == 'Saturday')
                {
                    $daysClosed[] = 6;
                }
            }
        }

        $this->daysClosed = $daysClosed;
    }

    private function _getBeverages(){
        $bclient = new Client(['verify' =>false]);
        $bresponse = $bclient->request('GET', $this->api_endpoints['testdrive'].'/dealer/' . $this->dealer->d_id .'/beverages');
        $this->beverages = json_decode($bresponse->getBody());
    }

    private function _getViewData(){

        $next = $this->_getNext();

        return ['currentPage' => $this->currentPage, 'd_id' => $this->d_id,'deal_id'=>$this->deal_id, 'api_endpoints' => $this->api_endpoints,
        'dealer' => $this->dealer, 'deal'=>$this->deal, 'accessToken' => $this->accessToken, 'contactCheck'=>$this->contactCheck,
         's_id' => $this->s_id, 'tradeCheck' => $this->tradeCheck, 'vehicleCheck' => $this->vehicleCheck, 'progress_show' => false,
         'appointmentCheck' => $this->appointmentCheck, 'preapprovedCheck' => $this->preapprovedCheck, 'paymentCheck' => $this->paymentCheck,
         'percentage' => $this->percentage, 'user_token' => $this->user_token, 'minSaved' => $this->minSaved, 'next' => $next, 'is_iframe' => $this->request->session()->get('is_iframe')
        ];


    }

    private function _getNext(){
        $next = 'summary';
        if($this->salesperson == ""){
            $next = 'salesperson';
        }else if($this->vehicle == ""){
            $next = 'vehicle';
        }else if(!$this->appointmentCheck){
            $next = 'appointment';
        }else if(!$this->preapprovedCheck){
            $next = 'preapproved';
        }

        return $next;
    }

    private function _appointmentCheck(){
        if($this->deal->td_id != null && is_numeric($this->deal->td_id) )
        {
            $this->appointmentCheck = true;
            $this->percentage+= 5;
            $this->minSaved+= 10;
        }
    }

    private function _tradeCheck(){
        if($this->deal->trade_year != null && $this->deal->trade_make != null && $this->deal->trade_model != null && $this->deal->trade_trim != null)
        {
            $this->tradeCheck = true;
            $this->percentage+= 15;
            $this->minSaved+= 20;
        }
    }

    private function _paCheck(){
        if($this->deal->address != null && $this->deal->zipcode != null && $this->deal->state != null && $this->deal->city != null && $this->deal->birthday != null)
        {
            $this->paCheckOne = true;
        }

        if($this->deal->income != null && $this->deal->years_address != null && $this->deal->months_address != null && $this->deal->rent_own != null &&
            $this->deal->rent_own_amount != null && $this->deal->months_company != null && $this->deal->years_company != null && $this->deal->company_phone != null &&
            $this->deal->company_address != null && $this->deal->company_name != null && $this->deal->job_title != null && $this->deal->employment_status != null)
        {
            $this->paCheckTwo = true;
        }

        if($this->paCheckOne && $this->paCheckTwo)
        {
            $this->preapprovedCheck = true;
            $this->percentage+= 30;
        }
    }

    private function _paymentCheck(){
        if( ($this->deal->payment_monthly != null && $this->deal->payment_term != null && $this->deal->payment_total != null) || (!empty($this->deal->offerlogix_json) && $this->deal->offerlogix_json != '{}'))
        {
            $this->paymentCheck = true;
            $this->percentage+= 15;
            $this->minSaved+= 20;
        }
    }

    private function _contactCheck(){
        if($this->deal->contact_id != null && is_numeric($this->deal->contact_id))
        {
            $this->contactCheck = true;
            $this->percentage+= 5;
            $this->minSaved+= 10;
        }
    }

    private function _submitContract(){
        $deal = $this->_getDeal();

        $deal->fname = $this->request->fname;
        $deal->lname = $this->request->lname;
        $deal->phone = $this->request->phone;
        $deal->email = $this->request->email;
        //$deal->contact_call_opt = $this->request->contact_call_opt;
        //$deal->contact_text_opt = $this->request->contact_text_opt;
        //$deal->contact_email_opt = $this->request->contact_email_opt;
        $deal->text_opt_in = $this->request->text_opt_in;
        $bodyFilters = '';

        $bodyFilters .= "CompanyId: " . $deal->d_id . ",";

        if(!is_null($deal->s_id))
        {
            $bodyFilters .= 'SalespeopleId: ' . $deal->s_id . ',';
        }

        if(!is_null($deal->fname))
        {
            $bodyFilters .= 'FirstName: "' . $deal->fname . '",';
        }

        if(!is_null($deal->lname))
        {
            $bodyFilters .= 'LastName: "' . $deal->lname . '",';
        }

        if(!is_null($deal->email))
        {
            $bodyFilters .= 'EmailAddress: "' . $deal->email . '",';
        }

        if(!is_null($deal->phone))
        {
            $bodyFilters .= 'PhoneNumber: "' . $deal->phone . '",';
        }

        if(!is_null($deal->text_opt_in))
        {
            $bodyFilters .= 'IsAgreeReceiveTextMessages: ' . $deal->text_opt_in . ',';
        }

        return [$deal, $bodyFilters];
    }

    private function _submitTrade(){
        $bodyFilters = [];
        $deal = $this->_getDeal();

        $deal->trade_year = $this->request->trade_year;
        $deal->trade_make = $this->request->trade_make;
        $deal->trade_model = $this->request->trade_model;
        $deal->trade_trim = $this->request->trade_trim;
         $deal->trade_drivetrain = $this->request->trade_drivetrain;
        //$deal->trade_condition = $this->request->trade_condition;
        $deal->trade_miles = $this->request->trade_miles;
        //$deal->trade_payoff = $this->request->trade_payoff;
        $deal->trade_vin = $this->request->trade_vin;
        $deal->trade_value = $this->request->trade_value;
        $deal->trade_body = $this->request->trade_body;
        $deal->trade_engine = $this->request->trade_engine;
        $deal->trade_fuel = $this->request->trade_fuel;



        $bodyFilters["DealerId"] = $deal->d_id;

        if(!is_null($deal->s_id))
        {
            $bodyFilters["SalespersonId"] = $deal->s_id;
        }

         if(!is_null($deal->contact_id))
        {
            $bodyFilters["ContactId"] = $deal->contact_id;
        }

        if(!is_null($deal->fname))
        {
            $bodyFilters["FirstName"] = $deal->fname;
        }

        if(!is_null($deal->lname))
        {
            $bodyFilters["LastName"] = $deal->lname;
        }

        if(!is_null($deal->email))
        {
            $bodyFilters["Email"] = $deal->email;
        }

        if(!is_null($deal->phone))
        {
            $bodyFilters["Phone"] = $deal->phone;
        }

        if(!is_null($deal->trade_year))
        {
            $bodyFilters["Year"] =$deal->trade_year;
        }

        if(!is_null($deal->trade_make))
        {
            $bodyFilters["Make"] = $deal->trade_make;
        }

        if(!is_null($deal->trade_model))
        {
            $bodyFilters["Model"] = $deal->trade_model;
        }

        if(!is_null($deal->trade_trim))
        {
            $bodyFilters["Trim"] = $deal->trade_trim;
        }else{
            if(!is_null($deal->trade_body)){
                $bodyFilters["Trim"] = $deal->trade_body;
            }
        }

        if(!is_null($deal->trade_miles))
        {
            $bodyFilters["Mileage"] = $deal->trade_miles;
        }

        // if(!is_null($deal->trade_condition))
        // {
        //     $bodyFilters .= 'Condition: "' . $deal->trade_condition . '",';
        // }
        $bodyFilters["Condition"] = "Good";

        return [$deal, $bodyFilters];
    }

    private function _submitApprovalEmployee($deal){

       // $deal = $this->_getDeal();
        $this->_getSalesperson();
        $bodyFilters = '';

        $bodyFilters .= '"DealerId": ' . $deal->d_id . ",";
        if($deal->s_id != null)
        {
            $full_name = $this->salesperson->salesperson->first." ".$this->salesperson->salesperson->last;
            $bodyFilters .= '"SalespersonId": ' . $deal->s_id . ',';
            $bodyFilters .= '"SalesFullName": "' . $full_name . '",';


        }

        if(!is_null($deal->fname) && !is_null($deal->lname) && !is_null($deal->email) && !is_null($deal->phone))
        {
            $bodyFilters .= '"ContactId": ' . $deal->contact_id . ',';
            $bodyFilters .= '"FirstName": "' . $deal->fname . '",';
            $bodyFilters .= '"MiddleName": "' . $deal->mname . '",';
            $bodyFilters .= '"LastName": "' . $deal->lname . '",';
            $bodyFilters .= '"Email": "' . $deal->email . '",';
            $bodyFilters .= '"Phone": "' . $deal->phone . '",';
        }

        if(!is_null($deal->address))
        {
            $bodyFilters .= '"Address": "' . $deal->address . '",';
            $bodyFilters .= '"Apartment": "' . $deal->apt_unit . '",';
            $bodyFilters .= '"PostalCode": "' . $deal->zipcode . '",';
            $bodyFilters .= '"City": "' . $deal->city . '",';
            $bodyFilters .= '"State": "' . $deal->state . '",';
            $bodyFilters .= '"BirthDate": "' . $deal->birthday . '",';
        }

        if(!is_null($deal->vehicle_stock))
        {
            $vclient = new Client(['verify' =>false]);
            $vresponse = $vclient->request('GET', $this->api_endpoints['azurewebsites'].'/api/inventorywidgets/getbyid?id=' . $deal->vehicle_id . '&dealerId=' . $deal->d_id, [
                'headers' => [
                    'Accept' => 'text/plain',
                    'Content-Type' => 'application/json-patch+json'
                ],
                'body' => '{}'
            ]);
            $vehicle = json_decode($vresponse->getBody());

            $bodyFilters .= '"Vehicle": { "StockNumber": "' . $deal->vehicle_stock . '", "Vin": "' . $vehicle->model->vin . '", "Year": "' . $vehicle->model->year . '", "Make": "' . $vehicle->model->make . '", "Model": "' . $vehicle->model->modelName . '", "Trim": "' . $vehicle->model->trim . '"},';
        }

        if(!is_null($deal->employment_status))
        {
            $bodyFilters .= '"EmployeeInformation": {';
            $bodyFilters .= '"EmploymentStatus": "' . $deal->employment_status . '",';
            $bodyFilters .= '"JobTitle": "' . $deal->job_title . '",';
            $bodyFilters .= '"CompanyName": "' . $deal->company_name . '",';
            $bodyFilters .= '"AverageHouseholdIncome": ' . number_format($deal->income, 0, '', '') . ',';
            //$bodyFilters .= '"DriversLicenseNo": "' . $this->request->dl_no . '",';
            //$bodyFilters .= '"DriversLicenseState": "' . $this->request->dl_state . '",';
            //$bodyFilters .= '"SSNSocialSec": "' . $this->request->ssn . '",';
            $bodyFilters .= '"RentOwn": "' . $deal->rent_own . '",';
            $bodyFilters .= '"MortgageLeaseRentAmount": ' . number_format($deal->rent_own_amount, 0, '', '') . ',';
            $bodyFilters .= '"YearsAtAddress": ' . number_format($deal->years_address, 0, '', '') . ',';
            $bodyFilters .= '"MonthsAtAddress": ' . number_format($deal->months_address, 0, '', '');
            $bodyFilters .= '},';
        }
       // $bodyFilters .= '"Comments" :{"DriversLicenseNo":"' . $this->request->dl_no . '","DriversLicenseState":"' . $this->request->dl_state . '", "SSNSocialSec":"' . $this->request->ssn . '"},';


        return [$deal, $bodyFilters];
    }

    private function _submitAppointment(){
        $deal = $this->_getDeal();

        $deal->td_date = $this->request->td_date;
        $deal->td_time = $this->request->td_time;
        $deal->td_beverage = $this->request->td_beverage;
        $deal->td_period = $this->request->td_period;
        if(!$this->request->has('td_comments'))
        {
            $deal->td_comments = 'No Comments';
        }
        else
        {
            $deal->td_comments = $this->request->td_comments;
        }
        //$deal->td_share_text = $this->request->td_share_text;
        //$deal->td_share_email = $this->request->td_share_email;
        $deal->td_calendar = $this->request->td_calendar;

        $dateTime = $deal->td_date . ' ' . $deal->td_time;

        $bodyFilters = '';

        $bodyFilters .= '"DealerId": ' . $deal->d_id . ",";


        if($deal->s_id != null)
        {
            $client = new Client(['base_uri' => $this->api_endpoints['buildabrand'],'verify' =>false]);
            $response = $client->request('GET', '/getSalesPersonByID?accessToken=' . $this->accessToken . '&s_id=' . $deal->s_id . '&d_id=' . $deal->d_id);

            $salesperson = json_decode($response->getBody());
            $salesperson = $salesperson->salesperson;

            $bodyFilters .= '"SalespersonId": ' . $deal->s_id . ',';
            $bodyFilters .= '"Expert": { "Id": ' . $deal->s_id . ', "Title": "' . $salesperson->first . ' ' . $salesperson->last . '"},';
            $full_name = $salesperson->first." ".$salesperson->last;
            $bodyFilters .= '"SalesFullName": "' . $full_name . '",';
        }

        $bodyFilters .= '"AppointmentDate": "' . Carbon::parse($dateTime)->toDateTimeString() . '",';

        if(!is_null($deal->fname) && !is_null($deal->lname) && !is_null($deal->email) && !is_null($deal->phone))
        {
            $text = ($deal->text_opt_in == 1) ? 1 : 0;
            $sms = ($deal->td_share_text == 1) ? 1 : 0;
            $email = ($deal->td_share_email == 1) ? 1 : 0;


            $bodyFilters .= '"Contact": { ';
            $bodyFilters .= '"FirstName": "' . $deal->fname . '",';
            $bodyFilters .= '"LastName": "' . $deal->lname . '",';
            $bodyFilters .= '"Id": ' . $deal->contact_id . ',';
            $bodyFilters .= '"Email": "' . $deal->email . '",';
            $bodyFilters .= '"Phone": "' . $deal->phone . '",';
            $bodyFilters .= '"IsAgreeReceiveTextMessages": ' . $text .',';
            $bodyFilters .= '"InformedBySms": ' . $sms . ',';
            $bodyFilters .= '"InformedByEmail": ' . $email;
            $bodyFilters .= '},';
        }

        if($deal->td_beverage != null)
        {
            $bclient = new Client(['verify' =>false]);
            $bresponse = $bclient->request('GET', $this->api_endpoints['testdrive'].'/dealer/' . $deal->d_id .'/beverages');
            $beverages = json_decode($bresponse->getBody());

            foreach($beverages as $beverage)
            {
                if($beverage->title == $deal->td_beverage)
                {
                    $bodyFilters .= '"Beverage" : { "Id": ' . $beverage->id . ',"Title": "' . $deal->td_beverage . '", "Comments": "' . $deal->td_comments . '"},';
                }
            }

        }

        if(!is_null($deal->vehicle_stock))
        {
            $vclient = new Client(['verify' =>false]);
            $vresponse = $vclient->request('GET', $this->api_endpoints['azurewebsites'].'/api/inventorywidgets/getbyid?id=' . $deal->vehicle_id . '&dealerId=' . $deal->d_id, [
                'headers' => [
                    'Accept' => 'text/plain',
                    'Content-Type' => 'application/json-patch+json'
                ],
                'body' => '{}'
            ]);
            $vehicle = json_decode($vresponse->getBody());

            $bodyFilters .= '"Car": { "StockNumber": "' . $deal->vehicle_stock . '", "Vin": "' . $vehicle->model->vin . '", "Year": "' . $vehicle->model->year . '", "Make": "' . $vehicle->model->make . '", "Model": "' . $vehicle->model->modelName . '", "Trim": "' . $vehicle->model->trim . '"}';
        }

        return [$deal, $bodyFilters];
    }





}
