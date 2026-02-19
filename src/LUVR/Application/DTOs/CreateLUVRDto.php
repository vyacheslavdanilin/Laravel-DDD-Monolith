<?php

declare(strict_types=1);

namespace LUVR\Application\DTOs;

use DateTimeImmutable;

final readonly class CreateLUVRDto
{
    public function __construct(
        public int $shiftId,
        public DateTimeImmutable $startDateTime,
        public DateTimeImmutable $endDateTime
    ) {
    }
}
