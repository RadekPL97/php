<?php
	session_start();
	if(!isset($_GET['userg']))
	{
		header('Location: galeria');
	}

?>
<!DOCTYPE html>
<html>
<head>
<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<script>
     (adsbygoogle = window.adsbygoogle || []).push({
          google_ad_client: "ca-pub-5065722811403990",
          enable_page_level_ads: true
     });
</script>
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-145086109-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-145086109-1');
</script>

	<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<link rel="stylesheet" type="text/css" href="mimizi.css">
	<title>Drawplatform - informacje o użytkowniku</title>
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
	<!-- CZCIONKA KOMENTARZY	-->
	<link href="https://fonts.googleapis.com/css?family=Red+Hat+Display&display=swap&subset=latin-ext" rel="stylesheet">
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
							if(isset($_SESSION['zalogowany']))
							{
								echo '<a class="menu-icon" href="#"><i class="fa fa-bars"></i></a>';
								echo '<a class="menu-href" href="nowosci">aktualności</a>';
								echo '<a class="menu-href" href="konto-drawplatform">moje_konto</a>';
								echo '<a class="menu-href" href="galeria">galeria</a>';
								echo '<a class="menu-href" href="upload-drawplatform">dodaj</a>';
								echo '<a class="menu-href" href="wyloguj.php">wyloguj się</a>';
							}
						else
							{
								echo '<a class="menu-icon" href="#"><i class="fa fa-bars"></i></a>';
								echo '<a class="menu-href" href="nowosci">aktualności</a>';
								echo '<a class="menu-href" href="galeria">galeria</a>';
								echo '<a class="menu-href" href="nowy-uzytkownik">nowy</a>';
								echo '<a class="menu-href" href="zaloguj-sie">zaloguj się</a>';
							}
						?>
					</div>
				</div>
		</nav>

	<div id="sections">
		<section class="mojeKonto">
			<article>
			<?php if(isset($_SESSION['error_comment_log'])){
						echo "<div class='error'>".$_SESSION['error_comment_log']."</div>";
						unset($_SESSION['error_comment_log']);
						}	
						if(isset($_SESSION['empty_comment'])){
							echo "<div class='error'>".$_SESSION['empty_comment']."</div>";
							unset($_SESSION['empty_comment']);
						}
						if(isset($_SESSION['error-komentarz'])){
							echo "<div class='error'>".$_SESSION['error-komentarz']."</div>";
							unset($_SESSION['error-komentarz']);
						}?>
			</article>
			<article>
				<h1>Informacje o użytkowniku</h1>
				<hr>
			</article>
			<?php

				require_once "config.php";
				try{
					$connect = new mysqli($host,$db_user,$db_password,$db_name);	//UTWORZENIE POLACZENIA Z BAZA DANYCH, UTWORZENIE OBIEKTU	
					if($connect->connect_errno!=0)
					{
						throw new Exception(mysqli_connect_errno());
					}
					else
					{	
							$connect -> query ('SET NAMES utf8');
							$connect -> query ('SET CHARACTER_SET utf8_unicode_ci');
							$userg = $_GET['userg'];
							$_SESSION['userg'] = $userg;
							try{
								$rezultat = $connect->query("SELECT * FROM users WHERE nick='$userg'");
								if(!$rezultat) throw new Exception("BŁĄD wykonania kwerendy. Skorzystaj z okazji i zdobądź 100 DrawDolarów: powiadom nas o tej sytuacji: pomoc@drawplatform.pl");
								else{
									try{
										if($rezultat->num_rows<1) throw new Exception ("Użytkownik o podanym loginie nie istnieje");
									}catch (Exception $e){
										echo $e->getMessage();
										exit();
									}
								}
							}catch(Exception $e){
								echo $e->getMessage();
								exit();
							}

							if($rezultat = $connect->query("SELECT * FROM users WHERE nick='$userg'"))
							{
								$wiersz = $rezultat->fetch_assoc();
								if($wiersz['avatar'] !=="") $avatar = $wiersz['avatar'];
								$logged = $wiersz['logged'];
								$login = $wiersz['nick'];	// LOGIN UZYTKOWNIKA
								$drawdolary = $wiersz['drawdolary'];	// LOGIN UZYTKOWNIKA
								$data_dolaczenia = $wiersz['termin'];	// DATA DOŁĄCZENIA
								$nr_rysunku = $wiersz['nr_rysunku'];	// LICZBA RYSUNKOW UZYTKOWNIKA
								$dir = "uploads/".$login."/";
								$pliki = array();
								
								if($dk = opendir($dir)){		// DK - DESKRYPTOR, CZYLI WARTOSC ZWRACANA PRZEZ FUNKCJE opendir()
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
									$_SESSION['e_galleria'] = "Nie udało się wyświetlić rysunków. Skorzystaj z okazji i zdobądź 100 DrawDolarów: powiadom nas o tej sytuacji: pomoc@drawplatform.pl";
									/*header('Location: account.php');*/
									/*exit ();*/
								}
								closedir($dk);		// ZAMKNIECIE Z DESKRYPTOREM JAKO ARGUMENTEM
								$rozmiar = sizeof($pliki);


								/* WYŚWIETLENIE INFORMACJI O UŻYTKOWNIKU */
								echo "<div class='user-info'>";
								echo "<div class='user_avatar_logged'>";
								echo "<div class='u_a_l_img'>";
								if(isset($avatar)){
									echo "<img class='img-avatar' src='avatary/".$userg."/".$avatar."' /></div><b>".$userg."</b>";
								}else echo $userg."</div>";						
								if($logged){
									echo "<span class='circleGreen'></span>";
								}else echo "<span class='circleRed'></span>";
								echo "</div>";
								echo "<p>ilość materiałów: <b>".$nr_rysunku."</b></p>";
								echo "<p>konto z Drawdolarami: <b>".$drawdolary." D$</b></p>";
								echo "<p>data dołączenia: <b>".$data_dolaczenia."</b></p>";
								echo "<p>dodane materiały:</p></div>";	

								/*	WYŚWIETLANIE OPISU	*/
								$findMaxId = $connect -> query("SELECT * FROM imgtable WHERE id=(SELECT MAX(id) FROM imgtable WHERE nick='$userg')");
								$wiersz = $findMaxId -> fetch_assoc();
								$max_id = $wiersz['id'];
								$describion = array();
								for($i=1;$i<=$max_id;$i++){
									if(!$result = $connect -> query("SELECT * FROM imgtable WHERE nick='$userg' AND ktory_rysunek='$i' ")){
										$_SESSION['err_kwerenda'] = "BŁĄD wykonania kwerendy. Skorzystaj z okazji i zdobądź 100 DrawDolarów: powiadom nas o tej sytuacji: pomoc@drawplatform.pl";}
									else{
										if($result->num_rows>0){
											$wiersz = $result->fetch_assoc();
											$describtion[] = $wiersz['imgSign'];
										}
									}
								}
								/*	WYŚWIETLANIE OPISU	*/

								if($rozmiar!=0)
									{
										for($j=0;$j<$rozmiar;$j++)
											{
												echo "<div class='aboutimg'><div class='image-div-class'><img class='img-class'src='".$dir.$pliki[$j]."'/>".$describtion[$j]."</div></div>";
											}
									}
							}						
						}
					}
				catch(Exception $e){		/* $e przechowuje rodzaj napotkanego bledu	*/
					echo '<span style="color:#ff0000">Bład serwera. Przepraszamy za niedogodności. Skorzystaj z okazji i zdobądź 100 DrawDolarów: powiadom nas o tej sytuacji: pomoc@drawplatform.pl</span>';
				}
			?>
		</section>
		<footer>
			<div class="div-w-footer">
				<!-- <p class="p-hidden">Pokaż komentarze</p> -->
					<div class = "logClassEdit">
						<!-- <div class="div-w-footer-hidden"> -->

							<form method = "post" action = "addComment.php">
								<textarea name = "komentarz" class="input-footer" rows = "8"></textarea>
								<input type = "submit" name = "submit-komentarz" class="input-footer" value = "Dodaj komentarz" />
							</form>

								<!-- 	WYŚWIETLANIE KOMENTARZY 	-->
								<!-- 	POBIERANIE KOMENTARZY Z BAZY DANYCH	-->
							<?php
							if(isset($_SESSION['error_komentarz'])){
								echo "<div class='error3'>".$_SESSION['error_komentarz']."</div>";
								unset($_SESSION['error_komentarz']);
							}				
								/*	ILE KOMENTARZY JEST W BAZIE	*/
								$userg = str_replace(' ','',$userg);
								if($findMaxId = $connect->query("SELECT * FROM $userg WHERE id = ( SELECT MAX( id ) FROM $userg )"))
								{
									$connect -> query ('SET NAMES utf8');
									$connect -> query ('SET CHARACTER_SET utf8_unicode_ci');
									$wiersz = $findMaxId -> fetch_assoc();
									$max_id = $wiersz['id'];

									if($max_id>0)
									{
										for($i=1;$i<=$max_id;$i++)
										{									
											$pobierz_komentarz = "SELECT * FROM $userg WHERE id='$i'";

											
											if($wynik = @$connect ->query($pobierz_komentarz)){
												$tablica_danych = $wynik -> fetch_assoc();
												$komentarz = $tablica_danych['tresc'];
												$userc = $tablica_danych['usercomment'];
												$result = $connect->query("SELECT avatar FROM users WHERE nick='$userc'") -> fetch_assoc();
												$avatar = $result['avatar'];
									
												echo "<div class='komentarz'>".$komentarz."<div class='comment-user'>";
												echo "<a class ='comment-href' href='dane-uzytkownika?userg=$userc'><div class='u_a_l_img2'>";
												if(!empty($avatar)) {
													echo"<img class='img-avatar' src='avatary/".$userc."/".$avatar."' />";}
												echo $userc."</div></a></div></div>";
											}
											else{
												echo "<div class='error3'>BŁĄD: Nie udało się wczytać komentarzy. Skorzystaj z okazji i zdobądź 100 DrawDolarów: powiadom nas o tej sytuacji: pomoc@drawplatform.pl</div>";
											}
										}
									}
									else{
										echo "<div class='error3'>Brak komentarzy. Dodaj pierwszy komentarz</div>";
									}
								}
								else{
									echo "<div class='error3'>BŁĄD wykonania kwerendy. Skorzystaj z okazji i zdobądź 100 DrawDolarów: powiadom nas o tej sytuacji: pomoc@drawplatform.pl</div>";
								}

								$connect -> close();
							?>

						<!-- </div> -->
					</div> 
			</div>
		</footer>
		</div>
	</div>
		<script src="scripts/jquery-3.4.0.slim.min.js"></script>
		<script src="scripts/mimizi.js"></script>
		<script src="scripts/pokaz-footer.js"></script>
</body>
</html>