<?php
require APPPATH . 'libraries/REST_Controller.php';

class Genres extends REST_Controller
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


     public function index_get($id=0)
     {
          if($id > 0)
          {
               $data = $this->db->get_where("genres", ['id' => $id])->row_array();
          }
          else
          {
               $data = $this->db->get("genres")->result();
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

     public function index_post()
	{
          $name = $this->post('name');

          if(empty($name))
          {
               $this->response([
                  'status' => False,
                  'message' => "Data missing, name needed"
              ], REST_Controller::HTTP_NOT_FOUND);

          }
          else
          {
               $data = [
                   'name' => $this->post('name')
                 ];

               $this->db->insert('genres', $data);
               $id = $this->db->insert_id();

               $data = $this->db->get_where("genres", ['id' => $id])->row_array();

               $this->response([
                    'status' => True,
                    'data' => $data,
                    'message' => "Genre added successfully",
                    'id' => $id
               ], REST_Controller::HTTP_CREATED);
          }
	}


     public function index_put($id)
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
                    $name = $_POST['name'];
               }
               else
               {
                    $name = $this->put('name');
               }

               //die();

               if(empty($name))
               {
                     $this->response([
                    'status' => False,
                    'message' => 'Data missing: Name needed'
                    ], REST_Controller::HTTP_BAD_REQUEST);
               }
               else
               {
                    $fields = array(
                         "name"
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


                    //print_r($data);
                    //die("Ready");


                    $this->db->update('genres', $data, array('id'=>$id));

                    $data = $this->db->get_where("genres", ['id' => $id])->row_array();

                    $this->response([
                        'status' => True,
                        'data' => $data,
                        'message' => 'Genre updated successfully.'
                    ], REST_Controller::HTTP_OK);
               }
         }
    }



    public function index_patch($id)
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

             $this->db->update('genres', $data, array('id'=>$id));

             $data = $this->db->get_where("genres", ['id' => $id])->row_array();


             $this->response([
                 'status' => True,
                 'data' => $data,
                 'message' => 'genres updated successfully.'
             ], REST_Controller::HTTP_OK);
         }
    }


     public function index_delete($id=0)
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
               $this->db->delete('genres', array('id'=>$id));

               $this->response([
                   'status' => true,
                   'message' => 'Genre deleted successfully.'
               ], REST_Controller::HTTP_OK);
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
