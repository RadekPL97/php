<?php	
	session_start(); 
		if(isset($_SESSION['zalogowany']))
		{
			header('Location: konto-drawplatform');
			exit();
		}
	if(isset($_POST['emailNewUser'])){
		//	UDANA WALIDACJA
		$wszystko_OK = true;
		// POBIERAMY LOGIN I SPRAWDZAMY JEGO DLUGOSC, USUWAMY NIEBEZPIECZNE ZNAKI, CZY ZAWIERA TYLKO ZNAKU ALFANUMERYCZNE
		$login = test_input($_POST['loginNewUser']);
		if(strlen($login)<3 || strlen($login)>20){	$wszystko_OK=false; $_SESSION['e_login'] = "Login musi posiadać od 3 do 20 znaków";		}
		if(ctype_alnum($login)==false){$wszystko_OK=false;$_SESSION['e_login']="Login może zawierać tylko litery(bez polskich znaków)	i cyfry";}
		
		//	SPRAWDZANIE POPRAWNOSCI MAILA
		$email = $_POST['emailNewUser'];
		$emailB = filter_var($email,FILTER_SANITIZE_EMAIL);
		if(filter_var($emailB,FILTER_VALIDATE_EMAIL)==false || $emailB!=$email){
			$wszystko_OK=false;
			$_SESSION['e_email']="Podaj poprawny adres e-mail";
		}
		//SPRAWDZANIE POPRAWNOSCI HASLA
		$haslo1=test_input($_POST['password1']);
		$haslo2=test_input($_POST['password2']);
		if(strlen($haslo1)<8 || strlen($haslo2)>20){
			$wszystko_OK=false;
			$_SESSION['e_haslo']="Hasło musi posiadać od 8 do 20 znaków";
		}
		if($haslo1!=$haslo2){
			$wszystko_OK=false;
			$_SESSION['e_haslo']="Podane hasła są różne";}
		$haslo_hash=password_hash($haslo1,PASSWORD_DEFAULT);

		//	BOT OR NOT?
		$secret = "6LeZj6sUAAAAACw3Zo5NJhBBIFMaQX6TDGqMaCmZ"; 
		// $secret = "6LeEHowUAAAAALebPzRJSmisCqw_xz7ogBm2jx2I"; xampp
		$sprawdz = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secret.'&response='.$_POST['g-recaptcha-response']);	//	POBIERZ ZAWARTOSC PLIKU DO ZMIENNEJ
		$odp = json_decode($sprawdz);
		if($odp->success==false){			
			$wszystko_OK=false;
			$_SESSION['e_bot']="Potwierdź, że nie jesteś botem";
		}
		
		require_once "config.php";	/*	WKLEJA ZAWARTOSC PLIKU config.php DO TEGO PLIKU. JEZELI PLIKU NIE UDA SIE OTWORZYC TO WYGENERUJE BLAD KRYTYCZNY	*/
		mysqli_report(MYSQLI_REPORT_STRICT);
		try{
			if(!$connect = new mysqli($host,$db_user,$db_password,$db_name)) throw new Exception('<span style="color:#ff0000">BŁĄD serwera. Skorzystaj z okazji i zdobądź 100 DrawDolarów: powiadom nas o tej sytuacji: pomoc@drawplatform.pl</span>');	/*	UTWORZENIE POLACZENIA Z BAZA DANYCH, UTWORZENIE OBIEKTU	*/
		} catch(Exception $e){		/* $e przechowuje rodzaj napotkanego bledu	*/
			$e->getMessage();
			header('Location: nowy-uzytkownik');
			exit();
		}

				//CZY EMAIL JUZ ISTNIEJE
				$rezultat = $connect->query("SELECT id FROM users WHERE email='$email'");
				if(!$rezultat) throw new Exception($connect->error);
				$ile_takich_maili = $rezultat->num_rows;
				if($ile_takich_maili>0){
					$wszystko_OK = false;
					$_SESSION['e_email'] = "Istnieje już konto przypisane do tego adresu e-mail";
					}
				//CZY NICK JUZ ISTNIEJE
				$rezultat = $connect->query("SELECT id FROM users WHERE nick='$login'");
				if(!$rezultat) throw new Exception($connect->error);
				$ile_takich_loginow = $rezultat->num_rows;
				if($ile_takich_loginow>0){
					$wszystko_OK = false;
					$_SESSION['e_login']="Istnieje już użytkownik z takim loginem";
					}
					//WSZYSTKIE TESTY ZALICZONE, DODAJEMY USERA DO BAZY
				if($wszystko_OK==true)
				{		
					$data_dolaczenia = date("Y-m-d");
					$login_bez_s = str_replace(' ','',$login);
					//	BŁĄD PODCZAS TWORZENIA TABELI DO KOMENTARZY 
					$query = "CREATE TABLE $login_bez_s(
						id INT AUTO_INCREMENT PRIMARY KEY,
						tresc TEXT NOT NULL,
						usercomment TEXT NOT NULL
						)";
					if($connect -> query($query))
					{

						if($connect->query("INSERT INTO users VALUES ( NULL,'$email','$login','$haslo_hash',0,'$data_dolaczenia',100,0,'')"))
						{
							$subject = "Witaj na DrawPlatform.pl";
							$headers = "Dzień dobry. Cieszymy się, że możemy powitać Cię w gronie użytkowników DrawPlatform.pl. Od teraz możesz logować się na swoje konto. Zachęcamy do regularnych odwiedzin i publikowania swoich dzieł, co pozwoli w przyszłości dzielić się z użytkownikami zyskami pochodzącymi z reklam, sponsorów i innych źródeł. Dlatego codzienne logowanie zwiększa Twój udział w zyskach. Może Twoich znajomych również mógłby zainteresować nasz portal ? Poinformuj ich o nas: https://drawplatform.pl";
							$message = 'DrawPlatform';

							 try{
							if(!mail($email,$subject,$message,$headers)) throw new Exception("BŁĄD wysłania maila. Skorzystaj z okazji i zdobądź 100 DrawDolarów: powiadom nas o tej sytuacji: pomoc@drawplatform.pl");
							}catch(Exception $e){
							echo $e->getMessage();				
							}
							$new_directory_path="uploads/".$login;
							$new_avatar = "avatary/".$login;
							if (!mkdir($new_directory_path, 0777, true))
								{			/* UTWORZENIE KATALOGU	*/
								$_SESSION['e_mkdir'] = "BŁĄD: utworzenia katalogu. Skorzystaj z okazji i zdobądź 100 DrawDolarów: powiadom nas o tej sytuacji: pomoc@drawplatform.pl";
								header('Location:nowy-uzytkownik');
								}
							if(!mkdir($new_avatar, 0777, true)){
								$_SESSION['e_mkdir'] = "BŁĄD: utworzenia katalogu. Skorzystaj z okazji i zdobądź 100 DrawDolarów: powiadom nas o tej sytuacji: pomoc@drawplatform.pl";
								header('Location:nowy-uzytkownik');
							}		
							$_SESSION['udana_rejestracja'] = true;
							header('Location:witaj');
						}

					}
					else
					{
						$_SESSION['blad_utworzenia_tabeli'] = "BŁĄD: tabela nie została stworzona. Skorzystaj z okazji i zdobądź 100 DrawDolarów: powiadom nas o tej sytuacji: pomoc@drawplatform.pl";
						header('Location: nowy-uzytkownik');
						exit();
					}
				}		
				$connect->close();

		}	//KLAMRA ZAMYKAJACA ifset
	/*	FUNKCJA DO NEUTRALIZACJI ZAGROZENIA POCHODZACEGO Z WPISYWANEGO TEKSTU	*/
