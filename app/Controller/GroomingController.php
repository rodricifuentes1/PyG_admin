<?php

class GroomingController extends AppController
{

    /**
     * Variable que modela los modelos usados por este controlador
     * @var type array
     */
    var $uses = array ('Grooming', 'Client');

    /**
     * Metodo que renderiza la vista "index"
     */
    public function index ()
    {
        $clients = $this->Client->find('all', array ('fields' => array ('Client.id', 'Client.name')));
        $todayBaths = $this->Grooming->query('SELECT * from grooming Grooming
            JOIN pets Pet
            ON Grooming.pet_id=Pet.id
            JOIN clients Client
            ON Grooming.client_id=Client.id
            WHERE Grooming.date = CURDATE()
            AND (Grooming.is_booking=0 
            OR (Grooming.is_booking=1 AND Grooming.booking_pet_arrived=1))
            ORDER BY Grooming.pet_arrival ASC');

        $todayBookings = $this->Grooming->find('all', array ('conditions' => array ('Grooming.date = CURDATE()', 'Grooming.is_booking' => 1, 'Grooming.booking_pet_arrived' => 0)));

        $this->set(array ('clients', 'baths', 'bookings'), array ($clients, $todayBaths, $todayBookings));
    }

    /**
     * Metodo que renderiza la vista "history"
     */
    public function history ()
    {
        $baths_number = $this->Grooming->find('count');

        $current_page = 1;
        $max_per_page = 20;
        $total_pages = $this->getNumberOfPages($baths_number, $max_per_page);

        $baths = $this->getPaginatedList($max_per_page, $current_page);

        $this->set(array ('baths', 'total_pages', 'current_page', 'max_per_page'), array ($baths, $total_pages, $current_page, $max_per_page));
    }

    /**
     * Metodo que renderiza la vista "booking"
     */
    public function booking ()
    {
        $clients = $this->Client->find('all', array ('fields' => array ('Client.id', 'Client.name')));
        $this->set(array('clients'),array($clients));
    }

    /**
     * Metodo que busca las mascotas de un cliente
     * Recibe el parametro "client_id" por el metodo POST
     */
    public function getClientById ()
    {
        $this->autoRender = false;
        if ($this->request->is('ajax'))
        {
            $client_id = $this->request->data('client_id');
            $client = $this->Client->find('first', array ('conditions' => array ('Client.id' => $client_id)));

            if (count($client['Pet']) > 0)
            {
                echo json_encode(array ('result' => 'success', 'list' => $client['Pet']));
            }
            else
            {
                echo json_encode(array ('result' => 'error', 'errors' => 'El cliente no tiene mascotas registradas'));
            }
        }
    }

    /**
     * Metodo que retorna cuantas veces una mascota ha ido a recibir servicio de baño y peluqueria
     */
    public function getGroomingServicesByPet ()
    {
        $this->autoRender = false;
        if ($this->request->is('ajax'))
        {
            $pet_id = $this->request->data('pet_id');
            $baths = $this->Grooming->find('count', array ('conditions' => array ('pet_id' => $pet_id)));
            echo json_encode(array ('result' => 'success', 'count' => $baths));
        }
    }

    /**
     * Metodo que registra un servicio de baño de una mascota entrante
     */
    public function saveGroomingService ()
    {
        $this->autoRender = false;
        if ($this->request->is('ajax'))
        {
            $client_id = $this->request->data('client_id');
            $pet_id = $this->request->data('pet_id');
            $date = date('Y-m-d');
            $status = "Llegó";
            $is_booking = 0;
            $service_type = $this->request->data('service_type');
            $pet_arrival = date('H:i:s');

            $this->Grooming->create();
            $data = array ('client_id' => $client_id, 'pet_id' => $pet_id, 'date' => $date, 'status' => $status, 'is_booking' => $is_booking, 'service_type' => $service_type, 'pet_arrival' => $pet_arrival);
            if ($this->Grooming->save($data))
            {
                echo json_encode(array ('result' => 'success'));
            }
            else
            {
                echo json_encode(array ('result' => 'error', 'errors' => $this->Grooming->validationErrors));
            }
        }
    }

    /**
     * Metodo que registra el inicio de un servicio de baño/peluqueria
     */
    public function startGroomingService ()
    {
        $this->autoRender = false;
        if ($this->request->is('ajax'))
        {
            $id = intval($this->request->data('id'));

            $this->Grooming->id = $id;

            $status = "Comenzó baño ";
            if ($this->Grooming->field('is_booking') == 1)
            {
                $status.="(R)";
            }

            if ($this->Grooming->saveField('grooming_start_hour', date('H:i:s')) && $this->Grooming->saveField('status', $status))
            {
                echo json_encode(array ('result' => 'success'));
            }
            else
            {
                echo json_encode(array ('result' => 'error'));
            }
        }
    }

    /**
     * Metodo que registra el fin de un servicio de baño/peluqueria
     */
    public function endGroomingService ()
    {
        $this->autoRender = false;
        if ($this->request->is('ajax'))
        {
            $id = intval($this->request->data('id'));

            $this->Grooming->id = $id;

            $status = "Terminó baño ";
            if ($this->Grooming->field('is_booking') == 1)
            {
                $status.="(R)";
            }

            if ($this->Grooming->saveField('grooming_end_hour', date('H:i:s')) && $this->Grooming->saveField('status', $status))
            {
                echo json_encode(array ('result' => 'success'));
            }
            else
            {
                echo json_encode(array ('result' => 'error'));
            }
        }
    }

    /**
     * Metodo que registra el fin de un servicio de baño/peluqueria
     */
    public function registerPetDeparture ()
    {
        $this->autoRender = false;
        if ($this->request->is('ajax'))
        {
            $id = intval($this->request->data('id'));

            $this->Grooming->id = $id;

            $status = "Salió / pagó ";
            if ($this->Grooming->field('is_booking') == 1)
            {
                $status.="(R)";
            }

            if ($this->Grooming->saveField('pet_departure', date('H:i:s')) && $this->Grooming->saveField('status', $status))
            {
                echo json_encode(array ('result' => 'success'));
            }
            else
            {
                echo json_encode(array ('result' => 'error'));
            }
        }
    }

    /**
     * Metodo que registra la llegada de una mascota que estaba en reserva de baño/peluqueria
     */
    public function registerBookingPetArrival ()
    {
        $this->autoRender = false;
        if ($this->request->is('ajax'))
        {
            $id = intval($this->request->data('id'));
            $this->Grooming->id = $id;
            $status = "Llegó (R)";

            if ($this->Grooming->saveField('pet_arrival', date('H:i:s')) && $this->Grooming->saveField('status', $status) && $this->Grooming->saveField('booking_pet_arrived', 1))
            {
                echo json_encode(array ('result' => 'success'));
            }
            else
            {
                echo json_encode(array ('result' => 'error'));
            }
        }
    }

    /**
     * Metodo que devuelve el numero de paginas
     * Usado para la paginacion
     * @param int $number
     * @return int
     */
    public function getNumberOfPages ($baths_number, $max_per_page)
    {
        $total = $baths_number / $max_per_page;
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
        $date = date('Y-m-d');

        return $this->Grooming->find('all', array (
                    'limit' => $max_per_page,
                    'offset' => $min,
                    'conditions' => array (
                        'Grooming.date <' => $date
                    )
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
            $max_per_page = $this->request->data('max_per_page');

            $min = $max_per_page * ($selected_page - 1);

            $list = $this->Grooming->find('all', array (
                'limit' => $max_per_page,
                'offset' => $min
            ));

            echo json_encode(array ('result' => 'success', 'list' => $list));
        }
    }

    /**
     * Metodo que ingresa finaliza el servicio de baño y peluqueria
     * Registra el reporte y el precio del servicio
     */
    public function registerGroomingPrice ()
    {
        $this->autoRender = false;
        if ($this->request->is('ajax'))
        {
            $id = intval($this->request->data('id'));
            $price = $this->request->data('price');
            $report = $this->request->data('report');
            $this->Grooming->id = $id;

            if ($this->Grooming->saveField('price', $price) && $this->Grooming->saveField('grooming_report', $report))
            {
                echo json_encode(array ('result' => 'success'));
            }
            else
            {
                echo json_encode(array ('result' => 'error'));
            }
        }
    }

    /**
     * Metodo que se usa para filtrar la tabla de baños
     */
    public function filterGrooming ()
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
                    $max_per_page = $this->request->data('max_per_page');
                    $current_page = $this->request->data('selected_page');
                    $min = $max_per_page * ($current_page - 1);

                    $result = $this->Grooming->find('all', array (
                        'limit' => $max_per_page,
                        'offset' => $min
                    ));

                    break;

                case 'id':

                    $result = $this->Grooming->find('all', array ('conditions' => array ("Grooming.id" => $query)));
                    break;

                case 'pet_name':

                    $result = $this->Grooming->find('all', array (
                        'conditions' => array (
                            "Pet.name LIKE" => "%$query%"
                        )
                    ));
                    break;

                case 'client_name':
                    
                    $result = $this->Grooming->find('all', array (
                        'conditions' => array (
                            "Client.name LIKE" => "%$query%"
                        )
                    ));
                    break;

                case 'date_range':

                    break;

                case 'start_hour':
                    
                    
                    break;

                case 'end_hour':

                    break;

                case 'pet_arrival':

                    break;

                case 'pet_departure':

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
     * Metodo que carga el reporte de un baño en un modal
     */
    public function getGroomingReport()
    {
        $this->autoRender=false;
        if($this->request->is('ajax'))
        {
            $grooming_id=$this->request->data('grooming_id');
            $grooming=$this->Grooming->findById(intval($grooming_id));
            
            $html='<div class="modal-dialog">';
            $html.='<div class="modal-content">';
            $html.='<div class="modal-header">';
            $html.='<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>';
            $html.='<h4 class="modal-title">Reporte de baño y peluqeuría de '.$grooming['Pet']['name'].'</h4>';
            $html.='</div>';
            $html.='<div class="modal-body">';
            $html.='<p>'.$grooming['Grooming']['grooming_report'].'</p>';
            $html.='</div>';
            $html.='<div class="modal-footer">';
            $html.='<button type="button" class="btn btn-primary" data-dismiss="modal">Cerrar</button>';
            $html.='</div>';
            $html.='</div>';
            $html.='</div>';
            
            echo json_encode(array('result'=>'success','html'=>$html));
        }
    }

    /**
     * Metodo que retorna ls reservas de un dia
     */
    public function getBookingsByDate ()
    {
        $this->autoRender = false;
        if ($this->request->is('ajax'))
        {
            $date=$this->request->data('date');
            $list = $this->Grooming->find('all', array (
                'conditions' => array(
                    'Grooming.date'=>$date,
                    'Grooming.is_booking'=>1
                )
            ));
            if(count($list)>0)
            {
                 echo json_encode(array('result'=>'success','list'=>$list));
            }
            else
            {
                 echo json_encode(array('result'=>'error','error'=>"No hay reservas para el ".$date));
            }
        }
    }
    
    /**
     * Metodo que ingresa una nueva reserva en el sistema
     */
    public function bookGroomingService ()
    {
        $this->autoRender = false;
        if ($this->request->is('ajax'))
        {  
            $this->Grooming->create();
            if($this->Grooming->save($this->request->data))
            {
                 echo json_encode(array('result'=>'success'));
            }
            else
            {
                 echo json_encode(array('result'=>'error','errors'=>$this->Grooming->validationErrors));
            }
        }
    }
    
}