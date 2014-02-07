<?php
//read the string.
//perform the necessary CRUD function
//Return the required data to the calling script
//connect to the db
function getscan($list)
{
//    $list = (string) $_GET['item'];

    
    try 
    {   
        $redis = new Predis\Client(array(
            "scheme" => "tcp",
            "host" => "127.0.0.1",
            "port" => 6379));
        $scanList = $redis->lrange($list, 0, 1);
        return($scanList);
    }
    catch (Exception $e) 
    {
        echo "Couldn't connect to Redis";
        echo $e->getMessage();
    }
}

?>