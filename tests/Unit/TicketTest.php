<?php

namespace Tests\Unit;

use Carbon\Carbon;
use App\Movie;
use App\Ticket;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TicketTest extends TestCase
{
  use RefreshDatabase;


  /** @test */
  function a_ticket_can_be_reserved()
  {
    $ticket = factory(Ticket::class)->create();
    $this->assertNull($ticket->reserved_at);

    $ticket->reserve();

    $this->assertNotNull($ticket->fresh()->reserved_at);
  }

  // /** @test */
  // function a_ticket_can_be_released()
  // {
  //   $movie = factory(Movie::class)->create();
  //   $movie->addTickets(1);
  //   $order = $movie->orderTickets('jane@example.com', 1);
  //   $ticket = $order->tickets()->first();
  //   $this->assertEquals($order->id, $ticket->order_id);
  //
  //   $ticket->release();
  //   $this->assertNull($ticket->fresh()->order_id);
  // }

  /** @test */
  function a_ticket_can_be_released()
  {
    $ticket = factory(Ticket::class)->states('reserved')->create();
    $this->assertNotNull($ticket->reserved_at);

    $ticket->release();

    $this->assertNull($ticket->fresh()->reserved_at);
  }
}
