  <?php
  /*
  rispondi.php
  Copyright 2021 pi <onofrio@dozeen.ns0.it>
  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License as published by
  the Free Software Foundation; either version 2 of the License, or
  (at your option) any later version.
  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.
  You should have received a copy of the GNU General Public License
  along with this program; if not, write to the Free Software
  Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
  MA 02110-1301, USA.
  //Siamo nella Stanza
  */
  //Definiamo i soliti parametri di configurazione
  $sdirectory = '../stanze/'; //le iseriamo al di fuori del reposity
  $idirectory = '../immagini_caricate/';
  //Definiamo l`indirizzo del nostro SitoWEB (non dimenticate la /)
  $sito = 'http://www.onofrio.homepc.it/';
  $bodystyle = "#e6ded6";
  $aggiorna = "57";
  $avar = rand(0, 77); //Audio 20-28 RobertaSax
  $audioa = "$avar.mp3";
  //Generiamo una variabile con delle lettere casuali, ci servira` pre creare il nome delle stanze 
  function generatePassword($length)
  {
    //per il momento impostiamo a 8 il limite massimo dei carratteri che formeranno la password da generare
    $limit = 8;
    if ($length > $limit) {
      $length = $limit;
    }
    srand(time());
    //consideriamo i caratteri da randommizzare
    $alfa_number = "abcdefghijlmnopqrstuvwxyzABCDEFGHIJLMNOPQRSTUVWXYZ0123456789";
    $len_alfa_number = strlen($alfa_number);
    $pass_random = "";
    $i = 0;
    //vado a pescare i caratteri uno per uno finchè raggiungo il valore di $length
    while ($i < $length) {
      //con rand trovo l'indice casuale
      $number_random = rand(0, $len_alfa_number - 1);
      $pass_random .= $alfa_number[$number_random];
      $i++;
    }
    return $pass_random;
  }
  $password = generatePassword(8);
  //Prendiamo i dati 
  $fbclid = $_GET["fbclid"];  // Facebook? interessante 
  $scriviamo = $_POST["scriviamo"];  // Messaggio da inserire nella stanza
  $marchiofacebook = substr($fbclid, -16); // prendiamo la coda del marchio di facebook
  // prendiamo la chiave
  $chiave = $_GET["chiave"];
  if (empty($chiave)) {
    $chiave = $_POST['chiave'];
  }
  //Se la chiave e` vuota allora la assegnamo ad un valore, Facebook o casuale? CREIAMO STANZA
  if (empty($chiave)) {
    if (isset($fbclid)) {
      $chiave = $marchiofacebook;
      $myfile = fopen($sdirectory . $marchiofacebook, "a") or die('<H2><a href="' . $sito . 'Volant/rispondi.php?scriviamo=">Stanza Inesistente, Creane un`altra.</a></H2>');
      fclose($myfile);
    } else {
      $chiave = $password;
      $costruttore = fopen($sdirectory . $chiave, "a+") or die("Temporaneamente non Disponibile!");
      fwrite($costruttore, "");
      fclose($costruttore);
    }
  }
  // Prendiamo PIPE
  $stanza = $chiave;
  $contatore = $_POST["contatore"];  //Quante linee abbiamo inviato?
  $nomef = $_POST["nomef"];
  $prefisso = $_GET["prefisso"];
  if (empty($prefisso)) {
    $prefisso = $_POST['prefisso'];
  }
  $lunghezzav = $_GET['lunghezzav'];
  $nometit = $_POST['nometit'];
  if (!empty($prefisso)) {
    $nomef = $prefisso;
  }
  if (empty($nomef)) {
    $nomef = $_SERVER['REMOTE_ADDR'];
  }

  //Alert contatore >1
  if ($contatore > "0") {
    $bodystyle = "#d71313";
    $audioa = "Cancella.mp3";
    $aggiorna = '2';
    $lunghezzav = $lunghezzaf;
  }
  if ($contatore > "1") {
    $audioa = "OkCanc.mp3";
    $lunghezzav = $lunghezzaf;
  }

  //Audio
  $myfile = fopen($sdirectory . $stanza, "r") or die('<H2><a href="' . $sito . 'Volant/rispondi.php?scriviamo=">Stanza Inesistente, Creane un`altra.</a></H2>');
  $swap = fgets($myfile);
  $lunghezzaf = strlen($swap);
  fclose($myfile);

  //echo "var $lunghezzav --- $lunghezzaf ";
  if ($lunghezzaf != $lunghezzav) {
    echo '<audio controls autoplay style="display:none">
  <source src="audio/' . $audioa . '" type="audio/mpeg">
  Your browser does not support the audio element.
  </audio>
  ';
    $lunghezzav = $lunghezzaf;
  }

  //Layout e Contenuti
  $aggiungi = "<figure class=\"text-center\"><blockquote class=\"blockquote\"><p>  $scriviamo</blockquote><figcaption class=\"blockquote-footer\"> $nomef </figcaption><br>";
  // Aggiungiamo solo se la variabile $scriviamo viene inviata 
  if (is_string($scriviamo)) {
    $costruttore = fopen($sdirectory . $stanza, "a+") or die("Temporaneamente non Disponibile!");
    fwrite($costruttore, $aggiungi);
    fclose($costruttore);
    $contatore = ++$contatore;        //Ecco proprio adesso arriva una nuova linea
    //Quanto e lungo il file? $lunghezzav
    $myfile = fopen($sdirectory . $stanza, "r") or die('<H2><a href="' . $sito . 'Volant/rispondi.php?scriviamo=">Stanza Inesistente, Creane un`altra.</a></H2>');
    $swap = fgets($myfile);
    $lunghezzaf = strlen($swap);
    fclose($myfile);
    $lunghezzav = $lunghezzaf;
  }
  $myfile = fopen($sdirectory . $stanza, "r") or die('<H2><a href="' . $sito . 'Volant/rispondi.php?scriviamo=">Stanza Inesistente, Creane un`altra.</a></H2>');
  $swap =  fgets($myfile);
  $nometit = substr($swap, -27, 10); // Che scriviamo nel titolo?
  fclose($myfile);

  //Memorizzo Immagine della singola stanza in Immagini_caricate
  if (isset($_FILES['img'])) {
    // inserisco il percorso dove verranno caricate le foto 
    $upload_percorso = '../immagini_caricate/';
    // salvo il percorso temporaneo dell'immagine caricata 
    $file_tmp = $_FILES['img']['tmp_name'];
    // salvo il nome dell'immagine caricata 
    $filename = $_POST['chiave'];
    // sposto l'immagine nel percorso che prima abbiamo deciso 
    move_uploaded_file($file_tmp, $upload_percorso . $filename);

    class SimpleImage {

      var $image;
      var $image_type;
   
      function load($filename) {
   
         $image_info = getimagesize($filename);
         $this->image_type = $image_info[2];
         if( $this->image_type == IMAGETYPE_JPEG ) {
   
            $this->image = imagecreatefromjpeg($filename);
         } elseif( $this->image_type == IMAGETYPE_GIF ) {
   
            $this->image = imagecreatefromgif($filename);
         } elseif( $this->image_type == IMAGETYPE_PNG ) {
   
            $this->image = imagecreatefrompng($filename);
         }
      }
      function save($filename, $image_type=IMAGETYPE_JPEG, $compression=75, $permissions=null) {
   
         if( $image_type == IMAGETYPE_JPEG ) {
            imagejpeg($this->image,$filename,$compression);
         } elseif( $image_type == IMAGETYPE_GIF ) {
   
            imagegif($this->image,$filename);
         } elseif( $image_type == IMAGETYPE_PNG ) {
   
            imagepng($this->image,$filename);
         }
         if( $permissions != null) {
   
            chmod($filename,$permissions);
         }
      }
      function output($image_type=IMAGETYPE_JPEG) {
   
         if( $image_type == IMAGETYPE_JPEG ) {
            imagejpeg($this->image);
         } elseif( $image_type == IMAGETYPE_GIF ) {
   
            imagegif($this->image);
         } elseif( $image_type == IMAGETYPE_PNG ) {
   
            imagepng($this->image);
         }
      }
      function getWidth() {
   
         return imagesx($this->image);
      }
      function getHeight() {
   
         return imagesy($this->image);
      }
      function resizeToHeight($height) {
   
         $ratio = $height / $this->getHeight();
         $width = $this->getWidth() * $ratio;
         $this->resize($width,$height);
      }
   
      function resizeToWidth($width) {
         $ratio = $width / $this->getWidth();
         $height = $this->getheight() * $ratio;
         $this->resize($width,$height);
      }
   
      function scale($scale) {
         $width = $this->getWidth() * $scale/100;
         $height = $this->getheight() * $scale/100;
         $this->resize($width,$height);
      }
   
      function resize($width,$height) {
         $new_image = imagecreatetruecolor($width, $height);
         imagecopyresampled($new_image, $this->image, 0, 0, 0, 0, $width, $height, $this->getWidth(), $this->getHeight());
         $this->image = $new_image;
      }      
   
   }  
    $aggiorna = '7';
  }
  //HTML
  echo '
  <!doctype html>
  <html lang="en">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="refresh" content="' . $aggiorna . '; URL=rispondi.php?chiave=' . $stanza . '&prefisso=' . $prefisso . '&lunghezzav=' . $lunghezzav . '" />
  <head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
  <title>(' . $contatore . ")" . $nometit . '  </title>
  <meta http-equiv="content-type" content="text/html;charset=utf-8" />
  </head>
  <body style="background-color: ' . $bodystyle . ' ;>
  
  <div class="container">
  <div class="container-fluid ">
  ';

  // se limmagine non esiste allora vuol dire che ha appena creato la stanza , mostramogli la chiave da condividere.
  $indirizzo = "../immagini_caricate/$chiave";
  $grandezza = filesize($indirizzo);
  //echo "$indirizzo $grandezza" ;
  if (empty($grandezza)){
  echo '<img src="immagini/msgVolant.gif" class="rounded float-start " alt="Stanza Creata" <figure class="text-center"></div>';
  echo '<input type="text"  size="1" value="' . $sito . 'Volant/rispondi.php?chiave=' . $stanza . '" id="myInput">
  <button class="btn btn-danger btn-sm" onclick="myFunction()">Copia la Chiave</button>
  <script>
  function myFunction() {
  var copyText = document.getElementById("myInput");
  copyText.select();
  copyText.setSelectionRange(0, 99999)
  document.execCommand("copy");
  }
  </script><img src="immagini/Chiave.gif"  alt="Copia la Chiave">
<br>
 <form action="rispondi.php"  method="post" enctype="multipart/form-data" name="upload_immagine"><input name="img"  class="btn btn-secondary btn-sm" type="file" />
      <input type="hidden" id="name" name="name" value="' . $stanza . '"><br>
      <input type="hidden" id="chiave" name="chiave" value="' . $chiave . '">
      <input type="hidden" id="contatore" name="contatore" value="' . $contatore . '">
      <input type="hidden" id="nomef" name="nomef" value="' . $nomef . '">
      <input type="hidden" id="lunghezzav" name="lunghezzav" value="' . $lunghezzav . '">
      <input type="submit" class="btn btn-primary btn-sm"  name="carica" value="Carica una Immagine" />
      </form> </a>
';
}else {
  echo '<img src="../immagini_caricate/' . $chiave . '" class="figure-img img-fluid rounded"  alt="Stanza Creata" width="320" </div>';
  }
  /*
*/
  //Leggiamo il contenuto della stanza
  $myfile = fopen($sdirectory . $chiave, "r") or die('<H2><a href="' . $sito . 'Volant/rispondi.php?scriviamo=">Stanza Inesistente, Creane un`altra.</a></H2>');
  echo fgets($myfile);
  fclose($myfile);

  //adesso svuotiamo la stanza e la marchiamo ^_^ se inseriamo piu di 3 righe di continuo (meno di 7 secondi)
  if ($contatore > "2") {
    $myfile = fopen($sdirectory . $chiave, "w") or die("Temporaneamente non Disponibile!");
    fwrite($myfile, "<br>");
    fclose($myfile);
    //Cancelliamo anche l`immagine
    //$myfile = fopen($idirectory.$chiave, "w") or die("Temporaneamente non Disponibile!");
    //fwrite($myfile, "<br>");
    //fclose($myfile);
    $contatore = '0';
    $bodystyle = "#e6ded6";
    $lunghezzav = $lunghezzaf;
    //abbiamo cancellato il contenuto della stanza , si riparte.
  }
  
  // FORM TAB 
  echo '
  </figure>
  </div>
  <div class="container fixed-bottom text-center">
  <form action="rispondi.php" method="post" >
  <input type="hidden" id="chiave" name="chiave" value="' . $chiave . '">
  <input type="hidden" id="contatore" name="contatore" value="' . $contatore . '"><br>
  <input type="hidden" id="nomef" name="nomef" value="' . $nomef . '">
  <input type="hidden" id="lunghezzav" name="lunghezzav" value="' . $lunghezzav . '">
  <input type="text" id="scriviamo" name="scriviamo"  placeholder="TAB Scrivi e Enter"><br>
  <input type="text" id="prefisso" name="prefisso" size="7" placeholder="' . $nomef . '"    >
  <button type="submit" class="btn btn-outline-secondary">Invia Messaggio</button>
  </form>
  <div class="d-flex">
  <div class="dropdown me-1">
    <button type="button" class="btn btn-secondary dropdown-toggle" id="dropdownMenuOffset" data-bs-toggle="dropdown" aria-expanded="false" data-bs-offset="10,20">
      Menu
    </button>
    <ul class="dropdown-menu" aria-labelledby="dropdownMenuOffset">
      <li><a class="dropdown-item text-center" href="#"><form action="rispondi.php"  method="post" enctype="multipart/form-data" name="upload_immagine"><input name="img" class="btn btn-danger btn-sm" type="file" />
      <input type="hidden" id="name" name="name" value="' . $stanza . '"><br>
      <input type="hidden" id="chiave" name="chiave" value="' . $chiave . '">
      <input type="hidden" id="contatore" name="contatore" value="' . $contatore . '">
      <input type="hidden" id="nomef" name="nomef" value="' . $nomef . '">
      <input type="hidden" id="lunghezzav" name="lunghezzav" value="' . $lunghezzav . '">
      <input type="submit" class="btn btn-primary btn-sm"  name="carica" value="Carica una Immagine" />
      </form> </a></li>
      <li><a class="dropdown-item" href="#"><input type="text"  size="1" value="' . $sito . 'Volant/rispondi.php?chiave=' . $stanza . '" id="myInput">
      <button class="btn btn-secondary btn-sm" onclick="myFunction()">Copia la Chiave </button>
      <script>
      function myFunction() {
      var copyText = document.getElementById("myInput");
      copyText.select();
      copyText.setSelectionRange(0, 99999)
      document.execCommand("copy");
      }
      </script><img src="immagini/Chiave.jpg" alt="Copia la Chiave"></a></li>
      <li><a class="dropdown-item text-center" href="' . $sito . 'Volant/rispondi.php?scriviamo=">Crea un`altra Stanza</a></li>
    </ul>
  </div>
  </div>
  </div>
  <br><br>
  </pre>
  </div>
   </span>
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js" integrity="sha384-b5kHyXgcpbZJO/tY9Ul7kGkf1S0CWuKcCD38l8YkeH8z8QjE0GmW1gYU5S9FOnJ0" crossorigin="anonymous"></script>
   </body>
  </html>';
  ?>