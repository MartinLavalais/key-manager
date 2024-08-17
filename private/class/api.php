<?php
/*
 * Author Name : Martin Lavalais
 * Package name : EasyAPI
 * Version : 0.3.2
 * Description : Manage the request of the client
 */

namespace atlas\kms\class;

class API 
{
    /**
     * Needed to use the API
     * @return void
     */
    public static function getApi()
    {
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json"); 
    }

    /**
     * Return the body
     * @return array
     */
    public static function getBody()
    {
        self::getApi();

        $body = json_decode(file_get_contents("php://input"), true);

        return $body;
    }

    /**
     * @param string $key 
     * @return mixed 
     */
    public static function getHeader($key) 
    {
        $reponse = null;

        foreach (getallheaders() as $name => $value) 
        {
            $vKey = strtolower($key);
            $vName = strtolower($name);

            if(strcmp($vKey, $vName) == 0)
            {
                $reponse = $value;
                break;
            }
        }

        return $reponse;
    }

    /**
     * @return string|false
     */
    public static function getAuthorization()
    {
        $reponse = false;

        $token = filter_var(self::getHeader("Authorization"), FILTER_SANITIZE_SPECIAL_CHARS);

        if ($token !== "" && $token !== null)
            $reponse = $token;

        return $reponse;
    }

    /**
     * Verify if all the element is filled
     * @param ?array $keys for the body
     * @param bool $elementsRequire 
     * @return array|bool
     */
    public static function verifyBody(?array $keys = null, bool $elementsRequire = true):array|bool
    {
        $response = false;

        if ($keys === null)
        {
            $response = true;
        }
        else
        {
            // Récupère le body
            $body = self::getBody();
            $cleanBody = [];

            if ($body !== null)
            {
                foreach($keys as $key)
                {
                    if (!isset($body[$key]))
                    {
                        if ($elementsRequire)
                        {
                            $response = false;
                            break;
                        }
                        else
                        {
                            $cleanBody[$key] = null;
                        }
                    }
                    else
                    {
                        $cleanBody[$key] = filter_var($body[$key], FILTER_SANITIZE_SPECIAL_CHARS);
                    }
                }
            }
            else
            {
                foreach($keys as $key)
                {
                    $value = filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS);
                    if($value !== false && $value !== null && $value !== "")
                    {
                        $cleanBody[$key] = $value;
                    }
                    else
                    {
                        if ($elementsRequire)
                        {
                            $response = false;
                            break;
                        }
                        else
                        {
                            $cleanBody[$key] = null;
                        }
                    }
                }
            }
            $response = $cleanBody;
        }

        return $response;
    }

    /**
     * Verify if the current method is authorized
     * @param string $currentMethod method used for the request
     * @param array $allowedMethods contain the authorized methods
     * @return bool
     */
    public static function methodAllowed(string $currentMethod, array $allowedMethods): bool
    {
        $response = false;

        foreach($allowedMethods as $allowedMethod)
        {
            if ($currentMethod === $allowedMethod)
            {
                $response = true;
                break;
            }
        }

        return $response;
    }

    /**
     * Return the result of the request
     * @param int $httpCode 5XX, 4XX ou 2XX
     * @param string|array $httpMess Return message
     * @return void
     */
    public static function showResponse(int $httpCode, string|array $httpMess): void
    {
        $response = ["status" => $httpCode < 300 ? "ok" : "ko", "result" => $httpMess];

        http_response_code($httpCode);
        echo json_encode($response);
    }
}