<?php
session_start();
?>
<html>
<head>
<title>Pagina Login</title>
</head>
<body>
<?php
if(isset($_SESSION['usuario'])){
echo "<p>Usuario: " . $_SESSION['usuario'] . "";

echo "<p><a href='pe_altaped.php'>Compra de Productos</a></p>";
echo "<p><a href='.php'>Consulta de Compras</a></p>";

echo "<p><a href='pagina3.php'>Cerrar Sesion</a></p>";
}else {
?>
<form action="pe_inicio.php" method="POST">
<h1> Login </h1>
<p>Usuario:<input type="text" placeholder="Introduce usuario" name="usuario" required/></p>
<p>Clave:<input type="password" placeholder="Introduce clave" name="clave" required/></p><br />
<input type="submit" value="Login" />

</form>
<?php
}
?>
</body>
</html>