<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\TestController;
use App\Http\Controllers\Api\V1\Auth\AuthController;
use App\Http\Controllers\Api\Jackpot\JackpotController;
use App\Http\Controllers\Api\V1\Auth\ProfileController;
use App\Http\Controllers\Api\V1\TwoD\TwoPlayController;
use App\Http\Controllers\Api\V1\Frontend\HomeController;
use App\Http\Controllers\Api\V1\Frontend\TwoDController;
use App\Http\Controllers\Api\V1\Frontend\ThreeDController;
use App\Http\Controllers\Api\V1\Frontend\WalletController;
use App\Http\Controllers\Api\V1\ThreeD\ThreeDPlayController;
use App\Http\Controllers\Api\V1\Frontend\PromotionController;
use App\Http\Controllers\Api\V1\TwoD\OneWeekHistoryController;
use App\Http\Controllers\Api\Jackpot\JackpotOneWeekGetDataController;
use App\Http\Controllers\Api\V1\Frontend\TwoDRemainingAmountController;
//publish routes
Route::get('/login', [AuthController::class, 'loginData']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

//protected routes
Route::group(["middleware" => ['auth:sanctum']], function(){
    Route::post('/logout', [AuthController::class, 'logout']);
    
    //profile management
    Route::get('/profile', [ProfileController::class, 'profile']);
    Route::post('/profile', [ProfileController::class, 'updateProfile']);
    Route::post('/profile/changePassword', [ProfileController::class, 'changePassword']);

    //Home Routes
    Route::get('/home', [HomeController::class, 'index']);

    //Wallet Routes
    Route::get('/wallet', [WalletController::class, 'banks']);
    Route::get('/wallet/bank/{id}', [WalletController::class, 'bankDetail']);
    Route::post('/wallet/deposit', [WalletController::class, 'deposit']);
    Route::post('/wallet/withdraw', [WalletController::class, 'withdraw']);
    Route::get('/wallet/transferLogs', [WalletController::class, 'transferLog']);

    //Promotion Routes
    Route::get('/promotions', [PromotionController::class, 'promotion']);
    Route::get('/promotion/{id}', [PromotionController::class, 'promotionDetail']);

    //2D Routes
    Route::get('/twoD', [TwoDController::class, 'index']);
    Route::post('/twoD/play', [TwoPlayController::class, 'play']);
    // Route::get('/twoD/playHistory', [TwoDController::class, 'playHistory']); //unfinished
    // for admin 
    // Route::get('/two-d-play-history-for-admin', [TwoDController::class, 'playHistoryForAdmin'])->name('TwoDPlayHistoryForAdmin');

    //3D Routes
    Route::get('/threeD', [ThreeDController::class, 'index']);
    Route::post('/threeD/play', [ThreeDPlayController::class, 'play']);
    Route::get('/threeD/playHistory', [ThreeDController::class, 'playHistory']); //unfinished
    // two once month history
    // Route::get('/twoDigitOnceMonthHistory', [TwoDController::class, 'TwoDigitOnceMonthHistory']);
    // three once month history
    Route::get('/threeDigitOnceMonthHistory', [ThreeDController::class, 'OnceMonthThreeDHistory']);
    // three digit one week play history
    Route::get('/threeDigitOneWeekHistory', [ThreeDController::class, 'OnceWeekThreedigitHistoryConclude']);
    // three digit one month play history
    Route::get('/threeDigitOneMonthHistory', [ThreeDController::class, 'OnceMonthThreedigitHistoryConclude']);

     // three digit winner history
        Route::get('/three-digit-winner-history', [App\Http\Controllers\Admin\ThreeD\ThreeDWinnerController::class, 'getWinnersHistoryForAdminApi'])->name('ThreeDigitHistory');
        // two digit winner history
    
     // commission balance update 
    Route::post('/balance-update', [ProfileController::class, 'balanceUpdateApi']);
    Route::get('/two-d-remaining-amount', [TwoDRemainingAmountController::class, 'index'])->name('twod.play.remaining.amount');
    // auth winner history 
    Route::get('/auth-winner-history', [App\Http\Controllers\Api\V1\ThreeD\AuthWinnerHistoryController::class, 'getWinnersHistoryForAuthUserOnly'])->name('authWinnerHistory');
    // auth two digit winner history
    Route::get('/auth-two-d-winner-history', [App\Http\Controllers\Api\V1\ThreeD\AuthWinnerHistoryController::class, 'TwoDigitWinnerHistory'])->name('authTwoDigitWinnerHistory');

    // auth first prize winner 
    Route::get('/auth-td-first-win-history', [App\Http\Controllers\Api\V1\ThreeD\WinnerHistoryController::class, 'firstPrizeWinnerForApk'])->name('TdfirstPrizeWinner');
    //second 
    Route::get('/auth-td-second-win-history', [App\Http\Controllers\Api\V1\ThreeD\WinnerHistoryController::class, 'secondPrizeWinnerForApk'])->name('TdsecondPrizeWinner');
    Route::get('/auth-td-third-win-history', [App\Http\Controllers\Api\V1\ThreeD\WinnerHistoryController::class, 'thirdPrizeWinnerForApk'])->name('TdthirdPrizeWinner');
    // twod morning prize winner history confirm 
     Route::get('/morning-two-win-history', [App\Http\Controllers\Api\V1\TwoD\TwoDPrizeController::class, 'MorningPrizeWinnerForApk'])->name('TwoMorningPrizeWinner');
     Route::get('/evening-two-win-history', [App\Http\Controllers\Api\V1\TwoD\TwoDPrizeController::class, 'EveningPrizeWinnerForApk'])->name('TwoEveningPrizeWinner');
    // two d morning one day history 
      Route::get('/morning-2d-history', [App\Http\Controllers\Api\V1\TwoD\MorningRecordController::class, 'MorningUserLog']);
     // two d evening one day history 
      Route::get('/evening-2d-history', [App\Http\Controllers\Api\V1\TwoD\EveningRecordController::class, 'EveningUserLog']);

       Route::get('/3d-oneweek-history', [App\Http\Controllers\Api\V1\ThreeD\OneWeekHistoryController::class, 'getUserLotteryData']);
       // 3d one week prize sent history
        Route::get('/3d-oneweek-prizesent', [App\Http\Controllers\Api\V1\ThreeD\OneWeekPrizeSentController::class, 'getUserLotteryPrizeSentData']);

        Route::get('/3d-permutation-prizesent', [App\Http\Controllers\Api\V1\ThreeD\PermutationPrizeSentController::class, 'getPermutationPrizeSentData']);
        Route::get('/3d-win-prizesent', [App\Http\Controllers\Api\V1\ThreeD\WinPrizeSentController::class, 'getWinPrizeSentData']);
      // morning prize sent
      Route::get('/2d-moning-prize', [App\Http\Controllers\Api\V1\TwoD\MorningPrizeSentController::class, 'showPrizeSentData']);
});

    // first prize winner 
    // Route::get('/threed-first-winner-history', [App\Http\Controllers\Api\V1\ThreeD\WinnerHistoryController::class, 'firstPrizeWinner'])->name('ThreedfirstPrizeWinner');
    // Route::get('/threed-second-winner-history', [App\Http\Controllers\Api\V1\ThreeD\WinnerHistoryController::class, 'secondPrizeWinner'])->name('secondthreedPrizeWinner');
    //  Route::get('/threed-third-winner-history', [App\Http\Controllers\Api\V1\ThreeD\WinnerHistoryController::class, 'thirdPrizeWinner'])->name('thirdthreedPrizeWinner');