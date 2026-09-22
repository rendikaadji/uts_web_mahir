<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\GateController;
use App\Http\Controllers\Api\V1\MemberImportController;
use App\Http\Controllers\Api\V1\QrController;
use App\Http\Controllers\Api\V1\VisitReportController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    // Authentication endpoints
    Route::post('/auth/login', [AuthController::class, 'login']);

    // Public Kiosk endpoints with rate limiting
    Route::middleware('throttle:60,1')->group(function () {
        Route::post('/gate/scan', [GateController::class, 'scan']);
        Route::post('/gate/guest', [GateController::class, 'guest']);
    });

    // Authenticated member & staff endpoints
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/auth/user', [AuthController::class, 'me']);
        Route::post('/auth/logout', [AuthController::class, 'logout']);

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
        Route::post('/gate/manual', [GateController::class, 'manual']);
        Route::get('/visits', [VisitReportController::class, 'index']);
        Route::get('/visits/stats', [VisitReportController::class, 'stats']);
        Route::get('/visits/export', [VisitReportController::class, 'export']);
    });
});
