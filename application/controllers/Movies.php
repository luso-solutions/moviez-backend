<?php
require APPPATH . 'libraries/REST_Controller.php';

class Movies extends REST_Controller
{
     public function __construct()
     {
          parent::__construct();

          header("Access-Control-Allow-Origin:*");
          header("Access-Control-Allow-Methods:GET,POST");
          header("Access-Control-Allow-Headers:*");
     }


     public function index_options()
     {
         return $this->response("NULL", REST_Controller::HTTP_OK);
     }


     public function index_get($token=0, $id=0)
     {
          $answer = $this->auth($token);
          if ($answer == "0")
          {
               $this->unauthorized();
          }
          else
          {
               // code...

               if($id > 0)
               {
                    $data = $this->db->get_where("movies", ['id' => $id])->row_array();
               }
               else
               {
                    $data = $this->db->get("movies")->result();
               }


               if ($data)
               {
                    $this->response([
                         'status' => true,
                         'data' => $data
                    ], REST_Controller::HTTP_OK);
               }
               else
               {
                    $this->response([
                        'status' => False,
                        'message' => 'Record(s) not found'
                    ], REST_Controller::HTTP_NOT_FOUND);
               }
          }
     }

     public function index_post()
	{
          $answer = $this->auth($this->post('token'));
          if ($answer == "0")
          {
               $this->unauthorized();
          }
          else
          {
               //print_r($_POST); die();

               $date = $this->post('date');
               $time = $this->post('time');
               $name = $this->post('name');
               $starring = $this->post('starring');
               $genres = $this->post('genres');
               $rating = $this->post('rating');
               $description = $this->post('description');

               if(empty($date) or empty($time) or empty($name) or empty($starring) or empty($genres) or empty($rating) or empty($description))
               {
                    $this->response([
                       'status' => False,
                       'message' => "Data missing, date, time, name, starring, genres, rating, description needed"
                   ], REST_Controller::HTTP_NOT_FOUND);

               }
               else
               {
                    $data = [
                        'date' => $this->post('date'),
                        'time' => $this->post('time'),
                        'name' => $this->post('name'),
                        'starring' => $this->post('starring'),
                        'genres' => $this->post('genres'),
                        'rating' => $this->post('rating'),
                        'description' => $this->post('description')
                      ];

                    $this->db->insert('movies', $data);
                    $id = $this->db->insert_id();

                    $data = $this->db->get_where("movies", ['id' => $id])->row_array();

                    $this->response([
                         'status' => True,
                         'data' => $data,
                         'message' => "Movie added successfully",
                         'id' => $id
                    ], REST_Controller::HTTP_CREATED);
               }
          }
	}


     public function index_put($token, $id)
    {
         $answer = $this->auth($token);
         if ($answer == "0")
         {
              $this->unauthorized();
         }
         else
         {
              if (isset($_GET['id']))
              {
                   $id = (int) $_GET['id'];
              }

              if($id == 0)
              {
                    $this->response([
                         'status' => false,
                         'message' => 'Please Provide an ID'
                         ], REST_Controller::HTTP_BAD_REQUEST);
              }
              else
              {
                    if (isset($_POST))
                    {
                         $date = $_POST['date'];
                         $time = $_POST['time'];
                         $name = $_POST['name'];
                         $starring = $_POST['starring'];
                         $genres = $_POST['genres'];
                         $rating = $_POST['rating'];
                         $description = $_POST['description'];
                    }
                    else
                    {
                         $date = $this->put('date');
                         $time = $this->put('time');
                         $name = $this->put('name');
                         $starring = $this->put('starring');
                         $genres = $this->put('genres');
                         $rating = $this->put('rating');
                         $description = $this->put('description');
                    }

                    //die();

                    if(empty($date) or empty($time) or empty($name) or empty($starring) or empty($genres) or empty($rating) or empty($description))
                    {
                         $this->response([
                            'status' => False,
                            'message' => "Data missing, date, time, name, starring, genres, rating, description needed"
                        ], REST_Controller::HTTP_NOT_FOUND);
                    }
                    else
                    {
                         $fields = array(
                              "date",
                              "time",
                              "name",
                              "starring",
                              "genres",
                              "rating",
                              "description"
                         );


                         if (isset($_POST)) // PUT by POST
                         {
                              $data = array();
                              foreach ($fields as $field)
                              {
                                   if (isset($_POST[$field]))
                                   {
                                        $data[$field] = $_POST[$field];
                                   }
                              }
                         }
                         else  //PUT OG
                         {
                              $data = array();
                              foreach ($fields as $field)
                              {
                                   if ($this->put($field) !== null)
                                   {
                                        $data[$field] = $this->put($field);
                                   }
                              }
                         }


                         // print_r($data);
                         // die();


                         $this->db->update('movies', $data, array('id'=>$id));

                         $data = $this->db->get_where("movies", ['id' => $id])->row_array();

                         $this->response([
                             'status' => True,
                             'data' => $data,
                             'message' => 'Movie updated successfully.'
                         ], REST_Controller::HTTP_OK);
                    }
              }
         }
    }



    public function index_patch($token, $id)
    {
         $answer = $this->auth($token);
         if ($answer == "0")
         {
              $this->unauthorized();
         }
         else
         {

              if (isset($_GET['id']))
              {
                   $id = (int) $_GET['id'];
              }


              if($id == 0)
              {
                     $this->response([
                         'status' => false,
                         'message' => 'Please Provide an ID'
                     ], REST_Controller::HTTP_BAD_REQUEST);
              }
              else
              {
                  $data = json_decode($this->input->raw_input_stream, true);

                  $this->db->update('movies', $data, array('id'=>$id));

                  $data = $this->db->get_where("movies", ['id' => $id])->row_array();


                  $this->response([
                      'status' => True,
                      'data' => $data,
                      'message' => 'Movie updated successfully.'
                  ], REST_Controller::HTTP_OK);
              }
         }
    }


     public function index_delete($token, $id=0)
     {
          $answer = $this->auth($token);
          if ($answer == "0")
          {
              $this->unauthorized();
          }
          else
          {
               if($id == 0)
               {
                    $this->response([
                         'status' => false,
                         'message' => 'Please Provide an ID'
                    ], REST_Controller::HTTP_BAD_REQUEST);
               }
               else
               {
                    $this->db->delete('movies', array('id'=>$id));

                    $this->response([
                        'status' => true,
                        'message' => 'Movie deleted successfully.'
                    ], REST_Controller::HTTP_OK);
               }
          }
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
