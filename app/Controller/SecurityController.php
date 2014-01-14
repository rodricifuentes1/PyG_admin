<?php

class SecurityController extends AppController
{

    function index ()
    {
        $session = $this->Session->check("PyG_admin");
        if ($session == false)
        {
            return $this->redirect(array ('controller' => 'grooming', 'action' => 'index'));
        }
    }

}