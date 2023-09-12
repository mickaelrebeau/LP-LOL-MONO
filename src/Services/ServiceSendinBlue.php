<?php

namespace App\Services;

use SendinBlue\Client\Api\TransactionalEmailsApi;
use SendinBlue\Client\Configuration;
use GuzzleHttp\Client;
use SendinBlue\Client\Model\SendSmtpEmail;

class ServiceSendinBlue
{
    public static function sendEmail($to, $id, $params)
    {
        $config = Configuration::getDefaultConfiguration()->setApiKey('api-key', 'API_KEY_HERE');

$apiInstance = new TransactionalEmailsApi(
    new Client(),
    $config
);
$sendSmtpEmail = new SendSmtpEmail();
$sendSmtpEmail['sender'] = array('name' => 'Lp-Lol', 'email' => 'EMAIL_HERE');
$sendSmtpEmail['to'] = array(
    $to
);
$sendSmtpEmail['templateId'] = $id;
$sendSmtpEmail['params'] = $params;

try {
    $result = $apiInstance->sendTransacEmail($sendSmtpEmail);
} catch (\Exception $e) {
    echo 'Exception when calling TransactionalEmailsApi->sendTransacEmail: ', $e->getMessage(), PHP_EOL;
}
    }
}
