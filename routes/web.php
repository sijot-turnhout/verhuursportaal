<?php

declare(strict_types=1);

use App\Http\Controllers\AvailabilityController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\DownloadDocumentController;
use App\Http\Controllers\Feedback\DisplayFormController;
use App\Http\Controllers\Feedback\StoreFeedbackController;
use App\Http\Controllers\FrontPageController;
use App\Http\Controllers\Invoices\QuotaController;
use App\Http\Controllers\Legal\PrivacyController;
use App\Http\Controllers\PriceInformationController;
use App\Http\Middleware\WelcomseNewFeedback;
use Illuminate\Support\Facades\Route;

Route::get('/', FrontPageController::class)->name('welcome');
Route::get('/wat-kost-dat', PriceInformationController::class)->name('price-information');
Route::get('/beschikbaarheid', AvailabilityController::class)->name('availability');
Route::post('/reserveren', BookingController::class)->name('booking.store');
Route::post('/contact', ContactController::class)->name('contact.send');
Route::get('/privacy', PrivacyController::class)->name('legal.privacy');
Route::get('/offerte', [QuotaController::class, 'index'])->name('offerte.information');

Route::get('status', function (): Illuminate\Http\RedirectResponse {
    abort_if( ! auth()->check(), 404);
    return redirect('pulse');
});

Route::get('/invoices/{record}/download', [DownloadDocumentController::class, 'invoice'])->name('invoices.download')->middleware('auth');

Route::group(['middleware' => [WelcomseNewFeedback::class]], function (): void {
    Route::get('/feedback/{lease}', DisplayFormController::class)->name('feedback.form');
    Route::post('/feedback/{lease}', StoreFeedbackController::class)->name('feedback.submit');
});

if (config('app.debug')) {
    Route::get('debug/invoice', fn() => view('pdfs.invoice', ['record' => App\Models\Invoice::first()]));
    Route::get('debug/feedback', fn() => view('feedback.submit-form', ['lease' => App\Models\Lease::first()]));
}
