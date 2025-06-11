<?php

namespace App\Http\Controllers;

use App\Models\ChatSession;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Prism\Prism\Enums\Provider;
use Prism\Prism\Prism;
use Prism\Prism\ValueObjects\Messages\AssistantMessage;
use Prism\Prism\ValueObjects\Messages\UserMessage;

class ChatController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $chatSessions = auth()->user()->chatSessions()->latest()->get();

        return Inertia::render('Chat', [
            'chatSessions' => $chatSessions,
        ]);
    }

    public function store(Request $request)
    {
        $session = auth()->user()->chatSessions()->create([
            'title' => 'New Chat', // You can generate a better title
        ]);

        return redirect()->route('chat.show', $session);
    }

    public function show(ChatSession $chatSession)
    {
        $this->authorize('view', $chatSession);

        $chatSessions = auth()->user()->chatSessions()->latest()->get();

        return Inertia::render('Chat', [
            'chatSession' => $chatSession->load('messages'),
            'chatSessions' => $chatSessions,
        ]);
    }

    public function update(Request $request, ChatSession $chatSession)
    {
        $this->authorize('update', $chatSession);

        $request->validate(['message' => 'required|string']);

        $chatSession->messages()->create([
            'sender' => 'user',
            'message' => $request->input('message'),
        ]);

        if ($chatSession->messages()->count() === 1) {
            $chatSession->update(['title' => substr($request->input('message'), 0, 50)]);
        }

        $messages = $chatSession->messages()->get()->map(function ($message) {
            if ($message->sender === 'user') {
                return new UserMessage($message->message);
            }

            return new AssistantMessage($message->message);
        })->all();

        $response = Prism::text()
            ->using(Provider::Gemini, 'gemini-1.5-flash-latest')
            ->withMessages($messages)
            ->asText();

        $chatSession->messages()->create([
            'sender' => 'ai',
            'message' => $response->text,
        ]);

        return redirect()->route('chat.show', $chatSession);
    }
}
