<?php


namespace App;


class Reservation
{
    private $tickets;
    private $email;
    /**
     * Reservation constructor.
     */
    public function __construct($tickets, $email)
    {
        $this->tickets = $tickets;
        $this->email = $email;
    }

    public function totalCost() {
        return $this->tickets->sum('price');
    }

    public function tickets() {
        return $this->tickets;
    }

    public function complete($paymentGateway, $paymentToken) {
        $paymentGateway->charge($this->totalCost(), $paymentToken);

        return Order::forTickets($this->tickets(), $this->email(), $this->totalCost(), $cardLastFour);
    }

    public function cancel() {
        foreach ($this->tickets as $ticket) {
            $ticket->release();
        }
    }

    public function email() {
        return $this->email;
    }
}