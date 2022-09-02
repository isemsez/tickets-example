<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTicketRequest;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Throwable;

class TicketController extends Controller
{
    public function index(): JsonResponse
    {
        $tickets = Ticket::where('status', '!=', 'closed');
        return response()->json($tickets);
    }


    public function store(StoreTicketRequest $request)
    {
        $user_rights = User::find($request['initiator'])->pluck('rights');

        if ($user_rights != 'admin') {
            return Response::deny('xx');
        }

        $ticket = new Ticket([
            'note' => $request['note'],
            'do_from' => $request['do_from'],
            'do_until' => $request['do_until'],
            'initiator' => $request['initiator'],
            'doer' => $request['doer'],
            'status' => $request['status'],
        ]);

        try {
            $ticket->save();
            return response()->json($ticket, 201);

        } catch (Throwable $e) {
            return response()->json(['Error'=>$e->getMessage()],500);
        }
    }


    public function show($id): JsonResponse
    {
        return response()->json(Ticket::findOrFail($id));
    }


    public function update(StoreUpdateTicketRequest $request): JsonResponse
    {
        $ticket = new Ticket([
            'note' => $request['note'],
            'do_from' => $request['do_from'],
            'do_until' => $request['do_until'],
            'initiator' => $request['initiator'],
            'doer' => $request['doer'],
            'status' => $request['status'],
        ]);

        try {
            $ticket->save();
            return response()->json($ticket);
        } catch (Throwable $e) {
            return response()->json(['Error'=>$e->getMessage()],500);
        }
    }


    public function destroy($id): JsonResponse
    {
        $ticket = Ticket::query()->findOrFail($id);

        try {
            $ticket->delete();
            return response()->json(['message'=>'Deleted']);

        } catch (Throwable $e) {
            return response()->json(['Error'=>$e->getMessage()],500);
        }
    }

}
