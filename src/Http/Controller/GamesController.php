<?php

namespace Xoxoday\Games\Http\Controller;

use Illuminate\Routing\Controller;
use Xoxoday\Games\Models\Xogame;
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

        $log_name = Config('xogames.easypromo_log_name');

        Log::channel($log_name)->info(date('Y-m-d H:i:s') . ':: Data - ' .$json );

        

        $unique_identifier = Config('xogames.easypromo_user_identifier');
        
        if(isset($data['webhook_key']) && $data['webhook_key'] ==  Config('xogames.easypromo_webhook_id')){
            if(isset($data['prize']['prize_type']['ref']) &&  $data['prize']['prize_type']['ref'] != '' && isset($data['user']['external_id']) &&  $data['user']['external_id'] != ''  && isset($data['user'][$unique_identifier])  &&  $data['user'][$unique_identifier] != ''){
                $prize = $data['prize']['prize_type']['ref'];
                $user_identifier = $data['user'][$unique_identifier];
                $external_id = $data['user']['external_id'];
                try{
                    $game_entry = Xogame::where('user_identifier',$user_identifier)->where('external_id',$external_id)->where('status','0')->first();
                } catch(QueryException $ex){
                    Log::channel($log_name)->info(date('Y-m-d H:i:s') . ':: ConsumerGamesWebhook - Fetching of customer Failed by phone number :: SQL Error code' . $ex->errorInfo[1] . ' -SQL Error Message' . $ex->getmessage());
                }

                if($game_entry){
                        try {
                            $game_entry_update = Xogame::where('id', $game_entry['id'])->update(['status' => '1','result' => $prize,'result_date' => date('Y-m-d H:i:s')]); 
                        } catch (QueryException $ex) {
                            Log::channel('sql_error')->info(date('Y-m-d H:i:s') . ':: ConsumerGamesWebhook - Code status update Failed :: SQL Error code' . $ex->errorInfo[1] . ' -SQL Error Message' . $ex->getmessage());
                        }
                        
                    
                }else{
                    Log::channel($log_name)->info(date('Y-m-d H:i:s') . ':: ConsumerGamesWebhook - Game Entry not found. ' );
                }

            }else{
                Log::channel($log_name)->info(date('Y-m-d H:i:s') . ':: ConsumerGamesWebhook - Webhook details missing. ' );
            }
        }else{
            Log::channel($log_name)->info(date('Y-m-d H:i:s') . ':: ConsumerGamesWebhook - Webhook key validation failed. ' );
        }
        die(); 
       
    }


}
