<?php
	session_start();
		if(!isset($_SESSION['zalogowany']))
			{
				header('Location: glowna');
				exit();
			}

			/*		CZY NACIŚNIĘTO PRZYCISK		*/
		if(!isset($_POST['submit-zmien-haslo'])){
			header('Location: konto-drawplatform');
			exit();
		}
		else{
			unset($_POST['submit-zmien-haslo']);
			require_once("config.php");

			/*		NOWE HASŁA Z FORMULARZA	*/
				$stare_haslo = $_POST['stare-haslo'];
				$nowe_haslo = $_POST['nowe-haslo'];
				$nowe_haslo2 = $_POST['nowe-haslo2'];

				$_SESSION['$stare_haslo'] = $stare_haslo;
				$_SESSION['$nowe_haslo'] = $nowe_haslo;
				$_SESSION['$nowe_haslo2'] = $nowe_haslo2;

				$wszystko_OK = 1;
			try{
					$connect = new mysqli($host, $db_user, $db_password, $db_name);

					if($connect->connect_errno!=0)
						{
							throw new Exception(mysqli_connect_errno());
						}
					else{
						$user = $_SESSION['user'];

						if($wykonaj_zapytanie = @$connect->query(sprintf("SELECT * FROM users WHERE nick='%s'",mysqli_real_escape_string($connect,$user)))){
							$tablica_danych = $wykonaj_zapytanie -> fetch_assoc();
						}
						else {
							$_SESSION['blad'] = "Błąd podczas wykonywania zapytania. Poinformuj nas o tym: pomoc@drawplatform.pl";
							header('Location: konto-drawplatform');
							exit();
						}

							if(!password_verify($stare_haslo,$tablica_danych['haslo'])){
							$_SESSION['error_stare_haslo'] = "Podaj poprawne hasło";
							$wszystko_OK = 0;
							}

						
						//	ZAMIENIA WPISANE HASŁO NA HASH, ABY DOKONAĆ PORÓWNIANIA
							$stare_haslo_hash = password_hash($stare_haslo,PASSWORD_DEFAULT);

						// CZY NOWE HASŁA SĄ TAKIE SAME
						if($nowe_haslo!=$nowe_haslo2){
							$_SESSION['err_nowe_hasla'] = "Podane hasła są różne";
							$wszystko_OK = 0;
						}

						//	CZY HASŁO SPEŁNIA KRYTERIA OKREŚLONE DLA HASEŁ
						if(strlen($nowe_haslo)<8 || strlen($nowe_haslo)>20){
							$_SESSION['err_nowe_haslo'] = "Hasło musi zawierać od 8 do 20 znaków";		
							$wszystko_OK = 0;	
						}
							
						$_SESSION['wszystko_OK'] = $wszystko_OK. " - Wartość wszystko_OK na koniec";

						if($wszystko_OK==1){
							//	 WKŁADAMY DO BAZY HASH HASŁA
							$nowe_haslo_hash = password_hash($nowe_haslo,PASSWORD_DEFAULT);

							if($connect -> query("UPDATE users SET haslo = '$nowe_haslo_hash' WHERE nick='$user'")){
								$_SESSION['zmiana_hasla_status'] = "HASŁO ZOSTAŁO ZMIENIONE";
							}
							}
						else{
								$_SESSION['zmiana_hasla_status'] = "HASŁO NIE ZOSTAŁO ZMIENIONE. ABY DOWIEDZIEĆ SIĘ WIĘCEJ ROZWIŃ <q>Zmień hasło</q> NA DOLE STRONY";
							}
					

					}
					$connect -> close();
				}	//	KONIEC try

			catch(Exception $e){		/* $e przechowuje rodzaj napotkanego bledu	*/
					echo '<span style="color:#ff0000">Bład serwera. Poinformuj nas o tym: pomoc@drawplatform.pl</span>';
				}
				header('Location: konto-drawplatform');
				exit();
		}	//	KONIEC ELSE	



		function test_input($data) {
			$data = trim($data);
			$data = stripslashes($data);
			$data = htmlspecialchars($data);
			return $data;
		  }
?>