<?php

namespace Tests\Unit;

use App\Order;
use App\Movie;
use App\Ticket;
use Carbon\Carbon;
use Tests\TestCase;
use App\Exceptions\NotEnoughTicketsException;

use Illuminate\Foundation\Testing\RefreshDatabase;

class MovieTest extends TestCase
{
  use RefreshDatabase;

  /** @test */
  function can_get_formatted_date()
  {
    $movie = factory(Movie::class)->make([
      'release_date' => Carbon::parse('1994-06-23'),
    ]);

    $this->assertEquals('23 June, 1994', $movie->formatted_date);
  }

  /** @test */
  function can_get_duration_in_hours()
  {
    $movie = factory(Movie::class)->make([
      'duration' => 12600
    ]);

    $this->assertEquals('3hrs 30m', $movie->duration);
  }

  /** @test */
  function can_get_ticket_price_in_dollars()
  {
    $movie = factory(Movie::class)->create([
      'ticket_price' => 6750,
    ]);
// dd($movie->ticket_price_in_dollars);
    $this->assertEquals('67.50', $movie->ticket_price_in_dollars);
  }

  /** @test */
  function movies_with_a_published_at_date_are_published()
  {
    $publishedMovieA = factory(Movie::class)->create(['published_at' => Carbon::parse('-2 week')]);
    $publishedMovieB = factory(Movie::class)->create(['published_at' => Carbon::parse('-2 week')]);
    $unpublishedMovie = factory(Movie::class)->create(['published_at' => null]);

    // $publishedMovies = Movie::published()->get();

    // $this->assertTrue($publishedMovies->contains($publishedMovieA));
    // $this->assertTrue($publishedMovies->contains($publishedMovieB));
    // $this->assertFalse($publishedMovies->contains($unpublishedMovie));

    $responseA = $this->get('movies/'.$publishedMovieA->id);
    $responseB = $this->get('movies/'.$publishedMovieB->id);
    $responseUn = $this->get('movies/'.$unpublishedMovie->id);

    $responseA->assertStatus(200);
    $responseB->assertStatus(200);
    $responseUn->assertStatus(404);
  }
  //
  // /** @test */
  // function can_order_movie_tickets()
  // {
  //   $movie = factory('App\Movie')->create();
  //   $movie->addTickets(3);
  //
  //   $order = $movie->orderTickets('jane@example.com', 3);
  //   $this->assertEquals('jane@example.com', $order->email);
  //   $this->assertEquals(3, $order->tickets()->count());
  // }



  /** @test */
  function can_add_tickets()
  {
    $movie = factory('App\Movie')->create();
    $movie->addTickets(50);
    $this->assertEquals(50,$movie->ticketsRemaining());
  }

  /** @test */
  function tickets_remaning_does_not_include_tickets_associated_with_an_order()
  {
    $movie = factory(Movie::class)->create();
    // $movie->addTickets(50);
    // $movie->orderTickets('jane@example.com', 30);
    $movie->tickets()->saveMany(factory(Ticket::class, 30)->create(['order_id' => 1]));
    $movie->tickets()->saveMany(factory(Ticket::class, 20)->create(['order_id' => null]));

    $this->assertEquals(20, $movie->ticketsRemaining());
  }

  /** @test */
  function trying_to_reserve_more_tickets_than_remain_throws_an_exception()
  {
    $movie = factory(Movie::class)->create();
    $movie->addTickets(10);
    try {
      $reservation = $movie->reserveTickets(11, 'jane@example.com');
      // $movie->orderTickets('jane@example.com',11);
    } catch(NotEnoughTicketsException $e) {
      $order = $movie->orders()->where('email', 'jane@example.com')->first();
      $this->assertNull($order);
      $this->assertEquals(10, $movie->ticketsRemaining());
      return ;
    }
    $this->fail('Order Succeedeed even though there were not enough tickets available');
  }

  // /** @test */
  // function cannot_order_tickets_that_have_already_been_purchased()
  // {
  //   $movie = factory(Movie::class)->create();
  //   $movie->addTickets(10);
  //   $movie->orderTickets('jane@example.com',8);
  //
  //
  //   try {
  //     $movie->orderTickets('john@example.com',3);;
  //   } catch(NotEnoughTicketsException $e) {
  //     $johnsorder = $movie->orders()->where('email', 'john@example.com')->first();
  //     $this->assertNull($johnsorder);
  //     $this->assertEquals(2, $movie->ticketsRemaining());
  //     return ;
  //   }
  //   $this->fail('Order Succeedeed even though there were not enough tickets available');
  // }


  /** @test */
  function can_reserve_available_tickets()
  {
    $movie = factory(Movie::class)->create();
    $movie->addTickets(3);

    $reservation = $movie->reserveTickets(2, 'john@example.com');

    $this->assertCount(2, $reservation->tickets());
    $this->assertEquals('john@example.com', $reservation->email());
    $this->assertEquals(1, $movie->ticketsRemaining());
  }

  /** @test */
  function cannot_reserve_tickets_that_have_already_been_purchased()
  {
    $movie = factory(Movie::class)->create()->addTickets(3);
    $order = factory(Order::class)->create();
    $order->tickets()->saveMany($movie->tickets->take(2));
    // $movie->orderTickets('jane@example.com', 2);

    try {
      $movie->reserveTickets(2, 'john@example.com');
    } catch(NotEnoughTicketsException $e) {
      $this->assertEquals(1, $movie->ticketsRemaining());
      return ;
    }

    $this->fail('Reserving tickets succeeded even thought the tickets were already sold.');
  }

  /** @test */
  function cannot_reserve_tickets_that_have_already_been_reserved()
  {
    $movie = factory(Movie::class)->create()->addTickets(3);
    $movie->reserveTickets(2, 'jane@example.com');

    try {
      $movie->reserveTickets(2, 'john@example.com');
    } catch(NotEnoughTicketsException $e) {
      $this->assertEquals(1, $movie->ticketsRemaining());
      return ;
    }

    $this->fail('Reserving tickets succeeded even thought the tickets were already reserved.');
  }

}
