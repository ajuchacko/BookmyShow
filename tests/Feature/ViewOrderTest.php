<?php

namespace Tests\Feature;

use App\Movie;
use App\Order;
use App\Ticket;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ViewOrderTest extends TestCase
{
  use RefreshDatabase;

  /** @test */
  function user_can_view_their_order_confirmation()
  { $this->disableEx( );
    $movie = factory(Movie::class)->create();
    $order = factory(Order::class)->create([
      'confirmation_number' => 'ORDERCONFIRMATION1234',
      'card_last_four' => '1881',
      'amount' => 8500,
    ]);
    $ticketA = factory(Ticket::class)->create([
      'movie_id' => $movie->id,
      'order_id' => $order->id,
      'code' => 'TICKETCODE123',
    ]);
    $ticketB = factory(Ticket::class)->create([
      'movie_id' => $movie->id,
      'order_id' => $order->id,
      'code' => 'TICKETCODE456',
    ]);


    $response = $this->get('orders/ORDERCONFIRMATION1234');

    $response->assertStatus(200);

    $response->assertViewHas('order', function($viewOrder) use ($order) {
      return $viewOrder->id === $order->id;
    });

    $response->assertSee('ORDERCONFIRMATION1234');
    $response->assertSee('$85.00');
    $response->assertSee('**** **** **** 1881');
    $response->assertSee('TICKETCODE123');
    $response->assertSee('TICKETCODE456');

    $response->assertSee($movie->name);
    // $response->assertSee($movie->image);
    $response->assertSee($movie->genre);
    $response->assertSee($movie->language);
    $response->assertSee($movie->duration);
    // $response->assertSee($movie->ticket_price);

  }
}
