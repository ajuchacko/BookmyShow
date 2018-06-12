<?php

use Illuminate\Database\Seeder;

class MoviesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      factory(App\Movie::class)->states('published')->create([
        'name' => 'Forest Gump',
        'image' => 'forrestGump.jpg',
        'trailer' => 'https://www.youtube.com/embed/uPIEn0M8su0',
        'language' => 'English',
        'genre' => 'Drama',
        'ticket_price' => 3250,
        'release_date' => Carbon\Carbon::parse('23 June, 1994'),
        'duration' => '8520',
        'summary' => 'The presidencies of Kennedy and Johnson, Vietnam, Watergate, and other history unfold through the perspective of an Alabama man with an IQ of 75.',
      ])->addTickets(10);
    }
}
