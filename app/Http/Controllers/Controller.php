<?php

namespace App\Http\Controllers;

abstract class Controller
{
    public function sendEmail($type, $receiverEmail, $receiverName, $username, $phone, $email, $emailData = "")
    {
        if ($type == 'NewUser') {
            $name = 'demo';
            $subject = 'Welcome' . '  ' . $username;
            $body = '<html><head></head><body><p>Hiiii ' . $username . ',</p> <p>Welcome to our New Family </p> <br> <p>Regards </p> </body></html>';
        }

        elseif ($type == 'resetPass') {
            $name = 'demo';
            $subject = 'password change is Requested';
            $body = '<html><head></head><body><p>Hiiii ' . $username . ',</p> <p>Your otp is'.$emailData.'</p> <br> <p>Regards </p> </body></html>';
        }

        $data = array(
            "sender" => array(
                "email" => 'vedantiS@hyplap.com',
                "name" => 'Vedanti sawant'
            ),
            "to" => array(
                array(
                    "email" => $receiverEmail,
                    "name" => $receiverName
                )
            ),
            "name" => $name,
            "subject" => $subject,
            "htmlContent" => $body
        );
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.sendinblue.com/v3/smtp/email',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => array(
                'Accept: application/json',
                'Content-Type: application/json',
                'Api-Key: ' . env('SENDINBLUE_API_KEY')
            ),
        ));
        // $response = curl_exec($curl);
        $result = curl_exec($curl);
        if (curl_errno($curl)) {
            echo 'Error:' . curl_error($curl);
        }
        error_log('this comes ->' . $result);
        curl_close($curl);
    }
}
