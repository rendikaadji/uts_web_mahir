<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\GateController;
use App\Http\Controllers\Api\V1\MemberImportController;
use App\Http\Controllers\Api\V1\QrController;
use App\Http\Controllers\Api\V1\VisitReportController;
use Illuminate\Support\Facades\Route;

// Memakai sesi web (cookie + CSRF) yang sama dengan portal; tidak ada login token terpisah.
Route::prefix('v1')->middleware('web')->group(function () {
    // Authenticated member & staff endpoints
    Route::middleware('auth')->group(function () {
        Route::get('/auth/user', [AuthController::class, 'me']);

        // Member Digital QR code & directory CRUD
        Route::get('/qr/me', [QrController::class, 'me']);
        Route::get('/qr/user/{user}', [QrController::class, 'showUserQr']);
        Route::get('/members', [QrController::class, 'members']);
        Route::post('/members', [QrController::class, 'storeMember']);
        Route::put('/members/{user}', [QrController::class, 'updateMember']);
        Route::delete('/members/{user}', [QrController::class, 'destroyMember']);
        Route::get('/members/template', [MemberImportController::class, 'downloadTemplate']);
        Route::post('/members/import', [MemberImportController::class, 'import']);
        Route::post('/qr/generate', [QrController::class, 'generate']);

        // Staff attendance override & reporting
        Route::post('/gate/scan', [GateController::class, 'scan'])->middleware('throttle:60,1');
        Route::post('/gate/guest', [GateController::class, 'guest'])->middleware('throttle:60,1');
        Route::post('/gate/manual', [GateController::class, 'manual']);
        Route::get('/visits', [VisitReportController::class, 'index']);
        Route::get('/visits/stats', [VisitReportController::class, 'stats']);
        Route::get('/visits/export', [VisitReportController::class, 'export']);
    });
});
