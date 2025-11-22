<?php

use App\Http\Controllers\ActivityController;
use App\Http\Controllers\AnnouncementViewerController;
use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\BarangayController;
use App\Http\Controllers\BarangayPriorityProgramController;
use App\Http\Controllers\IndicatorController;
use App\Http\Controllers\MunicipalityController;
use App\Http\Controllers\ProgramController;
use App\Http\Controllers\ProvinceController;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\SubProgramController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ServerLogsController;
use App\Http\Controllers\TeamBarangayController;
use App\Http\Controllers\TeamMemberController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReportController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::get('/user', function (Request $request) {
    $user = $request->user()->load(['teams.barangays']);
    // $teams = $user->load(['teamMemberships']);
    return $user;
})->middleware('auth:sanctum');

Route::post('/login',[AuthenticationController::class,'login']);

// Authenticated Routes
Route::group([
    'middleware' => 'auth:sanctum'
], function(){

    // Authentication
    Route::post('/logout',[AuthenticationController::class,'logout']);


    // Program
    Route::group([
        'prefix' => '/program'
    ],function(){
        Route::get('/list',[ProgramController::class,'list']);
        Route::get('/selection',[ProgramController::class,'selection']);
        // Route::get('/selection/sub-programs',[ProgramController::class,'subProgramSelection']);
        Route::post('/create',[ProgramController::class,'create']);
        Route::post('/update',[ProgramController::class,'update']);
        Route::post('/create/sub-program',[ProgramController::class,'createSubProgram']);

        Route::get('/find',[ProgramController::class,'findProgram']);
        // Route::post('/sub-program/create',[ProgramController::class,'createSubProgram']);
        // Route::post('/sub-program/update',[ProgramController::class,'updateSubProgram']);
        // Route::post('/indicator/update',[ProgramController::class,'updateIndicator']);
        // Route::post('/indicator/create',[ProgramController::class,'createIndicator']);
    });

    // Sub Program
    Route::group([
        'prefix' => '/sub-program'
    ],function(){
       Route::post('/update',[SubProgramController::class,'update']);
       Route::post('/create',[SubProgramController::class,'create']);
       Route::get('/selection',[SubProgramController::class,'selection']);
    });

     // Provinces
    Route::group([
        'prefix' => '/province'
    ],function(){
        Route::get('/selection',[ProvinceController::class,'selection']);
    });

    // Municipality
    Route::group([
        'prefix' => '/municipality'
    ],function(){
        Route::get('/selection',[MunicipalityController::class,'selection']);
    });

    // Barangay
    Route::group([
        'prefix' => '/barangay'
    ],function(){
        Route::get('/selection',[BarangayController::class,'selection']);
        Route::get('/list',[BarangayController::class,'list']);
        Route::get('/find',[BarangayController::class,'find']);
        Route::post('/update',[BarangayController::class,'update']);
    });

    // Barangay Priority Programs
    Route::group([
        'prefix' => '/barangay-priority-program'
    ],function(){
        Route::post('/update',[BarangayPriorityProgramController::class,'update']);
        Route::post('/create',[BarangayPriorityProgramController::class,'create']);
    });

     // Team
    Route::group([
        'prefix' => '/team'
    ],function(){
        Route::get('/list',[TeamController::class,'list']);
        Route::get('/find',[TeamController::class,'find']);
        Route::post('/update',[TeamController::class,'update']);
        Route::post('/create',[TeamController::class,'create']);
    });

    // Team Members
    Route::group([
        'prefix' => '/team-member'
    ],function(){
        Route::post('/create',[TeamMemberController::class,'create']);
    });

    // Team Barangay
    Route::group([
        'prefix' => '/team-barangay'
    ],function(){
        Route::post('/create',[TeamBarangayController::class,'create']);
    });

    // User
    Route::group([
        'prefix' => '/user'
    ],function(){
        Route::get('/selection',[UserController::class,'selection']);
        Route::get('/list',[UserController::class,'list']);
        // Route::post('/update',[TeamController::class,'updateTeam']);
    });

     // Activity
    Route::group([
        'prefix' => '/activity'
    ],function(){
        Route::get('/list',[ActivityController::class,'list']);
        Route::post('/create',[ActivityController::class,'createActivity']);
        Route::post('/update',[ActivityController::class,'updateActivity']);
        Route::get('/find',[ActivityController::class,'findActivity']);

        Route::post('/program/add',[ActivityController::class,'addProgram']);
        Route::post('/program/delete',[ActivityController::class,'deleteActivityProgram']);

        Route::post('/barangay/add',[ActivityController::class,'addBarangay']);
        Route::post('/barangay/delete',[ActivityController::class,'deleteActivityBarangay']);

        Route::post('/resource/add',[ActivityController::class,'addResource']);
        Route::post('/resource/delete',[ActivityController::class,'deleteActivityResource']);

        Route::post('/populate-indicators',[ActivityController::class,'populateIndicators']);

        Route::get('/report',[ActivityController::class,'getActivityReportData']);
    });

    // indicator
    Route::group([
        'prefix' => '/indicator'
    ],function(){
        Route::post('/create',[IndicatorController::class,'create']);
        Route::get('/list',[IndicatorController::class,'list']);
        Route::post('/update',[IndicatorController::class,'update']);
        Route::get('/active',[IndicatorController::class,'getActiveIndicators']);
    });

    Route::group([
        'prefix' => '/server-log'
    ],function(){
        Route::post('/create',[ServerLogsController::class,'createServerLog']);
        Route::get('/list',[ServerLogsController::class,'readAllServerLogs']);
    });

    Route::group([
        'prefix' => '/announcement'
    ],function(){
        Route::post('/create',[AnnouncementController::class,'createAnnouncement']);
        Route::get('/list',[AnnouncementController::class,'readAllAnnouncements']);
        Route::get('/posters/list',[AnnouncementController::class,'readAllAnnouncementForPosting']);
        Route::get('/find',[AnnouncementController::class,'readAnnouncement']);
        Route::put('/update',[AnnouncementController::class,'updateAnnouncement']);
        Route::delete('/delete',[AnnouncementController::class,'deleteAnnouncement']);
    });

    Route::group([
        'prefix' => '/announcement-viewer'
    ],function(){
        Route::post('/create',[AnnouncementViewerController::class,'createAnnouncementViewer']);
        Route::delete('/delete',[AnnouncementViewerController::class,'deleteAnnouncementViewer']);
    });


    // report
    Route::group([
        'prefix' => '/report'
    ],function(){
        Route::get('/list',[ReportController::class,'list']);
        Route::post('/create',[ReportController::class,'create']);
        Route::get('/monthly-municipal-level',[ReportController::class,'getMonthlyMunicipalLevelReport']);
        Route::get('/monthly-provincial-level',[ReportController::class,'getMonthlyProvincialLevelReport']);
        Route::get('/monthly-user-reports',[ReportController::class,'getUserMonthlyReport']);
    });

    // Dashboard
    Route::group([
        'prefix' => '/dashboard'
    ],function(){
        Route::get('/admin',[DashboardController::class,'getAdminDashboardData']);
       
    });

    //User
    Route::group([
        'prefix' => '/user'
    ],function(){
        Route::get('/report-latest',[UserController::class,'userLatestSubmittedReport']);
       
    });

});
