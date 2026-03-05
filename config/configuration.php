<?php
define('USER', "root");
define('PASSWD', "");
define('SERVER', "localhost");
define('BASE', "webb");
function connect_bd() {
    $dsn = "mysql:host=".SERVER.";dbname=".BASE.";charset=utf8";
    try {
        $connexion = new PDO($dsn, USER, PASSWD);
        $connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $connexion;
    } catch (PDOException $e) {
        die("Erreur connexion : " . $e->getMessage());
    }
}
function e($string) {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}
?>