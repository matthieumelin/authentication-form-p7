<?php

use Symfony\Component\Yaml\Yaml;
use Twig\Environment;
use Twig\Extension\DebugExtension;
use Twig\Loader\FilesystemLoader;

// autoloading de composer
require_once __DIR__ . '/../vendor/autoload.php';

// démarrage de la session
session_start();

// instanciation du chargeur de templates
$loader = new FilesystemLoader(__DIR__ . '/../templates');

// instanciation du moteur de template
$twig = new Environment($loader, [
    "debug" => true,
    "strict_variables" => true,
]);

// traitement des données
$config = Yaml::parseFile(__DIR__ . "/../config/config.yaml");

// ajout de l'extension de debug
$twig->addExtension(new DebugExtension());

// données du formulaire par défaut
$formData = [
    "login" => "Login",
    "password" => "Password",
];

// instanciation d'un tableau d'erreur
$errors = [];

// si la session est déjà initier
if (isset($_SESSION["login"])) {
    // redirection vers la page private
    header("Location: private.php");
}

// si le bouton est presser
if ($_POST) {
    // on stock les données des inputs dans le tableau
    foreach ($formData as $key => $value) {
        if (isset($_POST[$key])) {
            $formData[$key] = $_POST[$key];
        }
    }

    // tableau des tailles minimum et maximal
    $minLength = [8];
    $maxLength = [32, 190];

    $login = $_POST["login"];
    $password = password_hash($_POST["password"], PASSWORD_BCRYPT);

    // verification du mot de passe
    if (!password_verify($config["password"], $password)) {
        $errors["password"] = "Mot de passe ou login invalide";
    }

    // ajouter les valeures des inputs dans le tableau
    $formData["login"] = $_POST["login"];
    $formData["password"] = $_POST["password"];

    // ajouter les valeures du formulaire dans la session
    $_SESSION["login"] = $formData["login"];
    $_SESSION["password"] = $formData["password"];

    // si aucune erreurs n'est en tableau
    if (!$errors) {
        // instanciation des identifiants dans la session
        $_SESSION["login"] = $formData["login"];
        $_SESSION["password"] = $formData["password"];

        // redirection vers la page private
        header("Location: private.php");
    }
}

// rend les données vers la template
echo $twig->render('login.html.twig', [
    "errors" => $errors,
    "formData" => $formData,
]);
