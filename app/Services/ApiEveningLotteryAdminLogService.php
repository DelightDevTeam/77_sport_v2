<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ApiEveningLotteryAdminLogService
{
    protected function getCurrentSession()
    {
        $currentTime = Carbon::now()->format('H:i:s');

        if ($currentTime >= '12:01:00' && $currentTime <= '16:30:00') {
            return 'evening'; // Evening session
        } else {
            return 'closed';
        }
    }

    public function getLotteryAdminLogForAuthUser()
    {
        $today = Carbon::today()->toDateString(); // Get today's date
        $currentSession = $this->getCurrentSession(); // Get the current session
        $userId = Auth::id(); // Get the authenticated user's ID

        Log::info("Retrieving data for user ID: $userId, for the current session");

        // Fetch lottery IDs for the authenticated user within the current session
        $lotteryIds = DB::table('lottery_two_digit_pivot')
            ->where('user_id', $userId) // Filter by the authenticated user's ID
            ->where('session', $currentSession) // Filter by session
            //->where('user_log', 'open') // Ensure user log is open
            ->pluck('lottery_id'); // Get unique lottery IDs

        if ($lotteryIds->isEmpty()) {
            Log::info("No lotteries found for user ID: $userId during the current session.");

            return [
                'results' => collect([]), // Return an empty collection
                'totalSubAmount' => 0,
            ];
        }

        // Fetch the user's data based on the retrieved lottery IDs
        $results = DB::table('lottery_two_digit_pivot')
            ->join('lotteries', 'lottery_two_digit_pivot.lottery_id', '=', 'lotteries.id')
            ->join('users', 'lotteries.user_id', '=', 'users.id')
            ->select(
                'users.name as user_name',
                'users.phone as user_phone',
                'lottery_two_digit_pivot.bet_digit',
                'lottery_two_digit_pivot.res_date',
                'lottery_two_digit_pivot.res_time',
                'lottery_two_digit_pivot.sub_amount',
                'lottery_two_digit_pivot.prize_sent',
                'lottery_two_digit_pivot.match_status'
            )
            ->whereIn('lottery_two_digit_pivot.lottery_id', $lotteryIds) // Filter by the user's lottery IDs
            ->get();

        // Calculate the total sub_amount for this session and user
        $totalSubAmount = DB::table('lottery_two_digit_pivot')
            ->whereIn('lottery_two_digit_pivot.lottery_id', $lotteryIds) // Filter by the user's lottery IDs
            //->where('user_log', 'open') // Ensure user log is open
            ->where('session', $currentSession) // Filter by session

            ->sum('sub_amount');

        return [
            'results' => $results,
            'totalSubAmount' => $totalSubAmount,
        ];
    }

    // public function getLotteryAdminLogForAuthUser()
    // {
    //     $today = Carbon::today()->toDateString();

    //     $currentSession = $this->getCurrentSession();

    //     $userId = Auth::id();

    //     // Get the lottery ID(s) for the current user (assuming there's a unique or distinct key)
    //     $lotteryIds = DB::table('lotteries')
    //         ->where('user_id', $userId)
    //         ->pluck('id');

    //     if ($lotteryIds->isEmpty()) {
    //         return [
    //             'results' => collect([]), // Empty collection
    //             'totalSubAmount' => 0,
    //         ];
    //     }

    //     $results = DB::table('lottery_two_digit_pivot')
    //         ->join('lotteries', 'lottery_two_digit_pivot.lottery_id', '=', 'lotteries.id')
    //         ->join('users', 'lotteries.user_id', '=', 'users.id')
    //         ->select(
    //             'users.name as user_name',
    //             'users.phone as user_phone',
    //             'lottery_two_digit_pivot.bet_digit',
    //             'lottery_two_digit_pivot.res_date',
    //             'lottery_two_digit_pivot.res_time',
    //             'lottery_two_digit_pivot.session',
    //             'lottery_two_digit_pivot.match_status',
    //             'lottery_two_digit_pivot.sub_amount'
    //         )
    //         ->where('lottery_two_digit_pivot.user_log', 'open') // Admin log is open
    //         ->where('lottery_two_digit_pivot.res_date', $today) // Today's results
    //         ->where('lottery_two_digit_pivot.session', $currentSession) // Current session
    //         ->whereIn('lottery_two_digit_pivot.lottery_id', $lotteryIds) // Filter by lottery IDs for the user
    //         ->get();

    //     $totalSubAmount = DB::table('lottery_two_digit_pivot')
    //         ->where('user_log', 'open') // Admin log is open
    //         ->where('res_date', $today) // Today's results
    //         ->where('session', $currentSession) // Current session
    //         ->whereIn('lottery_two_digit_pivot.lottery_id', $lotteryIds) // Filter by lottery IDs for the user
    //         ->sum('sub_amount');

    //     return [
    //         'results' => $results,
    //         'totalSubAmount' => $totalSubAmount,
    //     ];
    // }
}
