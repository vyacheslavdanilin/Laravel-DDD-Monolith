<?php

declare(strict_types=1);

namespace LUVR\Domain\Repositories;

use LUVR\Domain\Entities\LUVR;

interface LUVRRepositoryInterface
{
    public function findById(int $id): ?LUVR;

    public function save(LUVR $luvr): void;

    public function delete(LUVR $luvr): void;
}
