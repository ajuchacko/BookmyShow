<?php

namespace Tests\Feature;

use Mockery;
use App\Movie;
use Tests\TestCase;
use App\Billing\PaymentGateway;
use App\Billing\FakePaymentGateway;
use App\OrderConfirmationNumberGenerator;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PurchaseTicketTest extends TestCase
{
  use  RefreshDatabase;

  protected function setUp()
  {
    parent::setUp();
    $this->paymentGateway = new FakePaymentGateway;
    $this->app->instance(PaymentGateway::class, $this->paymentGateway);

  }


  private function orderTickets($movie, $params) {
    return $this->json('POST', "/movies/{$movie->id}/orders", $params);
  }




  /** @test */
  function customer_can_purchase_tickets_to_a_published_movie()
  {
$this->disableEx();
    $movie = factory(Movie::class)->states('published')->create(['ticket_price' => 3250])->addTickets(3);
    // $movie->addTickets(3);

$orderConfirmationNumberGenerator = Mockery::mock(OrderConfirmationNumberGenerator::class, [
  'generate' => 'ORDERCONFIRMATION1234'
]);

$this->app->instance(OrderConfirmationNumberGenerator::class, $orderConfirmationNumberGenerator);

    //purchse movie tickets
    $response = $this->orderTickets($movie,[
      'email' => 'john@example.com',
      'ticket_quantity' => 3,
      'payment_token' => $this->paymentGateway->getValidTestToken(),
    ]);

    $response->assertStatus(201);

    $response->assertJsonFragment([
      'confirmation_number' => 'ORDERCONFIRMATION1234',
      'email' => 'john@example.com',
      'ticket_quantity' => 3,
      'amount' => 9750,
    ]);

    //correct Charge
    $this->assertEquals(9750, $this->paymentGateway->totalCharges());
    // order made or exists
    $order = $movie->orders()->where('email', 'john@example.com')->first();
    $this->assertNotNull($order);
    $this->assertEquals(3, $order->tickets()->count());
  }

  /** @test */
  function an_order_is_not_created_if_payment_fails()
  {
    $movie = factory(Movie::class)->states('published')->create(['ticket_price' => 3250]);
    $movie->addTickets(3);

    $response = $this->orderTickets($movie,[
      'email' => 'john@example.com',
      'ticket_quantity' => 3,
      'payment_token' => 'invalid-payment-token',
    ]);

    $response->assertStatus(422);
    $order = $movie->orders()->where('email', 'john@example.com')->first();
    $this->assertNull($order);
    $this->assertEquals(3, $movie->ticketsRemaining());
  }



  /** @test */
  function cannot_purchase_more_tickets_than_remain()
  {
    $movie = factory(Movie::class)->states('published')->create();
    $movie->addTickets(50);

    $response = $this->orderTickets($movie,[
      'email' => 'john@example.com',
      'ticket_quantity' => 51,
      'payment_token' => $this->paymentGateway->getValidTestToken(),
    ]);

    $response->assertStatus(422);
    $order = $movie->orders()->where('email', 'john@example.com')->first();
    $this->assertNull($order);
    $this->assertEquals(0, $this->paymentGateway->totalCharges());
    $this->assertEquals(50, $movie->ticketsRemaining());
  }


  /** @test */
  function cannot_purchase_tickets_another_customer_is_already_trying_to_purchase()
  {
    $movie = factory(Movie::class)->states('published')->create(['ticket_price' => 1200])->addTickets(3);

// PERSON B
// $this->disableEx();
    $this->paymentGateway->beforeFirstCharge(function ($paymentGateway) use($movie) {
      $savedRequest = $this->app['request'];

      $response = $this->orderTickets($movie,[
        'email' => 'personB@example.com',
        'ticket_quantity' => 1,
        'payment_token' => $this->paymentGateway->getValidTestToken(),
      ]);

          $response->assertStatus(422);
          $this->assertFalse($movie->hasOrderFor('personB@example.com'));
          $this->assertEquals(0, $this->paymentGateway->totalCharges());


          $this->app['request'] = $savedRequest;
    });


// PERSON A
    $this->orderTickets($movie,[
      'email' => 'personA@example.com',
      'ticket_quantity' => 3,
      'payment_token' => $this->paymentGateway->getValidTestToken(),
    ]);

    $this->assertEquals(3600, $this->paymentGateway->totalCharges());
    $this->assertTrue($movie->hasOrderFor('personA@example.com'));
    $this->assertEquals(3, $movie->ordersFor('personA@example.com')->first()->ticketQuantity());
  }



  /** @test */
  function cannot_purchase_tickets_to_an_unpublished_movie()
  {
    $movie = factory(Movie::class)->states('unpublished')->create();
    $movie->addTickets(3);

    $response = $this->orderTickets($movie,[
      'email' => 'john@example.com',
      'ticket_quantity' => 3,
      'payment_token' => $this->paymentGateway->getValidTestToken(),
    ]);

    $response->assertStatus(404);
    $this->assertEquals(0, $movie->orders->count());
    $this->assertEquals(0, $this->paymentGateway->totalCharges());
  }
















  /** @test */
  function email_is_required_to_purchase_tickets()
  {
      $movie = factory(Movie::class)->states('published')->create();

      $response = $this->orderTickets($movie,[
        'ticket_quantity' => 3,
        'payment_token' => $this->paymentGateway->getValidTestToken(),
      ]);

      $response->assertStatus(422);
      $response->assertJsonValidationErrors('email');
      // dd($response->decodeResponseJson());
  }




  /** @test */
  function email_must_be_valid_to_purchase_tickets()
  {
    $movie = factory(Movie::class)->states('published')->create();

    $response = $this->orderTickets($movie,[
      'email' => 'not-an-email-address',
      'ticket_quantity' => 3,
      'payment_token' => $this->paymentGateway->getValidTestToken(),
    ]);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors('email');
  }





  /** @test */
  function ticket_quantity_is_required_to_purchase_tickets()
  {
    $movie = factory(Movie::class)->states('published')->create();

    $response = $this->orderTickets($movie,[
      'email' => 'not-an-email-address',
      'payment_token' => $this->paymentGateway->getValidTestToken(),
    ]);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors('ticket_quantity');
  }




  /** @test */
  function ticket_quantity_must_be_at_least_1_to_purchase_tickets()
  {
    $movie = factory(Movie::class)->states('published')->create();

    $response = $this->orderTickets($movie,[
      'email' => 'not-an-email-address',
      'ticket_quantity' => 0,
      'payment_token' => $this->paymentGateway->getValidTestToken(),
    ]);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors('ticket_quantity');
  }




  /** @test */
  function payment_token_is_required()
  {
    $movie = factory(Movie::class)->states('published')->create();

    $response = $this->orderTickets($movie,[
      'email' => 'not-an-email-address',
      'ticket_quantity' => 3,
    ]);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors('payment_token');
  }



}
