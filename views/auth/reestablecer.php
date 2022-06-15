<div class="contenedor reestablecer">
    <?php include_once __DIR__ . '/../templates/nombre-sitio.php' ?>
    <div class="contenedor-sm">
        <p class="descripcion-pagina">Colocar tu nuevo Password</p>

        <form  class="formulario" method="POST">
            <div class="campo">
                <label for="password">Password</label>
                <input type="password" id="password" placeholder="Tu Password" name="password">
            </div>

            <input type="submit" class="boton" value="Iniciar Sesión">
        </form>

        <div class="acciones">
            <a href="/crear">¿Aún no tienes una cuenta? obtener una</a>
            <a href="/">¿Tienes una cuenta? Inciar sesión</a>
        </div>
    </div> <!--Contenedor-sm-->
</div>