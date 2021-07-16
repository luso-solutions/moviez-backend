<?php
require APPPATH . 'libraries/REST_Controller.php';

class Registration extends REST_Controller
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
               $data = $this->db->get_where("registry", ['id' => $id])->row_array();
          }
          else
          {
               $data = $this->db->get("registry")->result();
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
          $dob = $this->post('dob');
          $phone = $this->post('phone');
          $email = $this->post('email');
          $genres = $this->post('genres');

          if(empty($name) or empty($dob) or empty($phone))
          {
               $this->response([
                  'status' => False,
                  'message' => "Data missing, name, date of birth, phone needed"
              ], REST_Controller::HTTP_NOT_FOUND);

          }
          else
          {
               $data = [
                   'name' => $this->post('name'),
                   'dob' => $this->post('dob'),
                   'phone' => $this->post('phone'),
                   'email' => $this->post('email'),
                   'genres' => $this->post('genres')
                 ];

               $this->db->insert('registry', $data);
               $id = $this->db->insert_id();

               $data = $this->db->get_where("registry", ['id' => $id])->row_array();

               $this->response([
                    'status' => True,
                    'data' => $data,
                    'message' => "Patron added successfully",
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
                    $dob = $_POST['dob'];
                    $phone = $_POST['phone'];
                    $email = $_POST['email'];
                    $genres = $_POST['genres'];
               }
               else
               {
                    $name = $this->put('name');
                    $dob = $this->put('dob');
                    $phone = $this->put('phone');
                    $email = $this->put('email');
                    $genres = $this->put('genres');
               }

               //die();

               if(empty($name) or empty($dob) or empty($phone))
               {
                     $this->response([
                    'status' => False,
                    'message' => "Data missing, name, date of birth, phone needed"
                    ], REST_Controller::HTTP_BAD_REQUEST);
               }
               else
               {
                    $fields = array(
                         "name",
                         "dob",
                         "phone",
                         "email",
                         "genres"
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


                    $this->db->update('registry', $data, array('id'=>$id));

                    $data = $this->db->get_where("registry", ['id' => $id])->row_array();

                    $this->response([
                        'status' => True,
                        'data' => $data,
                        'message' => 'Patron updated successfully.'
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

             $this->db->update('registry', $data, array('id'=>$id));

             $data = $this->db->get_where("registry", ['id' => $id])->row_array();


             $this->response([
                 'status' => True,
                 'data' => $data,
                 'message' => 'Patron updated successfully.'
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
               $this->db->delete('registry', array('id'=>$id));

               $this->response([
                   'status' => true,
                   'message' => 'Patron deleted successfully.'
               ], REST_Controller::HTTP_OK);
          }
     }

}
