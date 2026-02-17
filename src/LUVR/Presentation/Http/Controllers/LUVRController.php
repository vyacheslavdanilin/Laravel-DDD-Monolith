<?php

declare(strict_types=1);

namespace LUVR\Presentation\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use LUVR\Application\Services\LUVRService;
use LUVR\Domain\Exceptions\LUVRException;
use LUVR\Presentation\Http\Requests\CreateLUVRRequest;
use LUVR\Presentation\Http\Requests\UpdateLUVRRequest;
use LUVR\Presentation\Http\Resources\LUVRResource;
use Psr\Log\LoggerInterface;
use Throwable;

final class LUVRController extends Controller
{
    public function __construct(
        private readonly LUVRService $service,
        private readonly LoggerInterface $logger
    ) {}

    public function create(CreateLUVRRequest $request): JsonResponse
    {
        try {
            $luvr = $this->service->create($request->toDto());

            return (new LUVRResource($luvr))
                ->response()
                ->setStatusCode(201);
        } catch (LUVRException $e) {
            $this->logger->warning('LUVR create failed: '.$e->getMessage(), ['exception' => $e]);

            return response()->json(['message' => $e->getMessage()], 400);
        } catch (Throwable $e) {
            $this->logger->error('LUVR create error: '.$e->getMessage(), ['exception' => $e]);

            return response()->json(['message' => 'Internal server error'], 500);
        }
    }

    public function update(UpdateLUVRRequest $request, int $id): JsonResponse
    {
        try {
            $luvr = $this->service->update($id, $request->toDto());

            return (new LUVRResource($luvr))->response();
        } catch (LUVRException $e) {
            $this->logger->warning('LUVR update failed: '.$e->getMessage(), ['exception' => $e]);

            return response()->json(
                ['message' => $e->getMessage()],
                $e->isNotFound() ? 404 : 400
            );
        } catch (Throwable $e) {
            $this->logger->error('LUVR update error: '.$e->getMessage(), ['exception' => $e]);

            return response()->json(['message' => 'Internal server error'], 500);
        }
    }

    public function show(int $id): JsonResponse
    {
        $luvr = $this->service->getById($id);
        if (! $luvr) {
            return response()->json(['message' => 'Not found'], 404);
        }

        return (new LUVRResource($luvr))->response();
    }

    public function delete(int $id): JsonResponse
    {
        try {
            $this->service->delete($id);

            return response()->json(null, 204);
        } catch (LUVRException $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        } catch (Throwable $e) {
            $this->logger->error('LUVR delete error: '.$e->getMessage(), ['exception' => $e]);

            return response()->json(['message' => 'Internal server error'], 500);
        }
    }
}
