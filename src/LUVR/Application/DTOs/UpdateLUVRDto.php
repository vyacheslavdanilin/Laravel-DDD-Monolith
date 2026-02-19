<?php

declare(strict_types=1);

namespace LUVR\Application\DTOs;

use DateTimeImmutable;
use LUVR\Domain\ValueObjects\LUVRStatus;

final readonly class UpdateLUVRDto
{
    public function __construct(
        public ?DateTimeImmutable $startDateTime = null,
        public ?DateTimeImmutable $endDateTime = null,
        public ?LUVRStatus $status = null
    ) {
    }
}
