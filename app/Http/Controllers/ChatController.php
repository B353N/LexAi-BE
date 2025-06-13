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

        // Add default AI welcome message
        $session->messages()->create([
            'sender' => 'ai',
            'message' => 'Здравейте, аз съм LexAI и мога да съм вашия правен съветник. Мога да ги помогна с разбирането на закони, да ви консултирам или изработя договори за наем и др.',
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
            ->using(Provider::Gemini, 'gemini-2.0-flash')
            ->withMessages($messages)
            ->asText();

        $chatSession->messages()->create([
            'sender' => 'ai',
            'message' => $response->text,
        ]);

        return redirect()->route('chat.show', $chatSession);
    }
}
