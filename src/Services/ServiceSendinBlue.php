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
        $config = Configuration::getDefaultConfiguration()->setApiKey('api-key', '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');

$apiInstance = new TransactionalEmailsApi(
    new Client(),
    $config
);
$sendSmtpEmail = new SendSmtpEmail();
$sendSmtpEmail['sender'] = array('name' => 'Lp-Lol', 'email' => 'johnb16@hotmail.fr');
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