<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\RandomTicketCodeGenerator;

class RandomTicketCodeGeneratorTestTest extends TestCase
{
  /** @test */
  function must_be_8_characters_long()
  {
    $generator = new RandomTicketCodeGenerator;
    $code = $generator->generate();

    $this->assertEquals(8, strlen($code));
  }

  /** @test */
  function all_letters_in_code_must_be_uppercase_characters_and_numbers()
  {
    $generator = new RandomTicketCodeGenerator;
    $code = $generator->generate();

    $this->assertRegExp('/^[A-Z0-9]+$/', $code);

  }

  /** @test */
  function cannot_contain_special_characters_and_ambiguous_letters()
  {
    $generator = new RandomTicketCodeGenerator;
    $code = $generator->generate();

    $this->assertFalse(strpos($code, '1'));
    $this->assertFalse(strpos($code, 'I'));
    $this->assertFalse(strpos($code, '0'));
    $this->assertFalse(strpos($code, 'o'));
    $this->assertFalse(strpos($code, 'O'));
    $this->assertFalse(strpos($code, '!'));
    $this->assertFalse(strpos($code, '@'));
    $this->assertFalse(strpos($code, '#'));
    $this->assertFalse(strpos($code, '$'));
    $this->assertFalse(strpos($code, '&'));
    $this->assertFalse(strpos($code, '*'));
    $this->assertFalse(strpos($code, '%'));
  }

  /** @test */
  function code_must_be_unique()
  {
    $generator = new RandomTicketCodeGenerator;

    $code = array_map(function($i) use($generator) {
        return $generator->generate();
    }, range( 1, 100));

    $this->assertEquals(100, count(array_unique($code)));

  }
}
