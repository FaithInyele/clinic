<?php

namespace App\Http\Controllers;

use App\Consult;
use App\Message;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

class ChatController extends Controller
{
    /**
     * method to check if a chat history exysts or start a new one (Doctor consultation).
     * @param Request $request
     * @return mixed
     */
    public function start(Request $request){
        if (Consult::where('ticket_id', $request->ticket_id)->where('consultant_id', $request->consultant_id)->exists()){
            //look for chat_messages and display back
            $consult = Consult::where('ticket_id', $request->ticket_id)->where('consultant_id', $request->consultant_id)->first();
            $consult['messages'] = Message::where('consultant_id', $consult->id)->get();
            if ($consult['messages']){
                foreach ($consult['messages'] as $message){
                    $message->user = User::findorFail($message->from);
                    if (Auth::user()->id == $message->from){
                        $message->me = 'yes';
                    }else{
                        $message->me = 'no';
                    }
                }
            }
        }else{
            $consult = new Consult($request->all());
            $consult->save();
            $consult['messages'] = [];
        }
        return Response::json($consult);
    }
    public function newMessage(Request $request){
        $request['from'] = Auth::user()->id;
        $message = new Message($request->all());
        $message->save();
        $message->me = 'yes';
        $message->user = User::findorFail($message->from);
        return Response::json($message);
    }
}
