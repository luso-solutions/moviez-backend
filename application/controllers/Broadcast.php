<?php
require APPPATH . 'libraries/REST_Controller.php';

class Broadcast extends REST_Controller
{
     public function __construct()
     {
          parent::__construct();

          header("Access-Control-Allow-Origin:*");
          header("Access-Control-Allow-Methods:GET,POST");
          header("Access-Control-Allow-Headers:*");
     }


     function index_post()
	{
          $answer = $this->auth($this->post('token'));
          if ($answer == "0")
          {
               $this->unauthorized();
          }
          else
          {
               if (isset($_POST['movie']))
               {
                    $ray = array();
                    $movie = $_POST['movie'];
                    $genres = $this->getsinglefield("select genres as res from movies where name = '$movie'");
                    $genrex = explode(",",$genres);
                    if (count($genrex) > 0)
                    {
                         foreach($genrex as $genre)
                         {
                              $ray[] = "genres like '%$genre%'";
                         }
                    }

                    $param = implode(" or ", $ray);

                    $sql = "Select phone from registry where $param";
                    $data = $this->db->query($sql)->result();

                    if ($data)
                    {
                         foreach($data as $r)
                         {
                              $mobiles[] = $r->phone;
                         }

                         $cnt = count($mobiles);

                         $this->sendSMS($mobiles, $movie);
                    }


                    $this->response([
                        'status' => true,
                        'message' => "SMS sent to $cnt patrons"
                   ], REST_Controller::HTTP_OK);
               }
               else
               {
                    $this->response([
                        'status' => false,
                       'message' => 'Please provide movie'
                    ], REST_Controller::HTTP_BAD_REQUEST);
               }
     	}
	}



     function sendSMS($mobiles, $movie)
     {
          $date = $this->getsinglefield("select date as res from movies where name = '$movie'");
          $time = $this->getsinglefield("select time as res from movies where name = '$movie'");
          $rating = $this->getsinglefield("select rating as res from movies where name = '$movie'");
          $genres = $this->getsinglefield("select genres as res from movies where name = '$movie'");
          $starring = $this->getsinglefield("select starring as res from movies where name = '$movie'");


          $numbers = array();

          $message = "Dear Patron, we have a movie you might like, " . strtoupper($movie) . " (" . $rating . ") on " . changetoview($date) . " at " . $time . " hours starring " . ucfirst($starring) . " book your seat now, Pepsi and popcorn on the house";
          // echo $message;
          // die();


          if (count($mobiles) > 0)
          {
               $i = 1;
               foreach($mobiles as $mobileno)
               {
                    $numbers[] = array('recipient_id' => $i, 'dest_addr'=>$mobileno);
                    $i++;
               }

               //print_r($numbers);
               //die();

               //.... replace <api_key> and <secret_key> with the valid keys obtained from the platform, under profile>authentication information
               $api_key='41ef72dce31eb561';
               $secret_key = 'NDcyMjllM2UzN2NmZWQ5NzY1NzE2Y2YwYTIwYjM4ODM3YzI5YmQ5NzIzMGFmZDNiYWMyOWEyODg3OTA2OTE0Mw';

               // The data to send to the API
               $postData = array(
               'source_addr' => 'BlackGold',
               'encoding'=>0,
               'schedule_time' => '',
               'message' => $message,
               'recipients' => $numbers
               );


               //print_r($postData);
               //die();

               //.... Api url
               $Url ='https://apisms.bongolive.africa/v1/send';


               // Setup cURL
               $ch = curl_init($Url);
               // error_reporting(E_ALL);
               // ini_set('display_errors', 1);
               curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
               curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
               curl_setopt_array($ch,

               array(
                    CURLOPT_POST => TRUE,
                    CURLOPT_RETURNTRANSFER => TRUE,
                    CURLOPT_HTTPHEADER => array(
                    'Authorization:Basic ' . base64_encode("$api_key:$secret_key"),
                    'Content-Type: application/json'
               ),

               CURLOPT_POSTFIELDS => json_encode($postData)
               ));

               // Send the request
               $response = curl_exec($ch);
               $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

               $res = json_decode($response);

               //print_r($res);
               //echo $httpcode;

               //die();

               $error = curl_error($ch);

               curl_close($ch);

               // Check for errors
               if($response === FALSE)
               {

                    $data = array(
                         'response' => $response,
                         'error' => $error,
                         'httpcode' => $httpcode
                    );

                    $this->response([
                         'status' => false,
                         'data' => $data,
                         'message' => 'Something went wrong'
                    ], REST_Controller::HTTP_BAD_REQUEST);
               }
               else
               {
                    $this->response([
                         'status' => True,
                         'message' => "Messages sent successfully"
                    ], REST_Controller::HTTP_OK);
               }
          }
     }


     function getsinglefield($sql)
     {
          $query = $this->db->query($sql);
          if ($query->num_rows() > 0)
          {
               $ans = $query->result();
               foreach($ans as $a)
               {
                    $res = $a->res;
               }
          }
          else
          {
               $res = "";
          }

          return $res;
     }

     function auth($token)
	{
          $this->load->model('Auth_model');
          $answer = $this->Auth_model->authenticate($token);
          return $answer;
	}

     function unauthorized()
     {
          $this->response([
             'status' => False,
             'message' => 'Unauthorized'
          ], REST_Controller::HTTP_UNAUTHORIZED);
     }
}
