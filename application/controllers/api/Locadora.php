<?php
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
/** @noinspection PhpIncludeInspection */
//To Solve File REST_Controller not found
require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';

/**
 * This is an example of a few basic user interaction methods you could use
 * all done with a hardcoded array
 *
 * @package         CodeIgniter
 * @subpackage      Rest Server
 * @category        Controller
 * @author          Phil Sturgeon, Chris Kacerguis
 * @license         MIT
 * @link            https://github.com/chriskacerguis/codeigniter-restserver
 */
class Locadora extends REST_Controller {

    function __construct()
    {
        // Construct the parent class
        parent::__construct();
        $this->load->model('Ator_model');

        // Configure limits on our controller methods
        // Ensure you have created the 'limits' table and enabled 'limits' within application/config/rest.php
        $this->methods['ator_get']['limit'] = 500; // 500 requests per hour per user/key
        $this->methods['ator_post']['limit'] = 100; // 100 requests per hour per user/key
        $this->methods['ator_delete']['limit'] = 50; // 50 requests per hour per user/key
    }

    public function ator_get()
    {
        
        $atores = $this->Ator_model->get();

        $id = $this->get('id');

        // If the id parameter doesn't exist return all the users

        if ($id === NULL)
        {
            // Check if the users data store contains users (in case the database result returns NULL)
            if ($atores)
            {
                // Set the response and exit
                $this->response($atores, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
            }
            else
            {
                // Set the response and exit
                $this->response([
                    'status' => FALSE,
                    'message' => 'Nenhum ator encontrado'
                ], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
            }
        }

        // Find and return a single record for a particular user.

        $id = (int) $id;

        // Validate the id.
        if ($id <= 0)
        {
            // Invalid id, set the response and exit.
            $this->response(NULL, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code
        }

        // Get the user from the array, using the id as key for retrieval.
        // Usually a model is to be used for this.

        $user = NULL;

        if (!empty($atores))
        {
            foreach ($atores as $key => $value)
            {
                if (isset($value['ator_id']) && $value['ator_id'] == $id)
                {
                    $ator = $value;
                }
            }
        }

        if (!empty($ator))
        {
            $this->set_response($ator, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
        }
        else
        {
            $this->set_response([
                'status' => FALSE,
                'message' => 'Ator não pode ser encontrado'
            ], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
        }
    }

    public function ator_post()
    {

        $id = $this->get('id');

        // If the id parameter doesn't exist insert new entry
        if ($id === NULL){
            $dados = [
                'primeiro_nome' => $this->post('primeiro_nome'),
                'ultimo_nome' => $this->post('ultimo_nome')
            ];

            $this->Ator_model->insert($dados);
        }else{
            $dados = [
                'primeiro_nome' => $this->post('primeiro_nome'),
                'ultimo_nome' => $this->post('ultimo_nome')
            ];
            $this->Ator_model->update($dados, $id);
        }

       
        $this->set_response($dados, REST_Controller::HTTP_CREATED); // CREATED (201) being the HTTP response code
    }

    public function ator_delete()
    {
        $id = (int) $this->get('id');

        // Validate the id.
        if ($id <= 0)
        {
            // Set the response and exit
            $this->response(NULL, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code
        }

        $this->Ator_model->delete($id);
        $message = [
            'id' => $id,
            'message' => 'Deleted the resource'
        ];

        $this->set_response($message, REST_Controller::HTTP_NO_CONTENT); // NO_CONTENT (204) being the HTTP response code
    }

}
