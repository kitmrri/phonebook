<?php

namespace App\Implementations;

use App\Interfaces\Queue;
use App\Models\SmsLog;
use Carbon\Carbon;

class QueueAdder
{
  private $queue;

  public function __construct(Queue $queue)
  {
    $this->queue = $queue;
  }

  public function addToQueue($employees, $message)
  {
    foreach ($employees as $employee) {
      $this->queue->push([
        'to' => $employee->phone_number,
        'message' => $message
      ]);

      $this->sendAndLog($employee->phone_number, $message);
    }
  }

  public function sendAndLog()
  {
    $queue = $this->queue->pull();
    foreach ($queue as $item) {
      $this->mockSend($item['to'], $item['message']);
      $this->insertIntoSmsLog($item['to'], $item['message']);
    }
  }

  private function mockSend($to, $message)
  {
    echo "Sending message to {$to}: {$message}\n";
    sleep(1);
  }

  private function insertIntoSmsLog($to, $message)
  {
    $smsLog = new SmsLog;
    $smsLog->to = $to;
    $smsLog->message = $message;
    $smsLog->sent_at = Carbon::now();
    $smsLog->save();
  }

}
