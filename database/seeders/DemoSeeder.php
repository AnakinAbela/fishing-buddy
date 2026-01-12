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
            'bio' => 'Regularly fishes the south coast.',
            'password' => Hash::make('password'),
        ]);

        $spot1 = FishingSpot::create([
            'user_id' => $demo->id,
            'name' => 'Valletta Breakwater',
            'description' => 'Outer breakwater near the harbor mouth, great at sunrise.',
            'country' => 'Malta',
            'city' => 'Valletta',
            'latitude' => 35.8989,
            'longitude' => 14.5146,
        ]);

        $spot2 = FishingSpot::create([
            'user_id' => $friend->id,
            'name' => 'Marsaxlokk Harbour',
            'description' => 'Calm bay with traditional luzzu boats and night bites.',
            'country' => 'Malta',
            'city' => 'Marsaxlokk',
            'latitude' => 35.8415,
            'longitude' => 14.5449,
        ]);

        $catchA = CatchLog::create([
            'user_id' => $demo->id,
            'fishing_spot_id' => $spot1->id,
            'species' => 'Lampuki (Mahi-mahi)',
            'weight_kg' => 3.4,
            'length_cm' => 62,
            'depth_m' => 8,
            'visibility' => 'public',
            'notes' => 'Trolled a feather near the breakwater.',
        ]);

        $catchB = CatchLog::create([
            'user_id' => $friend->id,
            'fishing_spot_id' => $spot2->id,
            'species' => 'Amberjack',
            'weight_kg' => 5.2,
            'length_cm' => 70,
            'depth_m' => 12,
            'visibility' => 'friends',
            'notes' => 'Live bait near the drop-off.',
        ]);

        Comment::create([
            'user_id' => $friend->id,
            'catch_log_id' => $catchA->id,
            'content' => 'Great Lampuki! Which feather color?',
        ]);

        Comment::create([
            'user_id' => $demo->id,
            'catch_log_id' => $catchB->id,
            'content' => 'Solid amberjack—nice catch!',
        ]);

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
