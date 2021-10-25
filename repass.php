<?php
	session_start();

		if(isset($_SESSION['zalogowany']))
		{
			header("Location: konto-drawplatform");
			exit();
		}
		else if(!isset($_POST['remail-submit'])){
			header('Location: zaloguj-sie');
			exit();
		}
			require_once("config.php");
				$email = test_input($_POST['remail']);

				/*	GDY KOMENTARZ JEST PUSTY TO */
			if(empty($email)){
				$_SESSION['error_repass'] = "BŁĄD: podaj email, na który zostanie wysłane hasło" ;	/*	NIE DZIAŁA	*/
				header('Location: zaloguj-sie');
				exit();
			}
				if($connect = @new mysqli($host,$db_user,$db_password,$db_name)){
					$connect -> query ('SET NAMES utf8');
					$connect -> query ('SET CHARACTER_SET utf8_unicode_ci');
					if($result = $connect->query("SELECT * FROM users WHERE email='$email'")){
						if($result->num_rows<1){
							$_SESSION['error_repass'] = "BŁĄD: nie ma w bazie takiego emaila";
						}
						else{
							//	EMAIL JEST W BAZIE I TRZEBA WYSŁAĆ TYMCZASOWE HASŁO ALBO ZAPISAĆ POWIADOMIENIE
						}
					}
					else{
						$_SESSION['error_repass'] = "BŁĄD: wykonania zapytania";
					}
				}
				else{
					$_SESSION['error_repass'] = "BŁĄD: połączenia z bazą danych";
				}
				header('Location: zaloguj-sie');
				exit();
//	LOSOWANIE NOWEGO HASŁA

				function random_str(int $length=20,string $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'): string {
					if ($length < 1) {
						throw new \RangeException("Length must be a positive integer");
					}
					$pieces = [];
					$max = mb_strlen($keyspace, '8bit') - 1;
					for ($i = 0; $i < $length; ++$i) {
						$pieces []= $keyspace[random_int(0, $max)];
					}
					return implode('', $pieces);
				}

		function test_input($data) {
			$data = trim($data);
			$data = stripslashes($data);
			$data = htmlspecialchars($data);
			return $data;
		  }
?>