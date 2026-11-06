<?php
//CLASE PRINCIPAL
class Conectar {
    private $servidor = "localhost";
    private $usuario = "root";
    private $contrasena = "";
    private $BD = "bd_jugueteria";
    public $conexion;
	//CONEXION
    public function __construct() {
        $this->conexion = mysqli_connect($this->servidor, $this->usuario, $this->contrasena, $this->BD);
        if (!$this->conexion) {
            die('No pudo conectarse: ' . mysqli_connect_error());
        }
        mysqli_set_charset($this->conexion, "utf8");
    }
	//METODO AGREGAR TIPO
    public function agregarTipo($nomtip) {
        $sql = "INSERT INTO tipo (nomtip) VALUES ('$nomtip')";
        mysqli_query($this->conexion, $sql) or die(mysqli_error($this->conexion));
    }
	//METODO LISTAR TIPO
    public function listarTipo() {
        $sql = "SELECT codtip, nomtip FROM tipo";
        $res = mysqli_query($this->conexion, $sql);
        $vec = array();
        while ($f = mysqli_fetch_array($res)) {
            $vec[] = $f;
        }
        return $vec;
    }
	//METODO ELIMINAR TIPO
    public function eliminarTipo($codtip) {
        $sql = "DELETE FROM tipo WHERE codtip='$codtip'";
        mysqli_query($this->conexion, $sql) or die(mysqli_error($this->conexion));
    }
    //METODO AGREGAR PRODUCTO
    public function agregarProducto($nompro, $prepro, $canpro, $fotpro, $codtip) {
        $sql = "INSERT INTO producto (nompro, prepro, canpro, fotpro, codtip) 
                VALUES ('$nompro', '$prepro', '$canpro', '$fotpro', '$codtip')";
        mysqli_query($this->conexion, $sql) or die(mysqli_error($this->conexion));
    }
	//METODO LISTAR PRODUCTO
    public function listarProducto() {
        $sql = "SELECT p.codpro, p.nompro, p.prepro, p.canpro, p.fotpro, t.nomtip 
                FROM producto p INNER JOIN tipo t ON p.codtip = t.codtip";
        $res = mysqli_query($this->conexion, $sql);
        $vec = array();
        while ($f = mysqli_fetch_array($res)) {
            $vec[] = $f;
        }
        return $vec;
    }
	//METODO ELIMINAR PRODUCTO
    public function eliminarProducto($codpro) {
        $sql = "DELETE FROM producto WHERE codpro='$codpro'";
        mysqli_query($this->conexion, $sql) or die(mysqli_error($this->conexion));
    }
}
//CREACION DEL OBJETO
$obj = new Conectar();
//CONTROL DE ACCIONES DEL FORMULARIO REGISTRAR TIPO
if (isset($_POST['registrar_tipo'])) {
    $obj->agregarTipo($_POST['nomtip']);
    header("Location: index.php");
    exit();
}
//CONTROL DE ACCIONES DEL FORMULARIO REGISTRAR PRODUCTO
if (isset($_POST['registrar_producto'])) {
    // Manejo de imagen (guardando la ruta en la BD)
    $foto = $_FILES['fot']['name'];
    $ruta = $_FILES['fot']['tmp_name'];
    $destino = "img/" . $foto;
    copy($ruta, $destino);

    $obj->agregarProducto(
        $_POST['nompro'],
        $_POST['prepro'],
        $_POST['canpro'],
        $destino,
        $_POST['codtip']
    );
    header("Location: index.php");
    exit();
}
//CONTROL DE ACCIONES DEL FORMULARIO ELIMINAR TIPO
if (isset($_GET['eliminar_tipo'])) {
    $obj->eliminarTipo($_GET['eliminar_tipo']);
    header("Location: index.php");
    exit();
}
//CONTROL DE ACCIONES DEL FORMULARIO ELIMINAR PRODUCTO
if (isset($_GET['eliminar_producto'])) {
    $obj->eliminarProducto($_GET['eliminar_producto']);
    header("Location: index.php");
    exit();
}
//OBTENER LISTAS ACTUALIZADAS
$tipos = $obj->listarTipo();
$productos = $obj->listarProducto();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Aplicación Web - BD Juguetería</title>
</head>
<body>
	<center>
    <h1>Gestión de Juguetería</h1>
    <!-- FORMULARIO: REGISTRAR TIPO -->
    <h2>Registrar Tipo</h2>
    <form method="POST">
        <label>Nombre del Tipo:</label>
		</br>
		</br>
        <input type="text" name="nomtip" required>
        </br>
		</br>
		<input type="submit" name="registrar_tipo" value="Guardar Tipo">
    </form>
    <!-- LISTAR TIPOS -->
    <h2>Listado de Tipos</h2>
    <table border="1">
        <tr>
            <th>Código</th>
            <th>Nombre del Tipo</th>
            <th>Acción</th>
        </tr>
        <?php foreach ($tipos as $t): ?>
        <tr>
            <td><?= $t[0]; ?></td>
            <td><?= $t[1]; ?></td>
            <td><a href="index.php?eliminar_tipo=<?= $t[0]; ?>" onclick="return confirm('¿Desea eliminar este tipo?');">Borrar</a></td>
        </tr>
        <?php endforeach; ?>
    </table>
    <!-- FORMULARIO: REGISTRAR PRODUCTO -->
    <h2>Registrar Producto</h2>
    <form method="POST" enctype="multipart/form-data">
        <label>Nombre del Producto:</label>
        <input type="text" name="nompro" required>
		</br>
		</br>
        <label>Precio:</label>
        <input type="number" step="0.01" name="prepro" required>
		</br>
		</br>
        <label>Cantidad:</label>
        <input type="number" name="canpro" required>
		</br>
		</br>
        <label>Foto:</label>
        <input type="file" name="fot" required>
		</br>
		</br>
        <label>Tipo de Juguete:</label>
        <select name="codtip" required>
            <option value="">Seleccione un tipo</option>
            <?php foreach ($tipos as $t): ?>
                <option value="<?= $t[0]; ?>"><?= $t[1]; ?></option>
            <?php endforeach; ?>
        </select>
		</br>
		</br>
        <input type="submit" name="registrar_producto" value="Guardar Producto">
	</form>
    <!-- LISTAR PRODUCTOS -->
    <h2>Listado de Productos</h2>
    <table border="1">
        <tr>
            <th>Código</th>
            <th>Nombre</th>
            <th>Precio</th>
            <th>Cantidad</th>
            <th>Foto</th>
            <th>Tipo</th>
            <th>Acción</th>
        </tr>
        <?php foreach ($productos as $p): ?>
        <tr>
            <td><?= $p[0]; ?></td>
            <td><?= $p[1]; ?></td>
            <td>$<?= number_format($p[2], 2); ?></td>
            <td><?= $p[3]; ?></td>
            <td><img src="<?= $p[4]; ?>" width="50" height="50"></td>
            <td><?= $p[5]; ?></td>
            <td><a href="index.php?eliminar_producto=<?= $p[0]; ?>" onclick="return confirm('¿Desea eliminar este producto?');">Borrar</a></td>
        </tr>
        <?php endforeach; ?>
    </table>
	</center>
</body>
</html>
