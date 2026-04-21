<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BarangayController;
use App\Http\Controllers\BarangayGeographyController;
use App\Http\Controllers\BarangayOrganizationalIndicatorController;
use App\Http\Controllers\BarangayPKProfileController;
use App\Http\Controllers\BarangayPopulationController;
use App\Http\Controllers\BarangayPriorityProgramController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DeleteController;
use App\Http\Controllers\DisaggregationController;
use App\Http\Controllers\GenerateReportController;
use App\Http\Controllers\OrganizationalIndicatorController;
use App\Http\Controllers\ProgramBaselineController;
use App\Http\Controllers\ProgramController;
use App\Http\Controllers\ProgramIndicatorController;
use App\Http\Controllers\ProgramIndicatorDisaggregationController;
use App\Http\Controllers\ProvinceController;
use App\Http\Controllers\PurokalusuganActivityController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\TeamBarangayController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\TeamMemberController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserHandledMunicipalityController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::inertia('/', 'Welcome')->name('home');
    Route::get('login', [AuthController::class, 'showLoginPage'])->name('login');
    Route::post('login', [AuthController::class, 'login'])->name('login.store');
});

Route::middleware('auth')->group(function () {
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');

    //dashboard routes
    Route::prefix('dashboard')->name('dashboard.')->group(function () {
        Route::get('access-level-one', [DashboardController::class, 'accessLevelOneDashboard']);
        Route::get('access-level-two', [DashboardController::class, 'accessLevelTwoDashboard']);
        Route::get('access-level-three', [DashboardController::class, 'accessLevelThreeDashboard']);
        Route::get('access-level-four', [DashboardController::class, 'accessLevelFourDashboard']);
        Route::get('dmo/municipalities',[DashboardController::class,'getDMOMunicipalityDashboard']);
        Route::get('admin/barangay-monitoring',[DashboardController::class,'adminBarangayMonitoringDashboard']);
        Route::get('barangay-monitoring/organizational-indicators',[DashboardController::class,'barangayOrganizationalIndicatorsMonitoring']);
    });

    //program routes
    Route::resource('program', ProgramController::class);

    //program baseline routes
    Route::resource('program-baseline', ProgramBaselineController::class);

    //barangay routes
    Route::resource('barangays', BarangayController::class);

    //barangay pk profile routes
    Route::resource('barangay/pk-profile', BarangayPKProfileController::class);
    
    //barangay organizational indicator routes
    Route::resource('barangay/organizational-indicator', BarangayOrganizationalIndicatorController::class);

    //barangay population routes
    Route::resource('barangay/population', BarangayPopulationController::class);

     //barangay priority programs routes
    Route::resource('barangay/priority-program', BarangayPriorityProgramController::class);

    //barangay geography routes
    Route::resource('barangay/geography', BarangayGeographyController::class);

    Route::resource('indicator/organizational', OrganizationalIndicatorController::class);
    Route::resource('indicator/program', ProgramIndicatorController::class);

    //disaggregation routes
    Route::resource('disaggregations', DisaggregationController::class);

    //program indicator disaggregation routed
    Route::resource('program-indicator-disaggregation', ProgramIndicatorDisaggregationController::class);
    

    //team routes
    Route::resource('teams', TeamController::class);
    Route::get('/teams/{team}/members', [TeamMemberController::class, 'index']); //manage members page
    Route::post('/teams/{team}/members', [TeamMemberController::class, 'store']);//add member
    Route::put('/teams/{team}/members/{member}', [TeamMemberController::class, 'update']);//update member
    Route::delete('/teams/{team}/members/{team_member}', [TeamMemberController::class, 'destroy']);//remove member

    Route::get('teams/{team}/barangays', [TeamBarangayController::class, 'index']);//manage barangays page
    Route::post('teams/{team}/barangays', [TeamBarangayController::class, 'store']);//add barangay
    Route::delete('/teams/{team}/barangays/{barangay}', [TeamBarangayController::class, 'destroy']);//remove barangay

    //report routes
    Route::resource('reports', ReportController::class);

    //purokalusugan activity routes
    Route::resource('pk-activities', PurokalusuganActivityController::class);


    //generation of report routes
    Route::get('generate/quarterly-large-scale-report', [GenerateReportController::class, 'generateQuarterlyLargeScaleReport']);
    Route::get('generate/approved-report-submissions', [GenerateReportController::class, 'generateApprovedReportSubmissions']);
    Route::get('generate/report', [GenerateReportController::class, 'generateReport']);
    Route::get('generate/pk_activities', [GenerateReportController::class, 'generatePKActivitiesReport']);

    //user routes
    Route::get('account-settings', [UserController::class, 'accountSettings']);
    Route::put('password-update', [UserController::class, 'updatePassword']);
    Route::get('users', [UserController::class, 'getUsers']);
    Route::put('users/{user}', [UserController::class, 'resetPassword']);
    Route::post('users/create', [UserController::class, 'createUser']);
    Route::get('user/profile', [UserController::class, 'userProfile']);
    Route::put('user/profile', [UserController::class, 'updateUserProfile']);
    //dmo user routes
    Route::get('users/handled-municipalities', [UserHandledMunicipalityController::class, 'create']);
    Route::post('users/handled-municipalities', [UserHandledMunicipalityController::class, 'store']);

    //delete routes
    Route::delete('team/{id}', [DeleteController::class, 'deleteTeam']);
    Route::delete('report/{id}', [DeleteController::class, 'deleteReport']);
    Route::delete('barangay/{id}', [DeleteController::class, 'deleteBarangay']);

    //Province Routes
    Route::get('province/dashboard/{id}',[ProvinceController::class,'renderProvinceDashboardData']);

});