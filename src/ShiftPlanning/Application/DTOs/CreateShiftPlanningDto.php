<?php

declare(strict_types=1);

namespace ShiftPlanning\Application\DTOs;

use DateTimeImmutable;

final readonly class CreateShiftPlanningDto
{
    public function __construct(
        public string $startDateTime,
        public string $endDateTime,
    ) {
    }

    public function getStartDateTime(): DateTimeImmutable
    {
        return new DateTimeImmutable($this->startDateTime);
    }

    public function getEndDateTime(): DateTimeImmutable
    {
        return new DateTimeImmutable($this->endDateTime);
    }

    public static function fromArgs(array $args): self
    {
        return new self(
            startDateTime: (string) ($args['startDateTime'] ?? ''),
            endDateTime: (string) ($args['endDateTime'] ?? ''),
        );
    }
}
