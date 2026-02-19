<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class NotificationSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();

        if ($users->isEmpty()) {
            $this->command->warn('No users found!');
            return;
        }

        foreach ($users as $user) {
            for ($i = 1; $i <= 30; $i++) {
                DB::table('notifications')->insert([
                    'id' => (string) Str::uuid(),
                    'type' => 'App\\Notifications\\NotifyUser',
                    'notifiable_type' => User::class,
                    'notifiable_id' => $user->id,
                    'data' => json_encode([
                        'title' => "Test Notification #{$i}",
                        'message' => "This is seeded notification number {$i}",
                        'url' => url('/'),
                    ]),
                    'read_at' => $i % 3 === 0 ? Carbon::now() : null, 
                    'created_at' => now()->subMinutes($i),
                    'updated_at' => now()->subMinutes($i),
                ]);
            }
        }
    }
}
