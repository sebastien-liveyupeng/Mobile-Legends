<?php
session_start();
if (!isset($_SESSION['utilisateur'])) {
    header("Location: connexion.php");
    exit();
}

echo "Bienvenue, " . htmlspecialchars($_SESSION['utilisateur']) . " !";
echo "<br><a href='logout.php'>Se déconnecter</a>";
?>
