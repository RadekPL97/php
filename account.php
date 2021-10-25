<?php 
	session_start(); 
	/*	CZY UZYTKOWNIK JEST ZALOGOWANY, JEZELI NIE TO KIERUJEMY GO NA STRONE GLOWNA	*/
		if(!isset($_SESSION['zalogowany']))
		{
			header('Location: glowna');
			exit();
		}

	function test_input($data){
		$data.trim($data);						/* USUNIECIE BIALYCH ZNAKOW */
		$data.htmlspecialchars($data);		/* ZAMIANA ZNAKOW NA ZNAKI HTML */
		$data.stripslashes($data);		/* USUNIECIE ODWROCONYCH SLASHY */
		return $data;
	}

?>
<!DOCTYPE html>
<html>
<head>
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-145086109-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-145086109-1');
</script>

	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<link rel="stylesheet" type="text/css" href="mimizi.css">
	<title>Twoje konto na drawplatform</title>
	<meta name="robots" content="index,follow">
	<link rel="canonical" href="https://drawplatform.pl" />
	<meta name="description" content="Dziel się swoimi rysunkami, obrazami lub innymi wytworami Twojego umysłu. Pokaż użytkownikom swoją artystyczną twórczość. ">
	<meta name="keywords" content="rysunki, rysowanie, maluj, obrazki, sztuka, malarstwo, zarabianie">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="alternate" media="only screen and (max-width: 680px)"  href="https://drawplatform.pl" />
	
	<!-- Load an icon library to show a hamburger menu (bars) on small screens -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<!-- Load JQuery	-->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
	 <!--		CZCIONKA HEADER		-->
	<link href="https://fonts.googleapis.com/css?family=Charm:700" rel="stylesheet">
	<!-- 	CZCIONKA NAV 	-->
	<link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">
		<!--	CZCIONKA INPUTOW	-->
	<link href="https://fonts.googleapis.com/css?family=Anonymous+Pro&amp;subset=latin-ext" rel="stylesheet">
		<!-- 	CZCIONKA PROBA-->
	<link href="https://fonts.googleapis.com/css?family=Quicksand&amp;subset=latin-ext" rel="stylesheet">
