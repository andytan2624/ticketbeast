<?php

use App\Concert;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ViewConcertListingTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function user_can_view_a_published_concert_listing()
    {
        // arrange
        // create a concert
        $concert = factory(Concert::class)->states('published')->create([
            'title' => 'Jenny\'s Tang',
            'subtitle' => 'With Animosity and Theaberton',
            'date' => Carbon::parse('February 24, 1987 12:12pm'),
            'ticket_price' => 3250,
            'venue' => 'The Most Pit',
            'venue_address' => '123 Example Lane',
            'city' => 'Laraville',
            'state' => 'ON',
            'zip' => '17916',
            'additional_information' => 'For tickets, call (555) 555-55555',
        ]);

        // act
        // view the concert listing

        $response = $this->get('/concerts/'.$concert->id);


        // assert
        // see the concert details

        $response->assertSee('Tang');
        $response->assertSee('Animosity and');
        $response->assertSee('February 24, 1987');
        $response->assertSee('12:12pm');
        $response->assertSee('123 Example Lane');
        $response->assertSee('Laraville');
        $response->assertSee('17916');
        $response->assertSee('For tickets,');
    }

    /** @test */
    function user_cannot_view_unpublished_concert_listings()
    {
        $concert = factory(Concert::class)->states('unpublished')->create();

        $response = $this->get('/concerts/'.$concert->id);

        $response->assertStatus(404);
    }

    /** @test */
    function concerts_with_a_published_at_date_are_published()
    {
        $publishedConcertA = factory(Concert::class)->create(['published_at' => Carbon::parse('-1 week')]);
        $publishedConcertB = factory(Concert::class)->create(['published_at' => Carbon::parse('-1 week')]);
        $unpublishedConcert = factory(Concert::class)->create(['published_at' => null]);

        $publishedConcerts = Concert::published()->get();

        $this->assertTrue($publishedConcerts->contains($publishedConcertA));
        $this->assertTrue($publishedConcerts->contains($publishedConcertB));
        $this->assertFalse($publishedConcerts->contains($unpublishedConcert));

    }

}
