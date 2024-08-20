<?php

/**
 * Author : Martin Lavalais
 * Website : kms.atlas-eternal.com
 * Description : API page for
 */

use atlas\kms\class\API;
use atlas\kms\class\Key;

$allowedMethods = ["GET"];
$currentMethod = $_SERVER["REQUEST_METHOD"];
$neededValues = [
    "GET" => ["username", "reference_key"]
];
$httpCode = 500;
$httpMess = "Unknown Error";

try
{
    if (!API::methodAllowed($currentMethod, $allowedMethods))
        throw new Exception("Method not allowed.", 405);
    
    $body = API::verifyBody($neededValues[$currentMethod]);
    if (!$body)
        throw new Exception("Element(s) missing.", 400);

    $result = Key::getStatus($body["username"], $body["reference_key"]);

    $httpMess = ["last_update"=>$result];
    $httpCode = 200;
}
catch(Exception $e)
{
    $httpCode = $e->getCode();
    $httpMess = $e->getMessage();
}

API::showResponse($httpCode, $httpMess);