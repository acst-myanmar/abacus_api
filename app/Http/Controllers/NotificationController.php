<?php

namespace App\Http\Controllers;

use App\Http\Requests\NotificationRequest;
use App\Http\Resources\NotificationResource;
use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function SendNotification(NotificationRequest $request){

        $noti = new Notification;
        $noti->sender_id = auth()->user()->id;
        $noti->receiver_id = $request->receiver_id;
        $noti->title = $request->title;
        $noti->description = $request->title;
        $noti->read = $request->read;
        $noti->save();

        $noti->sender;
        $noti->receiver;
        return new NotificationResource($noti);
    }
}
