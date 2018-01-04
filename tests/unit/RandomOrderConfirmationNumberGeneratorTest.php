<?php

use App\RandomOrderConfirmationNumberGenerator;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class RandomOrderConfirmationNumberGeneratorTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function must_be_24_characters_long()
    {
        $generator = new RandomOrderConfirmationNumberGenerator();

        $confirmationNumber = $generator->generate();

        $this->assertEquals(24, strlen($confirmationNumber));
    }

    /** @test */
    function can_only_contain_letters_and_numbers()
    {
        $generator = new RandomOrderConfirmationNumberGenerator();

        $confirmationNumber = $generator->generate();

        $this->assertRegExp('/^[A-Z0-9]+$/', $confirmationNumber);
    }

    /** @test */
    function cannot_contain_ambigious_characters()
    {
        $generator = new RandomOrderConfirmationNumberGenerator();

        $confirmationNumber = $generator->generate();

        $this->assertFalse(strpos($confirmationNumber, '1'));
    }


    /** @test */
    function confirmation_numebrs_must_be_unique()
    {
        $generator = new RandomOrderConfirmationNumberGenerator();

        $confirmationNumbers = array_map(function ($i) use ($generator) {
            return $generator->generate();
        }, range(1,100));
        
        $this->assertCount(100, array_unique($confirmationNumbers));


    }
}
