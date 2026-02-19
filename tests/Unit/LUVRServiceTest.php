<?php

namespace Tests\Unit;

use DateTimeImmutable;
use LUVR\Application\DTOs\CreateLUVRDto;
use LUVR\Application\DTOs\UpdateLUVRDto;
use LUVR\Application\Services\LUVRService;
use LUVR\Domain\Entities\LUVR;
use LUVR\Domain\Exceptions\LUVRException;
use LUVR\Domain\Repositories\LUVRRepositoryInterface;
use LUVR\Domain\ValueObjects\LUVRStatus;
use PHPUnit\Framework\TestCase;
use ShiftPlanning\Domain\ValueObjects\ShiftId;

class InMemoryLUVRRepository implements LUVRRepositoryInterface
{
    public array $items = [];
    public array $saved = [];

    public function __construct(array $items = [])
    {
        $this->items = $items;
    }

    public function findById(int $id): ?LUVR
    {
        return $this->items[$id] ?? null;
    }

    public function save(LUVR $luvr): LUVR
    {
        if ($luvr->getId() === 0) {
            $newId = count($this->items) + 1;
            $persisted = new LUVR(
                $newId,
                $luvr->getShiftId(),
                $luvr->getStatus(),
                $luvr->getStartDateTime(),
                $luvr->getEndDateTime()
            );
            $this->items[$newId] = $persisted;
            $this->saved[] = $persisted;

            return $persisted;
        }
        $this->items[$luvr->getId()] = $luvr;
        $this->saved[] = $luvr;

        return $luvr;
    }

    public function delete(LUVR $luvr): void
    {
        unset($this->items[$luvr->getId()]);
    }
}

class LUVRServiceTest extends TestCase
{
    private function createService(?InMemoryLUVRRepository $repo = null): array
    {
        $repo = $repo ?? new InMemoryLUVRRepository();
        return [$repo, new LUVRService($repo)];
    }

    public function test_create_saves_luvr_with_default_status(): void
    {
        [$repo, $service] = $this->createService();

        $dto = new CreateLUVRDto(
            shiftId: 1,
            startDateTime: new DateTimeImmutable('2025-01-01 08:00'),
            endDateTime: new DateTimeImmutable('2025-01-01 16:00'),
        );

        $luvr = $service->create($dto);

        $this->assertSame(LUVRStatus::WaitingForApproval, $luvr->getStatus());
        $this->assertCount(1, $repo->saved);
    }

    public function test_update_throws_exception_when_not_found(): void
    {
        [$repo, $service] = $this->createService();

        $this->expectException(LUVRException::class);
        $service->update(1, new UpdateLUVRDto());
    }

    public function test_update_throws_exception_when_paid(): void
    {
        $luvr = new LUVR(
            id: 1,
            shiftId: new ShiftId(1),
            status: LUVRStatus::Paid,
            startDateTime: new DateTimeImmutable('2025-01-01 08:00'),
            endDateTime: new DateTimeImmutable('2025-01-01 16:00'),
        );
        $repo = new InMemoryLUVRRepository(['1' => $luvr]);
        [$repo, $service] = $this->createService($repo);

        $this->expectException(LUVRException::class);
        $service->update(1, new UpdateLUVRDto());
    }

    public function test_update_changes_data_and_saves(): void
    {
        $luvr = new LUVR(
            id: 1,
            shiftId: new ShiftId(1),
            status: LUVRStatus::WaitingForApproval,
            startDateTime: new DateTimeImmutable('2025-01-01 08:00'),
            endDateTime: new DateTimeImmutable('2025-01-01 16:00'),
        );
        $repo = new InMemoryLUVRRepository(['1' => $luvr]);
        [$repo, $service] = $this->createService($repo);

        $dto = new UpdateLUVRDto(
            startDateTime: new DateTimeImmutable('2025-01-02 08:00'),
            endDateTime: new DateTimeImmutable('2025-01-02 18:00'),
            status: LUVRStatus::Approved,
        );

        $updated = $service->update(1, $dto);

        $this->assertSame('2025-01-02 08:00:00', $updated->getStartDateTime()->format('Y-m-d H:i:s'));
        $this->assertSame('2025-01-02 18:00:00', $updated->getEndDateTime()->format('Y-m-d H:i:s'));
        $this->assertSame(LUVRStatus::Approved, $updated->getStatus());
        $this->assertCount(1, $repo->saved);
    }
}
