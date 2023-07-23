<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class MessagesController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function listMessages(User $user)
    {
        try {
            $userFrom = Auth::user()->id;
            $userTo = $user->id;

            $message = Message::where(function ($query) use ($userTo, $userFrom) {
                return $query->where([
                    'from' => $userFrom,
                    'to' => $userTo
                ]);
            })->orWhere(function ($query) use ($userTo, $userFrom) {
                return $query->where([
                    'from' => $userTo,
                    'to' => $userFrom
                ]);
            })
                ->orderBy('created_at', 'ASC')
                ->get();

            return response()->json([
                'message' => 'Listagem de mensagens',
                'data' => $message
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            /* @var $message Message */
            $message = new Message();
            $message->from = Auth::user()->id;
            $message->to = $request->to;
            $message->content = $request->content;
            $message->save();
            return response()->json([
                'message' => "Mensagem enviada",
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Message $message)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Message $message)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Message $message)
    {
        //
    }
}
