<?php

class Grooming extends AppModel
{
    public $useTable = 'grooming';
    
    /**
     * Variable que realiza la relacion "HasOne" con el modelo client, pet
     * @var type array
     */
    public $belongsTo = array(
        'Pet' => array(
            'className' => 'Pet',
            'foreignKey' => 'Pet_id'
        ),
        'Client' => array(
            'className' => 'Client',
            'foreignKey' => 'client_id'
        )
    );
    
    /**
     * Reglas de validacion para ingresar el modelo a la base de datos
     * @var type array
     */
    public $validate = array (
        'client_id' => array (
            'required' => array (
                'rule' => array ('notEmpty'),
                'message' => 'El cliente es requerido'
            )
        ),
        'pet_id' => array (
            'required' => array (
                'rule' => array ('notEmpty'),
                'message' => 'La mascota es requerida'
            )
        ),
        'date' => array (
            'required' => array (
                'rule' => array ('notEmpty'),
                'message' => 'La fecha es requerida'
            )
        ),
        'status' => array (
            'required' => array (
                'rule' => array ('notEmpty'),
                'message' => 'El estado es requerido'
            )
        ),
        'is_booking' => array (
            'required' => array (
                'rule' => array ('notEmpty'),
                'message' => 'Es necesario saber si es reserva'
            )
        ),
        'pet_arrival' => array (
            'required' => array (
                'rule' => array ('notEmpty'),
                'message' => 'La hora de llegada de la mascota es requerida'
            )
        ),
    );
}

