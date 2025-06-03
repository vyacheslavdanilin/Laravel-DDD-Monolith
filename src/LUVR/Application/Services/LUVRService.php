<?php

declare(strict_types=1);

namespace LUVR\Application\Services;

use LUVR\Application\DTOs\CreateLUVRDto;
use LUVR\Application\DTOs\UpdateLUVRDto;
use LUVR\Domain\Entities\LUVR;
use LUVR\Domain\Repositories\LUVRRepositoryInterface;
use LUVR\Domain\ValueObjects\LUVRStatus;
use LUVR\Infrastructure\Exceptions\LUVRException;
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

        $this->repository->save($luvr);

        return $luvr;
    }

    public function update(int $id, UpdateLUVRDto $dto): LUVR
    {
        $luvr = $this->repository->findById($id);
        if (! $luvr) {
            throw new LUVRException('LUVR not found');
        }

        if ($luvr->getStatus() === LUVRStatus::Paid) {
            throw new LUVRException('Cannot update a paid LUVR');
        }

        if ($dto->startDateTime !== null && $dto->endDateTime !== null) {
            $luvr->updateDates($dto->startDateTime, $dto->endDateTime);
        }
        if ($dto->status !== null) {
            $luvr->updateStatus($dto->status);
        }

        $this->repository->save($luvr);

        return $luvr;
    }

    public function getById(int $id): ?LUVR
    {
        return $this->repository->findById($id);
    }

    public function delete(int $id): void
    {
        $luvr = $this->repository->findById($id);
        if (! $luvr) {
            throw new LUVRException('LUVR not found');
        }
        $this->repository->delete($luvr);
    }
}
