<?php

declare(strict_types=1);

namespace LUVR\Application\Services;

use LUVR\Application\DTOs\CreateLUVRDto;
use LUVR\Application\DTOs\UpdateLUVRDto;
use LUVR\Domain\Entities\LUVR;
use LUVR\Domain\Repositories\LUVRRepositoryInterface;
use LUVR\Domain\ValueObjects\LUVRStatus;
use LUVR\Domain\Exceptions\LUVRException;
use ShiftPlanning\Domain\ValueObjects\ShiftId;

final class LUVRService
{
    public function __construct(
        private readonly LUVRRepositoryInterface $repository
    ) {}

    public function create(CreateLUVRDto $dto): LUVR
    {
        $luvr = new LUVR(
            id: 0,
            shiftId: new ShiftId($dto->shiftId),
            status: LUVRStatus::WaitingForApproval,
            startDateTime: $dto->startDateTime,
            endDateTime: $dto->endDateTime
        );

        return $this->repository->save($luvr);
    }

    public function update(int $id, UpdateLUVRDto $dto): LUVR
    {
        $luvr = $this->repository->findById($id);
        if (! $luvr) {
            throw LUVRException::notFound();
        }
        if ($luvr->getStatus() === LUVRStatus::Paid) {
            throw LUVRException::cannotModifyPaid();
        }

        if ($dto->startDateTime !== null && $dto->endDateTime !== null) {
            $luvr = $luvr->withUpdatedDates($dto->startDateTime, $dto->endDateTime);
        }
        if ($dto->status !== null) {
            $luvr = $luvr->withUpdatedStatus($dto->status);
        }

        return $this->repository->save($luvr);
    }

    public function getById(int $id): ?LUVR
    {
        return $this->repository->findById($id);
    }

    public function delete(int $id): void
    {
        $luvr = $this->repository->findById($id);
        if (! $luvr) {
            throw LUVRException::notFound();
        }
        $this->repository->delete($luvr);
    }
}
