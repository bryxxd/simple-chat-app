<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ChatRoom>
 */
class ChatRoomFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $userIds = [1, 2];
        return [
            //
            'from_user_id' => $userIds[array_rand($userIds)],
            'to_user_id' =>  $userIds[array_rand($userIds)],
            'content' => fake()->unique()->sentence(),
        ];
    }
}
