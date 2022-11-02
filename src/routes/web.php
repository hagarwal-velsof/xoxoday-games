<?php

use Illuminate\Support\Facades\Route;
use Xoxoday\Games\Http\Controller\GamesController;

Route::group(['middleware' => ['web']], function () {

Route::post('/games/result', [GamesController::class, 'result']);

});
