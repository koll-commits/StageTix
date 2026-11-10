<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    $user = auth()->user();

    if ($user && $user->isAdmin()) {
        return redirect()->route('admin.dashboard');
    }

    if ($user && $user->isEoOperator()) {
        return redirect()->route('eo.dashboard', ['eo' => 'demo']);
    }

    return redirect()->route('customer.home');
})->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__.'/auth.php';
