<?php

/**
 * Author : Martin Lavalais
 * Website : kms.atlas-eternal.com
 * Description : API page for
 */

use atlas\kms\class\API;
use atlas\kms\class\Invitation;

$allowedMethods = ["POST"];
$currentMethod = $_SERVER["REQUEST_METHOD"];
$neededValues = [
    "POST" => ["username", "for"]
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

    $code = Invitation::create($body["username"], $body["for"]);

    $httpCode = 200;
    $httpMess = ["code"=>$code];
}
catch(Exception $e)
{
    $httpCode = $e->getCode();
    $httpMess = $e->getMessage();
}

API::showResponse($httpCode, $httpMess);