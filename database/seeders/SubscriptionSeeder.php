<?php

namespace Database\Seeders;

use App\Models\Subscription;
use App\Models\User;
use Illuminate\Database\Seeder;

class SubscriptionSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::query()->where('email', 'demo@neonbill.test')->first()
            ?? User::factory()->create([
                'name' => 'Demo User',
                'email' => 'demo@neonbill.test',
                'password' => 'password',
                'default_currency' => 'IDR',
            ]);

        $samples = [
            ['name' => 'Netflix', 'category' => 'Streaming', 'price' => 186000, 'currency' => 'IDR', 'billing_cycle' => 'Monthly', 'next_due_date' => now()->addDays(5)],
            ['name' => 'Spotify Premium', 'category' => 'Streaming', 'price' => 54900, 'currency' => 'IDR', 'billing_cycle' => 'Monthly', 'next_due_date' => now()->addDays(12)],
            ['name' => 'Adobe Creative Cloud', 'category' => 'Software', 'price' => 15, 'currency' => 'USD', 'billing_cycle' => 'Monthly', 'next_due_date' => now()->addMonths(2)],
            ['name' => 'Home Internet', 'category' => 'Internet', 'price' => 350000, 'currency' => 'IDR', 'billing_cycle' => 'Monthly', 'next_due_date' => now()->subDays(2), 'is_active' => true],
        ];

        foreach ($samples as $sample) {
            Subscription::create([
                ...$sample,
                'user_id' => $user->id,
                'is_active' => $sample['is_active'] ?? true,
            ]);
        }
    }
}
