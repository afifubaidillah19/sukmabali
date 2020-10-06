<?php

namespace App\Http\Controllers;

use App\Helpers\GlobalHelper;
use App\Bupda;
use App\Produsen;
use App\Review;
use App\Product;
use App\ProductCategory;
use App\Transaction;
use App\TransactionItem;
use App\ProductHistory;
use App\User;
use App\Slider;
use GH;
use Illuminate\Http\Request;
use DB;
use App\Notification;

class NotificationController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    public function getNotification(Request $request){
        $notificationType = $request->notification_type;
        $receiver_id = $request->receiver_id;

        $notifications = Notification::where('notification_type', $notificationType)
                ->where('receiver_id', $receiver_id)
                ->where('notification_status',0)->get();

        return response()->json($notifications);
    }

    public function setNotificationReceived(Request $request){
        $notifications = json_decode($request->notifications);
        foreach($notifications as $n){
            Notification::where('notification_id', $n->notification_id)->update(['notification_status'=> 1]);
        }
        return response()->json($notifications);
    }
}
