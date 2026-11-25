<?php

declare(strict_types=1);

use App\Http\Controllers\LandingController;
use App\Http\Controllers\RobotsController;
use App\Http\Controllers\SitemapController;
use Illuminate\Support\Facades\Route;

Route::get('/', LandingController::class)->name('home');
Route::get('/robots.txt', RobotsController::class)->name('robots');
Route::get('/sitemap.xml', SitemapController::class)->name('sitemap');

// Without JavaScript the contact form posts here: nothing is stored and the visitor returns to the form.
Route::post('/contatto', fn () => redirect('/#contatti', 303))->name('contact');
