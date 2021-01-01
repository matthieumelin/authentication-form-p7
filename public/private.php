<?php

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

// autoloading composer
require __DIR__."/../vendor/autoload.php";

// démarrage de la session
session_start();

// instanciation du chargeur de templates
$loader = new FilesystemLoader(__DIR__."/../templates");

// instanciation du moteur de template
$twig = new Environment($loader);

// traitement des données
if (!isset($_SESSION["login"])) {
    // renvoyer l'utilisateur vers la page connection
    $url = "/login.php";
    header("Location: {$url}", true, 301);
    exit();
}

// afficage du rendu du template
echo $twig->render("private.html.twig", [
    // transmission de données au template
    "login" => $_SESSION["login"],
    "password" => $_SESSION["password"],
]);