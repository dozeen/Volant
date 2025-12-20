<?php
session_start();

$sdirectory = '../stanze/';
$sito = 'https://dozeen.ns0.it/Volant/rispondi.php';
$home = 'https://dozeen.ns0.it/Volant/';
$bodystyle = "#000";  // sfondo nero TRON
$aggiorna = 57;

$audio_files = range(0, 35);
$audio_random = $audio_files[array_rand($audio_files)];
$audio = "$audio_random.mp3";

// Genera una password casuale per la chiave
function generatePassword($length = 8) {
    $length = min($length, 8);
    $chars = "abcdefghijlmnopqrstuvwxyzABCDEFGHIJLMNOPQRSTUVWXYZ0123456789";
    return substr(str_shuffle($chars), 0, $length);
}

function sanitize($str) {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

function getStanzaFile($key, $dir) {
    return $dir . $key;
}

// INPUT
$scriviamo = $_POST['scriviamo'] ?? '';
$scriviamo = sanitize($scriviamo);

$chiave = $_POST['chiave'] ?? $_GET['chiave'] ?? '';
$contatore = intval($_POST['contatore'] ?? 0);
$lunghezzav = intval($_POST['lunghezzav'] ?? 0);

// CREAZIONE STANZA
if (empty($chiave)) {
    $chiave = generatePassword();
    $stanza_file = getStanzaFile($chiave, $sdirectory);
    $file = fopen($stanza_file, "a+") or die("Temporaneamente non disponibile!");
    fclose($file);
} else {
    $stanza_file = getStanzaFile($chiave, $sdirectory);
}

// URL completo della stanza
$chiave_url = $sito . '?chiave=' . $chiave;

// AGGIUNGI MESSAGGIO
if (!empty($scriviamo)) {
    $aggiungi = "<div class='messaggio-tron'><p>$scriviamo</p></div>\n";
    file_put_contents($stanza_file, $aggiungi, FILE_APPEND);
    $contatore++;
}

// AUDIO
$lunghezzaf = filesize($stanza_file);
$audio_html = ($lunghezzaf != $lunghezzav) ? "<audio controls autoplay style='display:none'><source src='audio/$audio' type='audio/mpeg'></audio>" : '';

// RESET STANZA
if ($contatore > 2) {
    file_put_contents($stanza_file,"<br>");
    $contatore = 0;
    $bodystyle = "#000";
}

// HTML
echo <<<HTML
<!DOCTYPE html>
<html lang="it">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Volant TRON - Messaggi</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body {
    background:$bodystyle; 
    color:#0ff; 
    font-family:'Courier New', monospace; 
    display:flex; 
    flex-direction:column; 
    min-height:100vh; 
    justify-content:center; 
    align-items:center;
    margin:0;
}
.messaggio-tron { 
    border-left: 2px solid #0ff; 
    padding: 5px; 
    margin: 3px 0; 
    animation: glow 1s infinite alternate; 
}
@keyframes glow { from {box-shadow:0 0 5px #0ff;} to {box-shadow:0 0 15px #0ff;} }
input, button {
    background:#111;
    border:1px solid #0ff;
    color:#0ff;
    margin:5px 0;
    padding:8px;
}
.dropdown { margin-bottom: 15px; }
.dropdown a { color:#0ff; text-decoration:none; }
.container { width:100%; max-width:700px; text-align:center; }
.text-start { max-height:400px; overflow-y:auto; width:100%; }
</style>
<meta http-equiv="refresh" content="$aggiorna; URL=rispondi.php?chiave=$chiave&lunghezzav=$lunghezzav">
</head>
<body>
<div class="container">

<div class="dropdown">
  <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenu2" data-bs-toggle="dropdown" aria-expanded="false">
    Menu
  </button>
  <ul class="dropdown-menu" aria-labelledby="dropdownMenu2">
    <li><a class="dropdown-item" href="$chiave_url">Crea/Stanza</a></li>
    <li><a class="dropdown-item" href="$home">Home</a></li>
    <li>
      <input type="text" id="myInput" value="$chiave_url" readonly class="form-control mb-2">
      <button class="btn btn-secondary btn-sm mb-2" onclick="copyKey()">Copia Chiave</button>
    </li>
  </ul>
</div>

$audio_html

<h5 class="mt-3">Contenuto della chat:</h5>
<div class="text-start mb-3">
HTML;

if(file_exists($stanza_file)) echo file_get_contents($stanza_file);

echo <<<HTML
</div>

<form action="rispondi.php" method="post" class="mb-3">
    <input type="hidden" name="chiave" value="$chiave">
    <input type="hidden" name="contatore" value="$contatore">
    <input type="hidden" name="lunghezzav" value="$lunghezzav">
    <input type="text" name="scriviamo" class="form-control mb-2" placeholder="Scrivi qui il tuo messaggio">
    <button type="submit" class="btn btn-primary btn-sm">Invia Messaggio</button>
</form>

<script>
function copyKey() {
    var copyText = document.getElementById("myInput");
    copyText.select();
    copyText.setSelectionRange(0, 99999); // per dispositivi mobili
    document.execCommand("copy");
    alert("Chiave copiata negli appunti!");
}
</script>

</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
HTML;
?>
