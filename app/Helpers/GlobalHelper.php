<?php
namespace App\Helpers;
use App\Notification;
use GH;

class GlobalHelper {

    //Deposit
    public static $DEPOSIT_CS = 0.01;
    public static $DEPOSIT_BUPDA = 0.035;
    public static $DEPOSIT_SUKMABALI = 0.005;

    //User Level
    public static $LEVEL_SUPER_ADMIN = 0;
    public static $LEVEL_PEMRPOV = 1;
    public static $LEVEL_BUPDA = 2;
    public static $LEVEL_USER = 3;

    //Bupda
    public static $BUPDA_ON_PENDING = 0;
    public static $BUPDA_ON_REJECTED = 1;
    public static $BUPDA_ON_ENABLED = 2;
    public static $BUPDA_ON_DISABLED = 3;
    
    //Produsen status
    public static $PRODUSEN_STATUS_PENDING = 0;
    public static $PRODUSEN_STATUS_REJECTED = 1;
    public static $PRODUSEN_STATUS_ENABLED = 2;
    public static $PRODUSEN_STATUS_DISABLED = 3;

    //Product status
    public static $PRODUCT_STATUS_PENDING = 0;
    public static $PRODUCT_STATUS_REJECTED = 1;
    public static $PRODUCT_STATUS_ENABLED = 2;
    public static $PRODUCT_STATUS_DISABLED = 3;
    public static $PRODUCT_STATUS_DELETED = 4;

    //Cart status
    public static $CART_STATUS_ON_CART = 0;
    public static $CART_STATUS_MOVED = 1;
    public static $CART_STATUS_DELETED = 2;

    //Transaction status
    public static $TRANSACTION_STATUS_ON_PROCCESS = 0;
    public static $TRANSACTION_STATUS_ACCEPTED_BY_BUPDA = 1;
    public static $TRANSACTION_STATUS_DONE = 2;
    public static $TRANSACTION_STATUS_CANCELED = 3;

    //Transaction item status
    public static $TRANSACTION_ITEM_STATUS_ON_PROCCESS = 0;
    public static $TRANSACTION_ITEM_STATUS_ON_PACKING = 1;
    public static $TRANSACTION_ITEM_STATUS_ON_DELIVERY = 2;
    public static $TRANSACTION_ITEM_STATUS_DONE = 3;
    public static $TRANSACTION_ITEM_STATUS_CANCELED = 4;

    //Notification
    public static $NOTIFICATION_TYPE_USER = 0;
    public static $NOTIFICATION_TYPE_PRODUSEN = 1;
    public static $NOTIFICATION_TYPE_BUPDA = 2;
    public static $NOTIFICATION_STATUS_UNREAD = 0;
    public static $NOTIFICATION_STATUS_READED = 1;
    
    public static function uploadFile($file, $folder){
        $fileName = time().'_'.$file->getClientOriginalName();
        $path = public_path().'/../../public_html/uploads/images';
        $file->move($path, $fileName);

        return 'images/'.$fileName;
    }
    public static function haversineGreatCircleDistance(
        $latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo, $earthRadius = 6371000)
    {
        // convert from degrees to radians
        $latFrom = deg2rad($latitudeFrom);
        $lonFrom = deg2rad($longitudeFrom);
        $latTo = deg2rad($latitudeTo);
        $lonTo = deg2rad($longitudeTo);
        
        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;
        
        $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
            cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));
        return ($angle * $earthRadius)/1000;
    }

    public static function pushNotification(
        $notification_type,
        $sender_id,
        $received_id,
        $message,
        $notification_status
    ){
        Notification::create([
            'notification_type' => $notification_type,
            'sender_id' => $sender_id,
            'received_id' => $received_id,
            'message' => $message,
            'notification_status' => $notification_status
        ]);
    }
    public static function sendNotification($data){
        GH::callAPI('POST', 'https://sukmabali-app.herokuapp.com/sendNotification', json_encode($data));
    }
    public static function callAPI($method, $url, $data){
       $curl = curl_init();
       switch ($method){
          case "POST":
             curl_setopt($curl, CURLOPT_POST, 1);
             if ($data)
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
             break;
          case "PUT":
             curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
             if ($data)
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data);			 					
             break;
          default:
             if ($data)
                $url = sprintf("%s?%s", $url, http_build_query($data));
       }
       // OPTIONS:
       curl_setopt($curl, CURLOPT_URL, $url);
       curl_setopt($curl, CURLOPT_HTTPHEADER, array(
          'APIKEY: 111111111111111111111',
          'Content-Type: application/json',
       ));
       curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
       curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
       // EXECUTE:
       $result = curl_exec($curl);
       if(!$result){die("Connection Failure");}
       curl_close($curl);
       return $result;
    }

    public static function generateUniqueCode($digits = 5){
        $i = 0; //counter
        $kode = ""; //our default pin is blank.
        while($i < $digits){
            //generate a random number between 0 and 9.
            $kode .= mt_rand(0, 9);
            $i++;
        }
        return $kode;
    }
}