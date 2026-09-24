<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_a_post(): void
    {
        $user = User::factory()->create();

        $response = $this->postJson('/api/posts', [
            'title' => 'Test Post',
            'body' => 'This is a test post.',
            'user_id' => $user->id,
        ]);

        $response
            ->assertStatus(201)
            ->assertJsonPath('data.title', 'Test Post');

        $this->assertDatabaseHas('posts', [
            'title' => 'Test Post',
            'user_id' => $user->id,
        ]);
    }
}