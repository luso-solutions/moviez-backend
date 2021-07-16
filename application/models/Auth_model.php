<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Auth_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();

        // Load the database library
        $this->load->database();
    }

    /*
     * Get rows from the clients table
     */
    function authenticate($token)
    {
         $q = $this->db->query("SELECT * FROM users where token = '$token'");
         if($q->num_rows() > 0)
         {
              return 1;
         }
         else
         {
              return 0;
         }
    }
}
