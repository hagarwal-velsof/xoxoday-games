<?php 

namespace  Xoxoday\Games;

use Config;
use Illuminate\Support\Facades\Http;


class Games
{
    
     /**
     * getLink
     *
     * @param String  $promo_id     Easy Promo Game Promotion id
     * @param Array   $user_data    User Data
     * 
     * @return Boolean
     */
    public function getLink($promo_id,$user_details,$external_id)
    {
        
        $api_url =  Config('app.easypromo_api_url') . 'users/autologin/' . $promo_id;

        $accesstoken =  Config('app.easypromo_accesstoken');

        if($user_details['last_name'] == ''){
            $user_details['last_name'] = '.';
        }

        $payload = array(
            "external_id" => $external_id,
            "first_name" =>  $user_details['first_name'],
            "last_name"=>  $user_details['last_name'],
            "email" => $user_details['email'],
            "phone" => $user_details['phone'],
            "country" => $user_details['country']
        );

        $json = json_encode($payload);
        
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $accesstoken
        ])->post($api_url, $payload);
        
        
        if ($response->status() == 200) {
            $result = json_decode(json_encode($response->object()), true);
            if (isset($result['lt'])) {
                $result_array = array(
                    'lt' => $result['lt'],
                    'url' => Config('app.easypromo_game_url').$result['lt']
                );
                return $result_array;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
}