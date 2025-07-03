<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PaymentsTableSeeder extends Seeder {
    public function run() {
        DB::table('payments')->insert([
            [ 'customer_name' => 'John Doe', 'gateway' => 'stripe', 'amount' => 150.00, 'status' => 'paid', 'date' => '2024-06-01', 'created_at' => now(), 'updated_at' => now() ],
            [ 'customer_name' => 'Jane Smith', 'gateway' => 'paypal', 'amount' => 75.00, 'status' => 'pending', 'date' => '2024-06-02', 'created_at' => now(), 'updated_at' => now() ],
            [ 'customer_name' => 'Bob Wilson', 'gateway' => 'square', 'amount' => 200.00, 'status' => 'failed', 'date' => '2024-06-03', 'created_at' => now(), 'updated_at' => now() ],
            [ 'customer_name' => 'Alice Brown', 'gateway' => 'stripe', 'amount' => 300.00, 'status' => 'due', 'date' => '2024-05-28', 'created_at' => now(), 'updated_at' => now() ],
            [ 'customer_name' => 'Carol Davis', 'gateway' => 'paypal', 'amount' => 95.00, 'status' => 'paid', 'date' => '2024-06-04', 'created_at' => now(), 'updated_at' => now() ],
            [ 'customer_name' => 'David Miller', 'gateway' => 'square', 'amount' => 200.00, 'status' => 'upcoming', 'date' => '2024-06-10', 'created_at' => now(), 'updated_at' => now() ],
            [ 'customer_name' => 'Emma Johnson', 'gateway' => 'square', 'amount' => 350.00, 'status' => 'paid', 'date' => '2024-06-01', 'created_at' => now(), 'updated_at' => now() ],
            [ 'customer_name' => 'Mike Thompson', 'gateway' => 'square', 'amount' => 125.00, 'status' => 'unpaid', 'date' => '2024-06-06', 'created_at' => now(), 'updated_at' => now() ],
        ]);
    }
} 