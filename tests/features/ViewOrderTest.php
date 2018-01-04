<?php
use App\Concert;
use App\Order;
use App\Ticket;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ViewOrderTest extends TestCase
{

    use DatabaseMigrations;

    /** @test */
    function user_can_view_their_order_confirmation() {

        $concert = factory(Concert::class)->create();

        $order = factory(Order::class)->create([
            'confirmation_number' => 'HEWIHEIHSDFNJKSDNFW322',
            'card_last_four' => 1080,
            'amount' => 8500
        ]);

        $ticket = factory(Ticket::class)->create([
            'concert_id' => $concert->id,
            'order_id' => $order->id,
            'code' => 'TANG123'
        ]);

        $ticket = factory(Ticket::class)->create([
            'concert_id' => $concert->id,
            'order_id' => $order->id,
            'code' => 'TANG456'
        ]);

        // Visit the order confirmation page
        $response = $this->get("/orders/HEWIHEIHSDFNJKSDNFW322");

        //dd($response);
        $response->assertStatus(200);


        $response->assertViewHas('order', function($viewOrder) use ($order) {
            return $order->id === $viewOrder->id;
        });

        $response->assertSee('HEWIHEIHSDFNJKSDNFW322');
        $response->assertSee('$85.00');
        $response->assertSee('**** **** **** 1080');
        $response->assertSee('TANG123');
        $response->assertSee('TANG456');

        //$response->assertSee('October 16, 2011');
    }
}