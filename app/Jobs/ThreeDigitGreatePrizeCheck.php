<?php

namespace App\Jobs;

use Carbon\Carbon;
use App\Models\Lotto;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Models\ThreeDigit\LotteryThreeDigitPivot;

class ThreeDigitGreatePrizeCheck implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $prize;

    public function __construct($prize)
    {
        $this->prize = $prize;
    }

    public function handle(): void
    {
        if (!$this->isPlayingDay()) {
            return;
        }

        // Process winning entries directly for prize_one
        $this->processWinningEntries((string) $this->prize->prize_one);

        // Process winning entries directly for prize_two
        $this->processWinningEntries((string) $this->prize->prize_two);
    }

    protected function isPlayingDay(): bool
    {
        $playDays = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
        return in_array(Carbon::now()->englishDayOfWeek, $playDays);
    }

    protected function processWinningEntries($prizeNumber)
    {
        $today = Carbon::today();

        $winningEntries = LotteryThreeDigitPivot::where('bet_digit', $prizeNumber)
            ->where('match_status', 'open')
            ->whereDate('created_at', $today)
            ->get();

        foreach ($winningEntries as $entry) {
            DB::transaction(function () use ($entry) {
                try {
                    $lottery = Lotto::findOrFail($entry->lotto_id);
                    $user = $lottery->user;

                    $prize = $entry->sub_amount * 10; // Calculate the prize amount
                    $user->balance += $prize; // Increase the user's balance
                    $user->save();

                    $entry->prize_sent = 3; // Mark as prize sent
                    $entry->save();

                    Log::info("Prize awarded and prize_sent set to true for entry ID {$entry->id}.");
                } catch (\Exception $e) {
                    Log::error("Error during transaction for entry ID {$entry->id}: " . $e->getMessage());
                    throw $e; // Trigger rollback if needed
                }
            });
        }
    }
}