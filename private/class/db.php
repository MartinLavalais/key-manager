<?php

/**
 * Author : Martin Lavalais
 * Website : kms.atlas-eternal.com
 * Description : Manage the connection between the website and the database
 */

namespace atlas\kms\class;

use PDO;
use PDOException;
use PDOStatement;
use atlas\kms\conn;
use atlas\kms\class\User;

class DB 
{
    /**
     * Get the connection to database if exists
     * or create it
     * @return PDO|null
     */
    static function getPdo():PDO|null
    {
        static $pdo = null;
        if($pdo == null)
        {
            $pdo = DB::connectionDatabase();
        }
        return $pdo;
    }

    /**
     * Make the connection between the database and the website
     * @return PDO|null
     */
    static function connectionDatabase():PDO|null
    {
        $dsn = "mysql:host=" . conn::$db_host .";dbname=" . conn::$db_name .";port=" . conn::$db_port . ";charset=utf8";
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        ];
        try
        {
            return new PDO($dsn, conn::$db_user, conn::$db_pass, $options);
        } 
        catch (\PDOException $e) 
        {
            throw new \PDOException($e->getMessage(), (int)$e->getCode());
        }
    }

    /**
     * Make the transaction between api and database
     * @param string $command The SQL command you want to execute
     * @param ?array $parameter The parameter of the SQL command
     * @param bool $getStatment if true, return the pdo statment to get fetch, if false, return the result of the command
     */
    static function makeTransaction(string $command, ?array $parameter = null, bool $getStatment = false):PDOStatement|bool
    {
        $pdo = DB::getPdo();
        $response = null;
        $stmt = $pdo->prepare($command);
        $result = $stmt->execute($parameter);
        if ($getStatment)
            $response = $stmt;
        else
            $response = $result;
        return $response;
    }

    /**
     * Get the result of the command
     * @param string $command The SQL command you want to execute
     * @param ?array $parameter The parameter of the SQL command
     * @param ?string $className
     * @param mixed $fetchAll if true, return all the result, if false, return the first result he found, return the class if className is not null
     */
    static function makeFetch(string $command, ?array $parameter = null, ?string $className = null, bool $fetchAll = false):mixed
    {
        $response = null;
        $stmt = DB::makeTransaction($command, $parameter, true);
        
        if ($className !== null)
            $stmt->setFetchMode(PDO::FETCH_CLASS, $className);

        if ($fetchAll)
            $response = $stmt->fetchAll();
        else
            $response = $stmt->fetch();

        return $response;
    }
}