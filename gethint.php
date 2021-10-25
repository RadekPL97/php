<?php
if(!isset($_REQUEST['q'])){
    header('Location: galeria');
    exit();
}
// get the q parameter from URL
require_once "config.php";
try{
    $connect = @new mysqli($host,$db_user,$db_password,$db_name);	//UTWORZENIE POLACZENIA Z BAZA DANYCH, UTWORZENIE OBIEKTU	
    if($connect->connect_errno!=0)
    {
        throw new Exception("Nie można połączyć z bazą danych");
    }
    else
    {
        /*	SZUKANIE NAJWIĘKSZEGO ID	*/
        try{
            $findMaxId = $connect->query("SELECT * FROM users WHERE id = ( SELECT MAX( id ) FROM users )");
            if(!$findMaxId) throw new Exception("BŁĄD wykonania kwerendy. Skorzystaj z okazji i zdobądź 100 DrawDolarów: powiadom nas o tej sytuacji: pomoc@drawplatform.pl");
              else{
                $wiersz = $findMaxId -> fetch_assoc();
                $max_id = $wiersz['id'];
            }
        }catch(Exception $e){
            echo $e -> getMessage();
            exit();
        }


        // PHP + AJAX
        $users = array();

        for($i=1;$i<=$max_id;$i++)	/*	PETLA DO PRZESKAKIWANIA PO USERACH	*/
        {
            if($rezultat = $connect->query("SELECT * FROM users WHERE id='$i'"))
            {
                $wiersz = $rezultat->fetch_assoc();
                $users[] = $wiersz['nick'];				// LOGIN UZYTKOWNIKA
            }
            else{
                $_SESSION['err-search'] = "Nie można wykonać kwerendy";
            }
        }
    }
}
catch(Exception $e){		/* $e przechowuje rodzaj napotkanego bledu	*/
    echo "Błąd".$e->getMessage();
}

//  PHP + AJAX
$q = $_REQUEST["q"];

$hint = array();


// lookup all hints from array if $q is different from "" 
if ($q !== "") {
    $q = strtolower($q);
    $len=strlen($q);
    foreach($users as $name) {
        if (stristr($q, substr($name, 0, $len))) {
            if (!sizeof($hint)) {
                $hint[] = $name;
            } else {
                $hint[] = $name;
            }
        }
    }
}

// Output "no suggestion" if no hint was found or output correct values 
if(!sizeof($hint))
{
    echo "<div class ='search-href'>nie znaleziono</div>";
} else{
    foreach($hint as $person){
    echo "<div class = 'gethint'><a class='login-href' href='dane-uzytkownika?userg=$person' target='_blank'>".$person."</a></div>";
    }
}
?>