<?php


use App\Concert;
use App\Order;
use App\Reservation;
use App\Ticket;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class OrderTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function creating_an_order_from_tickets_and_email_and_amount()
    {
        $concert = factory(Concert::class)->create()->addTickets(5);
        $this->assertEquals(5, $concert->ticketsRemaining());
        $order = Order::forTickets($concert->findTickets(3), 'john@example.com', 3600);
        $this->assertEquals('john@example.com', $order->email);
        $this->assertEquals(3, $order->ticketQuantity());
        $this->assertEquals(3600, $order->amount);
        $this->assertEquals(2, $concert->ticketsRemaining());

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
    function retrieving_a_nonexistent_order_by_confirmation_number_throws_an_exception() {
        try {
            Order::findByConfirmationNumber('NONEXISTENTNCONFIRMATIONNUMBER');
        } catch (ModelNotFoundException $e) {
            return;
        }
        $this->fail('No matching order was found for the specified confirmation number, but an exception was not thrown');
    }

    ///** @test */
    //function converting_to_an_array()
    //{
    //
    //    $order = factory(Order::class)->create([
    //        'email' => 'jane@example.com',
    //        'amount' => 6000,
    //    ]);
    //
    //    $order->tickets()->saveMany(factory(Ticket::class)->times(5)->create());
    //
    //    $result = $order->toArray();
    //
    //    $this->assertEquals([
    //        'email' => 'john@example.com',
    //        'ticket_quantity' => 5,
    //        'amount' => 10000
    //    ], $result);
    //}


}