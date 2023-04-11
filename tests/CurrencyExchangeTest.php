<?php
/**
 * Date 10/04/2023
 * @author   Kelvin Mukotso <kelvinmukotso@gmail.com>
 */

namespace mukotso\CurrencyExchange\Tests;

use Tests\TestCase;
use mukotso\CurrencyExchange\CurrencyExchangeServiceProvider;

class CurrencyExchangeTest extends TestCase
{

    /** @test */
    public function test_amount_is_required_to_convert_currency()
    {
        $response = $this->get(route("currency-exchange" , [  "currency" => "USD"]));
        $response->assertStatus(422);


    }


    /** @test */
    public function test_can_convert_currency_with_correct_data()
    {
        $response = $this->get(route("currency-exchange" , [ "amount" => 100, "currency" => "USD"]));
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'amount','currency',
        ]);
    
    }

    /** @test */
    public function test_default_configurations(): void
    {
        $configValue = config('currency-exchange.default_selected_currency');
        $this->assertEquals('EUR', $configValue);
    }

    /** @test */
    public function test_custom_configurations(): void
    {
        config(['currency-exchange.default_selected_currency' => 'USD']);
        $configValue = config('currency-exchange.default_selected_currency');
        $this->assertEquals('USD', $configValue);
    }



}
