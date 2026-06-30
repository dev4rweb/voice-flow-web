<?php

use App\Http\Controllers\DownloadController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\SeoController;
use Illuminate\Support\Facades\Route;

require __DIR__.'/admin.php';

Route::get('/', [LandingController::class, 'redirectByBrowser'])->name('landing.detect');
Route::get('/download/file', [DownloadController::class, 'file'])->name('download.file');
Route::get('/sitemap.xml', [SeoController::class, 'sitemap'])->name('seo.sitemap');
Route::get('/robots.txt', [SeoController::class, 'robots'])->name('seo.robots');
Route::get('/{locale}', [LandingController::class, 'show'])->name('landing');
