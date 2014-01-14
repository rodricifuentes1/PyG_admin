<?php

class ClientsController extends AppController
{

    /**
     * Metodo que renderiza la vista "index"
     */
    public function index ()
    {
        
    }

    /**
     * Metodo que renderiza la vista "client_list"
     */
    public function clientList ()
    {
        $clients_number = $this->Client->find('count');
        
        $current_page = 1;
        $max_per_page = 1;
        $total_pages = $this->getNumberOfPages($clients_number,$max_per_page);
        
        $clients = $this->getPaginatedList($max_per_page, $current_page);
        $this->set(array ('clients', 'total_pages', 'current_page', 'max_per_page'), array ($clients, $total_pages, $current_page, $max_per_page));
        
    }

    /**
     * Metodo que ingresa un cliente en el sistema
     * Este método se invoca por una peticion AJAX de la vista y recibe los parametros por el metodo POST
     * Parametros: email, name, address, home_phone, mobile_phone
     */
    public function saveClient ()
    {
        $this->autoRender = false;
        if ($this->request->is('ajax'))
        {
            $this->Client->create();
            if ($this->Client->save($this->request->data))
            {
                echo json_encode(array ('result' => 'success', 'errors' => ''));
            }
            else
            {
                echo json_encode(array ('result' => 'error', 'errors' => $this->Client->validationErrors));
            }
        }
    }
    
    /**
     * Metodo que se usa para filtrar la tabla de clientes
     */
    public function filterClients ()
    {
        $this->autoRender = false;
        if ($this->request->is('ajax'))
        {
            $param = $this->request->data('param');
            $query = $this->request->data('query');

            $result = array ();
            switch ($param)
            {
                case '':
                    $max_per_page=$this->request->data('max_per_page');
                    $current_page=$this->request->data('selected_page');
                    $min = $max_per_page* ($current_page - 1);
                    
                    $result = $this->Client->find('all', array (
                        'limit' => $max_per_page,
                        'offset' => $min
                    ));

                    break;

                case 'id':

                    $result = $this->Client->find('all', array ('conditions' => array ("Client.id" => $query)));
                    break;

                case 'name':
                    $result = $this->Client->find('all', array ('conditions' => array ("Client.name LIKE" => "%$query%")));

                    break;

                case 'client_id':

                    $result = $this->Client->find('all', array ('conditions' => array ('Client.client_id LIKE' => "%$query%")));
                    
                    break;

                case 'email':
                    $result = $this->Client->find('all', array ('conditions' => array ("Client.email LIKE" => "%$query%")));

                    break;
            }

            if (count($result) > 0)
            {
                echo json_encode(array ('result' => 'success', 'list' => $result));
            }
            else
            {
                echo json_encode(array ('result' => 'error', 'error' => 'No se encontraron mascotas con ese criterio'));
            }
        }
    }

    /**
     * Metodo que devuelve el numero de paginas
     * Usado para la paginacion
     * @param int $number
     * @return int
     */
    public function getNumberOfPages ($clients_number, $max_per_page)
    {
        $total = $clients_number / $max_per_page;
        $whole = floor($total);
        $decimal = $total - $whole;

        if ($decimal > 0)
        {
            return $whole + 1;
        }
        return $whole;
    }
    
    /**
     * Metodo que busca y devuelve una lista de clientes paginada
     */
    public function getPaginatedList ($max_per_page, $current_page)
    {
        $min = $max_per_page * ($current_page - 1);

        return $this->Client->find('all', array (
                    'limit' => $max_per_page,
                    'offset' => $min
        ));
    }
    
    /**
     * Metodo que busca y devuelve por AJAX una lista de clientes paginada
     */
    public function getAjaxPaginatedList ()
    {
        $this->autoRender = false;
        if ($this->request->is('ajax'))
        {
            $selected_page = $this->request->data('selected_page');
            $max_per_page=$this->request->data('max_per_page');
            
            $min = $max_per_page * ($selected_page - 1);
            
            $list = $this->Client->find('all', array (
                'limit' => $max_per_page,
                'offset' => $min
            ));

            echo json_encode(array ('result' => 'success', 'list' => $list));
        }
    }
}
