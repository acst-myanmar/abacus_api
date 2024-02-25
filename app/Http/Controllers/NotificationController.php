<?php

namespace App\Http\Controllers;

use App\Helpers\ApiHelper;
use App\Http\Requests\NotificationRequest;
use App\Http\Resources\NotificationResource;
use App\Models\Notification;
use Exception;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function SendNotification(NotificationRequest $request){

       try{
        $noti = new Notification;
        $noti->sender_id = auth()->user()->id;
        $noti->receiver_id = $request->receiver_id;
        $noti->title = $request->title;
        $noti->description = $request->description;
        $noti->read = $request->read;
        $noti->save();

        $noti->sender;
        $noti->receiver;
        return ApiHelper::responseWithSuccess(null, new NotificationResource($noti));
       }catch (Exception $e) {
        return ApiHelper::responseWithNotFound($e->getMessage());
    }
    }
}
