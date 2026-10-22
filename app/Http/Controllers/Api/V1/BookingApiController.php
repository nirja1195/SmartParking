<?php

namespace App\Http\Controllers\Api\V1;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ApiBookingRequest;
use App\Http\Resources\V1\BookingResource;
use App\Services\BookingService;
use App\Services\PaymentService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BookingApiController extends Controller
{
    protected BookingService $bookingService;
    protected PaymentService $paymentService;

    public function __construct(BookingService $bookingService, PaymentService $paymentService)
    {
        $this->bookingService = $bookingService;
        $this->paymentService = $paymentService;
    }

    /**
     * Get authenticated customer's bookings.
     */
    public function index(Request $request): JsonResponse
    {
        $perPage = (int) $request->input('per_page', 10);
        $bookings = $this->bookingService->getUserBookings($request->user()->id, $perPage);

        return ApiResponse::paginated($bookings, 'Customer bookings retrieved.');
    }

    /**
     * Create a new parking spot reservation.
     */
    public function store(ApiBookingRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();
            $data['user_id'] = $request->user()->id;

            $booking = $this->bookingService->createBooking($data);

            // Process demo payment
            $this->paymentService->processPayment($booking, 'api_token', [
                'client' => 'mobile_api',
            ]);

            return ApiResponse::success(
                new BookingResource($booking->load(['spot.lot', 'vehicle', 'user'])),
                'Parking spot successfully reserved.',
                Response::HTTP_CREATED
            );
        } catch (Exception $e) {
            return ApiResponse::error($e->getMessage(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * Get details of a single booking by reference.
     */
    public function show(Request $request, string $reference): JsonResponse
    {
        $booking = $this->bookingService->getBookingByReference($reference);

        if (! $booking || $booking->user_id !== $request->user()->id) {
            return ApiResponse::error('Booking not found.', Response::HTTP_NOT_FOUND);
        }

        return ApiResponse::success(new BookingResource($booking), 'Booking details retrieved.');
    }

    /**
     * Check in vehicle at parking gate.
     */
    public function checkIn(string $reference): JsonResponse
    {
        try {
            $booking = $this->bookingService->checkIn($reference);

            return ApiResponse::success(new BookingResource($booking), 'Check-in confirmed.');
        } catch (Exception $e) {
            return ApiResponse::error($e->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Check out vehicle and calculate overtime.
     */
    public function checkOut(string $reference): JsonResponse
    {
        try {
            $booking = $this->bookingService->checkOut($reference);

            return ApiResponse::success(new BookingResource($booking), 'Check-out completed.');
        } catch (Exception $e) {
            return ApiResponse::error($e->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Cancel reservation.
     */
    public function cancel(Request $request, string $reference): JsonResponse
    {
        try {
            $reason = $request->input('reason', 'Cancelled via API');
            $booking = $this->bookingService->cancelBooking($reference, $reason);

            return ApiResponse::success(new BookingResource($booking), 'Booking cancelled.');
        } catch (Exception $e) {
            return ApiResponse::error($e->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }
}
