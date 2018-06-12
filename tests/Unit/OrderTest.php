<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Movie;
use App\Order;
use App\Ticket;
use App\Reservation;
use App\Billing\Charge;
use App\Facades\TicketCode;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class OrderTest extends TestCase
{
  use RefreshDatabase;

  /** @test */
  function converting_to_an_array()
  {
    // $movie = factory(Movie::class)->create(['ticket_price' => 1200]);
    // $movie->addTickets(5);
    // $order = $movie->orderTickets('jane@example.com', 5);

    $order = factory(Order::class)->create([
      'confirmation_number' => 'ORDERCONFIRMATION1234',
      'email' => 'jane@example.com',
      'amount' => 6000,
    ]);

    $order->tickets()->saveMany(factory(Ticket::class)->times(5)->create());
    $result = $order->toArray();

    $this->assertEquals([
      'confirmation_number' => 'ORDERCONFIRMATION1234',
      'email' => 'jane@example.com',
      'ticket_quantity' => 5,
      'amount' => 6000,
    ], $result);

  }

  /** @test */
  // function tickets_are_released_when_an_order_is_cancelled()
  // {
  //   $movie = factory(Movie::class)->create();
  //   $movie->addTickets(10);
  //   $order = $movie->orderTickets('jane@example.com', 5);
  //   $this->assertEquals(5, $movie->ticketsRemaining());
  //
  //   $order->cancel();
  //
  //   $this->assertEquals(10, $movie->ticketsRemaining());
  //   $this->assertNull(Order::find($order->id));
  // }

  /** @test */
  function creating_an_order_from_tickets_email_and_charge()
  {
    // $movie = factory(Movie::class)->create();
    // $movie->addTickets(5);
    // $this->assertEquals(5, $movie->ticketsRemaining());


    $tickets = factory(Ticket::class, 3)->create();

    $charge = new Charge(['amount' => 3600, 'card_last_four' => '1234']);

    $order = Order::forTickets($tickets, 'john@example.com', $charge);
    $this->assertEquals('john@example.com', $order->email);
    $this->assertEquals(3, $order->ticketQuantity());
    $this->assertEquals(3600, $order->amount);
    $this->assertEquals('1234', $order->card_last_four);
  }

  /** @test */
  function retrieving_an_order_by_confirmation_number()
  {
    $order = factory(Order::class)->create([
      'confirmation_number' => 'ORDERCONFIRMATION1234',
    ]);

    $foundOrder = Order::findByConfirmationNumber('ORDERCONFIRMATION1234');

    $this->assertEquals($order->id, $foundOrder->id);
  }

  /** @test */
  function retrieving_a_nonexistent_order_by_confirmation_number_throws_an_exception()
  {
      try {
      $foundOrder = Order::findByConfirmationNumber('noneexistentnumber');
    } catch(ModelNotFoundException $e) {
      $this->assertTrue(true);
      return;
    }
    $this->fail('No matching order was found for the specified confirmation number, but an exception
        was not thrown.');
  }
  // /** @test */
  // function creating_and_order_from_a_reservation()
  // {
  //   $movie = factory(Movie::class)->create(['ticket_price' => 1200]);
  //   $tickets = factory(Ticket::class, 3)->create(['movie_id' => $movie->id]);
  //   $reservation = new Reservation($tickets, 'john@example.com');
  //
  //   $order = Order::fromReservation($reservation);
  //
  //   $this->assertEquals('john@example.com', $order->email);
  //   $this->assertEquals(3, $order->ticketQuantity());
  //   $this->assertEquals(3600, $order->amount);
  //
  // }


  /** @test */
  function an_ordered_ticket_has_ticket_code()
  {
    $order = factory(Order::class)->create([
      'confirmation_number' => 'ORDERCONFIRMATION1234',
      'card_last_four' => '1881',
      'amount' => 8500,
    ]);
    $tickets = factory(Ticket::class, 3)->create(['code' => TicketCode::generate()]);

    $charge = new Charge(['amount' => 3600, 'card_last_four' => '1234']);

    $order = Order::forTickets($tickets, 'john@example.com', $charge);
    $this->assertEquals('john@example.com', $order->email);
      foreach($order->tickets as $ticket)  {
        $this->assertNotNull($ticket->code);
      }
  }
}
