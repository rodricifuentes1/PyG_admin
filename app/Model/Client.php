<?php

class Client extends AppModel
{
    /**
     * Reglas de validacion para ingresar el modelo a la base de datos
     * @var type array
     */
    public $validate = array (
        'email' => array (
            'rule' => 'email',
            'required' => 'true',
            'allowEmpty' => false,
            'message' => 'Ingresa un email válido'
        ),
        'name' => array (
            'rule' => array ('minLength', 8),
            'required' => 'true',
            'allowEmpty' => false,
            'message' => 'El nombre del cliente debe ser de minimo de 8 letras'
        ),
        'client_id' => array (
            'rule' => array ('minLength', 4),
            'required' => 'true',
            'allowEmpty' => false,
            'message' => 'La cedula del cliente debe ser de minimo de 4 letras'
        ),
        'address' => array (
            'rule' => array ('minLength', 8),
            'required' => 'true',
            'allowEmpty' => false,
            'message' => 'La direccion del cliente debe ser de minimo de 8 letras'
        ),
        'home_phone' => array (
            'rule1' => array (
                'rule' => array ('between', 7, 7),
                'required' => 'true',
                'allowEmpty' => false,
                'message' => 'El telefono del cliente debe ser de 7 numeros'
            ),
            'rule2' => array (
                'rule' => 'numeric',
                'message' => 'El telefono del cliente solo debe contener numeros'
            ),
        ),
        'mobile_phone' => array (
            'rule1' => array (
                'rule' => array ('between', 10, 10),
                'required' => 'true',
                'allowEmpty' => false,
                'message' => 'El celular del cliente debe ser de 10 numeros'
            ),
            'rule2' => array (
                'rule' => 'numeric',
                'message' => 'El celular del cliente solo debe contener numeros'
            ),
        ),
    );
    
    /**
     * Varaible que realiza la relación "many to many" con el modelo "Pet"
     * @var type array
     */
    public $hasAndBelongsToMany = array (
        'Pet' =>
        array (
            'className' => 'Pet',
            'joinTable' => 'clients_pets',
            'foreignKey' => 'client_id',
            'associationForeignKey' => 'pet_id',
            'unique' => false
        )
    );

}
