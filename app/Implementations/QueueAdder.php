<?php

namespace App\Implementations;

use App\Interfaces\Queue;
use App\Models\SmsLogs;
use Carbon\Carbon;

class QueueAdder
{
  private $queue;

  public function __construct(Queue $queue)
  {
    $this->queue = $queue;
  }

  public function addToQueue($employees, $message, $company_id)
  {
    foreach ($employees as $employee) {
      $this->queue->push([
        'to' => $employee->phone_number,
        'message' => $message,
        'company_id' => $company_id
      ]);

      $this->sendAndLog();
    }
  }

  public function sendAndLog()
  {
    $queue = $this->queue->pull();
    $this->mockSend($queue['to'], $queue['message']);
    $this->insertIntoSmsLog($queue['to'], $queue['message'], $queue['company_id']);
  }

  private function mockSend($to, $message)
  {
    echo "Sending message to {$to}: {$message}\n";
    sleep(1);
  }

  private function insertIntoSmsLog($to, $message, $company_id)
  {
    $smsLog = new SmsLogs;
    $smsLog->to = $to;
    $smsLog->message = $message;
    $smsLog->company_id = $company_id;
    $smsLog->created_at = Carbon::now();
    $smsLog->save();
  }

}