</head>
<body>
<div id="content">
<header><a href="glowna"><img class ="img-header" src="img/logo.png" alt="drawplatformlogo"/></a></header>
			<nav class="nav-mimizi" id="nav-mimizi">
				<div class="menu" id="menu-big">
					<div class="menu-content">
						
						<?php
							echo '<a class="menu-icon" href="#"><i class="fa fa-bars"></i></a>';
							echo '<a class="menu-href" href="nowosci">aktualności</a>';
							echo '<a class="menu-href" href="#">moje_konto</a>';
							echo '<a class="menu-href" href="galeria">galeria</a>';
							echo '<a class="menu-href" href="upload-drawplatform">dodaj</a>';
							echo '<a class="menu-href" href="wyloguj.php">wyloguj się</a>';
						?>
					</div>
				</div>
		</nav>
	</section>

	<div id="sections">
		<section class="mojeKonto">
				<?php
				
					if(isset($_SESSION['e_upload'])){
						echo "<div class='error2'>".$_SESSION['e_upload']."</div>";
					unset($_SESSION['e_upload']);}
					if(isset($_SESSION['status_upload'])){
						echo "<div class='error2'>".$_SESSION['status_upload']."</div>";
					unset($_SESSION['status_upload']);}
									
				
					if(isset($_SESSION['blad'])){
						echo "<div class='error2'>".$_SESSION['blad']."/div>";
						unset($_SESSION['blad']);}

					if(isset($_SESSION['zmiana_hasla_status'])){
						echo "<div class='error2'>".$_SESSION['zmiana_hasla_status']."</div>";
						unset($_SESSION['zmiana_hasla_status']);}	

					if(isset($_SESSION['usun-obrazek'])){
						echo "<div class='error2'>".$_SESSION['usun-obrazek']."</div>";
						unset($_SESSION['usun-obrazek']);}	

						echo "<h2>Witaj ".$_SESSION['user'].", to jest Twoje konto:</h2>";
						echo "<div class='user-info'>";
						echo "<div class='user_avatar_logged'>";
						echo "<div class='u_a_l_img'>";
						if(isset($_SESSION['avatar']) && $_SESSION['avatar']!==""){
							echo "avatar:<img class='img-avatar' src='avatary/".$_SESSION['user']."/".$_SESSION['avatar']."'/></div>";
						}else echo "avatar: <b>brak</b></div>";
						echo "</div>";
						echo "<p>ilość materiałów: <b>".$_SESSION['nr_rys']."</b></p>";
						echo "<p>konto z Drawdolarami: <b>".$_SESSION['drawdolary']." D$</b></p>";
						echo "<p>data dołączenia: <b>".$_SESSION['termin']."</b></p>";
						echo "<p>dodane materiały:</p></div>";	


				?>
		</section>

		<section class="dodane-rysunki">
			<!--	WYSWIETLANIE RYSUNKOW PO KOLEI	-->
			<?php
				$dir = "uploads/".$_SESSION['user']."/";
				$pliki = array();
				
				if($dk=opendir($dir)){		// DK - DESKRYPTOR, CZYLI WARTOSC ZWRACANA PRZEZ FUNKCJE opendir()
						while(($file=readdir($dk))!==false){		//ZMIENNA $file DO PRZECHOWANIA BIEZACEJ NAZWY PLIKU, FUNKCJA DO ODCZYTU Z ARGUMENTEM- DESKRYPTOREM; NIEIDENTYCZNY ZE WZGLEDU NA KONWERSJE NAZW PLIKOW
							if(($file!=".")&&($file!="..")){
								$pliki[]=$file;
							}
							else {
								continue;
							}
						}
				}
				else {
					$_SESSION['e_open'] = "Nie udało się wyświetlić rysunków. Zgłoś to do nas.";
					header('Location: konto-drawplatform');
					exit ();
				}
				closedir($dk);		// ZAMKNIECIE Z DESKRYPTOREM JAKO ARGUMENTEM
				$rozmiar = sizeof($pliki);
				/*	WYŚWIETLANIE OPISU	
				$user = $_SESSION['user'];
				require_once ("config.php");
				$connect = new mysqli($host,$db_user,$db_password,$db_name);
						$findMaxId = $connect -> query("SELECT * FROM imgtable WHERE id=(SELECT MAX(id) FROM imgtable WHERE nick='$user')");
						$wiersz = $findMaxId -> fetch_assoc();
						$max_id = $wiersz['id'];
						$describion = array();
						for($i=1;$i<=$max_id;$i++){
							if(!$result = $connect -> query("SELECT * FROM imgtable WHERE nick='$user' AND ktory_rysunek='$i'")){
								$_SESSION['err_kwerenda'] = "BŁĄD wykonania kwerendy. Skorzystaj z okazji i zdobądź 100 DrawDolarów: powiadom nas o tej sytuacji: pomoc@drawplatform.pl";}
							else{
								if($result->num_rows>0){
									$wiersz = $result->fetch_assoc();
									$describtion[] = $wiersz['imgSign'];
								}
							}
						}
					WYŚWIETLANIE OPISU	*/
				if($rozmiar!=0)
				{
					for($i=0;$i<$rozmiar;$i++)
					{				
						echo "<div class='aboutimg'><div class='image-div-class'><img class='img-class' src='".$dir.$pliki[$i]."'/>Nazwa: ".$pliki[$i]."</div>";	
						// echo "<div class='logClass'><form method='post' action='describe.php'>";		
						// echo "<input type='text' name='img-describe' />";	
						// echo "<input type='submit' value='Podpisz rysunek' />";	
						// echo "<form></div>";
						echo "</div>";				
					}
				}
				else{
					$_SESSION['brak_rysunkow'] = "NIE MASZ JESZCZE  WŁASNYCH MATERIAŁÓW - <a class='error-link' href='upload-drawplatform'>DODAJ PRACĘ</a>";
				}
				
				if(isset($_SESSION['e_open'])) {
					echo "<div class='error2'>".$_SESSION['e_open']."</div>";
					unset($_SESSION['e_open']);
				}
				if(isset($_SESSION['brak_rysunkow'])){ 
					echo "<div class='error2'>".$_SESSION['brak_rysunkow']."</div>";
					unset($_SESSION['brak_rysunkow']);
				}
			?>
		</section>

		<footer>

			<div class="div-w-footer">
				
					<p class="p-hidden">Zmień hasło</p>

					<div class="div-w-footer-hidden">
						<div class="logClass">
							<form name="zmienHaslo" action="zmienhaslo.php" method="post">
									<input type="password" class="input-footer" name="stare-haslo" placeholder="stare hasło"/>
								<?php
									if(isset($_SESSION['error_stare_haslo'])){
										echo '<div class="error3">'.$_SESSION['error_stare_haslo'].'</div>';
										unset($_SESSION['error_stare_haslo']);
									}
								?>
									<input type="password" class="input-footer" name="nowe-haslo" placeholder ="nowe hasło"/>
								<?php
									if(isset($_SESSION['err_nowe_hasla'])){
										echo '<div class="error3">'.$_SESSION['err_nowe_hasla'].'</div>';
										unset($_SESSION['err_nowe_hasla']);
									}
								?>
									
									<input type="password" class="input-footer" name="nowe-haslo2" placeholder ="potwierdź nowe hasło" />
								<?php
									if(isset($_SESSION['err_nowe_haslo'])){
										echo '<div class="error3">'.$_SESSION['err_nowe_haslo'].'</div>';
										unset($_SESSION['err_nowe_haslo']);
									}
								?>
							<!-- PO WYSŁANIU submit-zmien-haslo JEST USTAWIONY. TRZEBA GO USUNĄĆ PRZED PONOWNYM WYSŁANIEM FORMULARZA -->
									<input type="submit" value="Zmień hasło" name="submit-zmien-haslo" />

							</form>
						</div>
					</div>	
			</div>

			<div class="div-w-footer">	
				<p class="p-hidden">Zarządzaj avatarem</p>
				<div class="div-w-footer-hidden">
					<div class="logClass">
					<form method="post" action="sendAvatar.php" enctype="multipart/form-data">
						<div class="logClass">
							<input type="file" name="avatar" id="avatar" accept="image/png, image/jpg, image/jpeg, image/gif" />
							<input type="submit" value="Prześlij" name="sendAvatar" />
						</div>
					</form>
					</div>
				</div>
		</div>

			<div class="div-w-footer">
			<p class="p-hidden">Usuń obrazek</p>
				<div class = "logClass">
					<div class="div-w-footer-hidden">
						<p>Podaj nazwę obrazka wraz z rozszerzenim ( dane te znajdziesz pod obrazkiem )</p>
						<form name="usunObrazek" action="usunObrazek.php" method="post">
							<input type = "text" class="input-footer" name="obrazek-do-usuniecia" />
							<input type="submit" value="Usuń obrazek" name="submit-usun-obrazek" />
						</form>
					</div>
				</div>
			</div>

			<div class="div-w-footer">
				<p class="p-hidden">Usuń konto</p>
					<div class="div-w-footer-hidden">
						<p>Aby usunąć konto wraz z wszystkimi danymi
						napisz maila na adres pomoc@drawplatform.pl o tytule
						<q>Chcę usunąć konto</q>.</p>
					</div>
			</div>

			<div class="div-w-footer">
				<p class="p-hidden">Kontakt</p>
					<div class="div-w-footer-hidden">
						<p>pomoc@drawplatform.pl</p>
					</div>
			</div>	
			<div class="div-w-footer">
				<p class="p-hidden"><a class='reg-href'href='regulamin' target="_blank">Regulamin</a></p>
			</div>
		</footer>

	</div>

</div>

	<script src="scripts/jquery-3.4.0.slim.min.js"></script>
	<script src="scripts/mimizi.js"></script>
	<script src="scripts/pokaz-footer.js"></script>
</body>
</html>

