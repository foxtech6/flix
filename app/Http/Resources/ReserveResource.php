<?php

namespace App\Http\Resources;

use App\Models\City;
use App\Models\Trip;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;

/**
 * @property string $email
 * @property string $places
 * @property Trip $trip
 */
class ReserveResource extends JsonResource
{
    /**
     * @param Request $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'code' => $this->email,
            'places' => $this->places,
            'trip' => new TripResource($this->trip),
        ];
    }
}
