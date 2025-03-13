<?php
session_start();
require_once "config.php"; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $pseudo = htmlspecialchars(trim($_POST['pseudo']));
    $motdepasse = $_POST['motdepasse'];

    $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE pseudo = ?");
    $stmt->execute([$pseudo]);
    $user = $stmt->fetch();

    if ($user && password_verify($motdepasse, $user['motdepasse'])) {
        $_SESSION['utilisateur'] = $user['pseudo'];
        echo "<p class='success-msg'>Connexion réussie ! <a href='accueil.html'>Accéder à votre espace</a></p>";
    } else {
        echo "<p class='error-msg'>Pseudo ou mot de passe incorrect.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - CRUSIX</title>
    <style>
        body {
            background-color: #0a0f1e;
            color: white;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        .container {
            background-color: #1e2a47;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.5);
            width: 90%;
            max-width: 400px;
            text-align: center;
        }

        h2 {
            margin-bottom: 20px;
        }

        label {
            display: block;
            text-align: left;
            font-weight: bold;
            margin: 10px 0 5px;
        }

        input {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: none;
            border-radius: 5px;
            background-color: #121a2f;
            color: white;
        }

        button {
            background-color: #00509e;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            font-size: 16px;
            transition: 0.3s;
        }

        button:hover {
            background-color: #003f7f;
        }

        .error-msg {
            color: red;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .success-msg {
            color: green;
            font-weight: bold;
            margin-bottom: 10px;
        }

        a {
            color: #00aaff;
            text-decoration: none;
            display: block;
            margin-top: 10px;
        }

        a:hover {
            text-decoration: underline;
        }

        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px;
            background-color: #121a2f;
            flex-wrap: wrap;
        }
        nav ul {
            list-style: none;
            display: flex;
            gap: 20px;
            padding: 0;
        }
        nav a {
            color: #fff;
            text-decoration: none;
            padding: 10px 15px;
            border-radius: 5px;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }
        nav a:hover {
            background-color: #00509e;
            transform: scale(1.1);
        }
        .hero {
            text-align: center;
            padding: 50px;
            background: linear-gradient(to right, #001f3f, #00509e);
        }
        .team {
            text-align: center;
            padding: 50px;
        }
        @media (max-width: 768px) {
            header {
                flex-direction: column;
                align-items: center;
                text-align: center;
            }
            nav ul {
                flex-direction: column;
                text-align: center;
            }
            .hero {
                padding: 30px;
            }
           
        }
    </style>
</head>
<body>


<div class="container">
    <h2>Connexion</h2>

    <form method="post">
        <label>Pseudo :</label>
        <input type="text" name="pseudo" required>
        
        <label>Mot de passe :</label>
        <input type="password" name="motdepasse" required>

        <button type="submit">Se connecter</button>
    </form>

    <a href="index.php">Pas encore de compte ? Inscrivez-vous</a>
</div>

</body>
</html>
