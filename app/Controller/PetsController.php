<?php

class PetsController extends AppController
{

    var $uses = array ('Pet', 'DogBreed', 'Client', 'ClientsPets', 'CatBreed', 'Grooming');
    
    /**
     * Metodo que renderiza la vista "index"
     */
    public function index ()
    {
        $clients = $this->Client->find('all');
        $this->set('clients', $clients);
    }

    /**
     * Metodo que renderiza la vista "petList"
     */
    public function petList ()
    {
        $pets_number = $this->Pet->find('count');

        $current_page = 1;
        $max_per_page = 3;
        $total_pages = $this->getNumberOfPages($pets_number,$max_per_page);
        
        $pets = $this->getPaginatedList($max_per_page, $current_page);

        $this->set(array ('pets', 'total_pages', 'current_page', 'max_per_page'), array ($pets, $total_pages, $current_page, $max_per_page));
    }

    /**
     * Metodo que devuelve en formato JSON todas las razas de perros
     */
    public function getDogBreeds ()
    {
        $this->autoRender = false;
        if ($this->request->is('ajax'))
        {
            $breeds = $this->DogBreed->find('all');
            echo json_encode($breeds);
        }
    }

    /**
     * Metodo que devuelve en formato JSON todas las razas de gatos
     */
    public function getCatBreeds ()
    {
        $this->autoRender = false;
        if ($this->request->is('ajax'))
        {
            $cat_breeds = $this->CatBreed->find('all');
            echo json_encode($cat_breeds);
        }
    }

    /**
     * Metodo que ingresa una mascota en el sistema
     * Este método se invoca por una peticion AJAX de la vista y recibe los parametros por el metodo POST
     * Parametros: specie, breed_id, name, color, age, medical_issues
     */
    public function savePet ()
    {
        $this->autoRender = false;
        if ($this->request->is('ajax'))
        {
            $this->Pet->create();
            if ($this->Pet->save($this->request->data))
            {
                echo json_encode(array ('result' => 'success', 'id' => $this->Pet->id));
            }
            else
            {
                echo json_encode(array ('result' => 'error', 'errors' => $this->Pet->validationErrors));
            }
        }
    }

    /**
     * Metodo que asigna una mascota a un cliente
     * Este metodo recibe los parametros client_id , pet_id por el metodo POST
     */
    public function asignPetToClient ()
    {
        $this->autoRender = false;
        if ($this->request->is('ajax'))
        {
            $this->ClientsPets->create();
            if ($this->ClientsPets->save($this->request->data))
            {
                echo json_encode(array ('result' => 'success', 'errors' => ''));
            }
            else
            {
                echo json_encode(array ('result' => 'error', 'errors' => $this->ClientsPets->validationErrors));
            }
        }
    }

    /**
     * Metodo que se usa para filtrar la tabla de mascotas
     */
    public function filterPets ()
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
                    
                    $result = $this->Pet->find('all', array (
                        'limit' => $max_per_page,
                        'offset' => $min
                    ));

                    break;

                case 'id':

                    $result = $this->Pet->find('all', array ('conditions' => array ("Pet.id" => $query)));
                    break;

                case 'name':
                    $result = $this->Pet->find('all', array ('conditions' => array ("Pet.name LIKE" => "%$query%")));

                    break;

                case 'breed':

                    $result1 = $this->Pet->find('all', array ('conditions' => array ('Pet.dog_breed_id !=' => null, 'DogBreed.breed LIKE' => "%$query%")));
                    $result2 = $this->Pet->find('all', array ('conditions' => array ('Pet.cat_breed_id !=' => null, 'CatBreed.breed LIKE' => "%$query%")));
                    $result = array_merge($result1, $result2);
                    break;

                case 'color':
                    $result = $this->Pet->find('all', array ('conditions' => array ("Pet.color LIKE" => "%$query%")));

                    break;

                case 'age':
                    $result = $this->Pet->find('all', array ('conditions' => array ("Pet.age LIKE" => "%$query%")));

                    break;

                case 'specie':
                    $result = $this->Pet->find('all', array ('conditions' => array ("Pet.specie LIKE" => "%$query%")));

                    break;

                case 'client_name':

                    $result = $this->Pet->query("Select * from pets AS Pet 
                        LEFT JOIN dogBreeds AS DogBreed
                        ON (Pet.dog_breed_id=DogBreed.id)
                        LEFT JOIN catBreeds AS CatBreed
                        ON (Pet.cat_breed_id=CatBreed.id)
                        JOIN clients_pets AS ClientPet
                        ON (Pet.id=ClientPet.pet_id)
                        JOIN clients AS Client
                        ON (ClientPet.client_id=Client.id)
                        WHERE Client.name LIKE '%$query%'");
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
    public function getNumberOfPages ($pets_number, $max_per_page)
    {
        $total = $pets_number / $max_per_page;
        $whole = floor($total);
        $decimal = $total - $whole;

        if ($decimal > 0)
        {
            return $whole + 1;
        }
        return $whole;
    }

    /**
     * Metodo que busca y devuelve una lista de mascotas paginada
     */
    public function getPaginatedList ($max_per_page, $current_page)
    {
        $min = $max_per_page * ($current_page - 1);

        return $this->Pet->find('all', array (
                    'limit' => $max_per_page,
                    'offset' => $min
        ));
    }

    /**
     * Metodo que busca y devuelve por AJAX una lista de mascotas paginada
     */
    public function getAjaxPaginatedList ()
    {
        $this->autoRender = false;
        if ($this->request->is('ajax'))
        {
            $selected_page = $this->request->data('selected_page');
            $max_per_page=$this->request->data('max_per_page');
            
            $min = $max_per_page * ($selected_page - 1);
            
            $list = $this->Pet->find('all', array (
                'limit' => $max_per_page,
                'offset' => $min
            ));

            echo json_encode(array ('result' => 'success', 'list' => $list));
        }
    }

    /**
     * Metodo que carga las indicaciones medicas de una mascota en un modal
     */
    public function getPetMedicalIssues()
    {
        $this->autoRender=false;
        if($this->request->is('ajax'))
        {
            $pet_id=$this->request->data('pet_id');
            $pet=$this->Pet->findById(intval($pet_id));
            
            $html='<div class="modal-dialog">';
            $html.='<div class="modal-content">';
            $html.='<div class="modal-header">';
            $html.='<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>';
            $html.='<h4 class="modal-title">Indicaciones medicas de '.$pet['Pet']['name'].'</h4>';
            $html.='</div>';
            $html.='<div class="modal-body">';
            $html.='<p>'.$pet['Pet']['medical_issues'].'</p>';
            $html.='</div>';
            $html.='<div class="modal-footer">';
            $html.='<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>';
            $html.='</div>';
            $html.='</div>';
            $html.='</div>';
            
            echo json_encode(array('result'=>'success','html'=>$html));
        }
    }
    
}