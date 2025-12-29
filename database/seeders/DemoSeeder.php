<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\FishingSpot;
use App\Models\CatchLog;
use App\Models\Comment;
use App\Models\Follow;

class DemoSeeder extends Seeder
{
    public function run(): void
    {
        $demo = User::create([
            'name' => 'Demo Angler',
            'email' => 'demo@example.com',
            'bio' => 'Demo account for class walkthroughs.',
            'password' => Hash::make('password'),
        ]);

        $friend = User::create([
            'name' => 'Friend Fisher',
            'email' => 'friend@example.com',
            'bio' => 'Shares catches with demo.',
            'password' => Hash::make('password'),
        ]);

        $spot1 = FishingSpot::create([
            'user_id' => $demo->id,
            'name' => 'Harbor Jetty',
            'description' => 'Rocky jetty with good dawn bites.',
            'country' => 'Spain',
            'city' => 'Cadiz',
            'latitude' => 36.5299,
            'longitude' => -6.2925,
        ]);

        $spot2 = FishingSpot::create([
            'user_id' => $friend->id,
            'name' => 'Lake Point',
            'description' => 'Shaded point, best at dusk.',
            'country' => 'Canada',
            'city' => 'Ottawa',
            'latitude' => 45.4215,
            'longitude' => -75.6972,
        ]);

        $catchA = CatchLog::create([
            'user_id' => $demo->id,
            'fishing_spot_id' => $spot1->id,
            'species' => 'Bass',
            'weight_kg' => 2.1,
            'length_cm' => 45,
            'depth_m' => 4,
            'visibility' => 'public',
            'notes' => 'Hit a crankbait near rocks.',
        ]);

        $catchB = CatchLog::create([
            'user_id' => $friend->id,
            'fishing_spot_id' => $spot2->id,
            'species' => 'Trout',
            'weight_kg' => 1.3,
            'length_cm' => 38,
            'depth_m' => 3,
            'visibility' => 'friends',
            'notes' => 'Evening bite on spoon.',
        ]);

        Comment::create([
            'user_id' => $friend->id,
            'catch_log_id' => $catchA->id,
            'content' => 'Nice bass! What lure?',
        ]);

        Comment::create([
            'user_id' => $demo->id,
            'catch_log_id' => $catchB->id,
            'content' => 'Great trout spot!',
        ]);

        // Basic follow relationships so "Friends" filter shows data
        Follow::create([
            'follower_id' => $demo->id,
            'followed_id' => $friend->id,
        ]);

        Follow::create([
            'follower_id' => $friend->id,
            'followed_id' => $demo->id,
        ]);
    }
}
