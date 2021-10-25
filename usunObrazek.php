<?php
	session_start();
		if(!isset($_SESSION['zalogowany']))
			{
				header('Location: glowna');
				exit();
			}

		require_once("config.php");
			/*		CZY NACIŚNIĘTO PRZYCISK USUNIĘCIA OBRAZKA		*/
		if(!isset($_POST['submit-usun-obrazek'])){
			header('Location: konto-drawplatform');
			exit();
		}
		else{
			unset($_POST['submit-usun-obrazek']);
			/*		NAZWA OBRAZKA DO USUNIĘCIA	*/

				$nazwa_obrazka = test_input($_POST['obrazek-do-usuniecia']);
				$user = $_SESSION['user'];

			if(unlink("uploads/".$user."/".$nazwa_obrazka))
				{
					$_SESSION['usun-obrazek'] = "UDAŁO SIĘ USUNĄĆ OBRAZEK";

				try
					{
						$connect = new mysqli($host, $db_user, $db_password, $db_name);

						if($connect->connect_errno!=0)
							{
								throw new Exception(mysqli_connect_errno());
							}

						else
							{

							if($wykonaj_zapytanie = $connect->query(sprintf("SELECT * FROM users WHERE nick='%s'",mysqli_real_escape_string($connect,$user)))){
								$tablica_danych = $wykonaj_zapytanie -> fetch_assoc();
								$drawdolary = $tablica_danych['drawdolary']-50;
								$nr_rysunku = $tablica_danych['nr_rysunku']-1;	
							}
							else {
								$_SESSION['blad'] = "Błąd podczas wykonywania zapytania. Poinformuj nas o tym: pomoc@drawplatform.pl";
								header('Location: konto-drawplatform');
								exit();
							}

							if($connect -> query("UPDATE users SET nr_rysunku ='$nr_rysunku', drawdolary='$drawdolary' WHERE nick='$user'")){
								$_SESSION['usun-obrazek'] = "UDAŁO SIĘ USUNĄĆ TWÓJ OBRAZEK. TWOJE KONTO ZOSTAŁO POMNIEJSZONE O 50 D$";
								$_SESSION['nr_rys']--;
								$_SESSION['drawdolary']-=50;
								try{
									if(!($connect->query("DELETE FROM imgtable WHERE imgTitle='$nazwa_obrazka'"))) throw new Excetion("BŁĄD: nie udało się usunąć wiersza w bazie danych. Skorzystaj z okazji i zdobądź 100 DrawDolarów: powiadom nas o tej sytuacji: pomoc@drawplatform.pl");
								}catch(Exception $e){
									echo $e->getMessage();
									exit();
								}
							}
							else{
								$_SESSION['usun-obrazek'] = "NIE UDAŁO SIĘ AKTUALIZOWAĆ ILOŚCI TWOICH DRAWDOLARÓW. POINFORMUJ NAS O TYM: pomoc@drawdolary.pl";
							}	
						}

						$connect -> close();
					}	//	KONIEC try

				catch(Exception $e){		/* $e przechowuje rodzaj napotkanego bledu	*/
						echo '<span style="color:#ff0000">Bład serwera. Poinformuj nas o tym: pomoc@drawplatform.pl</span>';
					}
				}

				else{
					$_SESSION['usun-obrazek'] = "NIE UDAŁO SIĘ USUNĄĆ TWOJEGO OBRAZKA. SPRAWDŹ CZY WPISUJESZ WŁAŚCIWĄ NAZWĘ. JEŻELI TAK, POINFORMUJ NAS O TYM: pomoc@drawplatform.pl";
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