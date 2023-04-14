<?php
session_start();
include "includes/config.php";
include "includes/funciones.php";

if(isset($_SESSION['usuario'])) {
	header("Location: index.php");
}

ini_set('error_reporting',0);
?>
<style>
	html,
body {
  height: 100%;
}

body {
  display: flex;
  align-items: center;
  padding-top: 40px;
  padding-bottom: 40px;
  background-color: #f5f5f5;
}

.form-signin {
  max-width: 330px;
  padding: 15px;
}

.form-signin .form-floating:focus-within {
  z-index: 2;
}

.form-signin input[type="email"] {
  margin-bottom: -1px;
  border-bottom-right-radius: 0;
  border-bottom-left-radius: 0;
}

.form-signin input[type="password"] {
  margin-bottom: 10px;
  border-top-left-radius: 0;
  border-top-right-radius: 0;
}
</style>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css">
<form id="form1" name="form1" method="post" action="">
  <p>
    <label for="textfield"></label>
    Usuario: 
    <input type="text" name="usuario" id="textfield" />
  </p>
  <p>
    Contraseña: 
    <input type="password" name="contrasena" id="textfield2" />
  </p>
  <p>
    <input type="submit" name="guardar" id="button" value="Entrar" />
  </p>
</form>


<main class="form-signin w-100 m-auto">
	
  <form id="form1" name="form1" method="post" action="">
    <img class="mb-4" src="/docs/5.3/assets/brand/bootstrap-logo.svg" alt="" width="72" height="57">
    <h1 class="h3 mb-3 fw-normal">Please sign in</h1>

    <div class="form-floating">
      <input type="text" class="form-control" id="floatingInput" placeholder="" name="usuario" >
      <label for="floatingInput">Usuario</label>
    </div>
    <div class="form-floating">
      <input type="password" class="form-control" id="floatingPassword" placeholder="" name="contrasena">
      <label for="floatingPassword">Password</label>
    </div>

    <div class="checkbox mb-3">
      <label>
        <input type="checkbox" value="remember-me"> Remember me
      </label>
    </div>
    <button class="w-100 btn btn-lg btn-primary" type="submit" name="guardar">Sign in</button>
    <p class="mt-5 mb-3 text-body-secondary">&copy; 2017–2023</p>
  </form>
</main>


<?php
if(isset($_POST['guardar'])) {

	$usuario = clean($_POST['usuario']);
	$contrasena = md5($_POST['contrasena']);
	
	$query = mysqli_prepare($connect, "SELECT * FROM usuarios WHERE usuario = ? AND contrasena = ?");
    mysqli_stmt_bind_param($query, 'ss', $usuario, $contrasena);
    mysqli_stmt_execute($query);
    $result = mysqli_stmt_get_result($query);
	
	$contar = mysqli_num_rows($result);
	
	if ($contar != 0) {
	
		while($row=mysqli_fetch_array($result)) {
		
			if($usuario == $row['usuario'] && $contrasena == $row['contrasena']) 
			
			{
			
				$_SESSION['usuario'] = $usuario;
				
				$_SESSION['id'] = $row['id'];
				
				$_SESSION['rango'] = $row['rango'];
				
				header("Location: index.php");
				
			}
			
		} 
		
	} else { echo "El nombre de usuario y/o contraseña no coinciden"; }
	
}
?>
