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

        <div class="col-md-2">
            <ul class="nav nav-pills nav-stacked">
                <li><?php echo $this->Html->link("Registrar mascota", array ('controller' => 'pets', 'action' => 'index')); ?></li>
                <li class="active"><a href="#">Ver mascotas</a></li>
            </ul>
        </div>
        <div class="col-md-10">

            <div class="row">
                <h1 style="color: orange; margin-top: 0;">Lista de mascotas</h1>
                <br/>
            </div>
            <div class="row">
                <div class="col-md-3">
                    <select id="pet_list_search_param" class="form-control">
                        <option value="" selected disabled>Buscar por</option>
                        <option value="id">Id</option>
                        <option value="name">Nombre</option>
                        <option value="breed">Raza</option>
                        <option value="color">Color</option>
                        <option value="age">Edad</option>
                        <option value="specie">Especie</option>
                        <option value="client_name">Dueño</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <input type="text" class="form-control" placeholder="Ingresa tu busqueda" id="pet_list_search_txt"/>
                </div>
                <div class="col-md-1">
                    <input type="button" class="btn btn-danger" value="Borrar" id="pet_list_delete_btn"/>
                </div>
                <div class="col-md-2">
                    <input type="button" class="btn btn-primary btn-block" value="Buscar" id="pet_list_search_btn"/>
                </div>

            </div>
            <br/>
            <div class="row">
                <div class="col-md-12">
                    <table class="table table-striped table-bordered" id="pet_list_table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nombre</th>
                                <th>Raza</th>
                                <th>Color</th>
                                <th>Edad</th>
                                <th>Especie</th>
                                <th>Sexo</th>
                                <th>Dueño</th>
                                <th>Indicaciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($pets as $key => $pet): ?>
                                <tr>
                                    <td><?php echo $pet['Pet']['id']; ?></td>
                                    <td><a href="#"><?php echo $pet['Pet']['name']; ?></a></td>
                                    <td><?php echo isset($pet['DogBreed']['breed']) ? $pet['DogBreed']['breed'] : $pet['CatBreed']['breed']; ?></td>
                                    <td><?php echo $pet['Pet']['color']; ?></td>
                                    <td><?php echo $pet['Pet']['age']; ?></td>
                                    <td><?php echo $pet['Pet']['specie'] === 'canine' ? 'Canino' : 'Felino'; ?></td>
                                    <td><?php echo $pet['Pet']['gender'] === 'male' ? 'Masculino' : 'Femenino'; ?></td>
                                    <td><?php echo isset($pet['Client'][0]['name']) ? $pet['Client'][0]['name'] : "No esta registrado"; ?></td>
                                    <td><?php echo $pet['Pet']['medical_issues'] !== '' ? '<a class="btn btn-warning btn-xs btn-block" id="pet_list_medical_issues_' . $pet['Pet']['id'] . '">Leer</a>' : '--'; ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 col-md-offset-5">
                    <ul class="pagination" id="pet_list_ul_pag">
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
<div class="modal fade" id="pet_list_medical_issues_modal_dialog">
 
