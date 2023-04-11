<?php
/**
 * Date 10/04/2023
 *
 * @author   kelvin mukotso <kelvinmukotso@gmail.com>
 */

namespace mukotso\CurrencyExchange;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Ssiva\CurrencyExchange\Exchange\Exchange;

class CurrencyExchangeServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/config/currency-exchange.php' => config_path('currency-exchange.php'),
        ], 'currency-exchange');

        $this->loadRoutesFrom(__DIR__.'/../routes/api.php');
        Route::prefix('api')
            ->middleware('api')
            ->namespace('mukotso\CurrencyExchange\Controllers')
            ->group(__DIR__.'/../routes/api.php');

    }

    /**
     * Register the application services.
     */
    public function register(): void
    {
        $this->app->bind('mukotso\CurrencyExchange\Exchange', function ($app) {
            $config = $app['config']->get('currency-exchange');
            return new Exchange($config);
        });
    }
}
