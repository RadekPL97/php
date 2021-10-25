<?php
	session_start();
		if(!isset($_SESSION['zalogowany']))
			{
				header('Location: glowna');
				exit();
			}
	require_once("config.php");

			/*	UPLOAD FILES TO THE SERVER	*/
		if(isset($_POST["sendImage"]))
		{
			if($_POST['image']['name']!=="")
			{
				$target_dir = "uploads/".$_SESSION['user']."/";	/* KATALOG DOCELOWY, W KTORYM UMIESCIMY UPLOAD FILES	*/
				$target_file = $target_dir.basename($_FILES['image']['name']);		/* DOKLEJENIE NAZWY PLIKU, METODA basename ZWRACA NAZWE PLIKU*/
				$uploadOk=1;
				$imageFileExtension = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));	/* POBIERZ TYP PLIKU I ZAMIEN NA MALE LITERY	*/
				
				/* PO WGRANIU PLIKU*/
				$check = getimagesize($_FILES['image']['tmp_name']);	/* WEZ CHWILOWA NAZWE PLIKU I ZWROC JEJ ROZMIAR	*/
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
					else if($_FILES['image']['size'] > 2000000){		/* CZY WGRYWANE ZDJECIE ZAJMUJE WIECEJ NIZ 1 MB	*/
						$_SESSION['e_upload']= "Plik jest zbyt duży";	
						$uploadOk=0;
						$_SESSION['filesize'] = $_FILES['image']['size'];
					}
				if($uploadOk==1)
				{
					$namefile = $_FILES['image']['name'];
					if(move_uploaded_file($_FILES['image']['tmp_name'],$target_file))
					{
						unset($_SESSION['brak_rysunkow']);
						/*	ZAPISANIE W BAZIE DANYCH NAZWY PLIKU	UTWORZENIE KATALOGU*/
						$connect = new mysqli($host,$db_user,$db_password,$db_name);
						$id_user = $_SESSION['id'];
						$doQuery = $connect->query("SELECT * FROM users WHERE id='$id_user'");
						$wiersz = $doQuery->fetch_assoc();
						$nick = $wiersz['nick'];
						$nr_rysunku = $wiersz['nr_rysunku']+1;
						$drawdolary = $wiersz['drawdolary'] + 50;
						$connect -> query("UPDATE users SET nr_rysunku ='$nr_rysunku', drawdolary='$drawdolary' WHERE id='$id_user'");
						$_SESSION['status_upload'] = "Dziękujemy. Twój obrazek został przesłany !";
						$_SESSION['nr_rys']++;
						$_SESSION['drawdolary']+=50;

						try{
							if(!($connect->query("INSERT INTO imgtable VALUES (NULL,'$namefile','','$nick',NULL)"))) throw new Exception('BŁĄD: Nie udało się uaktualnić tabeli img');
						}catch(Exception $e){
							echo $e->getMessage();
						}
						$connect->close();
						header('Location: galeria');
						exit();
					}
				}
				else {
					$_SESSION['status_upload']="Niestety, nie udało się przesłać pliku. Spróbuj jeszcze raz.";
					header('Location:upload-drawplatform');exit();
				}
			}
			else{
				$_SESSION['e_upload'] = "Nie wybranego pliku";
				header('Location:upload-drawplatform'); exit();
				}
		}
		else{
			header('Location: upload-drawplatform'); exit();
		}
?>