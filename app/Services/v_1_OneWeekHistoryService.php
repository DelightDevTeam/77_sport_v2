<?php
namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class OneWeekHistoryService
{
  
    protected function getDateRangeForMonth()
    {
        $today = Carbon::now();
        $currentDay = $today->day;
        $currentMonth = $today->month;

        if ($currentMonth === 12) {
            if ($currentDay <= 16) {
                $start = Carbon::create($today->year, 12, 1);
                $end = Carbon::create($today->year, 12, 16);
            } else {
                $start = Carbon::create($today->year, 12, 17);
                $end = Carbon::create($today->year, 12, 30);
            }
        } else {
            if ($currentDay <= 16) {
                $start = Carbon::create($today->year, $currentMonth, 1);
                $end = Carbon::create($today->year, $currentMonth, 16);
            } else {
                $start = Carbon::create($today->year, $currentMonth, 17);
                $end = Carbon::create($today->year, $currentMonth, 1)->addMonth();
            }
        }

        return [$start, $end];
    }
    public function getUserData()
    {
        $userId = Auth::id(); // Get the authenticated user's ID

        [$startDate, $endDate] = $this->getDateRangeForMonth(); // Get the date range

        $lotteryIds = DB::table('lottos') // Get the related lottery IDs for the user
            ->where('user_id', $userId)
            ->pluck('id');

        if ($lotteryIds->isEmpty()) {
            return [
                'results' => collect([]),
                'totalSubAmount' => 0,
            ];
        }

        // Retrieve the user's played data within the specified date range
        $results = DB::table('lotto_three_digit_pivot')
            ->join('lottos', 'lotto_three_digit_pivot.lotto_id', '=', 'lottos.id')
            ->join('users', 'lottos.user_id', '=', 'users.id')
            ->select(
                'users.id as user_id',
                'users.name as user_name',
                'users.phone as user_phone',
                'lotto_three_digit_pivot.bet_digit',
                'lotto_three_digit_pivot.res_date',
                'lotto_three_digit_pivot.res_time',
                'lotto_three_digit_pivot.sub_amount',
                'lotto_three_digit_pivot.prize_sent',
                'lotto_three_digit_pivot.match_status'
            )
            ->whereBetween('lotto_three_digit_pivot.res_date', [$startDate, $endDate])
            ->whereIn('lotto_three_digit_pivot.lotto_id', $lotteryIds)
            ->get();

        // Calculate the total sub_amount for this user within the relevant date range
        $totalSubAmount = DB::table('lotto_three_digit_pivot')
            ->whereBetween('lotto_three_digit_pivot.res_date', [$startDate, $endDate])
            ->whereIn('lotto_three_digit_pivot.lotto_id', $lotteryIds)
            ->sum('sub_amount');

        return [
            'results' => $results,
            'totalSubAmount' => $totalSubAmount,
        ];
    }
    // public function getUserData()
    // {
    //     $userId = Auth::id();

    //     Log::info("Retrieving data for user ID: {$userId}");

    //     [$startDate, $endDate] = $this->getDateRangeForMonth();
    //     Log::info("Date range: {$startDate} to {$endDate}");

    //     $lotteryIds = DB::table('lottos')
    //         ->where('user_id', $userId)
    //         ->pluck('id');

    //     Log::info("Found lottery IDs for user ID: {$userId}: " . implode(',', $lotteryIds->toArray()));

    //     $results = DB::table('lotto_three_digit_pivot')
    //         ->join('lottos', 'lotto_three_digit_pivot.lotto_id', '=', 'lottos.id')
    //         ->join('users', 'lottos.user_id', '=', 'users.id')
    //         ->select(
    //             'users.name as user_name',
    //             'users.phone as user_phone',
    //             'lotto_three_digit_pivot.bet_digit',
    //             'lotto_three_digit_pivot.res_date',
    //             'lotto_three_digit_pivot.sub_amount',
    //             'lotto_three_digit_pivot.prize_sent',
    //             'lotto_three_digit_pivot.match_status'
    //         )
    //         ->where('lotto_three_digit_pivot.user_log', 'open')
    //         ->whereBetween('lotto_three_digit_pivot.res_date', [$startDate, $endDate])
    //         ->whereIn('lotto_three_digit_pivot.lotto_id', $lotteryIds)
    //         ->get();

    //     Log::info("Retrieved results for user ID: {$userId}: " . count($results));

    //     $totalSubAmount = DB::table('lotto_three_digit_pivot')
    //         ->where('user_log', 'open')
    //         ->whereBetween('lotto_three_digit_pivot.res_date', [$startDate, $endDate])
    //         ->whereIn('lotto_three_digit_pivot.lotto_id', $lotteryIds)
    //         ->sum('sub_amount');

    //     Log::info("Total sub_amount for user ID: {$userId}: {$totalSubAmount}");

    //     return [
    //         'results' => $results,
    //         'totalSubAmount' => $totalSubAmount,
    //     ];
    // }
}
   
 // protected function getDateRangeForMonth()
    // {
    //     $today = Carbon::now();
    //     $currentDay = $today->day;
    //     $currentMonth = $today->month;

    //     if ($currentMonth === 12) {
    //         if ($currentDay <= 16) {
    //             $start = Carbon::create($today->year, 12, 1);
    //             $end = Carbon::create($today->year, 12, 16);
    //         } else {
    //             $start = Carbon::create($today->year, 12, 17);
    //             $end = Carbon::create($today->year, 12, 30);
    //         }
    //     } else {
    //         if ($currentDay <= 16) {
    //             $start = Carbon::create($today->year, $currentMonth, 1);
    //             $end = Carbon::create($today->year, $currentMonth, 16);
    //         } else {
    //             $start = Carbon::create($today->year, $currentMonth, 17);
    //             $end = Carbon::create($today->year, $currentMonth, 1)->addMonth();
    //         }
    //     }

    //     return [$start, $end];
    // }

    // public function getUserData()
    // {
    //     $userId = Auth::id();

    //     Log::info("Retrieving data for user ID: {$userId}");

    //     [$startDate, $endDate] = $this->getDateRangeForMonth();
    //     Log::info("Date range: {$startDate} to {$endDate}");

    //     $lotteryIds = DB::table('lottos')
    //         ->where('user_id', $userId)
    //         ->pluck('id');

    //     if ($lotteryIds->isEmpty()) {
    //         Log::info("No lotteries found for user ID: {$userId}");
    //         return [
    //             'results' => collect([]), // Empty collection
    //             'totalSubAmount' => 0,
    //         ];
    //     }

    //     Log::info("Found lottery IDs for user ID: {$userId}: " . implode(',', $lotteryIds->toArray()));

    //     $results = DB::table('lotto_three_digit_pivot')
    //         ->join('lottos', 'lotto_three_digit_pivot.lotto_id', '=', 'lottos.id')
    //         ->join('users', 'lottos.user_id', '=', 'users.id')
    //         ->select(
    //             'users.name as user_name',
    //             'users.phone as user_phone',
    //             'lotto_three_digit_pivot.bet_digit',
    //             'lotto_three_digit_pivot.res_date',
    //             'lotto_three_digit_pivot.sub_amount',
    //             'lotto_three_digit_pivot.prize_sent',
    //             'lotto_three_digit_pivot.match_status'
    //         )
    //         ->where('lotto_three_digit_pivot.user_log', 'open')
    //         ->whereBetween('lotto_three_digit_pivot.res_date', [$startDate, $endDate])
    //         ->whereIn('lotto_three_digit_pivot.lotto_id', $lotteryIds) // Reference the correct lottery IDs
    //         ->get();

    //     Log::info("Retrieved results for user ID: {$userId}: " . count($results));

    //     $totalSubAmount = DB::table('lotto_three_digit_pivot')
    //         ->where('user_log', 'open')
    //         ->whereBetween('lotto_three_digit_pivot.res_date', [$startDate, $endDate])
    //         ->whereIn('lotto_three_digit_pivot.lotto_id', $lotteryIds) // Reference the correct lottery IDs
    //         ->sum('sub_amount');

    //     return [
    //         'results' => $results,
    //         'totalSubAmount' => $totalSubAmount,
    //     ];
    // }