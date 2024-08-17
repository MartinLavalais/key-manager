<?php

/**
 * Author : Martin Lavalais
 * Website : kms.atlas-eternal.com
 * Description : API page for
 */

use atlas\kms\class\API;
use atlas\kms\class\Key;

$allowedMethods = ["GET", "POST", "PUT", "DELETE"];
$currentMethod = $_SERVER["REQUEST_METHOD"];
$neededValues = [
    "GET" => ["username", "reference_key"],
    "POST" => ["username", "key"],
    "PUT" => ["username", "reference_key", "key"],
    "DELETE" => ["username", "reference_key"]
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

    if ($currentMethod === "GET")
    {
        $result = Key::research($body["username"], $body["reference_key"]);
        $httpMess = ["key"=>$result];
    }
    else if ($currentMethod === "POST")
    {
        $result = Key::create($body["username"], $body["key"]);
        $httpMess = ["reference_key"=>$result];
    }
    else if ($currentMethod === "PUT")
    {
        $result = Key::update($body["username"], $body["reference_key"], $body["key"]);
        $httpMess = null;
    }
    else
    {
        $result = Key::delete($body["username"], $body["reference_key"]);
        $httpMess = null;
    }
    $httpCode = 200;
}
catch(Exception $e)
{
    $httpCode = $e->getCode();
    $httpMess = $e->getMessage();
}

API::showResponse($httpCode, $httpMess);