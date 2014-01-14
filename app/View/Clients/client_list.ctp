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
            <li class="active"><?php echo $this->Html->link("Clientes", array ('controller' => 'clients', 'action' => 'index')); ?></li>
            <li><?php echo $this->Html->link("Mascotas", array ('controller' => 'pets', 'action' => 'index')); ?></li>
            <li><a href="#">Reportes</a></li>
        </ul>
    </div>
</nav>

<div class="container">
    <div class="row">

        <div class="col-md-2">
            <ul class="nav nav-pills nav-stacked">
                <li><?php echo $this->Html->link("Registrar cliente", array ('controller' => 'clients', 'action' => 'index')); ?></li>
                <li class="active"><a href="#">Ver Clientes</a></li>
            </ul>
        </div>
        <div class="col-md-10">

            <div class="row">
                <h1 style="color: orange; margin-top: 0;">Lista de clientes</h1>
                <br/>
            </div>
            <div class="row">
                <div class="col-md-3">
                    <select id="client_list_search_param" class="form-control">
                        <option disabled selected>Buscar por</option>
                        <option value="id">Id</option>
                        <option value="name">Nombre</option>
                        <option value="client_id">Cédula</option>
                        <option value="email">Correo</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <input type="text" class="form-control" placeholder="Ingresa tu busqueda" id="client_list_search_txt"/>
                </div>
                <div class="col-md-1">
                    <input type="button" class="btn btn-danger" value="Borrar" id="client_list_delete_btn"/>
                </div>
                <div class="col-md-2">
                    <input type="button" class="btn btn-primary btn-block" value="Buscar" id="client_list_search_btn"/>
                </div>
            </div>
            <br/>

            <div class="row">
                <div class="col-md-12">
                    <table class="table table-striped table-bordered" id="client_list_table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nombre</th>
                                <th>Cédula</th>
                                <th>Correo electrónico</th>
                                <th>Dirección</th>
                                <th>Teléfono</th>
                                <th>Celular</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($clients as $client) : ?>
                                <tr>
                                    <td><?php echo $client['Client']['id'] ?></td>
                                    <td><a href="#"><?php echo $client['Client']['name'] ?></a></td>
                                    <td><?php echo $client['Client']['client_id'] ?></td>
                                    <td><?php echo $client['Client']['email'] ?></td>
                                    <td><?php echo $client['Client']['address'] ?></td>
                                    <td><?php echo $client['Client']['home_phone'] ?></td>
                                    <td><?php echo $client['Client']['mobile_phone'] ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 col-md-offset-5">
                    <ul class="pagination" id="client_list_ul_pag">
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
<script>
function filter(clear)
    {
        var query = {
            param: $('#client_list_search_param').val(),
            query: $('#client_list_search_txt').val(),
            max_per_page: $('#max_per_page').val(),
            selected_page: 1
        }

        if (clear === false && ($('#client_list_search_param').val() === null || $('#client_list_search_txt').val() === "")) {
            noty({
                text: "Rellena bien los campos",
                type: "error",
                timeout: 2000
            });
            $('#client_list_search_param').addClass('error_default');
            $('#client_list_search_txt').addClass('error_default');
            setTimeout(function()
            {
                $('#client_list_search_param').removeClass('error_default');
                $('#client_list_search_txt').removeClass('error_default');
            }, 2000);
        }

        else {
            $.ajax({
                type: "POST",
                url: "<?php echo Router::url(array ('controller' => 'clients', 'action' => 'filterClients')); ?>",
                datatype: "json",
                data: query,
                success: function(data)
                {
                    var json = JSON.parse(data);
                    if (json.result === "success") {
                        $('#client_list_table tbody').remove();
                        $.each(json.list, function(index, value)
                        {
                            var html = '<tr>';
                            html += '<td>' + this.Client.id + '</td>';
                            html += '<td><a href="#">' + this.Client.name + '</a></td>';
                            html += '<td>' + this.Client.client_id + '</td>';
                            html += '<td>' + this.Client.email + '</td>';
                            html += '<td>' + this.Client.address + '</td>';
                            html += '<td>' + this.Client.home_phone + '</td>';
                            html += '<td>' + this.Client.mobile_phone + '</td>';
                            
                            $('#client_list_table').append(html);
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

    $('#client_list_search_btn').click(function(e) {
        e.preventDefault();
        filter(false);
    });

    $('#client_list_delete_btn').click(function(e) {
        e.preventDefault();
        $('#client_list_search_param').val('');
        $('#client_list_search_txt').val('');
        filter(true);
        $('#client_list_ul_pag .active').removeClass('active');
        $('#pagination_1').parent().addClass('active');
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
            url: "<?php echo Router::url(array ('controller' => 'clients', 'action' => 'getAjaxPaginatedList')); ?>",
            datatype: "json",
            data: params,
            success: function(data)
            {
                var json = JSON.parse(data);
                if (json.result === "success") {
                    $('#client_list_table tbody').remove();
                    $.each(json.list, function(index, value)
                    {
                        var html = '<tr>';
                            html += '<td>' + this.Client.id + '</td>';
                            html += '<td><a href="#">' + this.Client.name + '</a></td>';
                            html += '<td>' + this.Client.client_id + '</td>';
                            html += '<td>' + this.Client.email + '</td>';
                            html += '<td>' + this.Client.address + '</td>';
                            html += '<td>' + this.Client.home_phone + '</td>';
                            html += '<td>' + this.Client.mobile_phone + '</td>';
                            
                            $('#client_list_table').append(html);

                    });
                    $('#client_list_ul_pag .active').removeClass('active');
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
    
</script>