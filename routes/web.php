<?php

use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MasterController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StateController;
use App\Http\Controllers\DistrictController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

Route::get('/clear', function () {
    Artisan::call('cache:clear');
    Artisan::call('route:clear');
    //Artisan::call('migrate');
 
    return redirect()->back();
 });

Route::get('/phpinfo', function () {
    phpinfo();
});

Route::group(['middleware' => ['web']], function () {
    Route::controller(AuthController::class)->group(function () {
        Route::get('/', 'login')->name('login');
        Route::post('authenticate', 'authenticate')->name('authenticate');
        Route::get('forgot-password', 'forgotPassword')->name('forgot-password');
        Route::post('post-forgot-password', 'postForgotPassword')->name('post-forgot-password');
        Route::get('reset-password/{token}', 'resetPassword')->name('password.reset');
        Route::post('post-reset-password', 'postResetPassword')->name('post-reset-password');
    });
});
Route::group(['middleware' => ['auth']], function () {

    Route::controller(AuthController::class)->group(function () {
        Route::get('dashboard', 'dashboard')->name('dashboard');
        Route::get('logout', 'logout')->name('logout');

    });

    Route::controller(ProfileController::class)->group(function () {
        Route::get('profile', 'profile')->name('profile');
        Route::post('post-change-password', 'postChangePassword')->name('post-change-password');
        Route::post('update-profile', 'updateProfile')->name('update-profile');
    });

    Route::controller(ActivityLogController::class)->group(function () {
        Route::get('activity-log', 'activityLog')->name('activity-log')->middleware('checkPermission:Activity Log,view');
        Route::delete('delete-activity-log', 'deleteActivityLog')->name('delete-activity-log')->middleware('checkPermission:Activity Log,delete');
    });

    Route::controller(MasterController::class)->group(function () {
        //Menu
        Route::get('menus', 'menus')->name('menus')->middleware('checkPermission:Menus,view');
        Route::post('add-menu', 'storeMenu')->name('add-menu')->middleware('checkPermission:Menus,add');
        Route::get('edit-menu/{id}', 'editMenu')->name('edit-menu')->middleware('checkPermission:Menus,edit');
        Route::put('update-menu', 'updateMenu')->name('update-menu')->middleware('checkPermission:Menus,edit');
        Route::post('change-menu-status', 'changeMenuStatus')->name('change-menu-status')->middleware('checkPermission:Menus,edit');
        Route::delete('delete-menu', 'deleteMenu')->name('delete-menu')->middleware('checkPermission:Menus,delete');
        Route::post('update-menu-order', 'updateMenuOrder')->name('update-menu-order');
        Route::get('sub-menus/{id}', 'subMenus')->name('sub-menus')->middleware('checkPermission:Menus,view');

        //User Type
        Route::get('user-type', 'userType')->name('user-type')->middleware('checkPermission:User Type,view');
        Route::post('add-user-type', 'storeUserType')->name('add-user-type')->middleware('checkPermission:User Type,add');
        Route::get('edit-user-type/{id}', 'editUserType')->name('edit-user-type')->middleware('checkPermission:User Type,edit');
        Route::put('update-user-type', 'updateUserType')->name('update-user-type')->middleware('checkPermission:User Type,edit');
        Route::post('change-user-type-status', 'changeUserTypeStatus')->name('change-user-type-status')->middleware('checkPermission:User Type,edit');
        Route::delete('delete-user-type', 'deleteUserType')->name('delete-user-type')->middleware('checkPermission:User Type,delete');

        //User Access
        Route::get('user-access/{id}', 'userAccess')->name('user-access')->middleware('checkPermission:User Type,add');
        Route::post('add-user-access', 'storeUserAccess')->name('add-user-access')->middleware('checkPermission:User Type,add');
        Route::post('update-access-permission/{id}', 'updateAccessPermission')->name('update-access-permission')->middleware('checkPermission:User Type,add');
        Route::delete('delete-user-access', 'deleteUserAccess')->name('delete-user-access')->middleware('checkPermission:User Type,add');
    });

    // State Master

    Route::controller(StateController::class)->group(function () {
        Route::get('states', 'states')->name('states')->middleware('checkPermission:States,view');
        Route::post('add-state', 'storeState')->name('add-state')->middleware('checkPermission:States,add');
        Route::get('edit-state/{id}', 'editState')->name('edit-state')->middleware('checkPermission:States,edit');
        Route::put('update-state', 'updateState')->name('update-state')->middleware('checkPermission:States,edit');
        Route::post('change-state-status', 'changeStateStatus')->name('change-state-status')->middleware('checkPermission:States,edit');
        Route::delete('delete-state', 'deleteState')->name('delete-state')->middleware('checkPermission:States,delete');
    });

    // District Master 
    
    Route::controller(DistrictController::class)->group(function () {
        Route::get('districts', 'districts')->name('districts')->middleware('checkPermission:Districts,view');
        Route::post('add-district', 'storeDistrict')->name('add-district')->middleware('checkPermission:Districts,add');
        Route::get('edit-district/{id}', 'editDistrict')->name('edit-district')->middleware('checkPermission:Districts,edit');
        Route::put('update-district', 'updateDistrict')->name('update-district')->middleware('checkPermission:Districts,edit');
        Route::post('change-district-status', 'changeDistrictStatus')->name('change-district-status')->middleware('checkPermission:Districts,edit'); // ✅ THIS ONE
        Route::delete('delete-district', 'deleteDistrict')->name('delete-district')->middleware('checkPermission:Districts,delete');
    });
    
});
