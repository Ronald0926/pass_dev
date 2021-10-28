<?php

include('database_connection.php');

session_start();

$message = '';

if(isset($_SESSION['user_id']))
{
	header('location:index.php');
}

if(isset($_POST['login']))
{
	$query = "
		SELECT * FROM login 
  		WHERE username = :username
	";
	$statement = $connect->prepare($query);
	$statement->execute(
		array(
			':username' => $_POST["username"]
		)
	);	
	$count = $statement->rowCount();
	if($count > 0)
	{
		$result = $statement->fetchAll();
		foreach($result as $row)
		{
			if(password_verify($_POST["password"], $row["password"]))
			{
				$_SESSION['user_id'] = $row['user_id'];
				$_SESSION['username'] = $row['username'];
				$sub_query = "
				INSERT INTO login_details 
	     		(user_id, state) 
	     		VALUES ('".$row['user_id']."', 1)
				";
				$statement = $connect->prepare($sub_query);
				$statement->execute();
				$_SESSION['login_details_id'] = $connect->lastInsertId();
				
				$query = " SELECT * FROM rol_usuario WHERE user_id =:idusuario";
				$statement = $connect->prepare($query);
				$statement->execute(
					array(
						':idusuario' => $row["user_id"]
					)
				);
				$count = $statement->rowCount();
				$result = $statement->fetchAll();
				foreach($result as $row)
				{
					$_SESSION['user_id'] = $row['rol'];
					if($row['rol'] ==1)
						header('location:index.php');	
					else			
						header('location:userWeb.php');	
				}
				$message = '<label>Contraseña o usuario incorrecto</label>';
				
			}
			else
			{
				$message = '<label>Contraseña o usuario incorrecto</label>';
			}
		}
	}
	else
	{
		$message = '<label>Contraseña o usuario incorrecto</labe>';
	}
}
?>

<html>  
    <head>  
        <title></title>  
		<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
		<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  		<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    </head>  
    <body>  
        <div class="container">
			<div class="panel panel-default">
  				<div class="panel-heading">Chat Online 2</div>
				<div class="panel-body">
					<p class="text-danger"><?php echo $message; ?></p>
					<form method="post">
						<div class="form-group">
							<label>Ingrese usuario</label>
							<input type="text" name="username" class="form-control" required />
						</div>
						<div class="form-group">
							<label>Ingrese Contraseña</label>
							<input type="password" name="password" class="form-control" required />
						</div>
						<div class="form-group">
							<input type="submit" name="login" class="btn btn-info" value="Iniciar Sesion" />
						</div>
					</form>
					
					<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
					<!-- webslesson_mainblogsec_Blog1_1x1_as -->
					<ins class="adsbygoogle"
						 style="display:block"
						 data-ad-client="ca-pub-4529508631166774"
						 data-ad-host="ca-host-pub-1556223355139109"
						 data-ad-host-channel="L0007"
						 data-ad-slot="6573078845"
						 data-ad-format="auto"></ins>
					<script>
					(adsbygoogle = window.adsbygoogle || []).push({});
					</script>					
					<p><b>User 1</b></p>
					<p><b>Username</b> - johnsmith<br /><b>Password</b> - password</p>
					<p><b>Username</b> - peterParker<br /><b>Password</b> - password</p>
					<p><b>Username</b> - davidMoore<br /><b>Password</b> - password</p>
					<br />
					<br />
				</div>
			</div>
		</div>

    </body>  
</html>