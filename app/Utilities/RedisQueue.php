<?php

namespace App\Utilities;

use Illuminate\Support\Facades\Redis;
use App\Interfaces\Queue;
use App\Models\SmsLog;
use Carbon\Carbon;

class RedisQueue implements Queue
{
  public function push($item)
  {
    Redis::lpush('sms_queue', $item);
  }

  public function pull()
  {
    return Redis::lpop('sms_queue');
  }
}