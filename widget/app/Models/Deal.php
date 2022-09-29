<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Deal extends Model
{
    use HasFactory;

    protected $table = 'deal';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        's_id',
        'd_id',
        'user_ip',
        'entry',
        'fname',
        'mname',
        'lname',
        'email',
        'phone',
        'contact_call_opt',
        'contact_text_opt',
        'contact_email_opt',
        'contact_text_agree',
        'preapproved_id',
        'address',
        'apt_unit',
        'zipcode',
        'state',
        'city',
        'employment_status',
        'job_title',
        'company_name',
        'company_address',
        'company_phone',
        'years_company',
        'months_company',
        'income',
        'years_address',
        'months_address',
        'rent_own',
        'rent_own_amount',
        'birthday',
        'vehicle_id',
        'vehicle_stock',
        'trade_id',
        'trade_value',
        'trade_vin',
        'trade_year',
        'trade_make',
        'trade_model',
        'trade_trim',
        'trade_miles',
        'trade_condition',
        'trade_payoff',
        'td_id',
        'td_beverage',
        'td_beverage_id',
        'td_date',
        'td_time',
        'td_period',
        'td_share_text',
        'td_share_email',
        'td_comments',
        'td_calendar',
        'question',
        'text_opt_in',
        'payment_monthly',
        'payment_trade_value',
        'payment_down_payment',
        'payment_interest_rate',
        'payment_total',
        'payment_term',
        'payment_price',
        'user_token',
        'access_token'
    ];
}
