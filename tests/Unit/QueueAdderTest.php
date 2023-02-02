<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Implementations\QueueAdder;
use App\Interfaces\Queue;
use App\Models\SmsLogs;
use Carbon\Carbon;

class QueueAdderTest extends TestCase
{

    public function testAddToQueue()
    {
        $queueMock = $this->createMock(Queue::class);
        $queueMock->expects($this->once())
            ->method('push')
            ->with([
                'to' => '1234567890',
                'message' => 'Test message',
                'company_id' => 2
            ]);

        $queueAdder = new QueueAdder($queueMock);
        $queueAdder->addToQueue([
            (object) [
                'phone_number' => '1234567890',
            ]
        ], 'Test message', 2);
    }

    public function testSendAndLog()
    {
        $queueMock = $this->createMock(Queue::class);
        $queueMock->expects($this->once())
            ->method('pull')
            ->willReturn([
                [
                    'to' => '1234567890',
                    'message' => 'Test message',
                    'company_id' => 2
                ]
            ]);

        $queueAdder = new QueueAdder($queueMock);
        $queueAdder->sendAndLog();

        $this->assertDatabaseHas('sms_logs', [
            'to' => '1234567890',
            'message' => 'Test message',
            'company_id' => 2
        ]);
    }
}