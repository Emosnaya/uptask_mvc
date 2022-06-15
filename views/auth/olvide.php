<div class="contenedor olvide">
    <?php include_once __DIR__ . '/../templates/nombre-sitio.php' ?>
    <div class="contenedor-sm">
        <p class="descripcion-pagina">Coloca tu Email</p>

        <form action="/olvide" class="formulario" method="POST">
            <div class="campo">
                <label for="email">Email</label>
                <input type="email" id="email" placeholder="Tu Email" name="email">
            </div>

            <input type="submit" class="boton" value="Enviar">
        </form>

        <div class="acciones">
            <a href="/">¿Tienes una cuenta? Inciar sesión</a>
            <a href="/crear">¿Aún no tienes una cuenta? obtener una</a>
        </div>
    </div> <!--Contenedor-sm-->
</div>