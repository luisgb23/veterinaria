<?php include ("templates/header.php");

$mysqli = include_once "includes/conexion.php";
$resultado = $mysqli->query("SELECT * FROM especies WHERE EspecieEstado = 1");
$especies = $resultado->fetch_all(MYSQLI_ASSOC);

?>
<div class="card">
    <div class="card-header">
        <div class="cabezal">
            <h3 class="text-center">Especies</h3>
            <a href="frmAgregarEspecie.php" class="btn btn-success"><i class="bi bi-plus-circle"></i> Agregar</a>
        </div>
    </div>
    <div class="card-body">
        <div class="responsive-table">
            <table class="table table-hover text-center" id="myTable">
                <thead>
                <tr>
                    <th scope="col" class="text-center">Código</th>
                    <th scope="col" class="text-center">Descripción</th>
                    <th scope="col" class="text-center">Acciones</th>
                </tr>
                </thead>
                <tbody>
                <?php
                foreach ($especies as $especie){
                    ?>
                <tr>
                    <td><?php echo $especie['EspecieId'];?></td>
                    <td><?php echo $especie['EspecieNombre'];?></td>
                    <td>
                        <a href="frmEditarEspecie.php?id=<?php echo $especie['EspecieId'];?>" class="btn btn-warning"><i class="bi bi-pencil-square"></i> Editar</a>
                        <a href="eliminarEspecie.php?id=<?php echo $especie['EspecieId'];?>" class="btn btn-danger btn-del"><i class="bi bi-trash3-fill"></i> Eliminar</a>
                    </td>
                </tr>
                <?php
                    }
                ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script src="js/jquery-3.6.0.min.js"></script>
<script src="js/sweetalert2.all.min.js"></script>
<script>
        $('.btn-del').on('click', function(e){
        e.preventDefault();
        const href = $(this).attr('href')

        Swal.fire({
        title: 'Estas seguro de eliminar?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si, eliminar!'
    }).then((result)=>{
        if(result.value){
        document.location.href = href;
        Swal.fire(
        'Eliminada!',
        'La especie fue eliminada!',
        'success'
        )
    }
    })
    })
</script>


<?php include ("templates/footer.php");?>
