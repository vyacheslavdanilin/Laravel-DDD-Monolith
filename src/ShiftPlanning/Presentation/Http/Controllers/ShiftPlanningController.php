<?php

declare(strict_types=1);

namespace ShiftPlanning\Presentation\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Psr\Log\LoggerInterface;
use Shared\Domain\Contracts\EventBus;
use ShiftPlanning\Application\Services\ShiftPlanningService;
use ShiftPlanning\Domain\Exceptions\ShiftPlanningException;
use ShiftPlanning\Presentation\Http\Requests\CreateShiftPlanningRequest;
use ShiftPlanning\Presentation\Http\Resources\ShiftPlanningResource;
use Throwable;

final class ShiftPlanningController
{
    public function __construct(
        private readonly ShiftPlanningService $shiftPlanningService,
        private readonly EventBus $eventBus,
        private readonly LoggerInterface $logger
    ) {}

    public function create(CreateShiftPlanningRequest $request): JsonResponse
    {
        try {
            $shiftPlanning = $this->shiftPlanningService->create($request->toDto());
            $shiftPlanning->releaseEvents($this->eventBus);

            return (new ShiftPlanningResource($shiftPlanning))
                ->response()
                ->setStatusCode(201);

        } catch (ShiftPlanningException $e) {
            $this->logger->warning('Ошибка создания смены: '.$e->getMessage(), ['exception' => $e]);

            return response()->json([
                'message' => 'Ошибка при создании смены',
                'error' => $e->getMessage(),
            ], 400);

        } catch (Throwable $e) {
            $this->logger->error('Неизвестная ошибка в создании смены: '.$e->getMessage(), ['exception' => $e]);

            return response()->json([
                'message' => 'Внутренняя ошибка сервера',
            ], 500);
        }
    }
}
