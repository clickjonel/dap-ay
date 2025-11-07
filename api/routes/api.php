<?php

use App\Http\Controllers\ActivityController;
use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\BarangayController;
use App\Http\Controllers\IndicatorController;
use App\Http\Controllers\MunicipalityController;
use App\Http\Controllers\ProgramController;
use App\Http\Controllers\ProvinceController;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\UserController;
use App\Models\ActivityProgram;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
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
        Route::get('/selection/sub-programs',[ProgramController::class,'subProgramSelection']);
        Route::post('/create',[ProgramController::class,'create']);
        Route::patch('/update/{program_id}',[ProgramController::class,'update']);
        Route::post('/create/sub-program',[ProgramController::class,'createSubProgram']);

        Route::get('/find',[ProgramController::class,'findProgram']);
        Route::post('/sub-program/create',[ProgramController::class,'createSubProgram']);
        Route::post('/sub-program/update',[ProgramController::class,'updateSubProgram']);
        Route::post('/indicator/update',[ProgramController::class,'updateIndicator']);
        Route::post('/indicator/create',[ProgramController::class,'createIndicator']);
    });


    // Sites
    Route::group([
        'prefix' => '/site'
    ],function(){
        Route::get('/list',[SiteController::class,'list']);
        // Route::post('/create',[ProgramController::class,'create']);
        // Route::patch('/update/{program_id}',[ProgramController::class,'update']);
        // Route::post('/create/sub-program',[ProgramController::class,'createSubProgram']);
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
    });

    // Site
    Route::group([
        'prefix' => '/site'
    ],function(){
        Route::post('/update/priority_programs',[SiteController::class,'updateSitePrograms']);
        Route::post('/create',[SiteController::class,'createSiteProfile']);
        Route::post('/update/{site_id}',[SiteController::class,'updateSite']);
        Route::post('/update/profile/{site_profile_id}',[SiteController::class,'updateSiteProfile']);
        
    });

     // Team
    Route::group([
        'prefix' => '/team'
    ],function(){
        Route::get('/list',[TeamController::class,'list']);
        // Route::post('/update',[TeamController::class,'updateTeam']);
        Route::post('/member/add',[TeamController::class,'addTeamMember']);
        Route::delete('/member/remove',[TeamController::class,'removeTeamMember']);

        Route::post('/create',[TeamController::class,'createTeam']);
        Route::get('/find',[TeamController::class,'findTeam']);

        Route::post('/member/update',[TeamController::class,'updateMemberDetails']);
        Route::delete('/member/delete',[TeamController::class,'deleteMember']);
        Route::post('/update',[TeamController::class,'updateTeamDetails']);
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

    Route::group([
        'prefix' => '/indicator'
    ],function(){
        Route::post('/create',[IndicatorController::class,'create']);
        Route::get('/list',[IndicatorController::class,'list']);
    });

});
