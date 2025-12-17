<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ApiProvider; // Make sure to import your model

class ApiProviderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Define the permanent API providers your system relies on
        $providers = [
            [
                // This is the provider that the WarehouseController::SHIPBUBBLE_PROVIDER_ID = 1;
                // is likely pointing to if you don't change IDs.
                'name' => 'Shipbubble',
                'identifier' => 'SHIPBUBBLE',
                // Set a small, initial cost. This should be adjusted via the admin dashboard.
                'cost_per_call' => 0.0001, 
                'notes' => 'Shipping label creation, validation, and tracking API.',
            ],
            // You can add other providers here (e.g., Paystack, Flutterwave, etc.)
            [
                'name' => 'Paystack',
                'identifier' => 'PAYSTACK',
                'cost_per_call' => 0.00,
                'notes' => 'Payment gateway provider.',
            ],
        ];

        foreach ($providers as $providerData) {
            // Use updateOrCreate based on the unique identifier to prevent duplicates
            // if the seeder is run multiple times.
            ApiProvider::updateOrCreate(
                ['identifier' => $providerData['identifier']],
                $providerData
            );
        }
    }
}