function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
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

	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<link rel="stylesheet" type="text/css" href="mimizi.css">
	<title>Załóż nowe konto na drawplatform</title>
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
	<!--	CZCIONKA INPUTOW-->
	<link href="https://fonts.googleapis.com/css?family=Anonymous+Pro&amp;subset=latin-ext" rel="stylesheet">
	<!-- 	CZCIONKA PROBA-->
	<link href="https://fonts.googleapis.com/css?family=Quicksand&amp;subset=latin-ext" rel="stylesheet">
	<!-- 	RECAPTCHA	-->
	<script src='https://www.google.com/recaptcha/api.js'></script>
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
									echo '<a class="menu-href" href="galeria">galeria</a>';
									echo '<a class="menu-href" href="#">nowy</a>';
									echo '<a class="menu-href" href="zaloguj-sie">zaloguj się</a>';
						?>
					</div>
				</div>
		</nav>

	</section>
<div id="sections">
	<section class="loremipsum">
		<?php if(isset($_SESSION['blad_utworzenia_tabeli'])){
			echo '<div class="error">'.$_SESSION['blad_utworzenia_tabeli']."</div>";
			unset($_SESSION['blad_utworzenia_tabeli']);} ?>

	<h1>Utwórz swoje konto i zgarnij 100 Drawdolarów na start !</h1>
		<article>
			<h2>Drawdolar to wirtualna waluta na portalu drawplatform.pl. Z Drawdolarami jak z pieniędzmi - im więcej tym lepiej.</h2>
		</article>
	</section>
		<section id="logowanie">
					<form method="post">
						<div class="logClass">
							<label for="emailNewUser">E-mail</label>
							<input type="text" name="emailNewUser" value="<?php	if(isset($_POST['emailNewUser'])){echo $_POST['emailNewUser'];}?>"/>
							<?php
								if(isset($_SESSION['e_email'])){
									echo '<div class="error">'.$_SESSION['e_email']."</div>";
									unset($_SESSION['e_email']);
									} 
							?>
						</div>
						<div class="logClass">
							<label for="loginNewUser">Login</label>
							<input type="text" name="loginNewUser" value="<?php	if(isset($_POST['loginNewUser'])){echo $_POST['loginNewUser'];}?>"/></span>
							<?php	if(isset($_SESSION['e_login'])){
								echo '<div class="error">'.$_SESSION['e_login']."</div>";
								unset($_SESSION['e_login']);
								}	
							?>
						</div>
						<div class="logClass">
							<label for="password1">Hasło</label>
							<input type="password" name="password1" value="<?php	if(isset($_POST['password1'])){echo $_POST['password1'];}?>"/></span>	
							<?php
								if(isset($_SESSION['e_haslo'])){
									echo '<div class="error">'.$_SESSION['e_haslo']."</div>";
									unset($_SESSION['e_haslo']);
									} 
							?>
						</div>
						<div class="logClass">
							<label for="password2">Powtórz hasło</label>
							<input type="password" name="password2" value="<?php	if(isset($_POST['password2'])){echo $_POST['password2'];}?>"/></span>	
						</div>

						<div class="logClass">
						<!-- 6LeEHowUAAAAALHHzfp5iZtr5gPBCBW14BQH8CtR xampp -->
						
							<div class="g-recaptcha" data-sitekey="6LeZj6sUAAAAADlCW5KFVo_3aTYpxLhPzpNGDH68"></div>
								<?php	
								if(isset($_SESSION['e_bot'])){
									echo '<div class="error">'.$_SESSION['e_bot']."</div>";
									unset($_SESSION['e_bot']);
									}	
								if(isset($_SESSION['e_mkdir'])){
									echo '<div class="error">'.$_SESSION['e_mkdir']."</div>";
									unset($_SESSION['e_mkdir']);
									} ?>
							</div>
						<div class="logClass">
							<input type="submit" value="Wchodzę w to" name="submitNewUser" />
						</div>
					</form>
					<article>Zakładając konto akceptujesz <a class='error-link' href="regulamin" target="_blank">Regulamin</a></article>
		</section>
		<section class="loremipsum">
			
		<h1>Co daje założenie konta ?</h1>
			<article>
				
				<h2>Możesz udostępniać swoje obrazki.</h2>
				<h2>Dostajesz DrawDolary za założenie konta i przesłane materiały.</h2>
				<h2>Zyskujesz możliwość komentowania i oceniania pracy innych ( wkrótce ).</h2>
				<h2>Uzyskujesz pomoc od zespołu drawplatform.pl.</h2>
				<h2>Zarabiasz na regularnych odwiedzinach witryny.</h2>
			</article>
		</section>
	</div>
</div>
	<script src="scripts/jquery-3.4.0.slim.min.js"></script>
	<script src="scripts/mimizi.js"></script>
</body>
</html>
