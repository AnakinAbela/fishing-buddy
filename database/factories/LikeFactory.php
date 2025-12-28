<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\CatchLog;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories.Factory<\App\Models\Like>
 */
class LikeFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'catch_log_id' => CatchLog::factory(),
        ];
    }
}
