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
 * 
 */
class Invitation
{
    private int $id;
    private string $code;
    private int $by;
    private ?string $for;

    /**
     * Verify if the code is valid for the email
     * @param string $email
     * @param string $code
     * @param bool
     */
    public static function valid(string $email, string $code): bool
    {
        return self::get($email, $code) !== false ? true : false;
    }

    /**
     * Get the data from the database
     * @param string $email
     * @param string $code
     * @return
     */
    private static function get(string $email, string $code): mixed
    {
        $command = "SELECT * FROM invitations WHERE `code` = :code AND `for` = :for";
        $params = [":code"=>$code, ":for"=>$email];
        return DB::makeFetch($command, $params, self::class);
    }

    /**
     * Create an invitation for an account
     * @param string $username
     * @param string $for The email of the person able to use the invitation
     * @return bool
     */
    public static function create(string $username, string $for)
    {
        if(!User::isAdmin($username))
            throw new Exception("You need to be an administrator to create invitations.", 403);
        $id = User::getId($username);
        $email = User::decrypt($username, $for);
        if($email === null || $email === "")
            throw new Exception("An email is necessary to create invitations.", 400);
        $code = md5(uniqid());
        $command = "INSERT INTO invitations(`code`, `by`, `for`) VALUES (:code, :by, :for);";
        $params = [":code"=>$code, ":by"=>$id, ":for"=>$email];
        DB::makeTransaction($command, $params);
        return User::encrypt($username, $code);
    }
}