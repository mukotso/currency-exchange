<?php
/**
 * Date 10/04/2023
 * @author   Kelvin Mukotso <kelvinmukotso@gmail.com>
 */

namespace mukotso\CurrencyExchange\Controllers;

use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use OpenApi\Annotations as OA;
use mukotso\CurrencyExchange\Exchange\Exchange;

class CurrencyConverterController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/currency-exchange",
     *     summary="Convert currency",
     *     tags={"Currency Converter"},
     *     @OA\Parameter(
     *         name="amount",
     *         in="query",
     *         description="The amount to convert",
     *         required=true,
     *         @OA\Schema(type="number")
     *     ),
     *     @OA\Parameter(
     *         name="currency",
     *         in="query",
     *         description="The currency to convert to",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Conversion result",
     *         @OA\JsonContent(
     *             @OA\Property(property="amount", type="number", example="1.23"),
     *             @OA\Property(property="currency", type="string", example="USD")
     *         )
     *     )
     * )
     * @throws GuzzleException
     */
    public function exchange(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric',
            'currency' => 'string|max:4|min:2'
        ]);
        if($validator->fails()){
            return response()->json($validator->errors(), 400);
        }
        $amount = $request->get('amount');
        $conversion_to_currency = $request->get('currency');


        $exchangeRate = (new Exchange())->getExchangeRate($conversion_to_currency);

        if (!$exchangeRate) {
            return response()->json([
                'error' => 'Invalid currency to convert to'
            ], 400);
        }

        $total_amount = $amount * $exchangeRate;

        return response()->json([
            'amount' => $total_amount,
            'currency' => $conversion_to_currency
        ]);
    }
}
