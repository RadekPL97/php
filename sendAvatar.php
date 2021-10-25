<?php
	session_start();
		if(!isset($_SESSION['zalogowany']))
			{
				header('Location: galeria');
				exit();
			}
	require_once("config.php");

			/*	UPLOAD FILES TO THE SERVER	*/
		if(isset($_POST["sendAvatar"]))
		{
			if($_POST['avatar']['name']!=="")
			{
				$target_dir = "avatary/".$_SESSION['user']."/";	/* KATALOG DOCELOWY, W KTORYM UMIESCIMY AVATARY	*/
				$target_file = $target_dir.basename($_FILES['avatar']['name']);		/* DOKLEJENIE NAZWY PLIKU, METODA basename ZWRACA NAZWE PLIKU*/
				$uploadOk=1;
				$imageFileExtension = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));	/* POBIERZ TYP PLIKU I ZAMIEN NA MALE LITERY	*/
				
				/* PO WGRANIU PLIKU*/
				$check = getimagesize($_FILES['avatar']['tmp_name']);	/* WEZ CHWILOWA NAZWE PLIKU I ZWROC JEJ ROZMIAR	*/
				if($check!== false){
					echo "The file is ".$check['mime']."<br />";
					$uploadOk=1;
				}
				else {
					echo"File is not an image. <br />";
					$uploadOk=0;
				}
					if(file_exists($target_file)){	/* SPRAWDZAMY CZY ISTNIEJE TAKI PLIKU*/
						$_SESSION['e_upload']="Istnieje już taki plik";
						$uploadOk=0;
					}
					else if( $imageFileExtension!="jpg" &&  $imageFileExtension!="jpeg" && $imageFileExtension!="png" && $imageFileExtension!="gif"){	/* ROZSZERZENIE	*/
						$_SESSION['e_upload']="Niepoprawne rozszerzenie";
						$uploadOk=0;
					}
					else if($_FILES['avatar']['size'] > 200000){		/* CZY WGRYWANE ZDJECIE ZAJMUJE WIECEJ NIZ 200 kB	*/
						$_SESSION['e_upload']= "Plik jest zbyt duży. Maksymalny rozmiar pliku wynosi 200 kB";	
						$uploadOk=0;
						$_SESSION['filesize'] = $_FILES['avatar']['size'];
					}
				if($uploadOk==1)
				{
					$namefile = $_FILES['avatar']['name'];
					if(move_uploaded_file($_FILES['avatar']['tmp_name'],$target_file))
					{
						/*	ZAPISANIE W BAZIE DANYCH NAZWY PLIKU	UTWORZENIE KATALOGU*/
						$connect = new mysqli($host,$db_user,$db_password,$db_name);
						$id_user = $_SESSION['id'];
						$doQuery = $connect->query("SELECT * FROM users WHERE id='$id_user'");
						$wiersz = $doQuery->fetch_assoc();
						$nick = $wiersz['nick'];
						try{
						if(!unlink($target_dir."/".$_SESSION['avatar'])) throw new Exception("BŁĄD: Nie udało się usunąć obrazka. Skorzystaj z okazji i zdobądź 100 DrawDolarów: powiadom nas o tej sytuacji: pomoc@drawplatform.pl");
						else unset($_SESSION['avatar']);
						}catch (Exception $e){
							echo $e->getMessage();
						}

						try {
							if(!($connect -> query("UPDATE users SET avatar='$namefile' WHERE id='$id_user'"))) throw new Exception("BŁĄD wykonania kwerendy. Skorzystaj z okazji i zdobądź 100 DrawDolarów: powiadom nas o tej sytuacji: pomoc@drawplatform.pl");
							else{
								$_SESSION['status_upload'] = "Twój avatar został przesłany !";
								$_SESSION['avatar'] = $namefile;

							}
						}catch (Exception $e){
							echo $e -> getMessage();
							exit();
						}

						$connect->close();
						header('Location: konto-drawplatform');
						exit();
					}
				}
				else {
					$_SESSION['status_upload']="Nie udało się przesłać pliku.";
					header('Location: konto-drawplatform');exit();
				}
			}
			else{
				$_SESSION['e_upload'] = "Nie wybranego pliku";
				header('Location: konto-drawplatform'); exit();
				}
		}
		else{
			header('Location: konto-drawplatform'); exit();
		}
?>