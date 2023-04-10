<?php
/**
 * Date 10/04/2023
 *
 * @author   kelvin mukoto <kelvinmukotso@gmail.com>
 */

namespace mukotso\CurrencyExchange\Exchange;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class Exchange
{
    /**
     * @var Client
     */
    protected Client $client;
    /**
     * @var mixed|null
     */
    protected mixed $config;

    public function __construct($config = null)
    {
        $this->client = new Client();
        $this->config = $config;
    }

    /**
     * @throws GuzzleException
     */
    public function getExchangeRate($currency): mixed
    {

        $currency = $currency ?: config('currency-exchange.default_selected_currency');
        $source_url = config('currency-exchange.exchange_rate_source_url');
        $response = $this->client->get($source_url);

        $xml = $response->getBody()->getContents();
        $data = json_decode(json_encode(simplexml_load_string($xml)), true);
        $exchange_rates = $data['Cube']['Cube']['Cube'];
        $exchange_rate = null;
        foreach ($exchange_rates as $rate) {
            if ($rate['@attributes']['currency'] == $currency) {
                $exchange_rate = $rate['@attributes']['rate'];
                break;
            }
        }
        return $exchange_rate;
    }

}
