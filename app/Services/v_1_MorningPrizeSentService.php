<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class MorningPrizeSentService
{
    /**
     * Determine the current session based on the time of day.
     *
     * @return string
     */
    protected function getCurrentSession()
    {
        $currentTime = Carbon::now()->format('H:i:s');

        if ($currentTime >= '04:00:00' && $currentTime <= '12:01:00') {
            return 'morning'; // Morning session
        } elseif ($currentTime >= '12:01:01' && $currentTime <= '16:30:00') {
            return 'evening'; // Evening session
        } else {
            return 'closed'; // Default if outside session times
        }
    }

    /**
     * Retrieve the user data for the authenticated user where prize_sent is true,
     * filtered by session, weekday, and current day, with sub_amount * 85.
     *
     * @return array
     */
    public function getAuthUserPrizeSentData()
    {
        $userId = Auth::id(); // Authenticated user's ID
        $today = Carbon::today(); // Current date
        $weekday = $today->isoFormat('dddd'); // Day of the week
        $currentSession = $this->getCurrentSession(); // Determine the current session

        // Ensure the day is Monday to Friday
        if (!in_array(strtolower($weekday), ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'])) {
            return [
                'results' => collect([]), // Return an empty collection if not a weekday
                'totalSubAmount' => 0,
            ];
        }

        // Retrieve the required data
        $results = DB::table('lottery_two_digit_pivot')
            ->join('users', 'lottery_two_digit_pivot.user_id', '=', 'users.id')
            ->select(
                'users.name as user_name',
                'users.phone as user_phone',
                'lottery_two_digit_pivot.bet_digit',
                'lottery_two_digit_pivot.res_date',
                'lottery_two_digit_pivot.res_time',
                'lottery_two_digit_pivot.sub_amount * 85 as total_prize', // Multiplication
                'lottery_two_digit_pivot.prize_sent',
                'lottery_two_digit_pivot.match_status'
            )
            ->where('lottery_two_digit_pivot.prize_sent', true) // Where prize is sent
            ->where('lottery_two_digit_pivot.user_id', $userId) // Authenticated user
            ->where('lottery_two_digit_pivot.res_date', $today) // Current day
            ->where('lottery_two_digit_pivot.session', $currentSession) // Current session
            ->get();

        // Calculate the total prize (sub_amount * 85)
        $totalSubAmount = $results->sum(function ($row) {
            return $row->total_prize; // Using the calculated field
        });

        return [
            'results' => $results,
            'totalSubAmount' => $totalSubAmount,
        ];
    }
}