<?php
require_once 'vendor/autoload.php';

use Faker\Factory as Faker;

// Configuration de la base de données
$host = 'localhost';
$dbname = 'ecommerce'; // Nom de ta base de données
$user = 'root'; // Nom d'utilisateur de MySQL
$password = ''; // Mot de passe de MySQL

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Active les exceptions PDO
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

// Initialiser Faker
$faker = Faker::create();

// Fonction pour insérer des adresses
function insertAddresses($pdo, $faker, $n) {
    for ($i = 0; $i < $n; $i++) {
        try {
            $stmt = $pdo->prepare("INSERT INTO address (street, number, zip, country) VALUES (?, ?, ?, ?)");
            $stmt->execute([
                $faker->streetName,
                $faker->buildingNumber,
                $faker->postcode,
                $faker->country
            ]);
            echo $stmt->rowCount() . " ligne(s) insérée(s) dans address.<br>";
        } catch (PDOException $e) {
            echo "Erreur dans address : " . $e->getMessage() . "<br>";
        }
    }
}

// Fonction pour insérer des utilisateurs
function insertUsers($pdo, $faker, $n) {
    for ($i = 0; $i < $n; $i++) {
        try {
            $stmt = $pdo->prepare("INSERT INTO user (name, email, password, address_id) VALUES (?, ?, ?, ?)");
            $stmt->execute([
                $faker->name,
                $faker->email,
                password_hash($faker->password, PASSWORD_BCRYPT),
                rand(1, $n)
            ]);
            echo $stmt->rowCount() . " ligne(s) insérée(s) dans user.<br>";
        } catch (PDOException $e) {
            echo "Erreur dans user : " . $e->getMessage() . "<br>";
        }
    }
}

// Fonction pour insérer des avis
function insertReviews($pdo, $faker, $n) {
    for ($i = 0; $i < $n; $i++) {
        try {
            $stmt = $pdo->prepare("INSERT INTO rate (user_id, product_id, rating, comment) VALUES (?, ?, ?, ?)");
            $stmt->execute([
                rand(1, $n),
                rand(1, $n),
                rand(1, 5),
                $faker->sentence
            ]);
            echo $stmt->rowCount() . " ligne(s) insérée(s) dans rate.<br>";
        } catch (PDOException $e) {
            echo "Erreur dans rate : " . $e->getMessage() . "<br>";
        }
    }
}

// Fonction pour insérer des produits
function insertProducts($pdo, $faker, $n) {
    for ($i = 0; $i < $n; $i++) {
        try {
            $stmt = $pdo->prepare("INSERT INTO product (name, description, price) VALUES (?, ?, ?)");
            $stmt->execute([
                $faker->word,
                $faker->text,
                $faker->randomFloat(2, 5, 500)
            ]);
            echo $stmt->rowCount() . " ligne(s) insérée(s) dans product.<br>";
        } catch (PDOException $e) {
            echo "Erreur dans product : " . $e->getMessage() . "<br>";
        }
    }
}

// Fonction pour insérer des paiements
function insertPayments($pdo, $faker, $n) {
    for ($i = 0; $i < $n; $i++) {
        try {
            $stmt = $pdo->prepare("INSERT INTO payment (user_id, type, total, status, date) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([
                rand(1, $n),
                $faker->randomElement(['Credit Card', 'PayPal', 'Bank Transfer']),
                $faker->randomFloat(2, 20, 1000),
                $faker->randomElement(['Paid', 'Pending', 'Failed']),
                $faker->date
            ]);
            echo $stmt->rowCount() . " ligne(s) insérée(s) dans payment.<br>";
        } catch (PDOException $e) {
            echo "Erreur dans payment : " . $e->getMessage() . "<br>";
        }
    }
}

// Fonction pour insérer des paniers
function insertCarts($pdo, $n) {
    for ($i = 0; $i < $n; $i++) {
        try {
            $stmt = $pdo->prepare("INSERT INTO cart (user_id) VALUES (?)");
            $stmt->execute([
                rand(1, $n)
            ]);
            echo $stmt->rowCount() . " ligne(s) insérée(s) dans cart.<br>";
        } catch (PDOException $e) {
            echo "Erreur dans cart : " . $e->getMessage() . "<br>";
        }
    }
}

// Fonction pour insérer des éléments de panier
function insertCartItems($pdo, $n) {
    for ($i = 0; $i < $n; $i++) {
        try {
            $stmt = $pdo->prepare("INSERT INTO cart_item (cart_id, product_id, quantity) VALUES (?, ?, ?)");
            $stmt->execute([
                rand(1, $n),
                rand(1, $n),
                rand(1, 5)
            ]);
            echo $stmt->rowCount() . " ligne(s) insérée(s) dans cart_item.<br>";
        } catch (PDOException $e) {
            echo "Erreur dans cart_item : " . $e->getMessage() . "<br>";
        }
    }
}

// Fonction pour insérer des commandes
function insertOrders($pdo, $faker, $n) {
    for ($i = 0; $i < $n; $i++) {
        try {
            $stmt = $pdo->prepare("INSERT INTO command (user_id, cart_id, status) VALUES (?, ?, ?)");
            $stmt->execute([
                rand(1, $n),
                rand(1, $n),
                $faker->randomElement(['Pending', 'Shipped', 'Delivered'])
            ]);
            echo $stmt->rowCount() . " ligne(s) insérée(s) dans command.<br>";
        } catch (PDOException $e) {
            echo "Erreur dans command : " . $e->getMessage() . "<br>";
        }
    }
}

// Fonction pour insérer des factures
function insertInvoices($pdo, $faker, $n) {
    for ($i = 0; $i < $n; $i++) {
        try {
            $stmt = $pdo->prepare("INSERT INTO invoice (command_id, date, total) VALUES (?, ?, ?)");
            $stmt->execute([
                rand(1, $n),
                $faker->date,
                $faker->randomFloat(2, 20, 1000)
            ]);
            echo $stmt->rowCount() . " ligne(s) insérée(s) dans invoice.<br>";
        } catch (PDOException $e) {
            echo "Erreur dans invoice : " . $e->getMessage() . "<br>";
        }
    }
}

// Nombre de données à insérer par table
$n = 10;

// Exécution des fonctions
insertAddresses($pdo, $faker, $n);
insertUsers($pdo, $faker, $n);
insertProducts($pdo, $faker, $n);
insertPayments($pdo, $faker, $n);
insertCarts($pdo, $n);
insertCartItems($pdo, $n);
insertOrders($pdo, $faker, $n);
insertInvoices($pdo, $faker, $n);
insertReviews($pdo, $faker, $n);

echo "<p style='color: green;'>Données fictives insérées avec succès.</p>";
?>
