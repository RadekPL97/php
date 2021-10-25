<?php 
	session_start(); 
		if(!isset($_SESSION['zalogowany']))
			{
				header('Location: glowna');
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
	<title>Drawplatform - prześlij Twoje rysunki</title>
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
<header><a href="glowna"><img class ="img-header" src="img/logo.png" alt="drawplatformlogo" /></a></header>
			<nav class="nav-mimizi" id="nav-mimizi">
				<div class="menu" id="menu-big">
					<div class="menu-content">
						
						<?php
									echo '<a class="menu-icon" href="#"><i class="fa fa-bars"></i></a>';
									echo '<a class="menu-href" href="nowosci">aktualności</a>';
									echo '<a class="menu-href" href="konto-drawplatform">moje_konto</a>';
									echo '<a class="menu-href" href="galeria">galeria</a>';
									echo '<a class="menu-href" href="#">dodaj</a>';
									echo '<a class="menu-href" href="wyloguj.php">wyloguj się</a>';
						?>
					</div>
				</div>
		</nav>
	
	</section>
	<div id="sections">
		<section class="mojeKonto">
			
					<?php	
						if(isset($_SESSION['status_upload'])) 
							{ 
								echo $_SESSION['status_upload'];
								unset($_SESSION['status_upload']); 
							}
						else
							echo "<h2>Wrzuć swój rysunek i zgarnij</h2><h1> 50 Drawdolarów !</h1>";

						if(isset($_SESSION['e_upload']))
							{
								echo '<div class="error">'.$_SESSION['e_upload'].'</div>';
								unset($_SESSION['e_upload']);
							}
						if(isset($_SESSION['filesize'])){
							echo $_SESSION['filesize'];
							unset ($_SESSION['filesize']);
						}
					?>
			
		</section>
		<section class="loremipsum">
			<form method="post" action="sendImage.php" enctype="multipart/form-data">
				<div class="logClass">
					<input type="file" name="image" id="image" accept="image/png, image/jpg, image/jpeg, image/gif" />
					<input type="submit" value="Prześlij" name="sendImage" />
				</div>
			</form>
			<p> Dozwolone formaty plików: jpg, jpeg, png, gif.</p>
		<p>Maksymalny rozmiar: 2 MB ( 2 miliony kB )</p>
		</section>
	</div>
</div>
	<script src="scripts/jquery-3.4.0.slim.min.js"></script>
	<script src="scripts/mimizi.js"></script>
</body>
</html>

