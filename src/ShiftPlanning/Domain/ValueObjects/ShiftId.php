<?php

declare(strict_types=1);

namespace ShiftPlanning\Domain\ValueObjects;

use InvalidArgumentException;

final class ShiftId
{
    private int $id;

    public function __construct(int $id)
    {
        if ($id < 0) {
            throw new InvalidArgumentException("Некорректный ShiftId: $id");
        }
        $this->id = $id;
    }

    public function toInt(): int
    {
        return $this->id;
    }
}
