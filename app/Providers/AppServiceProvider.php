<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Billing\PaymentGateway;
use App\Billing\StripePaymentGateway;
use App\RandomTicketCodeGenerator;
use App\RandomOrderConfirmationNumberGenerator;
use App\OrderConfirmationNumberGenerator;
use App\TicketCodeGenerator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(StripePaymentGateway::class, function() {
          return new StripePaymentGateway(config('services.stripe.secret'));
        });

        $this->app->bind(PaymentGateway::class, StripePaymentGateway::class);
        $this->app->bind(OrderConfirmationNumberGenerator::class, RandomOrderConfirmationNumberGenerator::class);
        $this->app->bind(TicketCodeGenerator::class, RandomTicketCodeGenerator::class);
    }
}
