<?php
class Seeder extends CI_Controller
{
     public function index()
     {
          $faker = Faker\Factory::create();

          $options = array("+255755304053", "+255621142884", "+255754481744" , "+255782481744", "+255787880087", "+255756880087", "+255764021233", "+255758589166");
          $optionz = array("Action,Thriller", "Action,Comedy,Thriller,Drama" , "Action,Horror", "Comedy", "Fiction,Comedy", "Action,Fiction", "Drama,Action,Horror", "Comedy,Thriller,Drama");


          $query = "delete from registry";
          $this->db->query($query);


          foreach(range(1,4) as $x)
          {
               $phoneno = $options[array_rand($options, 1)];
               $genres = $optionz[array_rand($optionz, 1)];

               $datas = array(
                    'name' => $faker->name,
                    'dob' => $faker->date($format = 'Y-m-d'),
                    'phone' => $phoneno,
                    'email' => $faker->email,
                    'genres' => $genres
               );

               $this->db->insert('registry', $datas);
          }

          echo "<p>Successful</p>";
     }
}
