<?php

namespace App\Http\Controllers;

use App\Http\Requests\GroupRequest;
use App\Http\Requests\MessageRequest;
use App\Http\Resources\RoomResource;
use App\Http\Resources\TalkResource;
use App\Http\Resources\UserResource;
use App\Models\Room;
use App\Models\Talk;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class RoomController extends Controller
{
    public function createRoom($creater_id, $room_name)
    {
        $room = new Room;
        $room->creater_id = $creater_id;
        $room->room_name = $room_name;
        $room->save();
        $room->users()->attach($creater_id);
        return $room;
    }

    public function UserFriends($user_id)
    {
        $friends = User::all()->except($user_id);
        return UserResource::collection($friends);
    }

    public function OneToOne($friend_id)
    {

        $created_rooms = DB::table('room_user')->where('user_id', auth()->user()->id)->get();
        $joined_rooms = DB::table('room_user')->where('user_id', $friend_id)->get();

        $created_array = [];
        if ($created_rooms) {
            foreach ($created_rooms as $room) {
                array_push($created_array, $room->room_id);
            }
        }

        $joined_array = [];
        if ($joined_rooms) {
            foreach ($joined_rooms as $room) {
                array_push($joined_array, $room->room_id);
            }
        }

        $collection1 = new Collection($created_array);
        $collection2 = new Collection($joined_array);

        $room_ids = $collection1->intersect($collection2);

        if (count($room_ids) > 0) {
            foreach ($room_ids as $id) {
                $room_users = DB::table('room_user')->where('room_id', $id)->get();
                $room = Room::find($id);
                if (count($room_users) === 2 && empty($room->room_name)) {
                    foreach ($room_users as $room_user) {
                        $room = Room::find($room_user->room_id);
                        return new RoomResource($room);
                    }
                }
            }
        }
        $room = $this->createRoom(auth()->user()->id, null);
        $room->users()->attach($id);
        return new RoomResource($room);
    }

    public function UserGroups($user_id)
    {

        $rooms = [];
        $room_users = DB::table('room_user')->where('user_id', $user_id)->get();

        if ($room_users) {
            foreach ($room_users as $room_user) {
                $room = Room::find($room_user->room_id);
                if (!empty($room->room_name) && count($room->users) > 2) {
                    $room->users;
                    array_push($rooms, $room);
                }
            }
        }
        return $rooms;
    }

    public function CreateGroup(GroupRequest $request)
    {

        if (count($request->members) < 2) {
            return response()->json(['error' => 'you need 2 or more members to create a new group!']);
        }

        $room = $this->createRoom(auth()->user()->id, $request->room_name);

        foreach ($request->members as $member) {
            // $room->users()->attach($member['id']);
            $room->users()->attach($member);
        }

        return new RoomResource($room);
    }

    public function SendMessage(MessageRequest $request)
    {

        $talk = new Talk;
        $talk->room_id = $request->room_id;
        $talk->sender_id = auth()->user()->id;
        // $talk->sender_id = $request->sender_id;
        $talk->message = $request->message;
        $talk->save();

        return new TalkResource($talk);
    }

    public function GetMessages($room_id)
    {
        $talks = Talk::where('room_id', $room_id)->get();
        return TalkResource::collection($talks);
    }
}
