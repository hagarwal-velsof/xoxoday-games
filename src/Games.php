<?php

namespace Xoxoday\Games;

use Config;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Http;
use Xoxoday\Games\Models\Xogame;

class Games
{

    /**
     * getLink
     *
     * @param String  $promo_id     Easy Promo Game Promotion id
     * @param Array   $user_data    User Data
     *
     * @return array
     */
    public function getLink($promo_id, $user_details, $external_id)
    {

        $unique_identifier = Config('xogames.easypromo_user_identifier');

        try {
            $game_entries_check = Xogame::where('user_identifier', $user_details[$unique_identifier])->where('external_id', $external_id)->first();
        } catch (QueryException $ex) {
            return false;
        }

        

        if ($game_entries_check) {
            return false;
        } else {
            try {
                $game_entry = Xogame::create([
                    'user_identifier' => $user_details[$unique_identifier],
                    'external_id' => $external_id,
                    'result' => '',
                    'status' => 0,
                ]);

            } catch (QueryException $ex) {
              return false;
            }
        }

        if ($game_entry) {
            $api_url = Config('xogames.easypromo_api_url') . 'users/autologin/' . $promo_id;

            $accesstoken = Config('xogames.easypromo_accesstoken');

            if ($user_details['last_name'] == '') {
                $user_details['last_name'] = '.';
            }

            $payload = array(
                "external_id" => $external_id,
                "first_name" => $user_details['first_name'],
                "last_name" => $user_details['last_name'],
                "email" => $user_details['email'],
                "phone" => $user_details['phone'],
                "country" => $user_details['country'],
            );

            $json = json_encode($payload);

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $accesstoken,
            ])->post($api_url, $payload);

        

            if ($response->status() == 200) {
                $result = json_decode(json_encode($response->object()), true);
                if (isset($result['lt'])) {
                    $result_array = array(
                        'lt' => $result['lt'],
                        'url' => Config('xogames.easypromo_game_url') . $result['lt'],
                    );
                    return $result_array;
                } 
            } 
        }

        return false;
    }

}
