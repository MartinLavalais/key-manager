# key-manager

## Team

### Martin Lavalais

Email : martin.lavalais@atlas-eternal.com

## About the app

Actual Version : 1.0.0

The app is a Key Manager Service. (KMS keep the key that you used to encrypt data and give you a reference key.)
It's recommanded if you make your drive, password manager or something with critic data.
Also, DON'T PUT IT IN THE SAME MACHINE THAT THE APP THAT IT USE IT OR IT WILL BE USELESS.

KMS for : kms.atlas-eternal.com

## Installation

### Requirement

- MySQL (used version : 15.1 with MariaDB : 10.6.18)
- Apache2 or Nginx
- Composer (used version : 2.2.6)

### Procedure

#### Database

Go in `/docs/sql/` and take the last version of the file `dump-kms_database-YYYYMMDD.sql`

Import it into your MySQL server

Create a user with all info and the public_key is just a file name to access the public key like `admin_key`

#### Config

Now, in `/private/keys` create a file with the name and .pub extension (`admin_key.pub`)

Go on `/private`, copy `conn.php.exemple` and paste it as `conn.php` or just create the file and important it with the following code :

```php
<?php

namespace atlas\kms;

class conn
{
    public static $db_host = "Your host address";
    public static $db_user = "Your username";
    public static $db_pass = "Y0urP4ssw0rd";
    public static $db_name = "kms_database";
    public static $db_port = 3306;
}
```

now, go on your terminal and go on the racine folder `/` and download the dependecies

## API

### user

#### POST - Create a user

Expected input :

```json
{
    "username":"John-Doe",
    "email":"john-doe@atlas-eternal.com",
    "phone":"+41790000000",
    "public_key":"a veeeeeeeeeeeeeeeeeeryyyyyyyyyy looooooooooooooong keeeeeeeyyyyyyyyyy",
    "code":"g4r4FVew1Cgbe87"
}
```

Expected output :

```json
{
    "status":"ok",
    "result":"User created."
}
```

In case of error :

```json
{
    "status":"ko",
    "result":"The error message"
}
```

### invitation

#### POST - Create an invitation

Expected input :

```json
{
    "username":"John-Doe",
    "for":"(john-doe@atlas-eternal.com) but encrypted",
}
```
for is the email of the person you invite and he as to be crypted

Expected output :

```json
{
    "status":"ok",
    "result":"Invitation created."
}
```

In case of error :

```json
{
    "status":"ko",
    "result":"The error message"
}
```

### key

#### GET - Get a key by his reference

Expected input : `/key?username=John-Doe&reference_key=b2gEbw4vGTwf34xsE1`

Expected output : 

```json
{
    "status":"ok",
    "result":{"key":"db3blkfvui23vwERTGasd124.124cERw"}
}
```

The reference key as to be crypted

#### POST - Stock your key and get a reference

Expected input : 

```json
{
    "username":"John-Doe",
    "key":"db3blkfvui23vwERTGasd124.124cERw"
}
```

The key as to be crypted

Expected output : 

```json
{
    "status":"ok",
    "result":{"reference_key":"db3blkfvui23vwERTGasd124.124cERw"}
}
```

#### PUT - Modify a key

Expected input : 

```json
{
    "username":"John-Doe",
    "reference_key":"db3blkfvui23vwERTGasd124.124cERw",
    "key":"db3blkfvui23vwERTGasd124.124cERw"
}
```

The key and the reference key as to be crypted

Expected output : 

```json
{
    "status":"ok",
    "result":null
}
```

#### DELETE - Delete a key

Expected input : 

```json
{
    "username":"John-Doe",
    "reference_key":"db3blkfvui23vwERTGasd124.124cERw"
}
```

The key and the reference key as to be crypted

Expected output : 

```json
{
    "status":"ok",
    "result":null
}
```

### key-status

#### GET - Get the last update date of a key

Expected input : 

```json
{
    "username":"John-Doe",
    "reference_key":"db3blkfvui23vwERTGasd124.124cERw"
}
```

The key and the reference key as to be crypted

Expected output : 

```json
{
    "status":"ok",
    "result":{"last_update":"2024-08-20"}
}
```