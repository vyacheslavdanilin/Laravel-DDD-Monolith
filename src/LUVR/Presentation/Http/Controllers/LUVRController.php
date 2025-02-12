<?php

declare(strict_types=1);

namespace LUVR\Presentation\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use LUVR\Application\Services\LUVRService;
use LUVR\Presentation\Http\Requests\CreateLUVRRequest;
use LUVR\Presentation\Http\Requests\UpdateLUVRRequest;

final class LUVRController extends Controller
{
    public function __construct(
        private readonly LUVRService $service
    ) {}

    public function create(CreateLUVRRequest $request): JsonResponse
    {
        $luvr = $this->service->create($request->toDto());

        return response()->json($luvr, 201);
    }

    public function update(UpdateLUVRRequest $request, int $id): JsonResponse
    {
        $luvr = $this->service->update($id, $request->toDto());

        return response()->json($luvr);
    }

    public function show(int $id): JsonResponse
    {
        $luvr = $this->service->getById($id);
        if (! $luvr) {
            return response()->json(['error' => 'Not found'], 404);
        }

        return response()->json($luvr);
    }

    public function delete(int $id): JsonResponse
    {
        $this->service->delete($id);

        return response()->json(null, 204);
    }
}
