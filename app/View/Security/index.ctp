<div class="container">
    <div class="row">
        <div class="col-md-6 col-md-offset-3">

            <h2>Inicia sesión</h2>
            <div class="jumbotron">
                <form role="form" method="post" action="">
                    <div class="form-group">
                        <label for="user_id">Usuario</label>
                        <input type="text" class="form-control" id="user_id" placeholder="Escribe tu usuario">
                    </div>
                    <div class="form-group">
                        <label for="user_password">Contraseña</label>
                        <input type="password" class="form-control" id="user_password" placeholder="Contraseña">
                    </div>
                    <button type="submit" class="btn btn-success btn-lg">Ingresar</button>
                </form>
            </div>

        </div>
    </div>
</div>
<script>
    $('#user_id').focus();
</script>