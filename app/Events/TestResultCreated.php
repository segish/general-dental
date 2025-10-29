<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\TestResult;

class TestResultCreated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $testResult;

    public function __construct(TestResult $testResult)
    {
        $this->testResult = $testResult->load('laboratoryRequestTest.test');
    }

    public function broadcastOn()
    {
        // You can choose private or public channel
        return new PrivateChannel('patient.' . $this->testResult->laboratoryRequestTest->laboratoryRequest->visit->patient_id);
    }

    public function broadcastWith()
    {
        return [
            'id' => $this->testResult->id,
            'test_name' => $this->testResult->laboratoryRequestTest->test->test_name,
            'process_status' => $this->testResult->process_status,
            'verify_status' => $this->testResult->verify_status,
            // Add other fields you want to send
        ];
    }
}
