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

        <div class="col-md-2">
            <ul class="nav nav-pills nav-stacked">
                <li><?php echo $this->Html->link("Registrar baño", array ('controller' => 'grooming', 'action' => 'index')); ?></li>
                <li class="active"><a href="#">Historial de baños</a></li>
                <li><?php echo $this->Html->link("Reservar turno", array ('controller' => 'grooming', 'action' => 'booking')); ?></li>
            </ul>
        </div>
        <div class="col-md-10">

            <div class="row">
                <h1 style="color: orange; margin-top: 0;">Historial de baños</h1>
                <br/>
            </div>
            <div class="row">
                <div class="col-md-3">
                    <select id="grooming_list_search_param" class="form-control">
                        <option disabled selected>Buscar por</option>
                        <option value="id">Numero baño</option>
                        <option value="pet_name">Mascota</option>
                        <option value="client_name">Cliente</option>
                        <option value="date_range">Rengo de fechas</option>
                        <option value="start_hour">Hora de inicio</option>
                        <option value="end_hour">Hora fin</option>
                        <option value="pet_arrival">Hora llegada</option>
                        <option value="pet_departure">Hora salida</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <div class="col-md-12" id="div_texto_busqueda">
                        <input type="text" class="form-control" placeholder="Ingresa tu busqueda" id="grooming_list_search_txt"/>
                    </div>
                    <div style="display: none;" id="div_fecha_1" class="col-md-6">
                        <input type="text" class="form-control" placeholder="Fecha inicial" id="grooming_list_search_date1" data-date-format="dd/mm/yyyy"/>
                    </div>
                    <div style="display: none;" id="div_fecha_2" class="col-md-6">
                        <input type="text" class="form-control" placeholder="Fecha final" id="grooming_list_search_date2" data-date-format="dd/mm/yyyy"/>
                    </div>
                </div>
                <div class="col-md-1">
                    <input type="button" class="btn btn-danger" value="Borrar" id="grooming_list_delete_btn"/>
                </div>
                <div class="col-md-2">
                    <input type="button" class="btn btn-primary btn-block" value="Buscar" id="grooming_list_search_btn"/>
                </div>
            </div>
            <br/>
            <div class="row">
                <div class="col-md-12">
                    <table class="table table-striped table-bordered" id="grooming_list_table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Mascota</th>
                                <th>Cliente</th>
                                <th>Fecha</th>
                                <th>Hora llegada</th>
                                <th>Hora inicio</th>
                                <th>Hora fin</th>
                                <th>Hora salida</th>
                                <th>Precio</th>
                                <th>Estado</th>
                                <th>Reporte</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($baths as $bath) : ?>
                                <tr>
                                    <td><?php echo $bath['Grooming']['id'] ?></td>
                                    <td><a href="#"><?php echo $bath['Pet']['name'] ?></a></td>
                                    <td><a href="#"><?php echo $bath['Client']['name'] ?></a></td>
                                    <td><?php echo $bath['Grooming']['date'] ?></td>
                                    <td><?php echo isset($bath['Grooming']['pet_arrival']) ? date("g:i:s a", STRTOTIME($bath['Grooming']['pet_arrival'])) : '--'; ?></td>
                                    <td><?php echo isset($bath['Grooming']['grooming_start_hour']) ? date("g:i:s a", STRTOTIME($bath['Grooming']['grooming_start_hour'])) : '--'; ?></td>
                                    <td><?php echo isset($bath['Grooming']['grooming_end_hour']) ? date("g:i:s a", STRTOTIME($bath['Grooming']['grooming_end_hour'])) : '--'; ?></td>
                                    <td><?php echo isset($bath['Grooming']['pet_departure']) ? date("g:i:s a", STRTOTIME($bath['Grooming']['pet_departure'])) : '--'; ?></td>
                                    <td><span style="color:green;font-weight: bold;">$<?php echo $bath['Grooming']['price'] ?></span></td>
                                    <td><?php echo $bath['Grooming']['status'] ?></td>
                                    <td><?php echo isset($bath['Grooming']['grooming_report']) ? '<a class="btn btn-warning btn-xs btn-block" id="grooming_report_' . $bath['Grooming']['id'] . '">Leer</a>' : '--'; ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 col-md-offset-5">
                    <ul class="pagination" id="grooming_list_ul_pag">
                        <!--<li <?php echo(($current_page - 1) === 0 ? 'class="disabled"' : ''); ?>><a href="#">&laquo;</a></li>-->
                        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                            <li <?php echo($current_page === $i ? 'class="active"' : ''); ?>><a href="#" id="pagination_<?php echo $i; ?>"><?php echo $i; ?></a></li>
                        <?php endfor; ?>
    <!--<li <?php echo(($current_page + 1) > $total_pages ? 'class="disabled"' : ''); ?>><a href="#">&raquo;</a></li>-->
                    </ul>
                </div>
                <input type="hidden" id="max_per_page" value="<?php echo $max_per_page; ?>"/>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="grooming_list_report_modal">

