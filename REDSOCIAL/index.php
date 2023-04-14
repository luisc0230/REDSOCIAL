<?php
session_start();
include "includes/config.php";
include "includes/funciones.php";

if(!isset($_SESSION['usuario'])) {
	header("Location: login.php");
}

ini_set('error_reporting',0);
?>
<!doctype html>
<html lang="es-ES">
<head>
  <meta http-equiv="Content-Type" content="text/html;charset=utf-8">
  <title>Sistema de Comentarios</title>
  <link rel="stylesheet" type="text/css" href="styles.css">
</head>

<body>

<div id="L">

<h1>SISTEMA DE COMENTARIOS <a href="logout.php">(SALIR)</a></h1>

<form name="form1" method="post" action="">
  <label for="textarea"></label>
  <center>
    <p>
      <textarea name="comentario" cols="80" rows="5" id="textarea"><?php if(isset($_GET['user'])) { ?>@<?php echo $_GET['user']; ?><?php } ?> </textarea>
    </p>
    <p>
      <input type="submit" <?php if (isset($_GET['id'])) { ?>name="reply"<?php } else { ?>name="comentar"<?php } ?>value="Comentar">
    </p>
  </center>
</form>

<?php
	if(isset($_POST['comentar'])) {
		$query = mysqli_query($connect, "INSERT INTO comentarios (comentario,usuario,fecha) value ('".$_POST['comentario']."','".$_SESSION['id']."',NOW())");	
		if($query) { header("Location: index.php"); }
	}
?>

<?php
	if(isset($_POST['reply'])) {
		$query = mysqli_query($connect, "INSERT INTO comentarios (comentario,usuario,fecha,reply) value ('".$_POST['comentario']."','".$_SESSION['id']."',NOW(),'".$_GET['id']."')");	
		if($query) { header("Location: index.php"); }
	}
?>

<br>

	<div id="container">
    	<ul id="comments">
        <?php
        $comentarios = mysqli_query($connect,"SELECT * FROM comentarios WHERE reply = 0 ORDER BY id DESC");
		while($row=mysqli_fetch_array($comentarios)) {
			
			$usuario = mysqli_query($connect,"SELECT * FROM usuarios WHERE id = '".$row['usuario']."'");
			$user = mysqli_fetch_array($usuario);
		?>
        	<li class="cmmnt">
            	<div class="avatar">
                <img src="<?php echo $user['avatar']; ?>" height="55" width="55">
                </div>
                <div class="cmmnt-content">
                	<header>
                    <a href="#" class="userlink"><?php echo $user['usuario']; ?></a> - <span class="pubdate"><?php echo $row['fecha']; ?></span>
                    </header>
                    <p>
                    <?php echo $row['comentario']; ?>
                    </p>
                    <span>
                    <a href="index.php?user=<?php echo $user['usuario']; ?>&id=<?php echo $row['id']; ?>">
                    Responder
                    </a>
                    </span>
                </div>
                <?php
				$contar = mysqli_num_rows(mysqli_query($connect,"SELECT * FROM comentarios WHERE reply = '".$row['id']."'"));
				if($contar != '0') {
					
					$reply = mysqli_query($connect,"SELECT * FROM comentarios WHERE reply = '".$row['id']."' ORDER BY id DESC");
					while($rep=mysqli_fetch_array($reply)) {
						
					$usuario2 = mysqli_query($connect,"SELECT * FROM usuarios WHERE id = '".$rep['usuario']."'");
					$user2 = mysqli_fetch_array($usuario2);
				?>
                
                <ul class="replies">
                	<li class="cmmnt">
                    	<div class="avatar">
                <img src="<?php echo $user2['avatar']; ?>" height="55" width="55">
                </div>
                	<div class="cmmnt-content">
                        <header>
                        <a href="#" class="userlink"><?php echo $user2['usuario']; ?></a> - <span class="pubdate"><?php echo $rep['fecha']; ?></span>
                        </header>
                        <p>
                        <?php echo $rep['comentario']; ?>
                        </p>
                    </div>
                    
                    </li>
                </ul>
                
                <?php } } } ?>
                
                
            </li>        
        
        </ul>
    </div>
       
        
        
        

</div>

</body>
</html>