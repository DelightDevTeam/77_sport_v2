<?php

namespace App\Services;

use App\Models\Admin\TwoDLimit;
use App\Models\Lottery;
use App\Models\LotteryTwoDigitPivot;
use App\Models\Two\TwodGameResult;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TwoDService
{
    public function play($totalAmount, array $amounts)
    {
        // Check for authentication
        if (! Auth::check()) {
            return response()->json(['message' => 'User not authenticated'], 401);
        }

        $user = Auth::user();

        DB::beginTransaction();

        try {
            // Access `Limit` with error handling
            //$limit = $user->limit ?? null;
            $limit = TwoDLimit::latest()->first()->two_d_limit;

            if ($limit === null) {
                throw new \Exception("Commission rate 'limit' is not set for user.");
            }

            if ($user->balance < $totalAmount) {
                return 'Insufficient funds.';
            }

            $preOver = [];
            foreach ($amounts as $amount) {
                $preCheck = $this->preProcessAmountCheck($amount);
                if (is_array($preCheck)) {
                    $preOver[] = $preCheck[0];
                }
            }
            if (! empty($preOver)) {
                return $preOver;
            }

            // Create a new lottery entry
            $lottery = Lottery::create([
                'pay_amount' => $totalAmount,
                'total_amount' => $totalAmount,
                'user_id' => $user->id,
            ]);

            $over = [];
            foreach ($amounts as $amount) {
                $check = $this->processAmount($amount, $lottery->id);
                if (is_array($check)) {
                    $over[] = $check[0];
                }
            }
            if (! empty($over)) {
                return $over;
            }

            $user->balance -= $totalAmount;
            $user->save();

            DB::commit();

            return 'Bet placed successfully.';

        } catch (ModelNotFoundException $e) {
            DB::rollback();
            Log::error('Model not found in TwoDService play method: '.$e->getMessage());

            return 'Resource not found.';
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error in TwoDService play method: '.$e->getMessage());

            return $e->getMessage(); // Handle general exceptions
        }
    }

    protected function getCurrentSession()
    {
        $currentTime = Carbon::now()->format('H:i:s');

        if ($currentTime >= '04:01:00' && $currentTime <= '12:01:00') {
            return 'morning';
        } elseif ($currentTime >= '12:01:00' && $currentTime <= '15:45:00') {
            return 'evening';
        } else {
            return 'closed'; // If outside known session times
        }
    }

    protected function getCurrentSessionTime()
    {
        $currentTime = Carbon::now()->format('H:i:s');

        if ($currentTime >= '04:01:00' && $currentTime <= '12:01:00') {
            return '12:01:00';
        } elseif ($currentTime >= '12:01:00' && $currentTime <= '15:45:00') {
            return '16:30:00';
        } else {
            return 'closed'; // If outside known session times
        }
    }

    protected function preProcessAmountCheck($amount)
    {
        $twoDigit = str_pad($amount['num'], 2, '0', STR_PAD_LEFT); // Ensure two-digit format
        $break = TwoDLimit::latest()->first()->two_d_limit;
        //$break = Auth::user()->limit ?? 0; // Set default value if `cor` is not set

        Log::info("User's commission limit (limit): {$break}");
        Log::info("Checking bet_digit: {$twoDigit}");

        $totalBetAmountForTwoDigit = DB::table('lottery_two_digit_pivot')
            ->where('bet_digit', $twoDigit)
            ->sum('sub_amount');

        Log::info("Total bet amount for {$twoDigit}: {$totalBetAmountForTwoDigit}");

        $subAmount = $amount['amount'];

        if ($totalBetAmountForTwoDigit + $subAmount > $break) {
            Log::warning("Bet on {$twoDigit} exceeds limit.");

            return [$amount['num']]; // Indicates over-limit
        }

        return null; // Indicates no over-limit
    }

    protected function processAmount($amount, $lotteryId)
    {

        $twoDigit = str_pad($amount['num'], 2, '0', STR_PAD_LEFT); // Ensure three-digit format

        $break = TwoDLimit::latest()->first()->two_d_limit;
        //$break = Auth::user()->limit;

        $totalBetAmountForTwoDigit = DB::table('lottery_two_digit_pivot')
            ->where('bet_digit', $twoDigit)
            ->sum('sub_amount');
        $subAmount = $amount['amount'];
        $betDigit = $amount['num'];

        if ($totalBetAmountForTwoDigit + $subAmount <= $break) {
            $userID = Auth::user();
            $today = Carbon::now()->format('Y-m-d');
            $currentSession = $this->getCurrentSession();
            $currentSessionTime = $this->getCurrentSessionTime();
            // Retrieve results for today where status is 'open'
            $results = TwodGameResult::where('result_date', $today) // Match today's date
                ->where('status', 'open')      // Check if the status is 'open'
                ->first();

            LotteryTwoDigitPivot::create([
                'lottery_id' => $lotteryId,
                'twod_game_result_id' => $results->id,
                'user_id' => $userID->id,
                'bet_digit' => $betDigit,
                'sub_amount' => $subAmount,
                'prize_sent' => false,
                'match_status' => $results->status,
                'res_date' => $results->result_date,
                'res_time' => $currentSessionTime,
                'session' => $currentSession,
                'admin_log' => $results->admin_log,
                'user_log' => $results->user_log,

            ]);
        } else {
            // Handle the case where the bet exceeds the limit
            return [$amount['num']];
        }
    }

    private function determineSession()
    {
        return date('H') < 12 ? 'morning' : 'evening';
    }
}
