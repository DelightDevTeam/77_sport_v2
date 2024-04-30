<?php

namespace App\Http\Controllers\Api\V1\ThreeD;

use App\Http\Controllers\Controller;
use App\Services\OneWeekHistoryService;
use Illuminate\Http\JsonResponse;

class OneWeekHistoryController extends Controller
{
    protected $userLotteryDataService;

    public function __construct(OneWeekHistoryService $userLotteryDataService)
    {
        $this->userLotteryDataService = $userLotteryDataService;
    }

    public function getUserLotteryData(): JsonResponse
    {
        try {
            // Retrieve the data for the authenticated user
            $data = $this->userLotteryDataService->getUserData();

            return response()->json([
                'status' => 'success',
                'total_sub_amount' => $data['totalSubAmount'],
                'data' => $data['results'],
            ]);
        } catch (\Exception $e) {
            // Handle the exception and return a consistent error response
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve data: '.$e->getMessage(),
            ], 500); // Use status code 500 for server errors
        }
    }
}
