<?php

namespace App\Http\Controllers;

use App\Activities\Actions\GetReservesByEmailAction;
use App\Activities\Actions\PlaceReservationAction;
use App\Http\Resources\ReserveResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\MessageBag;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class ReserveController extends Controller
{
    /**
     * @throws ValidationException
     */
    public function store(Request $request, PlaceReservationAction $action)
    {
        $this->validate($request, [
            'trip_code' => 'required|string|exists:trips,code',
            'email' => 'required|string|email',
            'places' => 'required|int|min:1',
        ]);

        $action->run($request->trip_code, $request->email, $request->places);

        return response()->json(['message' => 'You have successfully reserved places']);
    }

    /**
     * @param string $email
     * @param GetReservesByEmailAction $action
     * @return MessageBag|AnonymousResourceCollection
     */
    public function index(string $email, GetReservesByEmailAction $action): MessageBag|AnonymousResourceCollection
    {
        $validator = Validator::make(['email' => $email], [
            'email' => 'required|string|email|exists:reserves,email',
        ]);

        if ($validator->fails()) {
            return $validator->errors();
        }

        return ReserveResource::collection($action->run($email));
    }
}
