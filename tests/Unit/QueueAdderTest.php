<?php

use App\Models\Employee;
use App\Models\SmsLog;
use App\Implementations\QueueAdder;
use App\Utilities\RedisQueue;
use Tests\TestCase;

class QueueAdderTest extends TestCase
{
    public function testAddToQueue()
    {
      $employees = factory(Employee::class, 2)->create();
      $message = 'Test message';

      $queueAdder = new QueueAdder(new RedisQueue());
      $queueAdder->addToQueue($employees, $message);

      // Check that the correct SMS was added to the queue for each employee
      foreach ($employees as $employee) {
        $expected = [
          'to' => $employee->phone_number,
          'message' => $message
        ];
        $this->assertEquals($expected, Redis::lpop('sms_queue'));
      }
    }

    public function testSendAndLog()
    {
      $employee = factory(Employee::class)->create();
      $message = 'Test message';

      $queueAdder = new QueueAdder(new RedisQueue());
      $queueAdder->addToQueue([$employee], $message);

      $queueAdder->sendAndLog();

      // Check that the SMS was sent
      $this->expectOutputString("Sending message to {$employee->phone_number}: {$message}\n");

      // Check that the SMS was logged
      $smsLog = SmsLog::first();
      $this->assertEquals($employee->phone_number, $smsLog->to);
      $this->assertEquals($message, $smsLog->message);
    }
}