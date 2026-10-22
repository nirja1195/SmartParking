<?php

namespace App\Http\Controllers\Api\V1;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\V1\ParkingLotResource;
use App\Http\Resources\V1\ParkingSpotResource;
use App\Models\ParkingLot;
use App\Services\ParkingLotService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ParkingLotApiController extends Controller
{
    protected ParkingLotService $lotService;

    public function __construct(ParkingLotService $lotService)
    {
        $this->lotService = $lotService;
    }

    /**
     * List all active parking lots.
     */
    public function index(): JsonResponse
    {
        $lots = $this->lotService->getActiveLots();

        return ApiResponse::success(
            ParkingLotResource::collection($lots),
            'Active parking lots retrieved.'
        );
    }

    /**
     * Get specific parking lot with spots.
     */
    public function show(int $id): JsonResponse
    {
        $lot = $this->lotService->getLotById($id);

        if (! $lot) {
            return ApiResponse::error('Parking lot not found.', Response::HTTP_NOT_FOUND);
        }

        return ApiResponse::success(new ParkingLotResource($lot), 'Parking lot retrieved.');
    }

    /**
     * Get real-time available spots for a lot.
     */
    public function availableSpots(int $id): JsonResponse
    {
        $lot = ParkingLot::findOrFail($id);

        $spots = $lot->spots()
            ->where('is_active', true)
            ->where('is_occupied', false)
            ->get();

        return ApiResponse::success(
            ParkingSpotResource::collection($spots),
            'Available spots retrieved.'
        );
    }
}
