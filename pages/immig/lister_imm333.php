<?php
// Démarrer la session
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['id'])) {
    // Rediriger vers la page de connexion si la session n'est pas active
    header("Location: acceuil.php");
    exit();
}

// Récupérer l'ID de l'utilisateur depuis la session
$userId = $_SESSION['id'];

// Connexion à la base de données
$servername = "4w0vau.myd.infomaniak.com";
$username = "4w0vau_dreamize";
$password = "Pidou2016";
$dbname = "4w0vau_dreamize";

$conn = new mysqli($servername, $username, $password, $dbname);

// Vérifier la connexion
if ($conn->connect_error) {
    die("Échec de la connexion : " . $conn->connect_error);
}

// Fonction pour générer une chaîne aléatoire
function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

// Vérifier l'action POST
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
    $id = $_POST['id'];
    $action = $_POST['action'];

    if ($action == 'toggle') {
        if (isset($_POST['current_status'])) {
            $current_status = $_POST['current_status'];
            $new_status = $current_status == 1 ? 0 : 1;
            $sql = "UPDATE userss SET statut = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ii", $new_status, $id);
            if (!$stmt->execute()) {
                echo "Erreur lors de la mise à jour du statut : " . $conn->error;
            }
        } else {
            echo "Paramètres manquants pour l'action 'toggle'.";
        }
    } elseif ($action == 'delete') {
        try {
            // Commencer une transaction
            $conn->begin_transaction();

            // Supprimer dans la table `userss`
            $sql1 = "DELETE FROM userss WHERE id = ?";
            $stmt1 = $conn->prepare($sql1);
            $stmt1->bind_param("i", $id);
            $stmt1->execute();

            // Supprimer dans la table `formulaire_immigration_session1`
            $sql2 = "DELETE FROM formulaire_immigration_session1 WHERE user_id = ?";
            $stmt2 = $conn->prepare($sql2);
            $stmt2->bind_param("i", $id);
            $stmt2->execute();

            // Valider la transaction
            $conn->commit();
        } catch (Exception $e) {
            // Annuler la transaction en cas d'erreur
            $conn->rollback();
            echo "Erreur lors de la suppression du record : " . $e->getMessage();
        }
    } elseif ($action == 'generate_code') {
        if (isset($_POST['email'])) {
            $email = $_POST['email'];
            $code = generateRandomString(8); // Générer un code aléatoire de longueur 8
            $sql = "UPDATE userss SET help = 'validé', help2 = ? WHERE email = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ss", $code, $email);
            if (!$stmt->execute()) {
                echo "Erreur lors de la génération du code : " . $conn->error;
            }
        } else {
            echo "Paramètre 'email' manquant pour l'action 'generate_code'.";
        }
    } else {
        echo "Action non reconnue : " . htmlspecialchars($action);
    }
}

// Requête SQL pour sélectionner les données où photot n'est pas null
$sql = "SELECT ds.*, ds.full_name, ds.preno, ds.email, ds.codeutilisateur, ds.phone, ds.country, ds.city, dd.statut, dd.help2, ds.user_id FROM formulaire_immigration_session1 AS ds LEFT JOIN userss AS dd ON dd.id = ds.user_id WHERE ds.user_id = $userId AND ds.codeutilisateur IS NOT NULL";
$result = $conn->query($sql);

