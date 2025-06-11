<?php

namespace Tests\Feature;

use App\Models\ChatSession;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Prism\Prism\Prism;
use Prism\Prism\Testing\TextResponseFake;
use Tests\TestCase;

class ChatFunctionalityTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_cannot_access_chat(): void
    {
        $this->get(route('chat.index'))->assertRedirect(route('login'));
    }

    public function test_user_can_create_a_new_chat_session(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('chat.store'))
            ->assertRedirect();

        $this->assertDatabaseHas('chat_sessions', [
            'user_id' => $user->id,
            'title' => 'New Chat',
        ]);
    }

    public function test_user_can_send_a_message_and_get_a_response(): void
    {
        $user = User::factory()->create();
        $chatSession = $user->chatSessions()->create(['title' => 'Test Chat']);

        $this->actingAs($user);

        Prism::fake([
            TextResponseFake::make()->withText('This is a mocked AI response.'),
        ]);

        $this->put(route('chat.update', $chatSession), [
            'message' => 'Hello, AI!',
        ])->assertRedirect(route('chat.show', $chatSession));

        $this->assertDatabaseHas('chat_messages', [
            'chat_session_id' => $chatSession->id,
            'sender' => 'user',
            'message' => 'Hello, AI!',
        ]);

        $this->assertDatabaseHas('chat_messages', [
            'chat_session_id' => $chatSession->id,
            'sender' => 'ai',
            'message' => 'This is a mocked AI response.',
        ]);
    }

    public function test_user_cannot_view_another_users_chat_session(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $chatSession = $user2->chatSessions()->create(['title' => 'User 2 Chat']);

        $this->actingAs($user1)
            ->get(route('chat.show', $chatSession))
            ->assertForbidden();
    }

    public function test_user_cannot_update_another_users_chat_session(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $chatSession = $user2->chatSessions()->create(['title' => 'User 2 Chat']);

        $this->actingAs($user1)
            ->put(route('chat.update', $chatSession), ['message' => 'Sneaky message'])
            ->assertForbidden();
    }
}
