<?php
//read the string.
//perform the necessary CRUD function
//Return the required data to the calling script
//connect to the db
include_once('resources.php');
include_once('getscans.php');
$action = (string) $_GET['x'];

class ListObj
{
//    public $userid; 
    public $item_id; 
    public $metadata; 
//    public $status; 
//    public $listid; 
//    public $date;
    public $scans;
}

try{$conn = new PDO("mysql:host=localhost; dbname=marqodb", "root", ''); 
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); } 
catch(PDOException $e){ 
    echo $e->getMessage(); } 

switch ($action) {
    case 'getall': 
                 require "predis/autoload.php";
               Predis\Autoloader::register();

                $sql = "Select item_id, metadata from item order by item_id asc;";
                $stmt = $conn->prepare($sql); 
                $stmt->execute();     
                $result = $stmt->fetch(PDO::FETCH_ASSOC); 

                $listItem = new ListObj();

                $val = array();
                 if ($result) 
                    { while ($result){ 
                        $listItem = new ListObj();
                        $listItem->item_id = $result['item_id'];
                        $listItem->metadata = $result['metadata'];
                        $listItem->scans=getscan($listItem->item_id);
                        array_push($val, $listItem);
                        $result = $stmt->fetch(PDO::FETCH_ASSOC); 
                      } 
                    } 
                  else{
                        $listItem = new ListObj();
                        $listItem->item_id = 'no data';
                        $listItem->metadata = 'no data';
                        array_push($val, $listItem);
                  }  

                $val = json_encode($val);
                echo($val);           
                break;
    case 'getid': 
                $stmt=$conn->prepare("select MAX(item_id) as max_id from item"); 
                $stmt->execute();  
                $result = $stmt->fetch(PDO::FETCH_ASSOC); 
                $maxid = $result['max_id'];
                $val = array();
                if ($maxid == NULL)
                {
                    echo('1');
                }
                else
                {
                    echo ($maxid);
                }                //var_dump($val);
                break;
    case 'getscans': 
                require "predis/autoload.php";
                Predis\Autoloader::register();
                try 
                {
                    $redis = new Predis\Client();
                    $redis = new Predis\Client(array(
                        "scheme" => "tcp",
                        "host" => "127.0.0.1",
                        "port" => 6379));
                    
                    $scanList = $redis->lrange($list, 0, 1);
                    echo (json_encode($scanList));
                }
                catch (Exception $e) {
                    echo "Couldn't connected to Redis";
                    echo $e->getMessage();
                }
                break;
    case 'add': 
                //SELECT MAX(Group_ID) as maxGroup FROM Conference
                $stmt=$conn->prepare("select MAX(item_id) as max_id from item"); 
                $stmt->execute();  
                $result = $stmt->fetch(PDO::FETCH_ASSOC); 
                $maxid = $result['max_id'];
                if ($maxid == NULL)
                {
                    $item_id = 1;
                }
                else
                {
                    $item_id = $maxid + 1;
                }
                
                $obj = json_decode(file_get_contents('php://input'));
                $metadata = $obj->message;
                $user_id = 'gopal.sandeep@gmail.com';
                $status = 'Active';
                $listid = 'default';
                $date = date('Y-m-d');
                $stmt=$conn->prepare("insert into item (user_id, item_id, metadata, status, listid, date) VALUES (?,?,?,?,?,?)"); 
                $stmt->execute(array($user_id, $item_id, $metadata, $status, $listid, $date));  
                //--------------
                $item_id = "marqo".$item_id;
                $url="https://chart.googleapis.com/chart?chs=150x150&cht=qr&chl=".$item_id;
                $img = 'images/qrs/'.$item_id.'.png';
                chmod($img,0777);
//                file_put_contents($img, file_get_contents($url));
                $image = imagecreatefrompng($url);
                header('Content-Type: image/png');
                imagepng($image, $img);
            
                //--------------
                break;
    case 'update': 
                $obj = json_decode(file_get_contents('php://input'));
                $message = $obj->message;
                $title = $message->title;
                $notes = $message->notes;
                if (($message->notes) == false)
                {
                    $notes = 'false';
                }
                else{
                    $notes='true';
                }

                $attrbs = $message->attrbs;
                $date = date('Y-m-d');
                try{$conn = new PDO("mysql:host=localhost; dbname=caketut", "sandeep2400", "kepler123"); 
                    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); } 
                catch(PDOException $e){ 
                    echo $e->getMessage(); } 

                $stmt = $conn->prepare("update listicle set date = ?, notes = ? where (title = ? AND attrbs = ?)"); 
                $stmt->execute(array($date, $notes, $title, $attrbs));                   
                break;
    case 'delete': 
                $obj = json_decode(file_get_contents('php://input'));
                $message = $obj->message;
                $title = $message->title;
                try{$conn = new PDO("mysql:host=localhost; dbname=caketut", "sandeep2400", "kepler123"); 
                    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); } 
                catch(PDOException $e){ 
                    echo $e->getMessage(); } 

                $stmt = $conn->prepare("delete from listicle where title = ?"); 
                $stmt->execute(array($title));
                break;                
}
?>