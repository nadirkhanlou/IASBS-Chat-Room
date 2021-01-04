<?php
require_once "config.php";

class database
{
    private static function ConnectToDB()
    {
        global $server;
        global $db_username;
        global $db_password;
        global $db_name;

        $connection = mysqli_connect($server, $db_username, $db_password, $db_name);
        if ($connection->connect_error)
        {
            die("Connection failed: " . $connection->connect_error);
        }
        return $connection;
    }

    public static function ExecuteQuery($query)
    {
        $connection = database::ConnectToDB();
        $result = mysqli_query($connection, $query);
        if(!$result)
            die(mysqli_error($connection));
        mysqli_close($connection);
        return $result;
    }
}
?>