<?php

namespace App\Interfaces;

interface Queue
{
	public function push($item);
	public function pull();
}
