<?php

namespace App\Http\Resources;

use App\Models\City;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;

/**
 * @property string $code
 * @property string $places
 * @property string $free_places
 * @property City $from
 * @property City $to
 */
class TripResource extends JsonResource
{
    /**
     * @param Request $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'code' => $this->code,
            'places' => $this->places,
            'free_places' => $this->free_places,
            'from' => new CityResource($this->from),
            'to' => new CityResource($this->to),
        ];
    }
}
