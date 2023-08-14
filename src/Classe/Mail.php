<?php

namespace App\Classe;



use Mailjet\Client;
use Mailjet\Resources;


class Mail

 {
     private $api_key = "555a03e6ea79be18fda9dea54d019a96";
     private $api_key_secret = "1b0d6ea9ce0afa6586b7e18f9e47b088";

    public function send($to_email, $to_name, $subject, $content)
     {
         
         $mj = new Client($this->api_key,$this->api_key_secret, true,['version' => 'v3.1']);
        $body = [
            'Messages' => [
                [
                    'From' => [
                        'Email' => "melleyanik@hotmail.com",
                        'Name' => "Purelife"
                    ],
                    'To' => [
                        [
                            'Email' => "$to_email",
                            'Name' => "$to_name"
                        ]
                    ],
                     'TemplateID' => 4984222,
                     'TemplateLanguage' => true,
                     'Subject' => $subject,
                     'Variables'=> [
                         'content' => $content,
                   
                    ]
                 ]
             ]
        ];
        
        // All resources are located in the Resources class
        $response = $mj->post(Resources::$Email, ['body' => $body]);
        // Read the response
         $response->success(); 
     }
    }
 
?>
