<?php

use App\Enums\RoleEnum;
use App\Http\Controllers\Auth\SocialiteAuthController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Email\BlackListEmailController;
use App\Http\Controllers\Email\BulkImportController;
use App\Http\Controllers\Mail\MailController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Settings\SettingsController;
use App\Http\Controllers\Settings\SmtpSettingsController;
use App\Http\Controllers\Setup\SetupController;
use App\Http\Controllers\User\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::controller(SocialiteAuthController::class)->group(function () {
    Route::get('/auth/socialite/{type?}', 'redirectToOAuth')->name('auth.login.socialite');
    Route::get('/auth/google/callback', 'handleGoogleCallback')->name('auth.google.callback');
    Route::get('/auth/outlook/callback', 'handleMicrosoftCallback')->name('auth.microsoft.callback');
});

Route::middleware(['auth', 'verified', 'role:'.RoleEnum::ADMIN->value])->group(function () {
    Route::prefix('dashboard')->controller(DashboardController::class)->group(function () {
        Route::get('/', 'index')->name('dashboard')->middleware('permissions:dashboard');
    });

    Route::prefix('settings')->controller(SettingsController::class)->group(function () {
        Route::get('/', 'show')->name('settings');
        Route::post('general-settings-save', 'generalSettingsSave')->name('settings.general.save');
        Route::post('general-settings-pwa', 'settingsPwaSave')->name('settings.general.pwa');
        Route::post('general-settings-socialite', 'settingsSocialiteSave')->name('settings.socialite');
        Route::post('general-settings-chat', 'settingsChatSave')->name('settings.chat');
        Route::post('general-settings-clear-cache', 'clearCache')->name('settings.clear.cache');
        Route::post('general-settings-storage-link', 'storageLink')->name('settings.storage.link');
        Route::post('general-settings-run-cron', 'runCron')->name('settings.run.cron');
    });

    // For Email Settings
    Route::prefix('settings')->controller(SmtpSettingsController::class)->group(function () {
        Route::post('email-settings-save', 'saveEmailSetting')->name('settings.email.save');
        Route::post('email-settings-test', 'testEmailSetting')->name('settings.email.test');
    });

    Route::prefix('black-list-email')->controller(BlackListEmailController::class)->group(function () {
        Route::get('/', 'index')->name('email.blacklist.index');
        Route::post('/file', 'saveBlackListEmail')->name('email.blacklist.save');
        Route::delete('file/{blackList}', 'destroyBlackListEmail')->name('email.blacklist.destroy');
    });

    Route::prefix('email-bulk')->controller(BulkImportController::class)->group(function () {
        Route::get('/', 'index')->name('email.index');
        Route::post('/store', 'store')->name('email.store');
    });

    Route::prefix('users')->controller(UserController::class)->group(function () {
        Route::get('/', 'index')->name('user.index');
        Route::post('/store-user', 'storeUser')->name('user.store');
        Route::delete('/destroy/{user}', 'destroyUser')->name('user.destroy');

        Route::get('/roles', 'role')->name('user.role.index');
        Route::post('/role-store', 'storeRole')->name('user.role.store');
        Route::post('/role-permissions-assign-role', 'permissionsAssignRole')->name('user.permissions.assign.role');
        Route::post('/user-permissions-assign', 'permissionsAssignUser')->name('user.permissions.assign');
        Route::delete('/destroy/{role}', 'destroy')->name('user.role.destroy');
    });

});

Route::middleware(['auth', 'verified', 'role:'.RoleEnum::EMPLOYEE->value])->prefix('employee')->as('employee.')->group(function () {
    Route::prefix('dashboard')->controller(DashboardController::class)->group(function () {
        Route::get('/', 'index')->name('dashboard');
    });

    Route::prefix('mail')->controller(MailController::class)->group(function () {
        Route::get('/gmail/{type}', 'gmailList')->name('gmail');
        Route::post('/gmail-view-inbox', 'gmailInboxView')->name('gmail.view');
        Route::get('/outlook/{type}', 'outlookList')->name('outlook');
        Route::post('/outlook-view-inbox', 'outlookInboxView')->name('outlook.view');
        Route::get('/webmail/{type}', 'webMailList')->name('webmail');
        Route::post('/webmail-view-inbox', 'webmailInboxView')->name('webmail.view');
        Route::get('/apple-mail/{type}', 'appleMailList')->name('apple-mail');
        Route::post('/apple-mail-view-inbox', 'appleMailInboxView')->name('apple-mail.view');

    });

    Route::prefix('setup')->controller(SetupController::class)->group(function () {
        Route::get('/', 'employeeSetup')->name('setup.index');
        Route::get('/authorized-gmail', 'authorizedGmail')->name('authorized.gmail');
        Route::get('/authorized-outlook', 'authorizedOutlook')->name('authorized.outlook');
        Route::get('imap/{type}', 'imapSettings')->where('type', 'webmail|applemail')->name('imap.settings');
        Route::post('imap/save', 'saveImapSettings')->name('imap.settings.save');
        Route::get('smtp', 'smtp')->name('smtp');
        Route::post('smtp-save', 'smtpSave')->name('smtp.save');
    });

});

Route::middleware(['auth', 'verified', 'role:'.RoleEnum::USER->value])->prefix('user')->as('user.')->group(function () {
    Route::prefix('dashboard')->controller(DashboardController::class)->group(function () {
        Route::get('/', 'index')->name('dashboard');
    });
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
