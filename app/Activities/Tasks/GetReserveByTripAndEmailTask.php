<?php

namespace App\Activities\Tasks;

use App\Models\Reserve;
use Illuminate\Support\Collection;

class GetReserveByTripAndEmailTask extends TaskAbstract
{
    /**
     * @param Reserve $reserve
     */
    public function __construct(
        private Reserve $reserve,
    ) {}

    /**
     * @param int|null $tripId
     * @param string|null $email
     * @return Collection
     */
    public function run(int $tripId = null, string $email = null): Collection
    {
        $data = [];

        if (null !== $tripId) {
            $data['trip_id'] = $tripId;
        }

        if (null !== $email) {
            $data['email'] = $email;
        }

        if (empty($data)) {
            return collect();
        }

        return $this->reserve->where($data)->get();
    }
}
