<?php
	session_start();
?>
<!DOCTYPE html>
<html>
<head>

	<!-- WYSZUKIWANIE USERA SCRIPT -->
<script>
function showHint(str) {
    if (str.length == 0) { 
        document.getElementById("txtHint").innerHTML = "";
        return;
    } else {
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("txtHint").innerHTML = this.responseText;
            }
        };
        xmlhttp.open("GET", "gethint.php?q=" + str, true);
        xmlhttp.send();
    }
}
</script>
	<!-- WYSZUKIWANIE USERA SCRIPT	 -->
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
	<link rel="stylesheet" type="text/css" href="fontello/css/fontello.css">
	<link rel="stylesheet" type="text/css" href="mimizi.css">
	<title>Drawplatform - galeria rysunków</title>
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
							if(isset($_SESSION['zalogowany']))
								{
									echo '<a class="menu-icon" href="#"><i class="fa fa-bars"></i></a>';
									echo '<a class="menu-href" href="nowosci">aktualności</a>';
									echo '<a class="menu-href" href="konto-drawplatform">moje_konto</a>';
									echo '<a class="menu-href" href="#">galeria</a>';
									echo '<a class="menu-href" href="upload-drawplatform">dodaj</a>';
									echo '<a class="menu-href" href="wyloguj.php">wyloguj się</a>';
								}
							else
								{
									echo '<a class="menu-icon" href="#"><i class="fa fa-bars"></i></a>';
									echo '<a class="menu-href" href="nowosci">aktualności</a>';
									echo '<a class="menu-href" href="#">galeria</a>';
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
				<?php
						if(isset($_SESSION['status_upload'])){
							echo "<div class='error'>".$_SESSION['status_upload']."</div>";
							unset($_SESSION['status_upload']);
							}
				?>
				<!-- <h2>Zobacz co wrzucili inni użytkownicy</h2>
				<hr> -->

				
				<!-- WYSZUKIWANIE USERA -->
					
				<div class ="search">
					<div class="search-form">
						<form> 
						<input type="text" class = "search-input" id="textgaleria" onkeyup="showHint(this.value)" placeholder="Szukaj"/>
						</form>
					
					<div id="txtHint"></div>
					</div>
				</div>

				<!-- WYSZUKIWANIE USERA	 -->

			<?php
				require_once "config.php";
				try{			 	
					$connect = @mysqli_connect($host,$db_user,$db_password,$db_name); //UTWORZENIE POLACZENIA Z BAZA DANYCH, UTWORZENIE OBIEKTU	
					if(!$connect)
					{
						// throw new Exception(mysqli_connect_errno());
						throw new Exception("Nie można połączyć z bazą danych. Skorzystaj z okazji i zdobądź 100 DrawDolarów: powiadom nas o tej sytuacji: pomoc@drawplatform.pl");
					}
					else
					{
						$connect -> query ('SET NAMES utf8');
						$connect -> query ('SET CHARACTER_SET utf8_unicode_ci');
					}
					}
					catch(Exception $e){		/* $e przechowuje rodzaj napotkanego bledu	*/
						echo "BŁĄD: ".$e->getMessage();
						exit();
					}
						// PHP + AJAX
						$users = array();
						// PHP + AJAX

						//	 WYŚWIETLANIE RYSUNKÓW OD NAJNOWSZYCH
						try{ 
							$findMaxId = $connect->query("SELECT * FROM imgtable WHERE id = ( SELECT MAX( id ) FROM imgtable )");
							if(!$findMaxId) throw new Exception("BŁĄD wykonania kwerendy. Skorzystaj z okazji i zdobądź 100 DrawDolarów: powiadom nas o tej sytuacji: pomoc@drawplatform.pl");
							else{
								$wiersz = $findMaxId -> fetch_assoc();
								$max_id = $wiersz['id'];
							}
						}catch (Exception $e){
							echo $e->getMessage();
							exit();
						}
						
						for($i=$max_id;$i>=1;$i--)	/*	PETLA DO PRZESKAKIWANIA PO USERACH	*/
						{
							try{
								if(!$result = $connect -> query("SELECT * FROM imgtable WHERE id='$i'")) throw new Exception("BŁĄD wykonania kwerendy. Skorzystaj z okazji i zdobądź 100 DrawDolarów: powiadom nas o tej sytuacji: pomoc@drawplatform.pl");
								else{
									if($result->num_rows<1){
										continue;
									}
									else{
									$wiersz = $result->fetch_assoc();
									$imgTitle = $wiersz['imgTitle'];	// TYTUŁ OBRAZKA
									$login = $wiersz['nick'];				// LOGIN UZYTKOWNIKA
									$result = $connect -> query("SELECT * FROM users WHERE nick='$login'");
									$w = $result ->fetch_assoc();
									$avatar = $w['avatar'];
									$_SESSION['login-galeria'] = $login;
								}
								}
							}catch(Exception $e){
								echo $e->getMessage();
								exit();
							}

							$users[] = $login; // PHP + AJAX WYSZUKIWANIE PO LOGINACH

							$dir = "uploads/".$login."/".$imgTitle;
							// echo "<div class='nick-z-ikonami'><div class = 'loginName'><a class='login-href' href='dane-uzytkownika?userg=$login' target='_blank'>".$login."</a></div>";
							// echo '<div class = "image-div-class"><img class="img-class" src="'.$dir.'"/>'.$imgSign.'</div>';

							echo "<div class='aboutimg'>";
							// echo "<div class = 'loginName'>";
							echo "<div class='u_a_l_img'>";
							echo "<a class='login-href' href='dane-uzytkownika?userg=$login' target='_blank'>".$login."</a>";
							
							if(!empty($avatar)){
								echo "<img class='img-avatar' src='avatary/".$login."/".$avatar."'/></div>";
							}else echo "</div>";
							echo '<div class = "image-div-class">';
							echo '<img class="img-class" src="'.$dir.'"/></div>';
							echo '</div>';
						}
						//	 WYŚWIETLANIE RYSUNKÓW OD NAJNOWSZYCH

			?>
			</article>
		</section>
	</div>
	</div>

	<script src="scripts/jquery-3.4.0.slim.min.js"></script>
		<script src="scripts/mimizi.js"></script>


</body>
</html>