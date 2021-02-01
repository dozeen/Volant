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
  //Definiamo la Directory che contiene tutte le stanze
  $sdirectory = '../stanze/';
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
  $scriviamo=$_GET["scriviamo"];  // Messaggio da inserire nella stanza
  $marchiofacebook= substr($fbclid, -16); // prendiamo la coda del marchio di facebook
  // prendiamo la chiave
  $chiave=$_GET["chiave"];
  //Se la chiave e` vuota allora la assegnamo ad un valore, Facebook o casuale? CREIAMO STANZA
  if (empty($chiave)) {
    if (isset($fbclid)){$chiave=$marchiofacebook;
            $myfile = fopen($sdirectory.$marchiofacebook, "a") or die('<H2><a href="'.$sito.'Volant/">Stanza Inesistente, Creane un`altra.</a></H2>');
    fclose($myfile);
          }
    else { $chiave=$password;}
  }
  // Prendiamo PIPE
  $stanza=$chiave;
  $contatore=$_GET["contatore"];  //Quante linee abbiamo inviato?
  $contatore=++$contatore;        //Ecco proprio adesso arriva una nuova linea
  $nomef=$_GET["nomef"];
  $prefisso=$_GET["prefisso"];
  $lunghezzafile=$_GET['lunghezzafile'];
  $size=$_GET['size'];
  if (isset($prefisso)){$nomef=$prefisso;} 
  if (empty($nomef)){$prefisso="Nome";}
  //Diamo un nome casuale
  $aggiungi="<mark>  $scriviamo</mark><figcaption class=\"blockquote-footer\"> $nomef </figcaption><br>";
  // Aggiungiamo solo se la variabile $scriviamo viene inviata 
  if (is_string($scriviamo))  {
  $costruttore = fopen($sdirectory.$stanza, "a+") or die("Temporaneamente non Disponibile!");
  fwrite($costruttore, $aggiungi);
  fclose($costruttore);
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
  <meta http-equiv="refresh" content="57; URL='.$sito.'Volant/rispondi.php?chiave='.$stanza.'&prefisso='.$prefisso.'" />
  <head>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
  <title>(' .$contatore. ")" .$nometit. '  </title>
  <meta http-equiv="content-type" content="text/html;charset=utf-8" />
  </head>
  <body>
  <div class="mx-auto" style="width: 200px;">
  ';
  $myfile = fopen($sdirectory.$stanza, "r") or die('<H2><a href="'.$sito.'Volant/">Stanza Inesistente, Creane un`altra.</a></H2>');
  echo fgets($myfile);
  fclose($myfile);
  
  
  //adesso svuotiamo la stanza e la marchiamo ^_^ se inseriamo piu di 3 righe di continuo
  if ( $contatore > "2") {
    $contatore = '0';
    $chiave = fopen($sdirectory.$chiave, "w") or die("Temporaneamente non Disponibile!");
    fwrite($chiave, "^_^_^_^_^_^_^_^_^_^_^_^");
    fclose($chiave);
    //abbiamo cancellato il contenuto della stanza , si riparte.
    
  }
  // Rispondiamo?
  echo '
  
  <form action="rispondi.php">
  <input type="hidden" id="chiave" name="chiave" value="'.$stanza.'">
  <input type="hidden" id="contatore" name="contatore" value="'.$contatore.'"><br>
  <input type="hidden" id="nomef" name="nomef" value="'.$nomef.'">
  <input type="hidden" id="size" name="size" value="'.$size.'">
  <input type="hidden" id="lunghezzafile" name="lunghezzafile" value="'.$lunghezzafile.'">
  <input type="text" id="scriviamo" name="scriviamo"  placeholder="TAB Scrivi e Enter" ><br>
  <input type="text" id="prefisso" name="prefisso" label="Prefisso" placeholder='.$nomef.' >
  <input type="hidden" id="chiave" name="chiave" value="'.$stanza.'"><br>
  <button type="submit" class="btn btn-outline-secondary">Invia Messaggio</button>
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