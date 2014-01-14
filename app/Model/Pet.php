<?php

class Pet extends AppModel
{
    
    /**
     * Reglas de validacion para ingresar el modelo a la base de datos
     * @var type array
     */
    public $validate = array (
        'specie' => array (
            'required' => array (
                'rule' => array ('notEmpty'),
                'message' => 'La especie es requerida'
            )
        ),
        'breed_id' => array (
            'required' => array (
                'rule' => array ('notEmpty'),
                'message' => 'La raza es requerida'
            )
        ),
        'name' => array (
            'required' => array (
                'rule' => array ('notEmpty'),
                'message' => 'El nombre es requerido'
            )
        ),
        'color' => array (
            'required' => array (
                'rule' => array ('notEmpty'),
                'message' => 'El color es requerido'
            )
        ),
        'age' => array (
            'required' => array (
                'rule' => array ('notEmpty'),
                'message' => 'La edad es requerida'
            )
        ),
    );

    /**
     * Variable que realiza la relacion "Many to many" con el modelo "Client"
     * @var type array
     */
    public $hasAndBelongsToMany = array (
        'Client' =>
        array (
            'className' => 'Client',
            'joinTable' => 'clients_pets',
            'foreignKey' => 'pet_id',
            'associationForeignKey' => 'client_id',
            'unique' => false
        )
    );

    /**
     * Variable que realiza la relacion "HasOne" con el modelo dogBreed, catBreed
     * @var type array
     */
    public $belongsTo = array(
        'DogBreed' => array(
            'className' => 'DogBreed',
            'foreignKey' => 'dog_breed_id'
        ),
        'CatBreed' => array(
            'className' => 'CatBreed',
            'foreignKey' => 'cat_breed_id'
        )
    );
    
}

?>
