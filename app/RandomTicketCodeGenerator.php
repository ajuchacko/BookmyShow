<?php

namespace App;
use App\TicketCodeGenerator;

class RandomTicketCodeGenerator implements TicketCodeGenerator {

  public function generate()
  {
    $pool = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
    return substr(str_shuffle(str_repeat($pool, 24)), 0, 8);
  }
}
