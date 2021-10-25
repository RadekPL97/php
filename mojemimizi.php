<?php
		session_start(); 
		if(isset($_SESSION['zalogowany']))
		{
			header('Location: konto-drawplatform');
			exit();
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
	<title>Zaloguj się drawplatform</title>
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
<header><a href="#"><img class ="img-header" src="img/logo.png" alt="drawplatformlogo" /></a></header>
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
									echo '<a class="menu-href" href="#">zaloguj się</a>';
								}
						?>
					</div>
				</div>
		</nav>

</section>
<div id="sections">

		<section class="loremipsum">
		<?php if(isset($_SESSION['error_repass'])){
						echo "<div class='error'>".$_SESSION['error_repass']."</div>";
						unset($_SESSION['error_repass']);}
						if(isset($_SESSION['newpass'])){
							echo "<div class='error'>".$_SESSION['newpass']."</div>";
							unset($_SESSION['newpass']);}
						
						?>
				<h1> Zaloguj się na swoje konto</h1>
		</section>
		<section id="logowanie">
			<form method="post" action="zaloguj.php">
				<div class="logClass">
					<label for="login">Login</label>
					<input type="text" name="login"/>
				</div>
				<div class="logClass">
					<label for="haslo">Hasło</label>
					<input type="password" name="haslo"/>
				</div>
				<div class="logClass">
					<input type="submit" name="zaloguj" value="Zaloguj się"/>
				</div>
			</form>
			
			
			
			<!--	ZAPOMNIAŁEŚ HASŁA ?	-->
			<!-- <div class="logClass">
				<div class="div-w-mm">
				<p class="p-hidden-mm">Nie pamiętam hasła</p>
					<div class = "logClass">
						<div class="div-w-mm-hidden">
							<p>Podaj e-mail, wyślemy Ci nowe hasło</p>
							<form name="repass" action="repass.php" method="post">
								<input type = "text" class="input-footer" name="remail" />
								<input type="submit" value="Wyślij" name="remail-submit" />
							</form>
					</div>
						</div>
				</div>
			</div> -->

			<?php
				if(isset($_SESSION['blad'])){
					echo '<div class="error">'.$_SESSION['blad']."</div>";
				}
				unset($_SESSION['blad']);
			?>
			<div class="logClass">
					Nie masz konta ? <a class='error-link' href='nowy-uzytkownik'>Utwórz konto</a>
					</div>
		</section>
	</div>
	</div>
	<script src="scripts/jquery-3.4.0.slim.min.js"></script>
	<script src="scripts/mimizi.js"></script>
	<script src="scripts/pokaz-footer.js"></script>
</body>
</html>
