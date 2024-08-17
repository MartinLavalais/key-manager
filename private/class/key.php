<?php

/**
 * Author : Martin Lavalais
 * Website : kms.atlas-eternal.com
 * Description : Manage the data from the database
 */

namespace atlas\kms\class;

use Exception;
use atlas\kms\class\DB;
use atlas\kms\class\User;

 /**
  * Manage the data from the database
  */
class Key
{
    private int $id;
    private string $key;
    private string $reference_key;
    private string $update_date;
    private int $user_id;

    /**
     * Get the key from the database
     * @param int $id
     * @param mixed $referenceKeyCrypted
     * @return Key|false
     */
    private static function get(string $id, $referenceKey)
    {
        $command = "SELECT * FROM `keys` WHERE user_id = :user_id AND reference_key = :reference_key";
        $params = [":user_id"=>$id, ":reference_key"=>$referenceKey];
        return DB::makeFetch($command, $params, self::class);
    }

    /**
     * Research a specific key
     * @param string $username
     * @param mixed $referenceKeyCrypted
     * @return string
     */
    public static function research(string $username, $referenceKeyCrypted)
    {
        $referenceKey = User::decrypt($username, $referenceKeyCrypted);
        if (!$referenceKey)
            throw new Exception("The username is invalid.");
        $id = User::getId($username);
        $result = self::get($id, $referenceKey);
        if (!$result)
            throw new Exception("The reference key is invalid.");
        return User::encrypt($username, $result->key);
    }

    /**
     * Add a key in the database
     * @param string $username
     * @param mixed $keyCrypted
     * @return mixed return the reference key crypted
     */
    public static function create(string $username, $keyCrypted)
    {
        $key = User::decrypt($username, $keyCrypted);
        if (!$key)
            throw new Exception("The username is invalid.");
        $id = User::getId($username);
        $referenceKey = null;
        do
        {
            $tempReferenceKey = bin2hex(random_bytes(32));
            if (!Key::getStatus($username, $tempReferenceKey))
            {
                $referenceKey = $tempReferenceKey;
                break;
            }
        }
        while (true);
        $date = date("Y-m-d");
        $command = "INSERT INTO `keys`(`key`,`reference_key`,`update_date`,`user_id`) VALUES (:key, :reference_key, :update_date, :user_id)";
        $params = [":key"=>$key, ":reference_key"=>$referenceKey, ":update_date"=>$date, ":user_id"=>$id];
        $result = DB::makeTransaction($command, $params);
        if (!$result)
            throw new Exception("DB Error");
        return User::encrypt($username, $referenceKey);
    }

    /**
     * Update the key
     * @param string $username
     * @param mixed $referenceKeyCrypted
     * @param mixed $newKeyCrypted
     * @return bool true if its succeed, false otherwise
     */
    public static function update(string $username, $referenceKeyCrypted, $newKeyCrypted)
    {
        $referenceKey = User::decrypt($username, $referenceKeyCrypted);
        if (!$referenceKey)
            throw new Exception("The username is invalid.");
        if (!Key::getStatus($username, $referenceKeyCrypted))
            throw new Exception("The reference key is invalid.");
        $newKey = User::decrypt($username, $newKeyCrypted);
        $id = User::getId($username);
        $date = date("Y-m-d");
        $command = "UPDATE `keys` SET `key` = :key, `update_date` = :date WHERE `reference_key` = :reference_key AND `user_id` = :user_id";
        $params = [":key"=>$newKey, ":date"=>$date, ":reference_key"=>$referenceKey, ":user_id"=>$id];
        return DB::makeTransaction($command, $params);
    }

    /**
     * Delete the key from the database
     * @param string $username
     * @param mixed $referenceKeyCrypted
     * @return bool
     */
    public static function delete(string $username, $referenceKeyCrypted)
    {
        $referenceKey = User::decrypt($username, $referenceKeyCrypted);
        if (!$referenceKeyCrypted)
            throw new Exception("The username is invalid.");
        if (!Key::getStatus($username, $referenceKeyCrypted))
            throw new Exception("The reference key is invalid.");
        $id = User::getId($username);
        $command = "DELETE FROM keys WHERE `user_id` = :user_id AND `reference_key` = :reference_key";
        $params = [":user_id" => $id, ":reference_key" => $referenceKey];
        return DB::makeTransaction($command, $params);
    }

    /**
     * Return the update date
     * @param string $username
     * @param mixed $referenceKeyCrypted
     * @return string|false
     */
    public static function getStatus(string $username, $referenceKeyCrypted)
    {
        $referenceKey = User::decrypt($username, $referenceKeyCrypted);
        if (!$referenceKey)
            throw new Exception("The username is invalid.");
        $id = User::getId($username);
        $key = self::get($id, $referenceKey);
        if (!$key)
            throw new Exception("The reference key is invalid.");
        return $key->update_date;
    }
}