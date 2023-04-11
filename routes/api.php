<?php

/**
 * Date 10/04/2023
 * @author   Kelvin Mukotso <kelvinmukotso@gmail.com>
 */


use mukotso\CurrencyExchange\Controllers\CurrencyConverterController;

Route::get('v1/currency-exchange', [CurrencyConverterController::class, 'exchange'])->name('currency-exchange');

