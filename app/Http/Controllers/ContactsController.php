<?php

namespace App\Http\Controllers;

use App\Events\NewMessage;
use App\Message;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ContactsController extends Controller
{
    //

    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index() {
        $user = Auth::guard('api')->user();

        $contacts = User::where('id', '!=', $user->id)->get();

        $unreadIds = Message::select('from as sender_id')
            ->where('to', $user->id)
            ->where('read', false)
            ->get();


        $contacts = $contacts->map(function($contact) use ($unreadIds) {
            $contactUnread = $unreadIds->where('sender_id', $contact->id)->count();

            $contact->unread = $contactUnread ? $contactUnread : 0;

            return $contact;
        });

        return response()->json($contacts);
    }

    public function getMessagesFor($id) {
        $user = Auth::guard('api')->user();

        Message::where('from', $id)
            ->where('to', $user->id)->update(['read' => 1]);

        $messages = Message::where( function($query) use ($id) {
                $query->where('from', $id)->orWhere('to', $id);
            })
            ->where( function($query) use ($user) {
                $query->where('from', $user->id)->orWhere('to', $user->id);
            })->get();

        return response()->json($messages);
    }

    public function send(Request $request) {
        $user =  Auth::guard('api')->user();

        $message = Message::create([
            'from' => $user->id,
            'to' => $request->input('contact_id'),
            'text' =>$request->input('text')
        ]);

        broadcast(new NewMessage($message));

        return response()->json($message);
    }
}
