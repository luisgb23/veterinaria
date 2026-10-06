<?php include ("templates/header.php");?>
    <div class="card">
        <div class="card-header">
          <h3 class="text-center">Agregar especie</h3>
        </div>
        <div class="card-body">
            <form action="agregarEspecie.php" method="post">
                <div class="mb-3">
                    <label for="descripción" class="form-label">Descripción</label>
                    <input type="text" id="descripción" class="form-control" name="txtNombre" required>
                </div>
                <input type="submit" class="btn btn-success" value="Agregar">
                <a href="especies.php" class="btn btn-danger">Cancelar</a>
            </form>
        </div>
    </div>
<?php include ("templates/footer.php");?>