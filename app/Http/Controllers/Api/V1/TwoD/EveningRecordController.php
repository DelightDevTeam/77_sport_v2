<?php

namespace App\Http\Controllers\Api\V1\TwoD;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\ApiEveningLotteryAdminLogService;
use Illuminate\Http\JsonResponse;
class EveningRecordController extends Controller
{
    protected $lotteryAdminLogService;


    public function __construct(ApiEveningLotteryAdminLogService $lotteryAdminLogService)
    {
        $this->lotteryAdminLogService = $lotteryAdminLogService;
    }

    public function EveningUserLog(): JsonResponse
    {
        try {
            // Retrieve data for the authenticated user
            $data = $this->lotteryAdminLogService->getLotteryAdminLogForAuthUser();

            // Extract results and totalSubAmount from the retrieved data
            $results = $data['results'];
            $totalSubAmount = $data['totalSubAmount'];

            // Return the data in JSON format
            return response()->json([
                'status' => 'success',
                'total_sub_amount' => $totalSubAmount,
                'data' => $results,
            ]);

        } catch (\Exception $e) {
            // If an error occurs, return an error response
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve data: ' . $e->getMessage(),
            ], 500);
        }
    }

}