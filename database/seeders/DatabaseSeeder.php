<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\FishingSpot;
use App\Models\CatchLog;
use App\Models\Comment;
use App\Models\Like;
use App\Models\Follow;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create a demo user for easy login
        $demoUser = User::factory()->create([
            'name' => 'Demo Angler',
            'email' => 'demo@example.com',
            'password' => Hash::make('password'),
        ]);

        // Additional users
        $users = User::factory(8)->create();

        // Spots owned by all users
        $spots = $users->flatMap(function ($user) {
            return FishingSpot::factory(2)->create([
                'user_id' => $user->id,
            ]);
        });

        // Catches per user, linked randomly to spots
        $catches = $users->flatMap(function ($user) use ($spots) {
            return CatchLog::factory(3)->create([
                'user_id' => $user->id,
                'fishing_spot_id' => $spots->random()->id,
            ]);
        });

        // Comments and likes on catches from random users
        $catches->each(function ($catch) use ($users) {
            Comment::factory(random_int(1, 4))->create([
                'catch_log_id' => $catch->id,
                'user_id' => $users->random()->id,
            ]);

            // A handful of likes, avoiding duplicate pairs via unique users sample
            $likers = $users->random(random_int(1, min(4, $users->count())));
            $likers->each(function ($user) use ($catch) {
                Like::factory()->create([
                    'user_id' => $user->id,
                    'catch_log_id' => $catch->id,
                ]);
            });
        });

        // Simple follow graph: everyone follows the demo user; demo follows first three users
        $users->each(function ($user) use ($demoUser) {
            Follow::factory()->create([
                'follower_id' => $user->id,
                'followed_id' => $demoUser->id,
            ]);
        });

        $users->take(3)->each(function ($user) use ($demoUser) {
            Follow::factory()->create([
                'follower_id' => $demoUser->id,
                'followed_id' => $user->id,
            ]);
        });
    }
}
