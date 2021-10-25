<?php
	session_start();

	if(!isset($_POST['submit-komentarz'])){
		header('Location: galeria');
		exit();
	}
		if(!isset($_SESSION['zalogowany']))
		{
			$_SESSION['error_comment_log'] = "<a class='error-link' href='zaloguj-sie'>ZALOGUJ SIĘ</a>, ABY DODAĆ KOMENTARZ";
			$u = $_SESSION['userg'];
			header("Location: dane-uzytkownika?userg=$u");
			exit();
		}



			/*		CZY NACIŚNIĘTO PRZYCISK DODANIA KOMENTARZA		*/


			require_once("config.php");
			unset($_POST['submit-komentarz']);
			/*		KOMENTARZ DO WSTAWIENIA $komentarz	*/
				$userg = str_replace(' ','',$_SESSION['userg']);
				$userc = $_SESSION['user'];
				$komentarz = test_input($_POST['komentarz']);

				/*	GDY KOMENTARZ JEST PUSTY TO */
			if(empty($komentarz)){
				$_SESSION['empty_comment'] = "NIE MOŻNA DODAĆ PUSTEGO KOMENTARZA";	/*	NIE DZIAŁA	*/
			}
			else{
				if($connect = @new mysqli($host,$db_user,$db_password,$db_name)){
					$connect -> query ('SET NAMES utf8');
					$connect -> query ('SET CHARACTER_SET utf8_unicode_ci');

					if(!$connect -> query("INSERT INTO $userg VALUES (NULL,'$komentarz','$userc')")){
						$_SESSION['error-komentarz'] = "BŁĄD wykonania kwerendy. Skorzystaj z okazji i zdobądź 100 DrawDolarów: powiadom nas o tej sytuacji: pomoc@drawplatform.pl";
					}
					else{
						$_SESSION['error-komentarz'] = "TWÓJ KOMENTARZ ZOSTAŁ DODANY";
					}
					}	
				else{
					$_SESSION['error-komentarz'] = "BŁĄD połączenia. Skorzystaj z okazji i zdobądź 100 DrawDolarów: powiadom nas o tej sytuacji: pomoc@drawplatform.pl";
				}
			}
			header("Location: dane-uzytkownika?userg=$userg");
			exit();
			

		function test_input($data) {
			$data = trim($data);
			$data = stripslashes($data);
			$data = htmlspecialchars($data);
			return $data;
		  }
?>