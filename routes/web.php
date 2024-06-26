<?php

use App\Http\Controllers\Admin\BankController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\BannerTextController;
use App\Http\Controllers\Admin\CommissionController;
use App\Http\Controllers\Admin\CurrencyController;
use App\Http\Controllers\Admin\FillBalanceReplyController;
use App\Http\Controllers\Admin\GameController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\PlayTwoDController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\PromotionController;
use App\Http\Controllers\Admin\RolesController;
use App\Http\Controllers\Admin\ThreeD\ThreeDCloseController;
use App\Http\Controllers\Admin\ThreeD\ThreeDLegarController;
use App\Http\Controllers\Admin\ThreeD\ThreeDPrizeNumberCreateController;
use App\Http\Controllers\Admin\ThreedHistoryController;
use App\Http\Controllers\Admin\ThreeDLimitController;
use App\Http\Controllers\Admin\ThreedMatchTimeController;
use App\Http\Controllers\Admin\TransferLogController;
use App\Http\Controllers\Admin\TwoD\CloseTwoDigitController;
use App\Http\Controllers\Admin\TwoD\DataLejarController;
use App\Http\Controllers\Admin\TwoD\HeadDigitCloseController;
use App\Http\Controllers\Admin\TwoD\TwoDLagarController;
use App\Http\Controllers\Admin\TwoD\TwoDWinnersPrizeController;
use App\Http\Controllers\Admin\TwoDigitController;
use App\Http\Controllers\Admin\TwoDLimitController;
use App\Http\Controllers\Admin\TwoDLotteryController;
use App\Http\Controllers\Admin\TwoDWinnerController;
use App\Http\Controllers\Admin\UsersController;
use App\Http\Controllers\Home\CashInRequestController;
use App\Http\Controllers\Home\CashOutRequestController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Auth::routes();
Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
// sub route
require __DIR__.'/auth.php';
require __DIR__.'/two_d_play.php';
require __DIR__.'/frontend.php';

