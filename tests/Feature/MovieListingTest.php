<?php

namespace Tests\Feature;

use App\Movie;
use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MovieListingTest extends TestCase
{
  use RefreshDatabase;

  /** @test */
  function user_can_view_a_published_movie_listing()
  {
    $movie = factory(Movie::class)->states('published')->create([
      'name' => 'Forest Gump',
      'image' => 'images/forrestGump.jpg',
      'trailer' => 'https://www.youtube.com/watch?v=uPIEn0M8su0',
      'language' => 'English',
      'genre' => 'Drama',
      'ticket_price' => 3250,
      'release_date' => Carbon::parse('23 June, 1994'),
      'duration' => '8520',
      'summary' => 'The presidencies of Kennedy and Johnson, Vietnam, Watergate, and other history unfold through the perspective of an Alabama man with an IQ of 75.',
    ]);

    $response = $this->get('movies/'.$movie->id);

    $response->assertStatus(200);
    $response->assertSee('Forest Gump');
    $response->assertSee('images/forrestGump.jpg');
    $response->assertSee('https://www.youtube.com/watch?v=uPIEn0M8su0');
    $response->assertSee('English');
    $response->assertSee('Drama');
    $response->assertSee('32.50');
    $response->assertSee('23 June, 1994');
    $response->assertSee('2hrs 22m');
    $response->assertSee('The Presidencies Of Kennedy And Johnson, Vietnam, Watergate, And Other History Unfold Through The Perspective Of An Alabama Man With An IQ Of 75.');

  }

  /** @test */
  function user_cannot_view_unpublished_movie_listing()
  {
    $movie = factory(Movie::class)->states('unpublished')->create();

    $response = $this->get('movies/'.$movie->id);

    $response->assertNotFound();
  }
}
