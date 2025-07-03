<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('customer_name');
            $table->enum('gateway', ['stripe', 'paypal', 'square']);
            $table->decimal('amount', 10, 2);
            $table->enum('status', ['paid', 'due', 'unpaid', 'upcoming', 'pending', 'failed', 'overdue']);
            $table->date('date');
            $table->timestamps();
        });
    }
    public function down() {
        Schema::dropIfExists('payments');
    }
}; 