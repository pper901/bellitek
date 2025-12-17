<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ShippingController extends Controller
{
    public function getRate(Request $request)
    {
        $validated = $request->validate([
            'state' => 'required|string',
            'city' => 'required|string',
        ]);

        $response = Http::withToken(env('sb_sandbox_9a338fa4295a698380c0be29c21851e08bcb72eb8781555c7170c58d5b928167'))
            ->post('https://api.shipbubble.com/v1/shipping/rates', [
                'pickup' => [
                    'country' => 'Nigeria',
                    'state' => 'Lagos',
                    'city' => 'Ikeja',
                    'postcode' => '100001'
                ],
                'delivery' => [
                    'country' => 'Nigeria',
                    'state' => $validated['state'],
                    'city' => $validated['city'],
                ],
                'package' => [
                    'weight' => 1,
                    'weight_unit' => 'kg'
                ]
            ]);

        return response()->json([
            'success' => true,
            'rate' => $response->json()['rates'][0]['total_charges'] ?? 0
        ]);
    }
}

