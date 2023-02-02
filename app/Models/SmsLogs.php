<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SmsLogs extends Model
{
    protected $fillable = [
        'to', 'message', 'company_id', 'created_at',
    ];
}
