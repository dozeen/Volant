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
  //Definiamo il nome del nostro SitoWEB (non dimenticate la /)
  $sito= 'http://dozeen.ns0.it/';

  //Generiamo una variabile con delle lettere casuali, ci servira` pre creare il nome delle stanze 
  function generatePassword($length)
  {
  //per il momento impostiamo a 8 il limite massimo dei carratteri che formeranno la password da generare
  $limit=8;
  if($length>$limit) {$length=$limit;}
  srand(time());
  //consideriamo i caratteri da randommizzare
  $alfa_number="abcdefghijlmnopqrstuvwxyzABCDEFGHIJLMNOPQRSTUVWXYZ0123456789";
  $len_alfa_number=strlen($alfa_number);
  $pass_random="";
  $i=0;
  //vado a pescare i caratteri uno per uno finchè raggiungo il valore di $length
  while($i<$length)
  {
  //con rand trovo l'indice casuale
  $number_random=rand(0,$len_alfa_number-1);
  $pass_random.=$alfa_number[$number_random];
  $i++;
  }
  return $pass_random;
  }
  $password=generatePassword(8);
  //Prendiamo i dati 
  $fbclid=$_GET["fbclid"];  // Facebook? interessante 
  $scriviamo=$_POST["scriviamo"];  // Messaggio da inserire nella stanza
  $marchiofacebook= substr($fbclid, -16); // prendiamo la coda del marchio di facebook
  // prendiamo la chiave
  $chiave=$_GET["chiave"];
  if (empty($chiave)) {
    $chiave=$_POST['chiave'];
  }
  //Se la chiave e` vuota allora la assegnamo ad un valore, Facebook o casuale? CREIAMO STANZA
  if (empty($chiave)) {
        if (isset($fbclid)){$chiave=$marchiofacebook;
            $myfile = fopen($sdirectory.$marchiofacebook, "a") or die('<H2><a href="'.$sito.'Volant/">Stanza Inesistente, Creane un`altra.</a></H2>');
    fclose($myfile);
          }
    else {
       $chiave=$password;
       $costruttore = fopen($sdirectory.$chiave, "a+") or die("Temporaneamente non Disponibile!");
  fwrite($costruttore, "^_^_^_^_^_^_^_^_^_^_^_^_^_^_^_^_^_^");
  fclose($costruttore);
    }
  }
 
  // Prendiamo PIPE
  $stanza=$chiave;
  $contatore=$_POST["contatore"];  //Quante linee abbiamo inviato?
  $nomef=$_POST["nomef"];
  $prefisso=$_GET["prefisso"];
  if (empty($prefisso)) {
    $prefisso=$_POST['prefisso'];
  }
  $lunghezzav=$_GET['lunghezzav'];
  $nometit=$_POST['nometit'];
  $size=$_POST['size'];
  if (!empty($prefisso)){$nomef=$prefisso;}
  if (empty($nomef)){
    $nomef=$_SERVER['REMOTE_ADDR'];
  }

//Audio
  $myfile = fopen($sdirectory.$stanza, "r") or die('<H2><a href="'.$sito.'Volant/">Stanza Inesistente, Creane un`altra.</a></H2>');
  $swap = fgets($myfile);
  $lunghezzaf = strlen($swap);
  fclose($myfile);  
