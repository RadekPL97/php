<?php
	session_start();
	require_once "config.php";
	try{
		if(!($connect = @new mysqli($host,$db_user,$db_password,$db_name))) throw new Exception("BŁĄD: Nie można połączyć z bazą danych");
		else{
			$id = $_SESSION['id'];
			try{
				if(!$connect->query("UPDATE users SET logged=0 WHERE id='$id'")) throw new Exception('BŁĄ: Nie udało się zmienić statusu zalogowany');
			}catch(Exception $e){
				$e->getMessage();
			}
			$connect->close();
		}
	}catch (Exception $e){
		$e->getMessage();
	}

	session_unset();
	header('Location:glowna');
	exit();
?>