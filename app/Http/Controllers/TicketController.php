<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\User;
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


    public function show($id): JsonResponse
    {
        return response()->json(Ticket::findOrFail($id));
    }


    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'note' => 'required|max:500',
            'do_until' => 'date|after_or_equal:today',
            'client_user_id' => 'required|exists:users,id',
            'doer' => 'exists:users,id',
            'status' => 'in:open,progress',
        ]) ;

        if ($validator->fails()) {
            return response()->json(['Error'=>$validator->errors()], 400);
        }

        $ticket = new Ticket([
            'note' => $request['note'],
            'do_until' => $request['do_until'],
            'initiator' => $request['client_user_id'],
            'doer' => $request['doer'],
            'status' => $request['status'],
        ]);

        try {
            $ticket->save();
            return response()->json(['result'=>'saved', 'data'=>$ticket], 201);

        } catch (Throwable $e) {
            return response()->json(['Error'=>$e->getMessage()],500);
        }
    }


    public function update(Request $request, Ticket $ticket): JsonResponse
    {
        $user_rights = (User::find($request['client_user_id']))->rights;

        if ($user_rights == 'admin') {
            $tmp1 = '';
            $tmp2 = ',closed';
        } else {
            $tmp1 = '|in:' . $ticket['do_until'];
            $tmp2 = '';
        }

        $validator = Validator::make($request->all(), [
            'note' => 'in:' . $ticket['note'],
            'do_until' => 'date|after_or_equal:today' . $tmp1,
            'client_user_id' => 'required|exists:users,id',
            'doer' => 'in:' . $ticket['doer'],
            'status' => 'in:open,progress' . $tmp2,
        ]); // TODO customize error messages

        if ($validator->fails()) {
            return response()->json(['Error'=>$validator->errors()], 400);
        }


        if ($user_rights != 'admin') {

            if ($ticket['initiator'] != $request['client_user_id'])
                return response()->json(['Error'=>'You have no rights to change the ticket.'], 403);

            if ($ticket['status'] == 'closed')
                return response()->json(['Error'=>'You can\'t change a closed ticket.'], 403);

            if ($ticket['status'] == 'progress' and $request['status'] == 'open')
                return response()->json(['Error'=>'You have no rights to change status backward.'], 403);
        } # validation end


        $ticket['do_until'] = $request['do_until'] ?: $ticket['do_until'];
        $ticket['status'] = $request['status'] ?: $ticket['status'];

        try {
            $ticket->save();
            return response()->json(['result'=>'successfully edited', 'data'=>$ticket ]);

        } catch (Throwable $e) {
            return response()->json(['Error'=>$e->getMessage()],500);
        }
    }


    public function destroy(Ticket $ticket): JsonResponse
    {
        try {
            $ticket->delete();
            return response()->json(['result'=>'successfully deleted', 'data'=>$ticket ]);

        } catch (Throwable $e) {
            return response()->json(['Error'=>$e->getMessage()],500);
        }
    }
}