</div>
<script>

    function filter(clear)
    {
        var query = {
            param: $('#pet_list_search_param').val(),
            query: $('#pet_list_search_txt').val(),
            max_per_page: $('#max_per_page').val(),
            selected_page: 1
        }

        if (clear === false && ($('#pet_list_search_param').val() === null || $('#pet_list_search_txt').val() === "")) {
            noty({
                text: "Rellena bien los campos",
                type: "error",
                timeout: 2000
            });
            $('#pet_list_search_param').addClass('error_default');
            $('#pet_list_search_txt').addClass('error_default');
            setTimeout(function()
            {
                $('#pet_list_search_param').removeClass('error_default');
                $('#pet_list_search_txt').removeClass('error_default');
            }, 2000);
        }

        else {
            $.ajax({
                type: "POST",
                url: "<?php echo Router::url(array ('controller' => 'pets', 'action' => 'filterPets')); ?>",
                datatype: "json",
                data: query,
                success: function(data)
                {
                    var json = JSON.parse(data);
                    if (json.result === "success") {
                        $('#pet_list_table tbody').remove();
                        $.each(json.list, function(index, value)
                        {
                            var html = '<tr>';
                            html += '<td>' + this.Pet.id + '</td>';
                            html += '<td><a href="#">' + this.Pet.name + '</a></td>';
                            html += '<td>';
                            if (this.DogBreed.breed !== null) {
                                html += this.DogBreed.breed;
                            }
                            else {
                                html += this.CatBreed.breed;
                            }
                            html += '</td>';
                            html += '<td>' + this.Pet.color + '</td>';
                            html += '<td>' + this.Pet.age + '</td>';
                            html += '<td>' + (this.Pet.specie === 'canine' ? 'Canino' : 'Felino') + '</td>';
                            html += '<td>' + (this.Pet.gender === 'male' ? 'Masculino' : 'Femenino') + '</td>';
                            html += '<td>';
                            if (Object.prototype.toString.call(this.Client) === '[object Array]') {
                                html += this.Client[0].name;
                            }
                            else if (this.Client) {
                                html += this.Client.name;
                            }
                            else {
                                html += "No esta registrado";
                            }
                            html += '</td>';
                            html += '<td>' + (this.Pet.medical_issues !== '' ? '<a class="btn btn-warning btn-xs btn-block" id="pet_list_medical_issues_' + this.Pet.id + '">Leer</a>' : '--') + '</td>';
                            html += '</tr>';

                            $('#pet_list_table').append(html);
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

    $('#pet_list_search_btn').click(function(e) {
        e.preventDefault();
        filter(false);
    });

    $('#pet_list_delete_btn').click(function(e) {
        e.preventDefault();
        $('#pet_list_search_param').val('');
        $('#pet_list_search_txt').val('');
        filter(true);
        $('#pet_list_ul_pag .active').removeClass('active');
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
            url: "<?php echo Router::url(array ('controller' => 'pets', 'action' => 'getAjaxPaginatedList')); ?>",
            datatype: "json",
            data: params,
            success: function(data)
            {
                var json = JSON.parse(data);
                if (json.result === "success") {
                    $('#pet_list_table tbody').remove();
                    $.each(json.list, function(index, value)
                    {
                        var html = '<tr>';
                        html += '<td>' + this.Pet.id + '</td>';
                        html += '<td><a href="#">' + this.Pet.name + '</a></td>';
                        html += '<td>';
                        if (this.DogBreed.breed !== null) {
                            html += this.DogBreed.breed;
                        }
                        else {
                            html += this.CatBreed.breed;
                        }
                        html += '</td>';
                        html += '<td>' + this.Pet.color + '</td>';
                        html += '<td>' + this.Pet.age + '</td>';
                        html += '<td>' + (this.Pet.specie === 'canine' ? 'Canino' : 'Felino') + '</td>';
                        html += '<td>' + (this.Pet.gender === 'male' ? 'Masculino' : 'Femenino') + '</td>';
                        html += '<td>';
                        if (Object.prototype.toString.call(this.Client) === '[object Array]') {
                            html += this.Client[0].name;
                        }
                        else if (this.Client) {
                            html += this.Client.name;
                        }
                        else {
                            html += "No esta registrado";
                        }
                        html += '</td>';
                        html += '<td>' + (this.Pet.medical_issues !== '' ? '<a class="btn btn-warning btn-xs btn-block" id="pet_list_medical_issues_' + this.Pet.id + '">Leer</a>' : '--') + '</td>';
                        html += '</tr>';

                        $('#pet_list_table').append(html);

                    });
                    $('#pet_list_ul_pag .active').removeClass('active');
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
    
    $('#pet_list_table').on('click','a[id^="pet_list_medical_issues_"]',function (e){
        e.preventDefault();
        
        var selected_pet = this.id + "";
        var id = selected_pet.split("_")[4];
        
        var sel={
            pet_id:id
        };
        
        $.ajax({
            type: "POST",
            url: "<?php echo Router::url(array ('controller' => 'pets', 'action' => 'getPetMedicalIssues')); ?>",
            datatype: "json",
            data: sel,
            success: function(data)
            {
                var json = JSON.parse(data);
                if (json.result === "success") {
                    $('#pet_list_medical_issues_modal_dialog').html(json.html).modal('show');
                }
            }
        });
    });
</script>