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
            <li class="active"><a href="#">Baño y peluquería</a></li>
            <li><?php echo $this->Html->link("Clientes", array ('controller' => 'clients', 'action' => 'index')); ?></li>
            <li><?php echo $this->Html->link("Mascotas", array ('controller' => 'pets', 'action' => 'index')); ?></li>
            <li><a href="#">Reportes</a></li>
        </ul>
    </div>
</nav>

<div class="container">
    <div class="row">

        <div class="col-md-3">
            <ul class="nav nav-pills nav-stacked">
                <li class="active"><a href="#">Registrar baño</a></li>
                <li><?php echo $this->Html->link("Ver historial de baños", array ('controller' => 'grooming', 'action' => 'history')); ?></li>
                <li><?php echo $this->Html->link("Reservar turno", array ('controller' => 'grooming', 'action' => 'booking')); ?></li>
            </ul>
        </div>
        <div class="col-md-9">
            <h1 style="color: orange; margin-top: 0;">Registrar baño</h1>
            <br/>

            <div class="row">
                <div class="col-md-6 col-md-offset-2">
                    <div class="form-group">
                        <label for="user_id">Cliente</label>
                        <select id="user_id" style="width: 100%; height: 100%;">
                            <option value=""></option>
                            <?php foreach ($clients as $client) : ?>
                                <option value="<?php echo $client['Client']['id'] ?>"><?php echo $client['Client']['name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <label>¿Cliente nuevo?</label>
                    <a href="<?php echo Router::url(array ('controller' => 'clients', 'action' => 'index')); ?>" class="btn btn-info">Crear cliente</a>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 col-md-offset-2">
                    <div class="form-group">
                        <label for="pet_id">Mascota</label>
                        <select id="pet_id" class="form-control" disabled>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <label id="grooming_bath_label" style="display: none;">Este es su baño</label><br/>
                    <a href="#">
                        <span class="badge" id="grooming_bath_number" style="background-color: #5cb85c; display: none; padding-top: 10px; padding-left: 25px; padding-right: 25px; padding-bottom: 10px;">Número 1</span>
                    </a>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 col-md-offset-2">
                    <div class="form-group">
                        <label for="service_type">Tipo de servicio</label>
                        <select id="service_type" class="form-control">
                            <option value=""></option>
                            <option value="BATH">Baño</option>
                            <option value="BATH_&_GROOMING">Baño y peluquería</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-1 col-md-offset-4">
                    <div class="form-group">
                        <input type="button" class="btn btn-success" value="Registrar" id="grooming_save_groomservice_btn"/>
                    </div>
                </div>
            </div>
            <hr class="divider"/>
            <div class="row">
                <h1 style="color: orange; margin-top: 0;">Baños de hoy</h1>
                <div class="col-md-12">
                    <table class="table table-striped table-bordered" id="grooming_today_bath_table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Mascota</th>
                                <th>Cliente</th>
                                <th>Hora llegada</th>
                                <th>Hora inicio</th>
                                <th>Hora fin</th>
                                <th>Hora salida</th>
                                <th>Precio</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($baths as $bath): ?>
                                <tr>
                                    <td><?php echo $bath['Grooming']['id']; ?></td>
                                    <td><a href=""><?php echo $bath['Pet']['name']; ?></a></td>
                                    <td><a href=""><?php echo $bath['Client']['name']; ?></a></td>
                                    <td><?php echo date("g:i:s a", STRTOTIME($bath['Grooming']['pet_arrival'])); ?></td>
                                    <td>
                                        <?php
                                        if (isset($bath['Grooming']['grooming_start_hour']))
                                        {
                                            echo date("g:i:s a", STRTOTIME($bath['Grooming']['grooming_start_hour']));
                                        }
                                        else
                                        {
                                            echo ('<input type="button" class="btn btn-success btn-xs btn-block" value="Comenzar" id="groomingstart_' . $bath['Grooming']['id'] . '"/>');
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        if (isset($bath['Grooming']['grooming_end_hour']))
                                        {
                                            echo date("g:i:s a", STRTOTIME($bath['Grooming']['grooming_end_hour']));
                                        }
                                        else
                                        {
                                            if (isset($bath['Grooming']['grooming_start_hour']))
                                            {
                                                echo ('<input type="button" class="btn btn-warning btn-xs btn-block" value="Terminar" id="groomingend_' . $bath['Grooming']['id'] . '"/>');
                                            }
                                            else
                                            {
                                                echo ('<input type="button" class="btn btn-warning btn-xs btn-block" value="Terminar" disabled/>');
                                            }
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        if (isset($bath['Grooming']['pet_departure']))
                                        {
                                            echo date("g:i:s a", STRTOTIME($bath['Grooming']['pet_departure']));
                                        }
                                        else
                                        {
                                            if (isset($bath['Grooming']['grooming_end_hour']))
                                            {
                                                echo ('<input type="button" class="btn btn-danger btn-xs btn-block" value="Salida" id="groomingleaving_' . $bath['Grooming']['id'] . '"/>');
                                            }
                                            else
                                            {
                                                echo ('<input type="button" class="btn btn-danger btn-xs btn-block" value="Salida" disabled/>');
                                            }
                                        }
                                        ?>
                                    </td>
                                    <td><?php echo (isset($bath['Grooming']['price']) ? '$ ' . $bath['Grooming']['price'] : '--'); ?></td>
                                    <td><span class="btn btn-success btn-xs btn-block "><?php echo ($bath['Grooming']['status']); ?></span></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <hr class="divider"/>
            <div class="row">
                <h1 style="color: orange; margin-top: 0;">Reservas para hoy</h1>
                <div class="col-md-12">
                    <table class="table table-striped table-bordered" id="grooming_bookings_table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Mascota</th>
                                <th>Cliente</th>
                                <th>Hora reserva</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($bookings as $booking): ?>
                                <tr>
                                    <td><?php echo ($booking['Grooming']['id']); ?></td>
                                    <td><a href=""><?php echo ($booking['Pet']['name']); ?></a></td>
                                    <td><a href=""><?php echo ($booking['Client']['name']); ?></a></td>
                                    <td><?php echo date("g:i:s a", STRTOTIME($booking['Grooming']['booking_pet_arrival'])); ?></td>
                                    <td><?php echo ($booking['Grooming']['status']); ?></td>
                                    <td><input type="button" class="btn btn-success btn-xs btn-block" value="Llegó mascota" id="bookingarrival_<?php echo $booking['Grooming']['id']; ?>"/>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="pet_departure_price_modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Finalizar servicio de baño y peluquería</h4>
            </div>
            <div class="modal-body">
                <h4>Recibe el dinero del baño e ingresa el valor a continuación</h4>
                <input type="text" id="pet_departure_modal_price_txt" class="form-control"/>
                <hr/>
                <h4>Escribe a continuación el reporte baño y peluquería</h4>
                <textarea id="pet_departure_modal_report_txt" class="form-control" rows="5"></textarea>
                <input type="hidden" id="pet_departure_modal_grooming_id"/>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" data-dismiss="modal">Finalizar servicio</button>
            </div>
        </div>
    </div>
</div>
<script>
    $('#user_id').select2().on('change', function()
    {
        var client = {
            client_id: $(this).val()
        };
        $.ajax({
            type: "POST",
            url: "<?php echo Router::url(array ('controller' => 'grooming', 'action' => 'getClientById')); ?>",
            data: client,
            success: function(data)
            {
                var json = JSON.parse(data);
                if (json.result === "success") {

                    $('#pet_id').empty();

                    var op = '<option value=""></option>';
                    $.each(json.list, function()
                    {
                        op += '<option value="' + this.id + '">' + this.name + '</option>';
                    });
                    $('#pet_id').html(op);

                    if (!$('#pet_id').is(':enabled')) {
                        $('#pet_id').removeAttr('disabled');
                    }
                }
                else
                {
                    $('#pet_id').empty();
                    $('#grooming_bath_label').hide();
                    $('#grooming_bath_number').hide();
                    if ($('#pet_id').is(':enabled')) {
                        $('#pet_id').attr('disabled', 'disabled');
                    }
                }
            }
        });
    });

    $('#pet_id').on('change', function() {
        var id = {pet_id: $('#pet_id').val()};
        $.ajax({
            type: "POST",
            url: "<?php echo Router::url(array ('controller' => 'grooming', 'action' => 'getGroomingServicesByPet')); ?>",
            data: id,
            success: function(data)
            {
                var json = JSON.parse(data);
                if (json.result === "success") {

                    var number = json.count;
                    $('#grooming_bath_label').fadeIn();
                    $('#grooming_bath_number').text("Número " + (number + 1));
                    $('#grooming_bath_number').fadeIn();
                }
                else
                {

                }
            }
        });
    });

    $('#grooming_save_groomservice_btn').click(function(e) {
        e.preventDefault();
        var obj = {
            client_id: $('#user_id').val(),
            pet_id: $('#pet_id').val(),
            service_type: $('#service_type').val(),
        };
        $.ajax({
            type: "POST",
            url: "<?php echo Router::url(array ('controller' => 'grooming', 'action' => 'saveGroomingService')); ?>",
            data: obj,
            success: function(data)
            {
                var json = JSON.parse(data);
                if (json.result === "success") {
                    location.reload();
                }
                else
                {
                    console.log(json.errors);
                }
            }
        });
    });

    $('#grooming_today_bath_table').on('click', 'input[id^="groomingstart_"]', function(e) {
        e.preventDefault();

        var clicked = this.id + "";
        var ids = clicked.split("_")[1];

        var sel = {
            id: ids
        };

        $.ajax({
            type: "POST",
            url: "<?php echo Router::url(array ('controller' => 'grooming', 'action' => 'startGroomingService')); ?>",
            data: sel,
            success: function(data)
            {
                var json = JSON.parse(data);
                if (json.result === "success") {
                    location.reload();
                }
                else
                {
                    console.log("error");
                }
            }
        });

    });

    $('#grooming_today_bath_table').on('click', 'input[id^="groomingend_"]', function(e) {
        e.preventDefault();

        var clicked = this.id + "";
        var ids = clicked.split("_")[1];

        var sel = {
            id: ids
        };

        $.ajax({
            type: "POST",
            url: "<?php echo Router::url(array ('controller' => 'grooming', 'action' => 'endGroomingService')); ?>",
            data: sel,
            success: function(data)
            {
                var json = JSON.parse(data);
                if (json.result === "success") {
                    location.reload();
                }
                else
                {
                    console.log("error");
                }
            }
        });
    });

    $('#grooming_today_bath_table').on('click', 'input[id^="groomingleaving_"]', function(e) {
        e.preventDefault();

        var clicked = this.id + "";
        var ids = clicked.split("_")[1];

        var sel = {
            id: ids
        };

        $.ajax({
            type: "POST",
            url: "<?php echo Router::url(array ('controller' => 'grooming', 'action' => 'registerPetDeparture')); ?>",
            data: sel,
            success: function(data)
            {
                var json = JSON.parse(data);
                if (json.result === "success") {

                    $('#pet_departure_modal_grooming_id').val(ids);
                    $('#pet_departure_price_modal').modal('show');
                }
                else
                {
                    console.log("error");
                }
            }
        });
    });

    $('#grooming_bookings_table').on('click', 'input[id^="bookingarrival_"]', function(e) {
        e.preventDefault();

        var clicked = this.id + "";
        var ids = clicked.split("_")[1];

        var sel = {
            id: ids
        };

        $.ajax({
            type: "POST",
            url: "<?php echo Router::url(array ('controller' => 'grooming', 'action' => 'registerBookingPetArrival')); ?>",
            data: sel,
            success: function(data)
            {
                var json = JSON.parse(data);
                if (json.result === "success") {
                    location.reload();
                }
                else
                {
                    console.log("error");
                }
            }
        });
    });

    $('#pet_departure_price_modal').on('hidden.bs.modal', function() {
        var pri={
            id:$('#pet_departure_modal_grooming_id').val(),
            price:$('#pet_departure_modal_price_txt').val(),
            report:$('#pet_departure_modal_report_txt').val(),
        };
        $.ajax({
            type: "POST",
            url: "<?php echo Router::url(array ('controller' => 'grooming', 'action' => 'registerGroomingPrice')); ?>",
            data: pri,
            success: function(data)
            {
                var json = JSON.parse(data);
                if (json.result === "success") {
                    location.reload();
                }
                else
                {
                    console.log("error");
                }
            }
        });
    });
</script>