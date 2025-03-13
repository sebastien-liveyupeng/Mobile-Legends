<?php
session_start();
require_once __DIR__ . "/config.php";

// Génération d'un token CSRF pour sécuriser le formulaire
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("Erreur de sécurité (CSRF détecté)");
    }

    $pseudo = htmlspecialchars(trim($_POST['pseudo']));
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $motdepasse = $_POST['motdepasse'];

    // Vérifications des champs
    if (empty($pseudo) || empty($email) || empty($motdepasse)) {
        $message = "Tous les champs sont obligatoires.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Email invalide.";
    } elseif (strlen($motdepasse) < 6) {
        $message = "Le mot de passe doit contenir au moins 6 caractères.";
    } else {
        // Vérification si l'email existe déjà
        $stmt = $pdo->prepare("SELECT id FROM utilisateurs WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $message = "Cet email est déjà utilisé.";
        } else {
            // Hachage du mot de passe
            $motdepasseHash = password_hash($motdepasse, PASSWORD_DEFAULT);

            // Inscription
            $stmt = $pdo->prepare("INSERT INTO utilisateurs (pseudo, email, motdepasse) VALUES (?, ?, ?)");
            if ($stmt->execute([$pseudo, $email, $motdepasseHash])) {
                $message = "Inscription réussie ! <a href='connexion.php'>Connectez-vous</a>";
            } else {
                $message = "Erreur lors de l'inscription.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - CRUSIX</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
    background-color: #0a0f1e;
    color: #fff;
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
}

/* Conteneur des formulaires */
.form-container {
    width: 100%;
    max-width: 400px;
    margin: 50px auto;
    padding: 20px;
    background-color: #1e2a47;
    border-radius: 10px;
    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.5);
    text-align: center;
}

/* Titres */
.form-container h2 {
    color: #fff;
}

/* Champs du formulaire */
.form-container input {
    width: 100%;
    padding: 10px;
    margin: 10px 0;
    border: none;
    border-radius: 5px;
    font-size: 16px;
}

/* Bouton */
.form-container button {
    width: 100%;
    padding: 10px;
    background-color: #00509e;
    color: white;
    border: none;
    font-size: 16px;
    cursor: pointer;
    border-radius: 5px;
    transition: 0.3s;
}

.form-container button:hover {
    background-color: #003f7f;
}

/* Message d'erreur */
.message {
    color: #ff4c4c;
    background: #2a1e1e;
    padding: 10px;
    border-radius: 5px;
    margin-bottom: 10px;
}

/* Lien de connexion/inscription */
.form-container p {
    margin-top: 10px;
}

.form-container a {
    color: #00aaff;
    text-decoration: none;
}

.form-container a:hover {
    text-decoration: underline;
}

/* Responsive */
@media (max-width: 768px) {
    .form-container {
        width: 90%;
    }
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
<header>
        <h1>CRUSIX</h1>
        <nav>
            <ul>
               
                <li><a href="connexion.php" class="lang" data-fr="Contact" data-en="Contact">Connexion</a></li>
               
            </ul>
        </nav>
        <button class="translate-btn" onclick="toggleLanguage()">🇫🇷 / 🇬🇧</button>
    </header>
<div class="form-container">
    <h2>Inscription</h2>

    <?php if (!empty($message)) : ?>
        <p class="message"><?php echo $message; ?></p>
    <?php endif; ?>

    <form method="post">
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

        <label>Pseudo :</label>
        <input type="text" name="pseudo" required>
        
        <label>Email :</label>
        <input type="email" name="email" required>

        <label>Mot de passe :</label>
        <input type="password" name="motdepasse" required>

        <button type="submit">S'inscrire</button>
    </form>

    <p>Déjà un compte ? <a href="connexion.php">Connectez-vous</a></p>
</div>

</body>
</html>
