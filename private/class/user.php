<?php

/**
 * Author : Martin Lavalais
 * Website : kms.atlas-eternal.com
 * Description : Manage the data from the database
 */

namespace atlas\kms\class;

use Exception;
use atlas\kms\class\DB;

/**
 * Manage the data from the database
 */
class User
{
    private int $id;
    private string $username;
    private string $email;
    private string $phone;
    private string $public_key;
    private bool $admin;

    /**
     * Verify if the username is available
     * @param string $username
     * @return bool
     */
    public static function usernameAvailable(string $username): bool
    {
        return self::get($username) !== false ? true : false;
    }

    /**
     * Verify if the user is an admin
     * @param string $username
     * @return bool
     */
    public static function isAdmin(string $username): bool
    {
        $user = self::get($username);
        return $user !== false ? $user->admin : false;
    }

    /**
     * Get the data of a user
     * @param string $username
     * @return User
     */
    private static function get(string $username)
    {
        $command = "SELECT * FROM users WHERE username = :username";
        $params = [":username"=>$username];
        return DB::makeFetch($command, $params, User::class);
    }

    public static function getId(string $username)
    {
        $user = self::get($username);
        return $user !== false ? $user->id : false;
    }

    /**
     * Create the user in the database
     * @param string $username
     * @param string $email
     * @param string $phone
     * @param string $public_key
     * @param string $code To verify if the user as an invitation
     * @param bool $admin False by default
     * @return bool|string True if the user has been created, create exception if fail
     */
    public static function create(string $username, string $email, string $phone, string $public_key, string $code): bool
    {
        if (Invitation::valid($email, $code) === false)
            throw new Exception("The code is invalid, please contact the person who provid you this code.");
        if ($username === null || $username === "")
            throw new Exception("The username is missing.");
        if (strlen($username) > 100)
            throw new Exception("The username is too long.");
        if (strlen($username) < 2)
            throw new Exception("The username is too short.");
        if (User::usernameAvailable($username) === true)
            throw new Exception("The username is already used.");
        if ($email === null || $email === "")
            throw new Exception("The email is missing.");
        if (strlen($email) > 255)
            throw new Exception("The email is too long.");
        if (!filter_var($email, FILTER_VALIDATE_EMAIL))
            throw new Exception("The email is invalid.");
        if (strlen($phone) > 20)
            throw new Exception("The phone number is too long.");
        if ($phone === null || $phone === "")
            throw new Exception("The phone number is missing.");
        if ($public_key === null || $public_key === "")
            throw new Exception("The public key is missing");

        $commandInsert = "INSERT INTO users (`username`, `email`, `phone`, `public_key`) VALUES (:username, :email, :phone, :public_key)";
        $commandDelete = "DELETE FROM invitations WHERE `code` = :code AND `for` = :for";
        $paramsDelete = [":code" => $code, ":for" => $email];
        $fileName = "";

        do
        {
            $fileNameTemp = md5(uniqid()) . ".pub";
            if(!file_exists(__DIR__ . "/../keys/" . $fileNameTemp))
            {
                $file = fopen(__DIR__ . "/../keys/" . $fileNameTemp, "wb", true);
                fwrite($file, $public_key);
                $fileName = $fileNameTemp;
                break;              
            }
        }
        while (true);

        $paramsInsert = [":username"=>$username, ":email"=>$email, ":phone"=>$phone, ":public_key"=>$fileName];
        $result = DB::makeTransaction($commandInsert, $paramsInsert);
        $result = DB::makeTransaction($commandDelete, $paramsDelete);

        if (!$result)
            throw new Exception("An error as occured from the database, please try later.");
        else
            return true;            
    }

    /**
     * Decrypt the message
     * @param string $username The username of the user who send the message
     * @param string $cryptedMessage
     * @return string return the decrypted message or an empty string if fail
     */ 
    public static function decrypt(string $username, string $cryptedMessage):string
    {
        $user = self::get($username);
        if (!$user)
            throw new Exception("The username is invalid");

        $keyFileName = $user->public_key;
        $key = file_get_contents(__DIR__ . "/../keys/" . $keyFileName);
        $message = false;
        
        openssl_public_decrypt($cryptedMessage, $message, $key);

        return $message !== "" ? $message : false;
    }

    /**
     * Encrypt a message
     * @param string $username The username of the user who send the message
     * @param string $message
     * @return string return the encrypted message
     */
    public static function encrypt(string $username, string $message):string
    {
        $user = self::get($username);
        if (!$user)
            throw new Exception("The username is invalid");

        $keyFileName = $user->public_key;
        $key = file_get_contents(__DIR__ . "/../keys/" . $keyFileName);
        $cryptedMessage = false;

        openssl_public_encrypt($message, $cryptedMessage, $key);

        return $cryptedMessage !== "" ? $cryptedMessage : false;
    }
}