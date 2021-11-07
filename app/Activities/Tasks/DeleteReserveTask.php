<?php

namespace App\Activities\Tasks;

use App\Exceptions\ElementNotFoundException;
use App\Models\Reserve;

class DeleteReserveTask extends TaskAbstract
{
    /**
     * @param Reserve $reserve
     */
    public function __construct(
        private Reserve $reserve,
    ) {}

    /**
     * @param int $id
     */
    public function run(int $id): void
    {
        $reserve = $this->reserve->find($id);

        if (!$reserve) {
            throw new ElementNotFoundException('Reserve not found');
        }

        $reserve->delete();
    }
}
