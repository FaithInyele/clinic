<?php

namespace App\Http\Controllers;

use App\Consult;
use App\Message;
use App\Ticket;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class ChatController extends Controller
{
    /**
     * view my current ticket consultations invites page. and be able to manipulate them.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(){
        $title = 'iHospital | Requested Consultations';
        $rightbar = 'ticket';

        return view('ticket.consultations.index', compact('title', 'rightbar'));
    }
    /**
     * method to check if a chat history exists or start a new one (Doctor consultation).
     * @param Request $request
     * @return mixed
     */
    public function start(Request $request){
        if (Consult::where('ticket_id', $request->ticket_id)->where('consultant_id', $request->consultant_id)->exists()){
            //look for chat_messages and display back
            $consult = Consult::where('ticket_id', $request->ticket_id)->where('consultant_id', $request->consultant_id)->first();
            $consult['messages'] = Message::where('consultant_id', $consult->id)->get();
            $consult['ticket'] = Ticket::findorFail($consult->ticket_id);
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
            $consult['messages'] = Message::where('consultant_id', $consult->id)->get();
            $consult['ticket'] = Ticket::findorFail($consult->ticket_id);
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
            
        }
        //who am i //originator will refer to the other person in this conversation
        if (Auth::user()->id == $consult->consultant_id){
            $consult['originator'] = User::findorFail($consult['ticket']->assigned_to);
        }else{
            $consult['originator'] = User::findorFail($consult->consultant_id);
        }
        return Response::json($consult);
    }

    /**
     * save new message to chat database
     * @param Request $request
     * @return mixed
     */
    public function newMessage(Request $request){
        //mark all as read
        $old_messages = Message::where('consultant_id', $request->consultant_id)->get();
        if ($old_messages){
            foreach ($old_messages as $message){
                Message::findorFail($message->id)->update(['read_status'=>'yes']);
            }
        }
        $request['from'] = Auth::user()->id;
        $request['read_status'] = 'no';
        $message = new Message($request->all());
        $message->save();
        $message->me = 'yes';
        $message->user = User::findorFail($message->from);
        return Response::json($message);
    }

    /**
     * method to retrieve all unread messages for current user
     * @return mixed
     */
    public function unread(){
        $unread = DB::table('messages')
            ->join('consults', 'messages.consultant_id', '=', 'consults.id')
            ->join('tickets', 'consults.ticket_id', '=', 'tickets.id')
            ->select('messages.*', 'tickets.status as t_status', 'tickets.id as t_id')
            ->where('messages.message_to', Auth::user()->id)
            ->where('messages.read_status', 'no')
            ->where('tickets.status', '=', 'open')
            ->oldest()
            ->groupBy('consultant_id')
            ->get();
        if ($unread){
            foreach ($unread as $message){
                $message->consult = Consult::findorFail($message->consultant_id);
                $message->message_from = User::findorFail($message->from);
                $message->ticket = app('App\Http\Controllers\TicketController')->selectedTicket($message->t_id);
            }
        }
        return Response::json($unread);
    }
    public function allMessages(Request $request){
        $consult = Consult::findorFail($request->consult_id);
        $consult['messages'] = Message::where('consultant_id', $consult->id)->get();
        $consult['ticket'] = Ticket::findorFail($consult->ticket_id);
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

        //who am i //originator will refer to the other person in this conversation
        if (Auth::user()->id == $consult->consultant_id){
            $consult['originator'] = User::findorFail($consult['ticket']->assigned_to);
        }else{
            $consult['originator'] = User::findorFail($consult->consultant_id);
        }
        return Response::json($consult);
    }
}
