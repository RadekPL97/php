<?php	
	session_start(); 
	/*	CZY UZYTKOWNIK JEST ZALOGOWANY, JEZELI NIE TO KIERUJEMY GO NA STRONE GLOWNA	*/
		if(isset($_SESSION['zalogowany']))
			{
				header('Location:konto-drawplatform');
				exit();
			}
		if(!isset($_SESSION['udana_rejestracja'])){
			header('Location: nowy-uzytkownik');
		}
		unset($_SESSION['udana_rejestracja']);
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
	<title>Drawplatform - witaj nowy użytkowniku</title>
	<meta name="robots" content="index,follow">
	<link rel="canonical" href="https://drawplatform.pl" />
	<meta name="description" content="Dziel się swoimi rysunkami, obrazami lub innymi wytworami Twojego umysłu. Pokaż użytkownikom swoją artystyczną twórczość. ">
	<meta name="keywords" content="rysunki, rysowanie, maluj, obrazki, sztuka, malarstwo, zarabianie">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="alternate" media="only screen and (max-width: 680px)"  href="https://www.drawplatform.pl" />

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
	<!--
	<header><a href="glowna"><img class ="img-header" src="img/logo.png" /></a></header>
			<nav class="nav-mimizi" id="nav-mimizi">
				<div class="menu" id="menu-big">
					<div class="menu-content">
											
						
							if(isset($_SESSION['zalogowany']))
							{
								echo '<a class="menu-icon" href="#"><i class="fa fa-bars"></i></a>';
								echo '<a class="menu-href" href="konto-drawplatform">moje_konto</a>';
								echo '<a class="menu-href" href="galeria">galeria</a>';
								echo '<a class="menu-href" href="upload-drawplatform">upload</a>';
								echo '<a class="menu-href" href="wyloguj.php">wyloguj się</a>';
							}
						else
							{
								echo '<a class="menu-icon" href="#"><i class="fa fa-bars"></i></a>';
								echo '<a class="menu-href" href="glowna">co to?</a>';
								echo '<a class="menu-href" href="nowy-uzytkownik">nowy</a>';
								echo '<a class="menu-href" href="galeria">galeria</a>';
								echo '<a class="menu-href" href="zaloguj-sie">zaloguj się</a>';
							}
						
					</div>
				</div>
		</nav>
	-->
	</section>
<div id="sections">
	<section class="loremipsum">
		<article>
			<h1>Dziękujemy za rejestrację. Możesz już zalogować się na swoje konto</h1>
			<a href="zaloguj-sie">Zaloguj się</a>
		</article>
	</section>
	</div>
</div>
	<script src="scripts/jquery-3.4.0.slim.min.js"></script>
	<script src="scripts/mimizi.js"></script>
</body>
</html>
