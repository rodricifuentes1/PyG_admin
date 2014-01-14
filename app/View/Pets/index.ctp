<nav class="navbar navbar-default navbar-fixed-top" role="navigation">
    <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="#" style="color: orange;">Pequeños y grandes
            <span>cuidado para mascotas</span></a>
    </div>

    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
        <ul class="nav navbar-nav">
            <li><?php echo $this->Html->link("Baño y peluquería", array ('controller' => 'grooming', 'action' => 'index')); ?></li>
            <li><?php echo $this->Html->link("Clientes", array ('controller' => 'clients', 'action' => 'index')); ?></li>
            <li class="active"><?php echo $this->Html->link("Mascotas", array ('controller' => 'pets', 'action' => 'index')); ?></li>
            <li><a href="#">Reportes</a></li>
        </ul>
    </div>
</nav>

<div class="container">
    <div class="row">

        <div class="col-md-3">
            <ul class="nav nav-pills nav-stacked">
                <li class="active"><a href="#">Registrar mascota</a></li>
                <li><?php echo $this->Html->link("Ver mascotas", array ('controller' => 'pets', 'action' => 'petList')); ?></li>
            </ul>
        </div>
        <div class="col-md-9">

            <div class="row">
                <h1 style="color: orange; margin-top: 0;">Registrar mascota</h1>
                <br/>
            </div>
            <div class="row">
                <div class="col-md-6 col-md-offset-2">
                    <div class="form-group">
                        <label for="pet_specie">Especie</label>
                        <select id="pet_specie" style="width: 100%;">
                            <option value=""></option>
                            <option value="canine">Canino</option>
                            <option value="feline">Felino</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 col-md-offset-2">
                    <div class="form-group">
                        <label for="pet_breed">Raza</label>
                        <select id="pet_breed" style="width: 100%;" disabled>
                            <option value=""></option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 col-md-offset-2">
                    <div class="form-group">
                        <label for="pet_name">Nombre</label>
                        <input type="email" class="form-control" id="pet_name"/>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 col-md-offset-2">
                    <div class="form-group">
                        <label for="pet_gender">Género</label>
                        <select id="pet_gender" style="width: 100%;">
                            <option value=""></option>
                            <option value="male">Masculino</option>
                            <option value="female">Femenino</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 col-md-offset-2">
                    <div class="form-group">
                        <label for="pet_color">Color</label>
                        <input type="text" class="form-control" id="pet_color"/>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 col-md-offset-2">
                    <div class="form-group">
                        <label for="pet_age">Edad</label>
                        <input type="text" class="form-control" id="pet_age"/>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 col-md-offset-2">
                    <div class="form-group">
                        <label for="pet_medical_issues">Indicaciones medicas</label>
                        <textarea class="form-control" rows="3" id="pet_medical_issues"></textarea>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-1 col-md-offset-4">
                    <div class="form-group">
                        <input type="button" class="btn btn-success" value="Registrar mascota" id="pet_register_btn"/>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="registration_modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">La mascota ha sido registrada</h4>
            </div>
            <div class="modal-body">
                <p>La mascota ha sido ingresada exitosamente, ¿Quieres registrarle el dueño?</p>
                <div class="form-group">
                    <label for="pet_owner_id">Selecciona al dueño de la mascota:</label>
                    <select id="pet_owner_id" style="width: 100%;">
                        <option value=""></option>
                        <?php foreach ($clients as $client) : ?>
                            <option value="<?php echo $client['Client']['id'] ?>"><?php echo $client['Client']['name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Omitir</button>
                <button type="button" class="btn btn-primary" id="register_pet_owner_btn">Seleccionar</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<script>
    var pt_id_sl = "";

    $('#pet_breed').select2();
    $('#pet_specie').select2();
    $('#pet_owner_id').select2();
    $('#pet_gender').select2();

    $('#pet_specie').change(function() {
        var sp_type = $(this).val();
        if (sp_type === "canine")
        {
            $.ajax({
                type: "GET",
                url: "<?php echo Router::url(array ('controller' => 'pets', 'action' => 'getDogBreeds')); ?>",
                success: function(data)
                {
                    $('#pet_breed').select2('data', null);
                    $('#pet_breed').empty();
                    if ($('#pet_breed').is(':enabled'))
                    {
                        $('#pet_breed').attr('disabled', 'disabled');
                    }
                    var json = JSON.parse(data);
                    $.each(json, function() {
                        $('#pet_breed').append($("<option />").val(this.DogBreed.id).text(this.DogBreed.breed));
                    });
                    $('#pet_breed').removeAttr('disabled');
                }
            });
        }
        else if (sp_type === "feline")
        {
            $.ajax({
                type: "GET",
                url: "<?php echo Router::url(array ('controller' => 'pets', 'action' => 'getCatBreeds')); ?>",
                success: function(data)
                {
                    $('#pet_breed').select2('data', null);
                    $('#pet_breed').empty();
                    if ($('#pet_breed').is(':enabled'))
                    {
                        $('#pet_breed').attr('disabled', 'disabled');
                    }
                    var json = JSON.parse(data);
                    $.each(json, function() {
                        $('#pet_breed').append($("<option />").val(this.CatBreed.id).text(this.CatBreed.breed));
                    });
                    $('#pet_breed').removeAttr('disabled');
                }
            });
        }
    });

    $('#pet_register_btn').click(function(e) {
        e.preventDefault();

        var pet;

        if ($('#pet_specie').val() === 'canine') {
            pet = {
                specie: $('#pet_specie').val(),
                dog_breed_id: $('#pet_breed').val(),
                name: $('#pet_name').val(),
                gender: $('#pet_gender').val(),
                color: $('#pet_color').val(),
                age: $('#pet_age').val(),
                medical_issues: $('#pet_medical_issues').val()
            };
        }
        else if ($('#pet_specie').val() === 'feline') {
            pet = {
                specie: $('#pet_specie').val(),
                cat_breed_id: $('#pet_breed').val(),
                name: $('#pet_name').val(),
                gender: $('#pet_gender').val(),
                color: $('#pet_color').val(),
                age: $('#pet_age').val(),
                medical_issues: $('#pet_medical_issues').val()
            };
        }

        $.ajax({
            type: "POST",
            url: "<?php echo Router::url(array ('controller' => 'pets', 'action' => 'savePet')); ?>",
            datatype: "json",
            data: pet,
            success: function(data)
            {
                var json = JSON.parse(data);
                if (json.result === "success")
                {
                    pt_id_sl = json.id;

                    $('#pet_specie').select2('data', null);
                    $('#pet_breed').select2('data', null);
                    $('#pet_name').val('');
                    $('#pet_gender').select2('data', null);
                    $('#pet_color').val('');
                    $('#pet_age').val('');
                    $('#pet_medical_issues').val('');

                    $('#registration_modal').modal('show');
                }
                else
                {
                    var message = "";
                    var array = $.map(json.errors, function(value, index) {
                        return [value];
                    });
                    for (var i = 0; i < array.length; i++) {
                        message += array[i][0] + "<br/>";
                    }
                    noty({
                        text: message,
                        type: "error"
                    });
                }
            }
        });
    });

    $('#register_pet_owner_btn').click(function(e) {
        e.preventDefault();



        var client_pet = {client_id: $('#pet_owner_id').val(), pet_id: pt_id_sl};

        $.ajax({
            type: "POST",
            url: "<?php echo Router::url(array ('controller' => 'pets', 'action' => 'asignPetToClient')); ?>",
            datatype: "json",
            data: client_pet,
            success: function(data)
            {
                var json = JSON.parse(data);
                if (json.result === "success") {
                    noty({
                        text: "La mascota ha sido asignada al cliente exitosamente",
                        type: "success"
                    });
                }
                else {
                    noty({
                        text: "Ocurrio un problema al asignar la mascota al cliente.",
                        type: "error"
                    });
                }
                $('#registration_modal').modal('hide');
            }
        });
    });
</script>