Route::group(['prefix' => 'admin', 'as' => 'admin.', 'namespace' => 'App\Http\Controllers\Admin', 'middleware' => ['auth']], function () {
    // Permissions
    Route::delete('permissions/destroy', [PermissionController::class, 'massDestroy'])->name('permissions.massDestroy');
    Route::resource('permissions', PermissionController::class);
    // Roles
    Route::delete('roles/destroy', [RolesController::class, 'massDestroy'])->name('roles.massDestroy');
    Route::resource('roles', RolesController::class);
    // Users
    Route::delete('users/destroy', [UsersController::class, 'massDestroy'])->name('users.massDestroy');
    Route::resource('users', UsersController::class);
    Route::get('/two-d-users', [App\Http\Controllers\Admin\TwoUsersController::class, 'index'])->name('two-d-users-index');
    // details route
    Route::get('/two-d-users/{id}', [App\Http\Controllers\Admin\TwoUsersController::class, 'show'])->name('two-d-users-details');
    Route::get('/first-prize', [ThreeDPrizeNumberCreateController::class, 'getFirstPrizeWinnersWithUserInfo'])->name('GetfirstPrizeWinner');
    Route::post('/first-prize-store', [ThreeDPrizeNumberCreateController::class, 'storeFirstPrizeWinners'])->name('PostFirstPrizeWinners');
    Route::post('/update-first-prize', [ThreeDPrizeNumberCreateController::class, 'updateFirstPrizeWinners'])->name('updateFirstwinners');

    //second prize winner
    Route::get('/second-prize', [ThreeDPrizeNumberCreateController::class, 'getSecondPrizeWinnersWithUserInfo'])->name('GetSecondPrizeWinner');
    Route::post('/second-prize-store', [ThreeDPrizeNumberCreateController::class, 'storeSecondPrizeWinners'])->name('PostSecondPrizeWinners');
    Route::post('/update-second-prize', [ThreeDPrizeNumberCreateController::class, 'updateSecondPrizeWinners'])->name('updateSecondwinners');
    // third prize winner
    Route::get('/third-prize', [ThreeDPrizeNumberCreateController::class, 'getThirdPrizeWinnersWithUserInfo'])->name('GetThirdPrizeWinner');
    Route::post('/third-prize-store', [ThreeDPrizeNumberCreateController::class, 'storeThirdPrizeWinners'])->name('PostThirdPrizeWinners');
    Route::post('/update-third-prize', [ThreeDPrizeNumberCreateController::class, 'updateThirdPrizeWinners'])->name('updateThirdwinners');

    // two d morning
    Route::get('/morning-prize', [TwoDWinnersPrizeController::class, 'getMorningPrizeWinnersWithUserInfo'])->name('TwoDMorningPrize');
    Route::post('/morning-prize-store', [TwoDWinnersPrizeController::class, 'storeMorningPrizeWinners'])->name('PostMorningPrizeWinners');
    Route::post('/update-morning-prize', [TwoDWinnersPrizeController::class, 'updateMorningPrizeWinners'])->name('updateMorningwinners');

    // two d evening
    Route::get('/evening-prize', [TwoDWinnersPrizeController::class, 'getEveningPrizeWinnersWithUserInfo'])->name('TwoDEveningPrize');
    Route::post('/evening-prize-store', [TwoDWinnersPrizeController::class, 'storeEveningPrizeWinners'])->name('PostEveningPrizeWinners');
    Route::post('/update-evening-prize', [TwoDWinnersPrizeController::class, 'updateEveningPrizeWinners'])->name('updateEveningwinners');
    //Banners
    Route::resource('banners', BannerController::class);
    Route::resource('text', BannerTextController::class);
    Route::resource('games', GameController::class);
    Route::resource('/promotions', PromotionController::class);
    Route::resource('/banks', BankController::class);
    //commissions route
    Route::resource('/commissions', CommissionController::class);
    // Two Digit Limit
    Route::resource('/two-digit-limit', TwoDLimitController::class);
    // three Ditgit Limit
    Route::resource('/three-digit-limit', ThreeDLimitController::class);
    // two digit close
    Route::resource('two-digit-close', CloseTwoDigitController::class);
    // morning - lajar
    Route::get('/morning-lajar', [TwoDLagarController::class, 'showData'])->name('morning-lajar');
    // two digit data
    Route::get('/two-digit-lejar-data', [DataLejarController::class, 'showData'])->name('two-digit-lejar-data');

    // morning - lajar
    Route::get('/evening-lajar', [TwoDLagarController::class, 'showDataEvening'])->name('evening-lajar');
    // two digit data
    Route::get('/evening-two-digit-lejar-data', [DataLejarController::class, 'showDataEvening'])->name('evening-two-digit-lejar-data');
    // three digit close
    Route::resource('three-digit-close', ThreeDCloseController::class);
    // three digit legar
    Route::get('/three-digit-lejar', [ThreeDLegarController::class, 'showData'])->name('three-digit-lejar');
    // display limit
    Route::get('/three-d-display-limit-amount', [App\Http\Controllers\Admin\ThreeDLimitController::class, 'overLimit'])->name('three-d-display-limit-amount');
    Route::get('/three-d-same-id-display-limit-amount', [App\Http\Controllers\Admin\ThreeDLimitController::class, 'SameThreeDigitIDoverLimit'])->name('three-d-display-same-id-limit-amount');
    // head digit close
    Route::resource('head-digit-close', HeadDigitCloseController::class);
    //cash in lists
    Route::get('/cashIn', [CashInRequestController::class, 'index'])->name('cashIn');
    Route::get('/cashIn/{id}', [CashInRequestController::class, 'show'])->name('cashIn.show');
    Route::post('/cashIn/accept/{id}', [CashInRequestController::class, 'accept'])->name('acceptCashIn');
    Route::post('/cashIn/reject/{id}', [CashInRequestController::class, 'reject'])->name('rejectCashIn');
    Route::post('/transfer/{id}', [CashInRequestController::class, 'transfer']);
    //cash out lists
    Route::get('/cashOut', [CashOutRequestController::class, 'index'])->name('cashOut');
    Route::get('/cashOut/{id}', [CashOutRequestController::class, 'show'])->name('cashOut.show');
    Route::post('/cashOut/accept/{id}', [CashOutRequestController::class, 'accept'])->name('acceptCashOut');
    Route::post('/cashOut/reject/{id}', [CashOutRequestController::class, 'reject'])->name('rejectCashOut');
    // Route::post('/withdraw/{id}', [CashOutRequestController::class, "withdraw"]);
    //transfer logs lists
    Route::get('/transferlogs', [TransferLogController::class, 'index'])->name('transferLog');
    //Currency
    Route::resource('currency', CurrencyController::class);
    // profile resource rotues
    Route::resource('profiles', ProfileController::class);
    // user profile route get method
    Route::put('/change-password', [ProfileController::class, 'newPassword'])->name('changePassword');
    // PhoneAddressChange route with auth id route with put method
    Route::put('/change-phone-address', [ProfileController::class, 'PhoneAddressChange'])->name('changePhoneAddress');
    Route::put('/change-kpay-no', [ProfileController::class, 'KpayNoChange'])->name('changeKpayNo');
    Route::put('/change-join-date', [ProfileController::class, 'JoinDate'])->name('addJoinDate');
    Route::get('/get-two-d-session-reset', [App\Http\Controllers\Admin\SessionResetControlller::class, 'index'])->name('SessionResetIndex');
    Route::post('/two-d-session-reset', [App\Http\Controllers\Admin\SessionResetControlller::class, 'SessionReset'])->name('SessionReset');
    Route::get('/close-two-d', [App\Http\Controllers\Admin\CloseTwodController::class, 'index'])->name('CloseTwoD');

    Route::post('/update-open-close-two-d/{id}', [App\Http\Controllers\Admin\CloseTwodController::class, 'closeTwoD'])->name('OpenCloseTwoD');
    Route::post('/update-open-close-three-d/{id}', [App\Http\Controllers\Admin\ThreeD\ThreeDOpenCloseController::class, 'closeThreeD'])->name('OpenCloseThreeD');

    Route::resource('twod-records', TwoDLotteryController::class);
    Route::resource('tow-d-win-number', TwoDWinnerController::class);
    // two d get  morning number
    Route::get('/tow-d-morning-number', [App\Http\Controllers\Admin\TwoD\MorningLotteryAdminLogController::class, 'MorningAdminLogOpenData']);

    Route::get('/two-d-morning-winner', [App\Http\Controllers\Admin\TwoDMorningWinnerController::class, 'MorningWinHistoryForAdmin'])->name('morningWinner');

    Route::get('/two-d-all-winner', [App\Http\Controllers\Admin\TwoD\AllLotteryWinPrizeSentController::class, 'TwoAllWinHistoryForAdmin']);

    Route::get('/two-d-evening-admin-log', [App\Http\Controllers\Admin\TwoD\EveningLotteryAdminLogController::class, 'showAdminLogOpenData'])->name('towDadminLog');

    Route::get('profile/fill_money', [ProfileController::class, 'fillmoney']);
    // kpay fill money get route
    Route::get('profile/kpay_fill_money', [ProfileController::class, 'index'])->name('kpay_fill_money');
    Route::resource('fill-balance-replies', FillBalanceReplyController::class);
    Route::get('/daily-income-json', [App\Http\Controllers\Admin\DailyTwodIncomeOutComeController::class, 'getTotalAmounts'])->name('dailyIncomeJson');
    Route::get('/with-draw-view', [App\Http\Controllers\Admin\WithDrawViewController::class, 'index'])->name('withdrawViewGet');
    Route::get('/with-draw-details/{id}', [App\Http\Controllers\Admin\WithDrawViewController::class, 'show'])->name('withdrawViewDetails');
    // withdraw update route
    Route::put('/with-draw-update/{id}', [App\Http\Controllers\Admin\WithDrawViewController::class, 'update'])->name('withdrawViewUpdate');
    Route::get('/daily-with-name-income-json', [App\Http\Controllers\Admin\DailyTwodIncomeOutComeController::class, 'getTotalAmountsDaily'])->name('getTotalAmountsDaily');
    // week name route
    Route::get('/weekly-income-json', [App\Http\Controllers\Admin\DailyTwodIncomeOutComeController::class, 'getTotalAmountsWeekly'])->name('getTotalAmountsWeekly');
    // month name route
    Route::get('/month-with-name-income-json', [App\Http\Controllers\Admin\DailyTwodIncomeOutComeController::class, 'getTotalAmountsMonthly'])->name('getTotalAmountsMonthly');
    // year name route
    Route::get('/yearly-income-json', [App\Http\Controllers\Admin\DailyTwodIncomeOutComeController::class, 'getTotalAmountsYearly'])->name('getTotalAmountsYearly');
    Route::get('/two-d-users', [App\Http\Controllers\Admin\TwoUsersController::class, 'index'])->name('two-d-users-index');
    // details route
    Route::get('/two-d-users/{id}', [App\Http\Controllers\Admin\TwoUsersController::class, 'show'])->name('two-d-users-details');
    //Banners
    Route::resource('banners', BannerController::class);
    // profile resource rotues
    Route::resource('profiles', ProfileController::class);
    Route::put('/super-admin-update-balance/{id}', [App\Http\Controllers\Admin\ProfileController::class, 'AdminUpdateBalance'])->name('admin-update-balance');
    // user profile route get method
    Route::put('/change-password', [ProfileController::class, 'newPassword'])->name('changePassword');
    // PhoneAddressChange route with auth id route with put method
    Route::put('/change-phone-address', [ProfileController::class, 'PhoneAddressChange'])->name('changePhoneAddress');
    Route::put('/change-kpay-no', [ProfileController::class, 'KpayNoChange'])->name('changeKpayNo');
    Route::put('/change-join-date', [ProfileController::class, 'JoinDate'])->name('addJoinDate');
    Route::resource('play-twod', PlayTwoDController::class);
    Route::get('/get-two-d', [App\Http\Controllers\Admin\TwoDPlayController::class, 'GetTwoDigit'])->name('GetTwoDigit');
    Route::post('lotteries-two-d-play', [TwoDigitController::class, 'store'])->name('StorePlayTwoD');
    Route::get('/morning-play-two-d', [App\Http\Controllers\Admin\TwoDPlayController::class, 'MorningPlayTwoDigit'])->name('MorningPlayTwoDigit');

    Route::get('/evening-play-two-d', [App\Http\Controllers\Admin\TwoDPlayController::class, 'EveningPlayTwoDigit'])->name('EveningPlayTwoDigit');

    Route::post('lotteries-two-d-play', [TwoDigitController::class, 'store'])->name('StorePlayTwoD');

    Route::post('/two-d-play', [App\Http\Controllers\Admin\TwoDPlayController::class, 'store'])->name('two-d-play.store');

    Route::get('/get-two-d-session-reset', [App\Http\Controllers\Admin\SessionResetControlller::class, 'index'])->name('SessionResetIndex');
    Route::post('/two-d-session-reset', [App\Http\Controllers\Admin\SessionResetControlller::class, 'SessionReset'])->name('SessionReset');
    Route::get('/close-two-d', [App\Http\Controllers\Admin\CloseTwodController::class, 'index'])->name('CloseTwoD');
    Route::get('profile/fill_money', [ProfileController::class, 'fillmoney']);
    // kpay fill money get route
    Route::get('profile/kpay_fill_money', [ProfileController::class, 'index'])->name('kpay_fill_money');
    Route::resource('fill-balance-replies', FillBalanceReplyController::class);
    Route::get('/daily-income-json', [App\Http\Controllers\Admin\DailyTwodIncomeOutComeController::class, 'getTotalAmounts'])->name('dailyIncomeJson');
    Route::get('/with-draw-view', [App\Http\Controllers\Admin\WithDrawViewController::class, 'index'])->name('withdrawViewGet');
    Route::get('/with-draw-details/{id}', [App\Http\Controllers\Admin\WithDrawViewController::class, 'show'])->name('withdrawViewDetails');
    // withdraw update route
    Route::put('/with-draw-update/{id}', [App\Http\Controllers\Admin\WithDrawViewController::class, 'update'])->name('withdrawViewUpdate');
    Route::get('/daily-with-name-income-json', [App\Http\Controllers\Admin\DailyTwodIncomeOutComeController::class, 'getTotalAmountsDaily'])->name('getTotalAmountsDaily');
    // week name route
    Route::get('/weekly-income-json', [App\Http\Controllers\Admin\DailyTwodIncomeOutComeController::class, 'getTotalAmountsWeekly'])->name('getTotalAmountsWeekly');
    // month name route
    Route::get('/month-with-name-income-json', [App\Http\Controllers\Admin\DailyTwodIncomeOutComeController::class, 'getTotalAmountsMonthly'])->name('getTotalAmountsMonthly');
    // year name route
    Route::get('/yearly-income-json', [App\Http\Controllers\Admin\DailyTwodIncomeOutComeController::class, 'getTotalAmountsYearly'])->name('getTotalAmountsYearly');

    // 3d lottery routes
    Route::get('/threed-lotteries-history', [ThreedHistoryController::class, 'index']);
    Route::get('/threed-lotteries-match-time', [ThreedMatchTimeController::class, 'index']);
    // 3d prize number create
    Route::get('/three-d-prize-number-create', [App\Http\Controllers\Admin\ThreeD\ThreeDPrizeNumberCreateController::class, 'index'])->name('three-d-prize-number-create');
    // store_permutations
    Route::post('/store-permutations', [App\Http\Controllers\Admin\ThreeD\ThreeDPrizeNumberCreateController::class, 'PermutationStore'])->name('storePermutations');
    //deletePermutation
    Route::delete('/delete-permutation/{id}', [App\Http\Controllers\Admin\ThreeD\ThreeDPrizeNumberCreateController::class, 'deletePermutation'])->name('deletePermutation');
    Route::post('/three-d-prize-number-create', [App\Http\Controllers\Admin\ThreeD\ThreeDPrizeNumberCreateController::class, 'store'])->name('three-d-prize-number-create.store');
    // 3d history
    Route::get('/three-d-history', [App\Http\Controllers\Admin\ThreeD\ThreeDOneWeekHistoryController::class, 'GetAllThreeDUserData'])->name('three-d-history');
    // 3d history show
    // Route::get('/three-d-history-show/{id}', [App\Http\Controllers\Admin\ThreeD\ThreeDRecordHistoryController::class, 'show'])->name('three-d-history-show');
    // three d list index
    Route::get('/three-d-list-index', [App\Http\Controllers\Admin\ThreeD\ThreeDListController::class, 'GetAllThreeDData'])->name('threedlist-index');
    // three d list show
    Route::get('/three-d-list-show/{id}', [App\Http\Controllers\Admin\ThreeD\ThreeDListController::class, 'show'])->name('three-d-list-show');
    // 3d winner list
    Route::get('/three-d-winner', [App\Http\Controllers\Admin\ThreeD\ThreeDWinnerController::class, 'index'])->name('three-d-winner');

    Route::post('/two-d-session-over-amount-limit-reset', [App\Http\Controllers\Admin\SessionResetControlller::class, 'OverAmountLimitSessionReset'])->name('OverAmountLimitSessionReset');
    // three d reset
    Route::post('/three-d-reset', [App\Http\Controllers\Admin\ThreeD\ThreeDResetController::class, 'ThreeDReset'])->name('ThreeDReset');

    Route::post('/permutation-reset', [App\Http\Controllers\Admin\ThreeD\PermutationResetController::class, 'PermutationReset'])->name('PermutationReset');

    // three digit history conclude
    Route::get('/three-digit-history-conclude', [App\Http\Controllers\Admin\ThreeD\ThreeDRecordHistoryController::class, 'OnceWeekThreedigitHistoryConclude'])->name('ThreeDigitHistoryConclude');
    // three digit one month history conclude
    Route::get('/three-digit-one-month-history-conclude', [App\Http\Controllers\Admin\ThreeD\ThreeDRecordHistoryController::class, 'OnceMonthThreedigitHistoryConclude'])->name('ThreeDigitOneMonthHistoryConclude');
    // three d winners history
    Route::get('/three-d-winners-history', [App\Http\Controllers\Admin\ThreeD\ThreeDWinnerController::class, 'FirstPrizeWinner'])->name('ThreeDWinnersHistory');
    // three d permutation winners history
    Route::get('/permutation-winners-history', [App\Http\Controllers\Admin\ThreeD\PermutationWinnerController::class, 'PermutationWinners'])->name('PermutationWinnersHistory');
    // greater than less than winner prize
    Route::resource('winner-prize', App\Http\Controllers\Admin\ThreeD\GreatherThanLessThanWinnerPrizeController::class);
    // three d permutation winner prize
    Route::get('/prize-winners', [App\Http\Controllers\Admin\ThreeD\GreatherThanLessThanWinnerPrizeController::class, 'ThirdPrizeWinner'])->name('getPrizeWinnersHistory');
    // two d winner history
    Route::get('/admin-two-d-winners-history', [App\Http\Controllers\Admin\TwoDWinnerHistoryController::class, 'getWinnersHistoryForAdmin'])->name('winnerHistoryForAdmin');
    Route::get('/admin-two-d-winners-history-group-by-session', [App\Http\Controllers\Admin\TwoDWinnerHistoryController::class, 'getWinnersHistoryForAdminGroupBySession'])->name('winnerHistoryForAdminSession');

    // two d commission route
    Route::get('/two-d-commission', [App\Http\Controllers\Admin\Commission\TwoDCommissionController::class, 'getTwoDTotalAmountPerUser'])->name('two-d-commission');

    // show details
    Route::get('/two-d-commission-show/{id}', [App\Http\Controllers\Admin\Commission\TwoDCommissionController::class, 'show'])->name('two-d-commission-show');
    Route::put('/two-d-commission-update/{id}', [App\Http\Controllers\Admin\Commission\TwoDCommissionController::class, 'update'])->name('two-d-commission-update');
    // commission update
    Route::post('two-d-transfer-commission/{id}', [App\Http\Controllers\Admin\Commission\TwoDCommissionController::class, 'TwoDtransferCommission'])->name('two-d-transfer-commission');

    // three d commission route
    Route::get('/three-d-commission', [App\Http\Controllers\Admin\Commission\ThreeDCommissionController::class, 'getThreeDTotalAmountPerUser'])->name('three-d-commission');
    // show details
    Route::get('/three-d-commission-show/{id}', [App\Http\Controllers\Admin\Commission\ThreeDCommissionController::class, 'show'])->name('three-d-commission-show');
    // three_d_commission_update
    Route::put('/three-d-commission-update/{id}', [App\Http\Controllers\Admin\Commission\ThreeDCommissionController::class, 'update'])->name('three-d-commission-update');
    // transfer commission route
    Route::post('/three-d-transfer-commission/{id}', [App\Http\Controllers\Admin\Commission\ThreeDCommissionController::class, 'ThreeDtransferCommission'])->name('three-d-transfer-commission');
    // TwodDailyMorningHistory
    Route::get('/twod-daily-morning-history', [App\Http\Controllers\Admin\DailyMorningHistoryController::class, 'TwodDailyMorningHistory'])->name('TwodDailyMorningHistory');
    // TwodDailyEveningHistory
    Route::get('/twod-daily-evening-history', [App\Http\Controllers\Admin\DailyMorningHistoryController::class, 'TwodDailyEveningHistory'])->name('TwodDailyEveningHistory');

    // two d result date and result number
    Route::get('two-d-result-date', [App\Http\Controllers\Admin\TwoD\TwoGameResultController::class, 'index']);
    Route::patch('/two-2-results/{id}/status', [App\Http\Controllers\Admin\TwoD\TwoGameResultController::class, 'updateStatus'])
        ->name('twoDResults.ResultupdateStatus');
    Route::patch('/two-d-results/{id}/status', [App\Http\Controllers\Admin\TwoD\TwoGameResultController::class, 'updateResultNumber'])
        ->name('update_result_number');

    // get three d result date
    Route::get('three-d-result-date', [App\Http\Controllers\Admin\ThreeD\ResultDateController::class, 'index']);
    // result date update
    Route::patch('/lottery-results/{id}/status', [App\Http\Controllers\Admin\ThreeD\ResultDateController::class, 'updateStatus'])
        ->name('ThreedOpenClose');

    Route::patch('/three-d-admin-log/{id}/status', [App\Http\Controllers\Admin\ThreeD\ResultDateController::class, 'AdminLogThreeDOpenClose'])
        ->name('ThreeDAdminLogOpenClose');

    Route::patch('/three-d-user-log/{id}/status', [App\Http\Controllers\Admin\ThreeD\ResultDateController::class, 'UserLogThreeDOpenClose'])
        ->name('ThreeDUserLogOpenClose');
    Route::patch('/three-d-results/{id}/status', [App\Http\Controllers\Admin\ThreeD\ResultDateController::class, 'updateResultNumber'])
        ->name('UpdateResult_number');

});
