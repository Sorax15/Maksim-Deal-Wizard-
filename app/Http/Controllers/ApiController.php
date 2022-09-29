<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use DB;
use App\Models\Deal;
use Carbon\Carbon;
use Illuminate\Support\Facades\Redirect;
use Validator;

class ApiController extends Controller
{
    public function getSalespeopleWidget(Request $request){
        $conn = DB::connection('bab');
        $d_id = $request->d_id;
        $access_token = $request->accessToken;

        $sql = "SELECT b.email,b.first,b.last,b.phone,b.photo,b.title,b.s_id, (
        SELECT
            AVG (Rating)
        FROM
            UserReview c
        WHERE
            c.s_id = b.s_id
    ) AS avg_stars
                FROM dealer_salespeople a
                Left Join salespeople as b ON(a.s_id = b.s_id)
                Left Join salespeople_score as c ON(a.s_id = c.s_id)
                where a.d_id=".$d_id." and b.var2 != 'DELETED' and b.RolesId=1 and b.photo != ''
                Order by c.score desc";

        $salespeople = $conn->select($sql);



        foreach($salespeople as $index=>$sp){
            $reviews = $conn->select('select count(*) as ct from customer_pages where s_id='.$sp->s_id.' and d_id='.$d_id.' and draft=0');
            if($reviews[0]->ct < 100){
                //unset($salespeople[$index]);
                //continue;
            }
            $sp->review_count = $reviews[0]->ct;
            $sp->d_id = $d_id;
            $sp->access_token = $access_token;
            if(!$sp->avg_stars || $sp->avg_stars < 1)$sp->avg_stars = '-';

            $sales_number_format = substr($sp->phone, -10);
            $number1 = substr($sales_number_format, 0, 3);
            $number2 = substr($sales_number_format, 3, 3);
            $number3 = substr($sales_number_format, 6, 4);
            $sp->phone = "(".$number1.") ".$number2."-".$number3;
        }

        //return view('wizard.pages.testb')->with([]);

        return response(json_encode($salespeople), 200);


    }
    //
}



