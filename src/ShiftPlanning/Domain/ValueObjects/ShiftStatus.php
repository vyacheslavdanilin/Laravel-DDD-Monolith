<?php

declare(strict_types=1);

namespace ShiftPlanning\Domain\ValueObjects;

use InvalidArgumentException;

enum ShiftStatus: int
{
    case SearchPerformer = 0;
    case PerformerAssigned = 1;
    case WaitShiftStarted = 2;
    case ShiftStarted = 3;
    case ShiftMissed = 4;
    case ApplicationCanceled = 5;
    case ApplicationNotClosed = 6;

    public static function fromValue(int $value): self
    {
        return match ($value) {
            0 => self::SearchPerformer,
            1 => self::PerformerAssigned,
            2 => self::WaitShiftStarted,
            3 => self::ShiftStarted,
            4 => self::ShiftMissed,
            5 => self::ApplicationCanceled,
            6 => self::ApplicationNotClosed,
            default => throw new InvalidArgumentException("Unknown value: $value"),
        };
    }

    public static function getAllStatuses(): array
    {
        return [
            self::SearchPerformer->value => self::SearchPerformer->getText(),
            self::PerformerAssigned->value => self::PerformerAssigned->getText(),
            self::WaitShiftStarted->value => self::WaitShiftStarted->getText(),
            self::ShiftStarted->value => self::ShiftStarted->getText(),
            self::ShiftMissed->value => self::ShiftMissed->getText(),
            self::ApplicationCanceled->value => self::ApplicationCanceled->getText(),
            self::ApplicationNotClosed->value => self::ApplicationNotClosed->getText(),
        ];
    }

    public function getText(): string
    {
        return match ($this) {
            self::SearchPerformer => 'Поиск исполнителя',
            self::PerformerAssigned => 'Назначен исполнитель',
            self::WaitShiftStarted => 'Ожидает выхода на смену',
            self::ShiftStarted => 'Вышел на смену',
            self::ShiftMissed => 'Не вышел на смену',
            self::ApplicationCanceled => 'Отмена заявки',
            self::ApplicationNotClosed => 'Заявка не закрыта',
        };
    }

    public static function getDefaultStatus(?int $performerId = null): self
    {
        return $performerId === null ? self::SearchPerformer : self::PerformerAssigned;
    }

    public static function getIterableData(): \Iterator
    {
        foreach (self::cases() as $case) {
            yield [
                'id' => $case->value,
                'name' => $case->getText(),
                'color' => $case->getColor(),
            ];
        }
    }

    public function getColor(): string
    {
        return match ($this) {
            self::SearchPerformer => '#A0ABB7',
            self::PerformerAssigned => '#EAC233',
            self::WaitShiftStarted => '#F2C90F',
            self::ShiftStarted => '#8BB53E',
            self::ShiftMissed => '#E02B2B',
            self::ApplicationCanceled => '#d12828',
            self::ApplicationNotClosed => '#d12828',
        };
    }

    public function isFinal(): bool
    {
        return in_array($this, [self::ShiftMissed, self::ApplicationCanceled, self::ApplicationNotClosed], true);
    }
}
