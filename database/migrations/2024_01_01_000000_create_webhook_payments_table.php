<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWebhookPaymentsTable extends Migration
{
    public function up()
    {
        Schema::connection('webhooks')->create('payments', function (Blueprint $table) {
            $table->id();

            // Source of payment
            $table->string('provider', 20); // 'stripe', 'paypal', 'square'

            // Event info
            $table->string('event_type', 100);
            $table->string('event_id')->unique();

            // Payment or resource ID
            $table->string('resource_id')->nullable();

            // Payment details
            $table->decimal('amount', 10, 2)->default(0.00);
            $table->string('currency', 10)->default('USD');

            // Customer info
            $table->string('customer_email')->nullable();
            $table->string('customer_id')->nullable();
            $table->string('payment_method')->nullable();

            // Processing status
            $table->string('status')->default('pending'); // 'pending', 'completed', etc.

            // Raw webhook payload
            $table->json('raw_payload');

            // Timestamps for processing
            $table->timestamp('received_at')->useCurrent();
            $table->timestamp('processed_at')->nullable();

            $table->boolean('is_test')->default(false);

            $table->timestamps(); // Laravel's created_at, updated_at
        });
    }

    public function down()
    {
        Schema::connection('webhooks')->dropIfExists('payments');
    }
} 