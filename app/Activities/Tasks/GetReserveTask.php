<?php

namespace App\Activities\Tasks;

use App\Exceptions\ElementNotFoundException;
use App\Models\Reserve;

class GetReserveTask extends TaskAbstract
{
    /**
     * @param Reserve $reserve
     */
    public function __construct(
        private Reserve $reserve,
    ) {}

    /**
     * @param int $id
     * @return Reserve
     */
    public function run(int $id): Reserve
    {
        $reserve = $this->reserve->find($id);

        if (!$reserve) {
            throw new ElementNotFoundException('Reserve not found');
        }

        return $reserve;
    }
}
