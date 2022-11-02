<?php

namespace Xoxoday\Games\Http\Controller;

use Illuminate\Routing\Controller;
use App\Models\Code;
use Xoxoday\Games\Models\SpinTheWheel;
use Xoxoday\Games\Models\User;
use Illuminate\Support\Facades\Log;
use Session;
use Config;
use Illuminate\Database\QueryException;



class GamesController extends Controller
{
    /*
     * Function to show the login page
     */
    public function result()
    {   
        $json = file_get_contents('php://input');
        $data = json_decode($json,TRUE);

        $log_name = Config('app.easypromo_log_name');
        
        if(isset($data['webhook_key']) && $data['webhook_key'] ==  Config('app.easypromo_webhook_id')){
            if(isset($data['prize']['prize_type']['ref']) &&  $data['prize']['prize_type']['ref'] != '' && isset($data['user']['external_id']) &&  $data['user']['external_id'] != ''  && isset($data['user']['phone'])  &&  $data['user']['phone'] != ''){
                $prize = $data['prize']['prize_type']['ref'];
                $mobile = $data['user']['phone'];
                $external_id = $data['user']['external_id'];
                try{
                    $user = User::where('mobile',$mobile)->first();
                } catch(QueryException $ex){
                    Log::channel($log_name)->info(date('Y-m-d H:i:s') . ':: ConsumerGamesWebhook - Fetching of customer Failed by phone number :: SQL Error code' . $ex->errorInfo[1] . ' -SQL Error Message' . $ex->getmessage());
                }

                if($user){
                    try{
                        $code = Code::where('code',$external_id)->where('used', 'In-Queue')->first();
                    } catch(QueryException $ex){
                        Log::channel($log_name)->info(date('Y-m-d H:i:s') . ':: ConsumerGamesWebhook - Fetching of code Failed :: SQL Error code' . $ex->errorInfo[1] . ' -SQL Error Message' . $ex->getmessage());
                    }
                    if($code){
                        try {
                            $code_status_update = Code::where('code', $external_id)->update(['used' => 'Yes']); 
                        } catch (QueryException $ex) {
                            Log::channel('sql_error')->info(date('Y-m-d H:i:s') . ':: ConsumerGamesWebhook - Code status update Failed :: SQL Error code' . $ex->errorInfo[1] . ' -SQL Error Message' . $ex->getmessage());
                        }
                        try {
                            $spin_entry = SpinTheWheel::create([
                                'user_id' => $user['id'],
                                'code_id' => $code['id'],
                                'result' => $prize,
                                'result_date' => date('Y-m-d H:i:s'),
                                'status' => 0, 
                            ]);
                        } catch (QueryException $ex) {
                            Log::channel($log_name)->info(date('Y-m-d H:i:s') . ':: ConsumerGamesWebhook - Entry for the result of spinwheel failed :: SQL Error code' . $ex->errorInfo[1] . ' -SQL Error Message' . $ex->getmessage());
                        }
                    }else{
                        Log::channel($log_name)->info(date('Y-m-d H:i:s') . ':: ConsumerGamesWebhook - Code not found. ' );
                    }
                }else{
                    Log::channel($log_name)->info(date('Y-m-d H:i:s') . ':: ConsumerGamesWebhook - User not found. ' );
                }

            }else{
                Log::channel($log_name)->info(date('Y-m-d H:i:s') . ':: ConsumerGamesWebhook - Webhook key validation failed. ' );
            }
        }else{
            Log::channel($log_name)->info(date('Y-m-d H:i:s') . ':: ConsumerGamesWebhook - Webhook key validation failed. ' );
        }

        Log::channel($log_name)->info(date('Y-m-d H:i:s') . ':: Data - ' .$json );
        die(); 
       
    }


}
