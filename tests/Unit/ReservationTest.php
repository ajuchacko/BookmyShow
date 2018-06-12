<?php

namespace Tests\Unit;
use App\Ticket;
use App\Movie;
use App\Reservation;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use App\Billing\FakePaymentGateway;
class ReservationTest extends TestCase
{
  use RefreshDatabase;

  /** @test */
  function calculating_the_total_cost()
  {
    // $movie = factory(Movie::class)->create(['ticket_price' => 1200])->addTickets(3);
    // $tickets = $movie->findTickets(3);

    $tickets = collect([
      (object) ['price' => 1200],
      (object) ['price' => 1200],
      (object) ['price' => 1200]
    ]);

    $reservation = new Reservation($tickets, 'john@example.com');

    $this->assertEquals(3600, $reservation->totalCost());
  }

  /** @test */
  function retrieving_the_reservation_tickets()
  {
    $tickets = collect([
      (object) ['price' => 1200],
      (object) ['price' => 1200],
      (object) ['price' => 1200]
    ]);

    $reservation = new Reservation($tickets, 'john@example.com');
    $this->assertEquals($tickets, $reservation->tickets());
  }

  /** @test */
  function retrieving_the_customers_email()
  {
    $reservation = new Reservation(collect(), 'john@example.com');;
    $this->assertEquals('john@example.com', $reservation->email());
  }

  /** @test */
  function reserved_tickets_are_released_when_a_reservation_is_cancelled()
  {
    // $ticket1 = \Mockery::mock(Ticket::class);
    // $ticket1->shouldReceive( 'release')->once();

    $tickets = collect([
        Mockery::spy(Ticket::class),
        Mockery::spy(Ticket::class),
        Mockery::spy(Ticket::class),
     ]);

    $reservation = new Reservation($tickets, 'john@example.com');

    $reservation->cancel();

    foreach($tickets as $ticket) {
      $ticket->shouldHaveReceived('release');
    }

  }

  /** @test */
  function completing_a_reservation()
  {
    $movie = factory(Movie::class)->create(['ticket_price' => 1200]);
    $tickets = factory(Ticket::class, 3)->create(['movie_id' => $movie->id]);
    $reservation = new Reservation($tickets, 'john@example.com');
    $paymentGateway = new FakePaymentGateway;

    $order = $reservation->complete($paymentGateway, $paymentGateway->getValidTestToken());

    $this->assertEquals('john@example.com', $order->email);
    $this->assertEquals(3, $order->ticketQuantity());
    $this->assertEquals(3600, $order->amount);
    $this->assertEquals(3600, $paymentGateway->totalCharges());
  }
}
