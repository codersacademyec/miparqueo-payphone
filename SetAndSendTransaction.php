<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

session_start();
include_once './library/api/Transaction.php';
include_once './library/Configuration.php';
include_once './Parameters.php';
header('Access-Control-Allow-Origin: *');

header('Access-Control-Allow-Methods: GET, POST');

header("Access-Control-Allow-Headers: X-Requested-With");

$config = ConfigurationManager::Instance();

$params = new Parameters();
$config->Token = $params->Token;
$config->ApplicationId = $params->ApplicationId;
$config->ApplicationPublicKey = $params->ApplicationPublicKey;
$config->PrivateKey = $params->PrivateKey;

$_POST = (array) json_decode(file_get_contents('php://input'), true);
$phone_number = $_POST['phoneNumber'];
$region_code = $_POST['countryCode'];

$uniq_id = uniqid('', true);

$data = new TransactionRequestModel();
$data->Amount = $_POST['monto'];
$data->AmountWithOutTax = $_POST['montoSinImpuesto'];
$data->AmountWithTax = $_POST['montoConImpuesto'];
$data->Tax = $_POST['impuesto'];
$data->Latitud = $_POST['lat'];
$data->Longitud = $_POST['long'];
$data->PurshaseLanguage = 'es';
$data->TimeZone = -5;
$data->Token = $config->Token;
$data->ClientTransactionId = $uniq_id;

$instance = new Transaction();
try {
    $response = $instance->SetAndDoTransaction($data, $phone_number, $region_code);
    header('Content-Type: application/json');
    print json_encode($response);
} catch (PayPhoneWebException $exc) {
    header('HTTP/1.1 '.$exc->StatusCode.' Error');
    header('Content-Type: application/json; charset=UTF-8');
    die(json_encode($exc->ErrorList));
}
