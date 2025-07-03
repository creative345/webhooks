<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Payment;
use Carbon\Carbon;

class WebhookPaymentsSeeder extends Seeder
{
    public function run()
    {
        $payments = [
            // Stripe payments
            [
                'provider' => 'stripe',
                'event_type' => 'payment_intent.succeeded',
                'event_id' => 'evt_stripe_001',
                'resource_id' => 'pi_stripe_001',
                'amount' => 299.99,
                'currency' => 'USD',
                'customer_email' => 'john.doe@example.com',
                'customer_id' => 'cus_stripe_001',
                'payment_method' => 'card',
                'status' => 'completed',
                'raw_payload' => ['payment_intent' => 'pi_stripe_001'],
                'received_at' => Carbon::now()->subHours(2),
                'processed_at' => Carbon::now()->subHours(2),
                'is_test' => false,
            ],
            [
                'provider' => 'stripe',
                'event_type' => 'payment_intent.payment_failed',
                'event_id' => 'evt_stripe_002',
                'resource_id' => 'pi_stripe_002',
                'amount' => 150.00,
                'currency' => 'USD',
                'customer_email' => 'jane.smith@example.com',
                'customer_id' => 'cus_stripe_002',
                'payment_method' => 'card',
                'status' => 'failed',
                'raw_payload' => ['payment_intent' => 'pi_stripe_002'],
                'received_at' => Carbon::now()->subHours(4),
                'processed_at' => Carbon::now()->subHours(4),
                'is_test' => false,
            ],
            [
                'provider' => 'stripe',
                'event_type' => 'charge.refunded',
                'event_id' => 'evt_stripe_003',
                'resource_id' => 'ch_stripe_003',
                'amount' => 75.50,
                'currency' => 'USD',
                'customer_email' => 'bob.wilson@example.com',
                'customer_id' => 'cus_stripe_003',
                'payment_method' => 'card',
                'status' => 'refunded',
                'raw_payload' => ['charge' => 'ch_stripe_003'],
                'received_at' => Carbon::now()->subHours(6),
                'processed_at' => Carbon::now()->subHours(6),
                'is_test' => false,
            ],

            // PayPal payments
            [
                'provider' => 'paypal',
                'event_type' => 'PAYMENT.CAPTURE.COMPLETED',
                'event_id' => 'evt_paypal_001',
                'resource_id' => 'capture_paypal_001',
                'amount' => 199.99,
                'currency' => 'USD',
                'customer_email' => 'alice.johnson@example.com',
                'customer_id' => 'buyer_paypal_001',
                'payment_method' => 'paypal',
                'status' => 'completed',
                'raw_payload' => ['capture' => 'capture_paypal_001'],
                'received_at' => Carbon::now()->subHours(1),
                'processed_at' => Carbon::now()->subHours(1),
                'is_test' => false,
            ],
            [
                'provider' => 'paypal',
                'event_type' => 'PAYMENT.CAPTURE.DENIED',
                'event_id' => 'evt_paypal_002',
                'resource_id' => 'capture_paypal_002',
                'amount' => 89.99,
                'currency' => 'USD',
                'customer_email' => 'charlie.brown@example.com',
                'customer_id' => 'buyer_paypal_002',
                'payment_method' => 'paypal',
                'status' => 'failed',
                'raw_payload' => ['capture' => 'capture_paypal_002'],
                'received_at' => Carbon::now()->subHours(3),
                'processed_at' => Carbon::now()->subHours(3),
                'is_test' => false,
            ],
            [
                'provider' => 'paypal',
                'event_type' => 'PAYMENT.CAPTURE.REFUNDED',
                'event_id' => 'evt_paypal_003',
                'resource_id' => 'capture_paypal_003',
                'amount' => 45.00,
                'currency' => 'USD',
                'customer_email' => 'diana.prince@example.com',
                'customer_id' => 'buyer_paypal_003',
                'payment_method' => 'paypal',
                'status' => 'refunded',
                'raw_payload' => ['capture' => 'capture_paypal_003'],
                'received_at' => Carbon::now()->subHours(5),
                'processed_at' => Carbon::now()->subHours(5),
                'is_test' => false,
            ],

            // Square payments
            [
                'provider' => 'square',
                'event_type' => 'payment.created',
                'event_id' => 'evt_square_001',
                'resource_id' => 'payment_square_001',
                'amount' => 125.00,
                'currency' => 'USD',
                'customer_email' => 'tony.stark@example.com',
                'customer_id' => 'customer_square_001',
                'payment_method' => 'card',
                'status' => 'completed',
                'raw_payload' => ['payment' => 'payment_square_001'],
                'received_at' => Carbon::now()->subHours(30),
                'processed_at' => Carbon::now()->subHours(30),
                'is_test' => false,
            ],
            [
                'provider' => 'square',
                'event_type' => 'payment.updated',
                'event_id' => 'evt_square_002',
                'resource_id' => 'payment_square_002',
                'amount' => 67.50,
                'currency' => 'USD',
                'customer_email' => 'peter.parker@example.com',
                'customer_id' => 'customer_square_002',
                'payment_method' => 'card',
                'status' => 'pending',
                'raw_payload' => ['payment' => 'payment_square_002'],
                'received_at' => Carbon::now()->subHours(2),
                'processed_at' => null,
                'is_test' => false,
            ],
            [
                'provider' => 'square',
                'event_type' => 'refund.created',
                'event_id' => 'evt_square_003',
                'resource_id' => 'refund_square_003',
                'amount' => 25.00,
                'currency' => 'USD',
                'customer_email' => 'bruce.wayne@example.com',
                'customer_id' => 'customer_square_003',
                'payment_method' => 'card',
                'status' => 'refunded',
                'raw_payload' => ['refund' => 'refund_square_003'],
                'received_at' => Carbon::now()->subHours(8),
                'processed_at' => Carbon::now()->subHours(8),
                'is_test' => false,
            ],

            // Test payments
            [
                'provider' => 'stripe',
                'event_type' => 'payment_intent.succeeded',
                'event_id' => 'evt_stripe_test_001',
                'resource_id' => 'pi_stripe_test_001',
                'amount' => 50.00,
                'currency' => 'USD',
                'customer_email' => 'test@example.com',
                'customer_id' => 'cus_stripe_test_001',
                'payment_method' => 'card',
                'status' => 'completed',
                'raw_payload' => ['payment_intent' => 'pi_stripe_test_001'],
                'received_at' => Carbon::now()->subHours(12),
                'processed_at' => Carbon::now()->subHours(12),
                'is_test' => true,
            ],
        ];

        foreach ($payments as $payment) {
            Payment::create($payment);
        }
    }
} 