<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
session_start();
include_once './library/api/Transaction.php';
include_once './library/Configuration.php';
include_once './library/models/request/AnnulmentRequestModel.php';
include_once './Parameters.php';

$config = ConfigurationManager::Instance();

$params = new Parameters();
$config->Token = $params->Token;
$config->ApplicationId = $params->ApplicationId;
$config->ApplicationPublicKey = $params->ApplicationPublicKey;
$config->PrivateKey = $params->PrivateKey;

$instance = new Transaction();
try {
    $model = new AnnulmentRequestModel();
    $model->Latitude = "-2.123321";
    $model->Longitude = "-78.12133";
    $model->TimeZone = -5;
    $model->TransactionId = $_SESSION['TransactionId'];

    $response = $instance->DoAnnulment($model);
    $_SESSION['AnnulmentId'] = $response->AnnulmentId;
    echo json_encode($response);
} catch (PayPhoneWebException $exc) {
    header('HTTP/1.1 '.$exc->StatusCode.' Error');
    echo json_encode($exc->ErrorList);
}