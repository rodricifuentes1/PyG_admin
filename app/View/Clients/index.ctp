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

        <div class="col-md-3">
            <ul class="nav nav-pills nav-stacked">
                <li class="active"><a href="#">Registrar Cliente</a></li>
                <li><?php echo $this->Html->link("Ver clientes", array ('controller' => 'clients', 'action' => 'clientList')); ?></li>
            </ul>
        </div>
        <div class="col-md-9">

            <div class="row">
                <h1 style="color: orange; margin-top: 0;">Registrar cliente</h1>
                <br/>
            </div>

            <div class="row">
                <div class="col-md-6 col-md-offset-2">
                    <div class="form-group">
                        <label for="client_email">Correo electrónico</label>
                        <input type="email" class="form-control" id="client_email"/>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 col-md-offset-2">
                    <div class="form-group">
                        <label for="client_name">Nombre</label>
                        <input type="text" class="form-control" id="client_name"/>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 col-md-offset-2">
                    <div class="form-group">
                        <label for="client_id">Cédula</label>
                        <input type="text" class="form-control" id="client_id"/>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 col-md-offset-2">
                    <div class="form-group">
                        <label for="client_address">Dirección</label>
                        <input type="text" class="form-control" id="client_address"/>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 col-md-offset-2">
                    <div class="form-group">
                        <label for="client_phone">Teléfono fijo</label>
                        <input type="text" class="form-control" id="client_phone"/>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 col-md-offset-2">
                    <div class="form-group">
                        <label for="client_mobile_phone">Celular</label>
                        <input type="text" class="form-control" id="client_mobile_phone"/>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-1 col-md-offset-4">
                    <div class="form-group">
                        <input type="button" class="btn btn-success" value="Registrar cliente" id="btn_saveClient"/>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $('#btn_saveClient').click(function(e)
    {
        e.preventDefault();
        var client = {
            email: $('#client_email').val(),
            name: $('#client_name').val(),
            client_id: $('#client_id').val(),
            address: $('#client_address').val(),
            home_phone: $('#client_phone').val(),
            mobile_phone: $('#client_mobile_phone').val()
        };
        $.ajax({
            type: "POST",
            url: "<?php echo Router::url(array ('controller' => 'clients', 'action' => 'saveClient')); ?>",
            datatype: "json",
            data: client,
            success: function(data)
            {
                var json = JSON.parse(data);
                if (json.result === "success") {

                    $('#client_email').val('');
                    $('#client_name').val('');
                    $('#client_id').val('');
                    $('#client_address').val('');
                    $('#client_phone').val('');
                    $('#client_mobile_phone').val('');

                    noty({
                        text: "El cliente ha sido agregado exitosamente",
                        type: "success"
                    });

                }
                else {
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
</script>