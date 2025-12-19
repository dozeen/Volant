<?php
session_start();

$sdirectory = '../stanze/';
$idirectory = '../immagini_caricate/';
$sito = 'http://www.onofrio.homepc.it/';
$home = 'https://dozeen.ns0.it/Volant/';
$bodystyle = "#000";  // sfondo nero TRON
$aggiorna = 57;

$audio_files = range(0, 35);
$audio_random = $audio_files[array_rand($audio_files)];
$audio = "$audio_random.mp3";

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

$scriviamo = $_POST['scriviamo'] ?? $_GET['scriviamo'] ?? '';
$scriviamo = sanitize($scriviamo);

$chiave = $_POST['chiave'] ?? $_GET['chiave'] ?? '';
$prefisso = sanitize($_POST['prefisso'] ?? $_GET['prefisso'] ?? '');
$contatore = intval($_POST['contatore'] ?? 0);
$nomef = sanitize($_POST['nomef'] ?? '');
$lunghezzav = intval($_POST['lunghezzav'] ?? 0);

// Creazione stanza se non esiste
if (empty($chiave)) {
    $chiave = generatePassword();
    $file = fopen(getStanzaFile($chiave, $sdirectory), "a+") or die("Temporaneamente non disponibile!");
    fclose($file);
}

// Nome utente
if (!empty($prefisso)) $nomef = $prefisso;
if (empty($nomef)) $nomef = $_SERVER['REMOTE_ADDR'];

// Aggiungi messaggio testuale
$stanza_file = getStanzaFile($chiave, $sdirectory);
if (!empty($scriviamo)) {
    $aggiungi = "<div class='messaggio-tron'><p>$scriviamo</p><span class='mittente'>$nomef</span></div>\n";
    file_put_contents($stanza_file, $aggiungi, FILE_APPEND);
    $contatore++;
}

// Audio
$lunghezzaf = filesize($stanza_file);
if ($lunghezzaf != $lunghezzav) {
    $audio_html = "<audio controls autoplay style='display:none'><source src='audio/$audio' type='audio/mpeg'></audio>";
    $lunghezzav = $lunghezzaf;
} else {
    $audio_html = '';
}

// Reset stanza dopo 3 messaggi
if ($contatore > 2) {
    file_put_contents($stanza_file,"<br>");
    $contatore = 0;
    $bodystyle = "#000";
}

echo <<<HTML
<!DOCTYPE html>
<html lang="it">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="refresh" content="$aggiorna; URL=rispondi.php?chiave=$chiave&prefisso=$prefisso&lunghezzav=$lunghezzav">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet">
<title>Volant TRON - Messaggi</title>
<style>
body {background:$bodystyle; color:#0ff; font-family:'Courier New', monospace;}
input, button {background:#111; border:1px solid #0ff; color:#0ff; margin:5px 0; padding:8px;}
.messaggio-tron {border-left:2px solid #0ff; padding:5px; margin:3px 0; animation: glow 1s infinite alternate;}
.mittente {display:block; font-size:0.8em; color:#0ff;}
.menu-dropdown {position:relative; display:inline-block; margin-bottom:10px;}
.menu-dropdown-content {display:none; position:absolute; background:#111; min-width:220px; padding:10px; border:1px solid #0ff; z-index:1;}
.menu-dropdown:hover .menu-dropdown-content {display:block;}
.menu-link {color:#0ff; text-decoration:none; display:block; margin-bottom:5px;}
@keyframes glow {from {box-shadow:0 0 5px #0ff;} to {box-shadow:0 0 15px #0ff;}}
</style>
</head>
<body>
<div class="container mt-3">

<div class="menu-dropdown">
  <button class="btn btn-secondary btn-sm">Menu</button>
  <div class="menu-dropdown-content">
    <input type="text" name="prefisso" value="$nomef" class="form-control mb-2" readonly>
    <input type="text" id="myInput" value="{$sito}Volant/rispondi.php?chiave={$chiave}" readonly class="form-control mb-2">
    <button class="btn btn-secondary btn-sm mb-2" onclick="copyKey()">Copia Chiave</button>
    <a href="$home" class="menu-link">Home</a>
  </div>
</div>

$audio_html

<h5 class="mt-3">Contenuto della chat:</h5>
<div class="text-start mb-3" style="max-height:400px; overflow-y:auto;">
HTML;

if(file_exists($stanza_file)) echo file_get_contents($stanza_file);

echo <<<HTML
</div>

<form action="rispondi.php" method="post" class="mb-3">
<input type="hidden" name="chiave" value="$chiave">
<input type="hidden" name="contatore" value="$contatore">
<input type="hidden" name="nomef" value="$nomef">
<input type="hidden" name="lunghezzav" value="$lunghezzav">
<input type="text" name="scriviamo" class="form-control mb-2" placeholder="Scrivi qui il tuo messaggio">
<button type="submit" class="btn btn-primary btn-sm">Invia Messaggio</button>
</form>

<script>
function copyKey() {
    var copyText = document.getElementById("myInput");
    copyText.select();
    document.execCommand("copy");
    alert("Chiave copiata negli appunti!");
}
</script>

</div>
</body>
</html>
HTML;
?>