//echo "var $lunghezzav --- $lunghezzaf ";
  if ($lunghezzaf != $lunghezzav) { 
  echo '<audio controls autoplay style="display:none">
  <source src="audio/wee.mp3" type="audio/mpeg">
  Your browser does not support the audio element.
</audio>
' ;
  $lunghezzav = $lunghezzaf;  
}
//Diamo un nome casuale
  $aggiungi="<mark>  $scriviamo</mark><figcaption class=\"blockquote-footer\"> $nomef </figcaption><br>";
  // Aggiungiamo solo se la variabile $scriviamo viene inviata 
  if (is_string($scriviamo))  {
  $costruttore = fopen($sdirectory.$stanza, "a+") or die("Temporaneamente non Disponibile!");
  fwrite($costruttore, $aggiungi);
  fclose($costruttore);
  $contatore=++$contatore;        //Ecco proprio adesso arriva una nuova linea
  //Quanto e lungo il file? $lunghezzav
  $myfile = fopen($sdirectory.$stanza, "r") or die('<H2><a href="'.$sito.'Volant/">Stanza Inesistente, Creane un`altra.</a></H2>');
  $swap = fgets($myfile);
  $lunghezzaf = strlen($swap);
  fclose($myfile);  
  $lunghezzav = $lunghezzaf;  
  }
  $myfile = fopen($sdirectory.$stanza, "r") or die('<H2><a href="'.$sito.'Volant/">Stanza Inesistente, Creane un`altra.</a></H2>');
  $swap =  fgets($myfile);
  $nometit = substr($swap, -27 , 10); // Che scriviamo nel titolo?
  fclose($myfile);
  echo '
  <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
  <html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="refresh" content="47; URL='.$sito.'Volant/rispondi.php?chiave='.$stanza.'&prefisso='.$prefisso.'&lunghezzav='.$lunghezzav.'" />
  <head>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
  <title>(' .$contatore. ")" .$nometit. '  </title>
  <meta http-equiv="content-type" content="text/html;charset=utf-8" />
  </head>
  <body>
  <div class="mx-auto" style="width: auto;">
  ';
  //Immagini_caricate
if (isset($_FILES['img'])){
   // inserisco il percorso dove verranno caricate le foto 
   $upload_percorso = '../immagini_caricate/';
   // salvo il percorso temporaneo dell'immagine caricata 
   $file_tmp = $_FILES['img']['tmp_name'];
   // salvo il nome dell'immagine caricata 
   $file_nome = $_POST['chiave'];
   // sposto l'immagine nel percorso che prima abbiamo deciso 
   move_uploaded_file($file_tmp, $upload_percorso.$file_nome);
  }

echo '<img src="../immagini_caricate/'.$chiave.'" class="img-fluid" alt=""><br> ' ; 

//adesso svuotiamo la stanza e la marchiamo ^_^ se inseriamo piu di 3 righe di continuo
if ( $contatore > "2") {
  $contatore = '0';
  $myfile = fopen($sdirectory.$chiave, "w") or die("Temporaneamente non Disponibile!");
  fwrite($myfile, "^_^_^_^_^_^_^_^_^_^_^_^_^<br>");
  fclose($myfile);
  //Cancelliamo anche l`immagine
  $myfile = fopen($idirectory.$chiave, "w") or die("Temporaneamente non Disponibile!");
  fwrite($myfile, "-");
  fclose($myfile);
}
//abbiamo cancellato il contenuto della stanza , si riparte.

  //Leggiamo il contenuto della stanza
  $myfile = fopen($sdirectory.$chiave, "r") or die('<H2><a href="'.$sito.'Volant/">Stanza Inesistente, Creane un`altra.</a></H2>');
  echo fgets($myfile);
  fclose($myfile);
  

 
  // FORM TAB ?
  echo '
  <form action="rispondi.php" method="post" >
  <input type="hidden" id="chiave" name="chiave" value="'.$chiave.'">
  <input type="hidden" id="contatore" name="contatore" value="'.$contatore.'"><br>
  <input type="hidden" id="nomef" name="nomef" value="'.$nomef.'">
  <input type="hidden" id="size" name="size" value="'.$size.'">
  <input type="hidden" id="lunghezzav" name="lunghezzav" value="'.$lunghezzav.'">
  <input type="text" id="scriviamo" name="scriviamo"  placeholder="TAB Scrivi e Enter"><br>
  <input type="text" id="prefisso" name="prefisso"  placeholder="'.$nomef.'"    >
  <button type="submit" class="btn btn-outline-secondary">Invia Messaggio</button>
  </form><br><br>
  <form action="rispondi.php"  method="post" enctype="multipart/form-data" name="upload_immagine">
  Scegli immagine <input name="img" type="file" />
  <input type="hidden" id="name" name="name" value="'.$stanza.'"><br>
  <input type="hidden" id="chiave" name="chiave" value="'.$chiave.'">
  <input type="hidden" id="contatore" name="contatore" value="'.$contatore.'">
  <input type="hidden" id="nomef" name="nomef" value="'.$nomef.'">
  <input type="hidden" id="size" name="size" value="'.$size.'">
  <input type="hidden" id="lunghezzav" name="lunghezzav" value="'.$lunghezzav.'">
  <input type="submit" name="carica" value="carica" />
  </form>


<br><br>
  
<div class="row align-items-end">Indirizzo di QUESTA stanza
  <input type="text"  value="'.$sito.'Volant/rispondi.php?chiave='.$stanza.'" id="myInput">
  <button onclick="myFunction()">Copia la Chiave </button>
  </div>
  <script>
  function myFunction() {
  var copyText = document.getElementById("myInput");
  copyText.select();
  copyText.setSelectionRange(0, 99999)
  document.execCommand("copy");
  }
  </script>
  <br><br>
  <div class="card-footer bg-transparent border-success">
  <a href="'.$sito.'Volant/">Crea un`altra Stanza</a>
  </pre>
  </div>
  </div>
  </body>
  </html>';
  ?> 