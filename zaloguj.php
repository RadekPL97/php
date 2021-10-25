<?php
	session_start();
	if(!isset($_POST['login']) || !isset($_POST['haslo'])){
		header('Location: glowna');
		exit();
	}
	require_once "config.php";
	//	WYCISZAMY WYRZUCANIE BLEDOW
	$connect = @new mysqli($host,$db_user,$db_password,$db_name);
	if($connect->connect_errno!=0){
		echo "Error ".$connect->connect_errno;
	}
	else{
		unset($_SESSION['blad']);
		$login = test_input($_POST['login']);
		$haslo = test_input($_POST['haslo']);
			if($doQuery = @$connect->query(sprintf("SELECT * FROM users WHERE nick='%s'",mysqli_real_escape_string($connect,$login)))){
				$ilu_userow = $doQuery->num_rows;	//	ILE WIERSZY ZWROCI ZAPYTANIE
				if($ilu_userow>0){
					$wiersz = $doQuery->fetch_assoc();
					if(password_verify($haslo,$wiersz['haslo'])){
						$id = $wiersz['id'];
						try{
							if(!$connect->query("UPDATE users SET logged=1 WHERE id='$id'")) throw new Exception('BŁĄ: Nie udało się zmienić statusu zalogowany');
							else{
								$_SESSION['zalogowany'] = true;
								$_SESSION['id'] = $wiersz['id'];
								$_SESSION['user'] = $wiersz['nick'];
								$_SESSION['termin'] = $wiersz['termin'];
								$_SESSION['drawdolary'] = $wiersz['drawdolary'];
								$_SESSION['nr_rys'] = $wiersz['nr_rysunku'];
								$_SESSION['avatar'] = $wiersz['avatar'];
								unset($_SESSION['blad']);
								$doQuery->free_result();
								header('Location: konto-drawplatform');
							}
						}catch(Exception $e){
							echo $e->getMessage();
						}
					}
					else{
						$_SESSION['blad'] = "Nieprawidłowy login lub hasło";
						header('Location: zaloguj-sie');
					}
				}
				else{
					$_SESSION['blad'] = "Nieprawidłowy login lub hasło";
					header('Location: zaloguj-sie');
				}
			}
		$connect->close();
	}
	function test_input($data) {
		$data = trim($data);
		$data = stripslashes($data);
		$data = htmlspecialchars($data);
  	return $data;
	}
?>