// Vérifier si des résultats ont été trouvés
if ($result && $result->num_rows > 0) {
    // Affichage des résultats dans un tableau HTML
    ?>
    <!DOCTYPE html>
    <html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Formulaire Immigration - Sessions</title>
        <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
        <style>
            .audio-player {
                display: flex;
                align-items: center;
            }
            .audio-player audio {
                margin-right: 10px;
            }
            .action-buttons button {
                margin-right: 5px;
            }
        </style>
    </head>
    <body class="container my-5">
        <h2 class="mb-4">Compétences Candidats</h2>
        <table class="table table-bordered table-hover">
            <thead class="thead-light">
                <tr>
                    <th>Nom Complet</th>
                    <th>Prenom</th>
                    <th>Pays</th>
                    <th>Ville</th>
                    <th>Email</th>
                    <th>Téléphone</th>
                    <th>Expérience</th>
                    <th>Photo</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['full_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['preno']); ?></td>
                        <td><?php echo htmlspecialchars($row['country']); ?></td>
                        <td><?php echo htmlspecialchars($row['city']); ?></td>
                        <td><a href="mailto:<?php echo htmlspecialchars($row['email']); ?>"><?php echo htmlspecialchars($row['email']); ?></a></td>
                        <td><?php echo htmlspecialchars($row['phone']); ?></td>
                        <td><?php echo htmlspecialchars($row['experience']); ?></td>
                        <td><img style="width: 60px; height: 60px;" src="uploads/<?php echo htmlspecialchars($row['photo']); ?>" alt="Photo"></td>
                        <td><?php echo htmlspecialchars($row['help2'] ?? ''); ?></td>
                        <td class="action-buttons">
                            <form action="" method="post" style="display:inline;">
                                <input type="hidden" name="id" value="<?php echo $row['user_id']; ?>">
                                <input type="hidden" name="codeutilisateur" value="<?php echo $row['codeutilisateur']; ?>">
                                <button class="btn btn-secondary" type="button" onclick="voirPlus('<?php echo $row['codeutilisateur']; ?>')">Voir plus</button>
                                <button class="btn btn-primary" type="button" onclick="modifierUtilisateur('<?php echo $row['codeutilisateur']; ?>')">Modifier</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <?php
        session_start();

        if (!isset($_SESSION['id'])) {
            header("Location: acceuil.php");
            exit();
        }

        $userId = $_SESSION['id'];

        $servername = "4w0vau.myd.infomaniak.com";
        $username = "4w0vau_dreamize";
        $password = "Pidou2016";
        $dbname = "4w0vau_dreamize";

        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $sql = "SELECT c.nom, c.prenom, c.email, c.sexe, c.pays, c.code, c.id, c.prof,
                d.diplome, d.passeport, d.certificat_naissance, d.certificat_scolarite, d.mandat_representation
                FROM candidats c
                LEFT JOIN documentss d ON c.code = d.code WHERE c.user_id = $userId";
        $result = $conn->query($sql);

        $conn->close();
        ?>

        <style>

            .table th {
                background-color: #343a40;
                color: white;
            }
  
        </style>

        <div style="width: 1900px;">
           
            <table style="width: 1900px;" class="table table-hover">
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Email</th>
                        <th>Sexe</th>
                        <th>Pays</th>
                        <th>Profession</th>
                        <th>Diplôme</th>
                        <th>Passeport</th>
                        <th>Certificat de Naissance</th>
                        <th>Certificat de Scolarité</th>
                        <th>Mandat de Représentation</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $row["nom"] . "</td>";
                            echo "<td>" . $row["prenom"] . "</td>";
                            echo "<td>" . $row["email"] . "</td>";
                            echo "<td>" . $row["sexe"] . "</td>";
                            echo "<td>" . $row["pays"] . "</td>";
                            echo "<td>" . $row["prof"] . "</td>";
                            echo "<td>" . (!empty($row["diplome"]) ? 'Présent' : 'Non Présent') . "</td>";
                            echo "<td>" . (!empty($row["passeport"]) ? 'Présent' : 'Non Présent') . "</td>";
                            echo "<td>" . (!empty($row["certificat_naissance"]) ? 'Présent' : 'Non Présent') . "</td>";
                            echo "<td>" . (!empty($row["certificat_scolarite"]) ? 'Présent' : 'Non Présent') . "</td>";
                            echo "<td>" . (!empty($row["mandat_representation"]) ? 'Présent' : 'Non Présent') . "</td>";
                            echo "<td class='action-buttons'>";
                            echo "<button class='btn btn-primary' onclick=\"voirPlus8('" . $row['code'] . "')\">Voir plus</button>";

                              echo "<button class='btn btn-primary' onclick=\"modifierCandidat('" . $row['code'] . "')\">Modifier</button>";
                            echo "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='12'>Aucun résultat trouvé</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>



        <script>
        function voirPlus(codeutilisateur) {
            // Ouvrir une nouvelle fenêtre avec les détails de l'utilisateur
            var url = 'voir_pluss.php?codeutilisateur=' + codeutilisateur;
            var windowName = 'DetailsUtilisateur';
            var windowFeatures = 'width=800,height=600,resizable=yes,scrollbars=yes';

            window.open(url, windowName, windowFeatures);
        }

        function modifierUtilisateur(codeutilisateur) {
            // Ouvrir une nouvelle fenêtre avec les détails de l'utilisateur
            var url = 'modif10.php?codeutilisateur=' + codeutilisateur;
            var windowName = 'DetailsUtilisateur';
            var windowFeatures = 'width=800,height=600,resizable=yes,scrollbars=yes';

            window.open(url, windowName, windowFeatures);
        }

            // Fonction pour voir plus de détails
            window.voirPlus8 = function(code) {
                var url = 'pages/bord/voir_voir_plus.php?code=' + encodeURIComponent(code);
                var newWindow = window.open(url, '_blank', 'width=800,height=600');
                newWindow.focus();
            };

            window.modifierCandidat = function(code) {
                var url = 'pages/bord/modif20.php?code=' + encodeURIComponent(code);
                var newWindow = window.open(url, '_blank', 'width=800,height=600');
                newWindow.focus();
            };
        </script>

        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    </body>
    </html>

    <?php

}

?>
