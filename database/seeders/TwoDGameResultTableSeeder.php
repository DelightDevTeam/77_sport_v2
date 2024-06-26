<?php

namespace Database\Seeders;

use App\Models\Two\TwodGameResult;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class TwoDGameResultTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
     public function run(): void
    {
        // Set the starting date to today's date
        $currentDate = Carbon::now();

        // Iterate over the next 10 years
        for ($year = 0; $year < 5; $year++) {
            // Iterate over each month in the year
            for ($month = 1; $month <= 12; $month++) {
                // Determine the number of days in the month
                $daysInMonth = Carbon::create($currentDate->year + $year, $month)->daysInMonth;

                // Iterate over each day in the month
                for ($day = 1; $day <= $daysInMonth; $day++) {
                    // Calculate the date
                    $date = Carbon::create($currentDate->year + $year, $month, $day);

                    // Set status to 'open' for today's sessions, 'closed' otherwise
                    $morningStatus = $date->isToday() ? 'open' : 'closed';
                    $eveningStatus = $date->isToday() ? 'open' : 'closed';

                    // Morning session
                    TwodGameResult::create([
                        'result_date' => $date->format('Y-m-d'),
                        'result_time' => '12:01:00', // Morning open time
                        'session' => 'morning',
                        'status' => $morningStatus,
                    ]);

                    // Evening session
                    TwodGameResult::create([
                        'result_date' => $date->format('Y-m-d'),
                        'result_time' => '16:30:00', // Evening open time
                        'session' => 'evening',
                        'status' => $eveningStatus,
                    ]);
                }
            }
        }
    }
    // public function run(): void
    // {
    //     // Set the starting date to today's date
    //     $currentDate = Carbon::now();

    //     // Find the closest Monday (today if it's Monday, or the next Monday)
    //     $startDate = $currentDate->copy()->next(Carbon::MONDAY);
    //     //$startDate = $currentDate->copy()->next(Carbon::SATURDAY);

    //     // Iterate over the next 10 years
    //     for ($year = 0; $year < 10; $year++) {
    //         // Iterate over each month in the year
    //         for ($month = 0; $month < 12; $month++) {
    //             // Iterate over each week in the month
    //             for ($week = 0; $week < 4; $week++) {
    //                 // Monday to Friday (5 days)
    //                 for ($day = 0; $day < 7; $day++) {
    //                     // Calculate the exact date based on week, month, and year
    //                     $date = $startDate->copy()
    //                         ->addYears($year) // Move through each year
    //                         ->addMonths($month)
    //                         ->addWeeks($week)
    //                         ->addDays($day);

    //                     // Determine if the calculated date is today's date
    //                     $isCurrentDay = $date->isSameDay($currentDate);

    //                     // Set status to 'open' for today's sessions, 'closed' otherwise
    //                     $morningStatus = $isCurrentDay ? 'open' : 'closed';
    //                     $eveningStatus = $isCurrentDay ? 'open' : 'closed';

    //                     // Morning session
    //                     TwodGameResult::create([
    //                         'result_date' => $date->format('Y-m-d'),
    //                         'result_time' => '12:01:00', // Morning open time
    //                         'session' => 'morning',
    //                         'status' => $morningStatus,
    //                     ]);

    //                     // Evening session
    //                     TwodGameResult::create([
    //                         'result_date' => $date->format('Y-m-d'),
    //                         'result_time' => '16:30:00', // Evening open time
    //                         'session' => 'evening',
    //                         'status' => $eveningStatus,
    //                     ]);
    //                 }
    //             }
    //         }
    //     }
    // }
    //  public function run(): void
    // {
    //     // Set the starting date to today's date
    //     $currentDate = Carbon::now();

    //     // Find the closest Monday (today if it's Monday, or the next Monday)
    //     $startDate = $currentDate->copy()->next(Carbon::MONDAY);

    //     // Iterate over the next 12 months
    //     for ($month = 0; $month < 12; $month++) {
    //         // Iterate over each week in the month
    //         for ($week = 0; $week < 4; $week++) {
    //             // Monday to Friday (5 days)
    //             for ($day = 0; $day < 5; $day++) {
    //                 // Calculate the exact date based on week and day
    //                 $date = $startDate->copy()
    //                     ->addWeeks($week + 4 * $month)
    //                     ->addDays($day);

    //                 // Determine if the calculated date is today's date
    //                 $isCurrentDay = $date->isSameDay($currentDate);

    //                 // Set status to 'open' for today's sessions, 'closed' otherwise
    //                 $morningStatus = $isCurrentDay ? 'open' : 'closed';
    //                 $eveningStatus = $isCurrentDay ? 'open' : 'closed';

    //                 // Morning session
    //                 TwodGameResult::create([
    //                     'result_date' => $date->format('Y-m-d'),
    //                     'result_time' => '12:01:00', // Morning open time
    //                     'session' => 'morning',
    //                     'status' => $morningStatus,
    //                 ]);

    //                 // Evening session
    //                 TwodGameResult::create([
    //                     'result_date' => $date->format('Y-m-d'),
    //                     'result_time' => '16:30:00', // Evening open time
    //                     'session' => 'evening',
    //                     'status' => $eveningStatus,
    //                 ]);
    //             }
    //         }
    //     }
    // }
    // public function run(): void
    // {
    //     $currentDate = Carbon::now(); // Today's date

    //     // Iterate over the next 12 months
    //     for ($i = 0; $i < 12; $i++) {
    //         // Iterate over each week
    //         for ($week = 0; $week < 4; $week++) {
    //             // Monday to Friday (5 days)
    //             for ($day = 0; $day < 5; $day++) {
    //                 $date = $currentDate->copy()->addWeeks($week)->next(Carbon::MONDAY)->addDays($day);

    //                 // Determine if the date matches today's date
    //                 $isCurrentDay = $date->isSameDay($currentDate);

    //                 // Set status to 'open' only for the current day's sessions
    //                 $morningStatus = $isCurrentDay ? 'open' : 'closed';
    //                 $eveningStatus = $isCurrentDay ? 'open' : 'closed';

    //                 // Morning session
    //                 TwodGameResult::create([
    //                     'result_date' => $date->format('Y-m-d'),
    //                     'result_time' => '12:01:00', // Morning open time
    //                     'session' => 'morning', // Session identifier
    //                     'status' => $morningStatus, // Set status based on the current day
    //                 ]);

    //                 // Evening session
    //                 TwodGameResult::create([
    //                     'result_date' => $date->format('Y-m-d'),
    //                     'result_time' => '16:30:00', // Evening open time
    //                     'session' => 'evening', // Session identifier
    //                     'status' => $eveningStatus, // Set status based on the current day
    //                 ]);
    //             }
    //         }
    //     }
    // }
}
