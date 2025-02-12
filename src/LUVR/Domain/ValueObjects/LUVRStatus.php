<?php

declare(strict_types=1);

namespace LUVR\Domain\ValueObjects;

use InvalidArgumentException;

enum LUVRStatus: int
{
    case WaitingForApproval = 0;
    case Approved = 1;
    case Paid = 2;

    /**
     * @throws InvalidArgumentException
     */
    public static function fromValue(int $value): self
    {
        return match ($value) {
            0 => self::WaitingForApproval,
            1 => self::Approved,
            2 => self::Paid,
            default => throw new InvalidArgumentException("Unknown value: $value"),
        };
    }

    public static function getAllStatuses(): array
    {
        return [
            self::WaitingForApproval->value => self::WaitingForApproval->getText(),
            self::Approved->value => self::Approved->getText(),
            self::Paid->value => self::Paid->getText(),
        ];
    }

    public function getText(): string
    {
        return match ($this) {
            self::WaitingForApproval => 'Ждет одобрения',
            self::Approved => 'Одобрено',
            self::Paid => 'Оплачено',
        };
    }

    public static function getIterableData(): \Iterator
    {
        foreach (self::cases() as $case) {
            yield [
                'id' => $case->value,
                'name' => $case->getText(),
            ];
        }
    }
}
