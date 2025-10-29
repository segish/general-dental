<?php

namespace App\Events;

use App\Models\TestResult;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewMenuTestResultCreated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;
    public $link;
    public $title;
    public $permission;
    /**
     * Create a new event instance.
     */
    public function __construct($message, $link, $title, $permission)
    {
        $this->message = $message;
        $this->link = $link;
        $this->title = $title;
        $this->permission = $permission;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('menu-testResults'),
        ];
    }

    /**
     * Get the data to broadcast.
     */
    public function broadcastWith(): array
    {
        $data = [
            'message' => $this->message,
            'title' => $this->title,
            'created_at' => now()->format('d M Y h:i A'),
            'link' => $this->link,
            'permission' => $this->permission,
        ];

        return $data;
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'new.menu.testResult';
    }

    /**
     * Determine if this event should broadcast.
     */
    public function broadcastWhen(): bool
    {
        return true;
    }
}
