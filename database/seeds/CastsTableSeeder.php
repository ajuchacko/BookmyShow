<?php

use Illuminate\Database\Seeder;

class CastsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      factory(App\Cast::class, 10)->create();
//       DB::table('casts')->insert([
//             'name' => "Nivin Pauly",
//             'male' => "1",
//             'female' => "0",
//             'image' => "2018_06_12_19_49nivin-pauly.jpg",
//             'birth' => "Born: Oct 11, 1984, Aluva, Kerala, India",
//             'details' => "Nivin Pauly is an Indian actor and producer who predominantly appears in Malayalam cinema. After making his acting debut with the movie Malarvaadi Arts Club (2009), the actor went on to appeared in several lead and cameo roles. He is best known for his performance in the movie Thattathin Marayathu (2012).
//
// EARLY LIFE
// Nivin was born into a Syro-Malabar Catholic family that hailed from Aluva, Kerela. Pauly Bonaventure, Navin's father was a mechanic who worked in Switzerland, and his mother was a nurse at a Swiss hospital. HIs parents stayed in Switzerland for 25 years, and as a child, Nivin would visit them during his holidays",
//         ]);
        // DB::table('casts')->insert([
        //       'name' => "Nivin Pauly",
        //       'male' => "",
        //       'female' => "",
        //       'image' => "",
        //       'birth' => "",
        //       'details' => "",
        //   ]);
    }
}
