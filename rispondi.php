<?php
/*
rispondi.php - Release 1.1
Copyright 2021 pi <onofrio@dozeen.ns0.it>
Pulizia codice, HTML5, Bootstrap aggiornato, input sanitizzati
*/

$sdirectory = '../stanze/';
$idirectory = '../immagini_caricate/';
$sito = 'http://www.onofrio.homepc.it/';
$bodystyle = "#e6ded6";
$aggiorna = 57;

// Generazione stanza casuale
function generatePassword($length = 8) {
    $alfa_number = "abcdefghijlmnopqrstuvwxyzABCDEFGHIJLMNOPQRSTUVWXYZ0123456789";
    return substr(str_shuffle($alfa_number), 0, min($length, 8));
}

// Prendi input in sicurezza
$scriviamo = htmlspecialchars($_REQUEST['scriviamo'] ?? '');
$chiave = preg_replace("/[^a-zA-Z0-9]/", "", $_REQUEST['chiave'] ?? '');
$prefisso = htmlspecialchars($_REQUEST['prefisso'] ?? '');
$contatore = intval($_POST['contatore'] ?? 0);
$nomef = htmlspecialchars($_POST['nomef'] ?? $_SERVER['REMOTE_ADDR']);

// Creazione stanza se non esiste
if (empty($chiave)) {
    $chiave = generatePassword();
    file_put_contents($sdirectory . $chiave, "");
}

$stanza = $chiave;

// Funzioni utili
function leggi_stanza($file) {
    return file_exists($file) ? file_get_contents($file) : '';
}
function scrivi_stanza($file, $contenuto) {
    file_put_contents($file, $contenuto, FILE_APPEND);
}

// Gestione messaggio
if (!empty($scriviamo)) {
    $aggiungi = "<figure class=\"text-center\"><blockquote class=\"blockquote\">$scriviamo</blockquote><figcaption class=\"blockquote-footer\">$nomef</figcaption><br>";
    scrivi_stanza($sdirectory . $stanza, $aggiungi);
    $contatore++;
}

// Gestione immagine upload
if (isset($_FILES['img'])) {
    $file_tmp = $_FILES['img']['tmp_name'];
    $filename = $chiave;
    move_uploaded_file($file_tmp, $idirectory . $filename);
}

// Lunghezza stanza per aggiornamento audio (logica semplificata)
$lunghezzaf = strlen(leggi_stanza($sdirectory . $stanza));

// HTML
echo '<!DOCTYPE html>
<html lang="it">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Volant</title>
<meta http-equiv="refresh" content="' . $aggiorna . '; URL=rispondi.php?chiave=' . $stanza . '&prefisso=' . $prefisso . '&lunghezzav=' . $lunghezzaf . '">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body style="background-color:' . $bodystyle . '">

<div class="container mt-4">';

// Mostra immagine stanza
$img_path = $idirectory . $chiave;
if (file_exists($img_path)) {
    echo '<img src="../immagini_caricate/' . $chiave . '" class="img-fluid rounded" alt="Stanza">';
} else {
    echo '<img src="immagini/msgVolant.gif" class="img-fluid" alt="Stanza">';
}

// Mostra messaggi
echo '<div class="mt-3">';
echo leggi_stanza($sdirectory . $chiave);
echo '</div>';

// Form invio messaggio e upload immagine
echo '<form action="rispondi.php" method="post" enctype="multipart/form-data" class="mt-3">
<input type="hidden" name="chiave" value="' . $chiave . '">
<input type="hidden" name="contatore" value="' . $contatore . '">
<input type="hidden" name="nomef" value="' . $nomef . '">
<input type="text" name="scriviamo" class="form-control mb-2" placeholder="Scrivi messaggio">
<input type="file" name="img" class="form-control mb-2">
<button type="submit" class="btn btn-outline-secondary">Invia</button>
</form>';

echo '</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>';
?>