</div>
<script>

    var nowTemp = new Date();
    var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);

    $('#grooming_list_search_date1, #grooming_list_search_date2').datepicker({
        onRender: function(date) {
            return date.valueOf() < now.valueOf() ? 'disabled' : '';
        }
    })
            .on('changeDate', function(ev) {
        $('#grooming_list_search_date1,#grooming_list_search_date2').datepicker('hide');
    });

    function filter(clear)
    {
        if ($('#grooming_list_search_param').val() === 'date_range') {

        }
        else {
            var query = {
                param: $('#grooming_list_search_param').val(),
                query: $('#grooming_list_search_txt').val(),
                max_per_page: $('#max_per_page').val(),
                selected_page: 1
            }

            if (clear === false && ($('#grooming_list_search_param').val() === null || $('#grooming_list_search_txt').val() === "")) {
                noty({
                    text: "Rellena bien los campos",
                    type: "error",
                    timeout: 2000
                });
                $('#grooming_list_search_param').addClass('error_default');
                $('#grooming_list_search_txt').addClass('error_default');
                setTimeout(function()
                {
                    $('#grooming_list_search_param').removeClass('error_default');
                    $('#grooming_list_search_txt').removeClass('error_default');
                }, 2000);
            }

            else {
                $.ajax({
                    type: "POST",
                    url: "<?php echo Router::url(array ('controller' => 'grooming', 'action' => 'filterGrooming')); ?>",
                    datatype: "json",
                    data: query,
                    success: function(data)
                    {
                        var json = JSON.parse(data);
                        if (json.result === "success") {
                            $('#grooming_list_table tbody').remove();

                            $.each(json.list, function(index, value)
                            {
                                var html = '<tr>';
                                html += '<td>' + this.Grooming.id + '</td>';
                                html += '<td><a href="#">' + this.Pet.name + '</a></td>';
                                html += '<td><a href="#">' + this.Client.name + '</a></td>';
                                html += '<td>' + this.Grooming.date + '</td>';
                                html += '<td>' + (this.Grooming.pet_arrival != null ? tConvert(this.Grooming.pet_arrival) : '--') + '</td>';
                                html += '<td>' + (this.Grooming.start_hour != null ? tConvert(this.Grooming.start_hour) : '--') + '</td>';
                                html += '<td>' + (this.Grooming.end_hour != null ? tConvert(this.Grooming.end_hour) : '--') + '</td>';
                                html += '<td>' + (this.Grooming.pet_departure != null ? tConvert(this.Grooming.pet_departure) : '--') + '</td>';
                                html += '<td><span style="color:green;font-weight: bold;">$' + (this.Grooming.price != null ? this.Grooming.price : '') + '</span></td>';
                                html += '<td>' + this.Grooming.status + '</td>';
                                html += '<td>' + (this.Grooming.grooming_report != null ? '<a class="btn btn-warning btn-xs btn-block" id="grooming_report_' + this.Grooming.id + '">Leer</a>' : '--') + '</td>';

                                $('#grooming_list_table').append(html);
                            });
                        }
                        else {
                            noty({
                                text: json.error,
                                type: "error",
                                timeout: 2000
                            });
                        }

                    }
                });
            }
        }
    }

    $('#grooming_list_search_btn').click(function(e) {
        e.preventDefault();
        filter(false);
    });

    $('#grooming_list_delete_btn').click(function(e) {
        e.preventDefault();
        $('#grooming_list_search_param').val('');
        $('#grooming_list_search_txt').val('');
        $('#grooming_list_search_date1').val('');
        $('#grooming_list_search_date2').val('');
        filter(true);
        $('#grooming_list_ul_pag .active').removeClass('active');
        $('#pagination_1').parent().addClass('active');
        if(!$('#div_texto_busqueda').is(':visible')){
            $('#div_fecha_1, #div_fecha_2').fadeOut(400, function() {
                $('#div_texto_busqueda').fadeIn();
            });
        }
    });

    $('a[id^="pagination_"]').click(function(e) {
        e.preventDefault();

        var selected_page = this.id + "";
        var id = selected_page.split("_")[1];

        var params = {
            selected_page: id,
            max_per_page: $('#max_per_page').val()
        };

        $.ajax({
            type: "POST",
            url: "<?php echo Router::url(array ('controller' => 'grooming', 'action' => 'getAjaxPaginatedList')); ?>",
            datatype: "json",
            data: params,
            success: function(data)
            {
                var json = JSON.parse(data);
                if (json.result === "success") {

                    $('#grooming_list_table tbody').remove();
                    $.each(json.list, function(index, value)
                    {
                        var html = '<tr>';
                        html += '<td>' + this.Grooming.id + '</td>';
                        html += '<td><a href="#">' + this.Pet.name + '</a></td>';
                        html += '<td><a href="#">' + this.Client.name + '</a></td>';
                        html += '<td>' + this.Grooming.date + '</td>';
                        html += '<td>' + (this.Grooming.pet_arrival != null ? tConvert(this.Grooming.pet_arrival) : '--') + '</td>';
                        html += '<td>' + (this.Grooming.start_hour != null ? tConvert(this.Grooming.start_hour) : '--') + '</td>';
                        html += '<td>' + (this.Grooming.end_hour != null ? tConvert(this.Grooming.end_hour) : '--') + '</td>';
                        html += '<td>' + (this.Grooming.pet_departure != null ? tConvert(this.Grooming.pet_departure) : '--') + '</td>';
                        html += '<td><span style="color:green;font-weight: bold;">$' + (this.Grooming.price != null ? this.Grooming.price : '') + '</span></td>';
                        html += '<td>' + this.Grooming.status + '</td>';
                        html += '<td>' + (this.Grooming.grooming_report != null ? '<a class="btn btn-warning btn-xs btn-block" id="grooming_report_' + this.Grooming.id + '">Leer</a>' : '--') + '</td>';

                        $('#grooming_list_table').append(html);

                    });
                    $('#grooming_list_ul_pag .active').removeClass('active');
                    $('#' + selected_page).parent().addClass('active');
                }
                else {
                    noty({
                        text: json.error,
                        type: "error",
                        timeout: 2000
                    });
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

    $('#grooming_list_table').on('click', 'a[id^="grooming_report_"]', function(e) {
        e.preventDefault();

        var selected_bath = this.id + "";
        var id = selected_bath.split("_")[2];

        var sel = {
            grooming_id: id
        };

        $.ajax({
            type: "POST",
            url: "<?php echo Router::url(array ('controller' => 'grooming', 'action' => 'getGroomingReport')); ?>",
            datatype: "json",
            data: sel,
            success: function(data)
            {
                var json = JSON.parse(data);
                if (json.result === "success") {
                    $('#grooming_list_report_modal').html(json.html).modal('show');
                }
            }
        });
    });

    $('#grooming_list_search_param').change(function() {
        if ($('#grooming_list_search_param').val() === 'date_range') {
            $('#div_texto_busqueda').fadeOut(400, function() {
                $('#div_fecha_1').fadeIn();
                $('#div_fecha_2').fadeIn();
            });
        }
        else {
            $('#div_fecha_1, #div_fecha_2').fadeOut(400, function() {
                $('#div_texto_busqueda').fadeIn();
            });
        }
    });
</script>