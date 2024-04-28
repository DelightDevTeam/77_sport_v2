<?php

namespace App\Http\Controllers\Api\V1\TwoD;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\MorningPrizeSentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class MorningPrizeSentController extends Controller
{
    protected $prizeSentService; 

    public function __construct(MorningPrizeSentService $prizeSentService)
    {
        $this->prizeSentService = $prizeSentService;
    }

    /**
     * Display the prize sent data for the authenticated user.
     *
     * @return JsonResponse
     */
    public function showPrizeSentData(): JsonResponse
    {
        try {
            // Attempt to retrieve data from the service
            $data = $this->prizeSentService->getAuthUserPrizeSentData();

            // Return the successful response with the retrieved data
            return response()->json([
                'status' => 'success',
                'data' => $data['results'],
                'totalSubAmount' => $data['totalSubAmount'],
                //'prize_amount' => $data['prize_amount']
            ]);
        } catch (\Exception $e) {
            // Log the error
            Log::error('Error retrieving prize sent data: ' . $e->getMessage());

            // Return a JSON response with an error message
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve data. Please try again later.',
            ], 500);
        }
    }
}