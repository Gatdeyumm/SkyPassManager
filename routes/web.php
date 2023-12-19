<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LogController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
Route::get('/', [AuthController::class, 'loginForm'])->name('loginForm');
Route::post('/', [AuthController::class, 'login'])->name('login');
Route::post('/dashboard', [AuthController::class, 'login'])->name('dashboard');

Route::get('/register', [AuthController::class, 'registerForm'])->name('registerForm');
Route::post('/register', [AuthController::class, 'register']);
Route::get('/dashboard', [AuthController::class, 'dashboard'])->name('dashboard');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');



Route::get('/tickets', [TicketController::class, 'index'])->name('tickets.index');
Route::get('/tickets/create', [TicketController::class, 'create'])->name('tickets.create');
Route::post('/tickets', [TicketController::class, 'store'])->name('tickets.store');
Route::delete('/tickets/{ticket}', [TicketController::class, 'destroy'])->name('tickets.destroy');
Route::patch('/tickets/{ticket}', [TicketController::class, 'update'])->name('tickets.update');



Route::get('/logs', [LogController::class, 'index'])->name('logs.index');
Route::delete('/log/{id}', [LogController::class, 'destroy'])->name('log.delete');
Route::post('/clear-all-logs', [LogController::class, 'clearAllLogs'])->name('logs.clearAll');


Route::post('/tickets/{ticket}', [TicketController::class, 'book'])->name('ticket.book');


Route::get('/index', [DashboardController::class, 'index']);


// Route::get('/sendmail', [EmailController::class, 'sendMail']);

Route::get('/verification/{user}/{token}', [AuthController::class, 'verification']);
