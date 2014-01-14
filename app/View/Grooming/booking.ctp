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
                <li><?php echo $this->Html->link("Registrar baño", array ('controller' => 'grooming', 'action' => 'index')); ?></li>
                <li><?php echo $this->Html->link("Ver historial de baños", array ('controller' => 'grooming', 'action' => 'history')); ?></li>
                <li class="active"><a href="#">Reservar turno</a></li>
            </ul>
        </div>
        <div class="col-md-9">

            <div class="row">
                <h1 style="color: orange; margin-top: 0;">Reservar turno de baño y peluqueria</h1>
                <br/>
            </div>

            <div class="row">
                <div class="col-md-6 col-md-offset-2">
                    <div class="form-group">
                        <label for="grooming_date">Fecha</label>
                        <input type="text" class="form-control span2" id="grooming_date" data-date-format="yyyy/mm/dd"/>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 col-md-offset-2">
                    <div class="panel panel-default" id="grooming_bookings_table_panel" style="display: none;">
                        <div class="panel-heading">
                            <a class="pull-right" href="#" id="grooming_hide_link">Cerrar</a>
                            <h3 class="panel-title">Reservas para el dia seleccionado</h3>
                        </div>
                        <div class="panel-body">
                            <h4 style="color: red; display: none;" id="grooming_panel_error"></h4>
                            <table class="table table-striped table-bordered" id="grooming_bookings_table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Mascota</th>
                                        <th>Cliente</th>
                                        <th>Hora de reserva</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div >
            <div class="row">
                <div class="col-md-6 col-md-offset-2">
                    <div class="form-group">
                        <label for="grooming_hour">Hora</label>
                        <input type="text" class="form-control span2" id="grooming_hour"/>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 col-md-offset-2">
                    <div class="form-group">
                        <label for="client_id">Cliente</label>
                        <select id="client_id" style="width: 100%; height: 100%;">
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
                        <input type="button" class="btn btn-success" value="Agendar cita" id="grooming_book_date_btn"/>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    var nowTemp = new Date();
    var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);

    $('#grooming_date').datepicker({
        onRender: function(date) {
            return date.valueOf() < now.valueOf() ? 'disabled' : '';
        }
    })
            .on('changeDate', function(ev) {
        $('#grooming_date').datepicker('hide');
        displayBookedEvents();
    });

    function displayBookedEvents()
    {
        var dat = $('#grooming_date').val();
        var params = {
            date: dat
        };

        $.ajax({
            type: "POST",
            url: "<?php echo Router::url(array ('controller' => 'grooming', 'action' => 'getBookingsByDate')); ?>",
            datatype: "json",
            data: params,
            success: function(data)
            {
                var json = JSON.parse(data);
                if (json.result === "success") {

                    $('#grooming_bookings_table tbody').remove();
                    $.each(json.list, function(index, value)
                    {
                        var html = '<tr>';
                        html += '<td>' + this.Grooming.id + '</td>';
                        html += '<td><a href="#">' + this.Pet.name + '</a></td>';
                        html += '<td><a href="#">' + this.Client.name + '</a></td>';
                        html += '<td>' + (this.Grooming.booking_pet_arrival != null ? tConvert(this.Grooming.booking_pet_arrival) : '--') + '</td>';

                        $('#grooming_bookings_table').append(html);
                    });


                    $('#grooming_panel_error').hide();
                    $('#grooming_bookings_table').show();

                    if (!$('#grooming_bookings_table_panel').is(':visible')) {
                        $('#grooming_bookings_table_panel').slideDown();
                    }

                }
                else {
                    $('#grooming_bookings_table').hide();
                    $('#grooming_panel_error').text(json.error);
                    $('#grooming_panel_error').show();
                    if (!$('#grooming_bookings_table_panel').is(':visible')) {
                        $('#grooming_bookings_table_panel').slideDown();
                    }

                }
            }
        });
    }

    $('#client_id').select2().on('change', function()
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

    function tConvert(time) {
        // Check correct time format and split into components
        time = time.toString().match(/^([01]\d|2[0-3])(:)([0-5]\d)(:[0-5]\d)?$/) || [time];

        if (time.length > 1) { // If time format correct
            time = time.slice(1);  // Remove full string match value
            time[5] = +time[0] < 12 ? ' am' : ' pm'; // Set AM/PM
            time[0] = +time[0] % 12 || 12; // Adjust hours
        }
        return time.join(''); // return adjusted time or original string
    }

    $('#grooming_hide_link').click(function(e) {
        e.preventDefault()
        $('#grooming_bookings_table_panel').slideToggle();
    });

    $('#grooming_book_date_btn').click(function(e) {
        e.preventDefault();

        var data = {
            pet_id: $('#pet_id').val(),
            client_id: $('#client_id').val(),
            date: $('#grooming_date').val(),
            status: "Reserva",
            is_booking: 1,
            service_type: $('#service_type').val(),
            booking_pet_arrival:$('#grooming_hour').val(),
            booking_pet_arrived:0
        }

        $.ajax({
            type: "POST",
            url: "<?php echo Router::url(array ('controller' => 'grooming', 'action' => 'bookGroomingService')); ?>",
            data: data,
            success: function(data)
            {
                var json = JSON.parse(data);
                if (json.result === "success") {
                    $('#pet_id').val('');
                    $('#client_id').select2('data',null);
                    $('#grooming_date').val('');
                    $('#service_type').val('');
                    $('#grooming_hour').val('');
                    if ($('#grooming_bookings_table_panel').is(':visible')) {
                        $('#grooming_bookings_table_panel').slideUp();
                    }
                    noty({
                        text: "La reserva ha sido creada exitosamente",
                        type: "success",
                        timeout:1500
                    });
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
                        type: "error",
                        timeout:2000
                    });
                }
            }
        });
    });

</script>