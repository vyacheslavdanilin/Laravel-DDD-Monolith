<?php

declare(strict_types=1);

namespace LUVR\Domain\Exceptions;

use Shared\Exceptions\DomainException;

final class LUVRException extends DomainException
{
    private function __construct(string $message, private readonly bool $notFound = false)
    {
        parent::__construct($message);
    }

    public static function notFound(): self
    {
        return new self('LUVR not found', true);
    }

    public static function cannotModifyPaid(): self
    {
        return new self('Cannot update a paid LUVR', false);
    }

    public static function invalidDateRange(): self
    {
        return new self('Start must be before end', false);
    }

    public function isNotFound(): bool
    {
        return $this->notFound;
    }
}
