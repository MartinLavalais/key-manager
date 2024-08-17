<?php

/**
 * Author : Martin Lavalais
 * Website : kms.atlas-eternal.com
 * Description : API page for
 */

 require_once './../../../vendor/autoload.php';

use atlas\kms\class\API;
use atlas\kms\class\User;

$allowedMethods = ["POST"];
$currentMethod = $_SERVER["REQUEST_METHOD"];
$neededValues = [
    "POST" => ["username", "email", "phone", "public_key", "code"]
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

    User::create($body["username"], $body["email"], $body["phone"], $body["public_key"], $body["code"]);

    $httpCode = 200;
    $httpMess = "User created.";
}
catch(Exception $e)
{
    $httpCode = $e->getCode();
    $httpMess = $e->getMessage();
}

API::showResponse($httpCode, $httpMess);