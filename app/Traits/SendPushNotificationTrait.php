<?php

namespace App\Traits;

use App\Models\User;

trait SendPushNotificationTrait
{
    public function push($title,$message,$user_id)
    {
        $tokens = array();
        $user = User::with('profile')->find($user_id);
        $tokens[] = $user->profile->device_token;
        $SERVER_API_KEY = env('SERVER_KEY_TARGET_USERS');
        $data = [
            "registration_ids" => $tokens,
            "notification" => [
                "title" => $title,
                "body" => $message,
                'image' => '',
                'sound' => true,
            ]
        ];
        $dataString = json_encode($data);
        $headers = [
            'Authorization: key=' . $SERVER_API_KEY,
            'Content-Type: application/json',
        ];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
        return curl_exec($ch);
    }
}
