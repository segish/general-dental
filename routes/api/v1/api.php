<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\MachineResultController;

Route::post('/machine-results', [MachineResultController::class, 'store']);