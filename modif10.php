<?php
session_start();

// Vérifier si l'utilisateur est connecté
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
    die("Échec de la connexion : " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['codeutilisateur'])) {
    $codeutilisateur = $_GET['codeutilisateur'];

    $sql = "SELECT ds.*, ds.full_name, ds.preno, ds.email, ds.codeutilisateur, ds.phone, ds.country, ds.city, dd.statut, dd.help2, ds.user_id 
            FROM formulaire_immigration_session1 AS ds 
            LEFT JOIN userss AS dd ON dd.id = ds.user_id 
            WHERE ds.user_id = ? AND ds.codeutilisateur = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $userId, $codeutilisateur);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
    } else {
        echo "Utilisateur non trouvé.";
        exit();
    }

    // Récupérer les documents
    $sqlDocuments = "SELECT * FROM formulaire_immigration_session2 WHERE user_id = ? AND codeutilisateur = ?";
    $stmtDocuments = $conn->prepare($sqlDocuments);
    $stmtDocuments->bind_param("is", $userId, $codeutilisateur);
    $stmtDocuments->execute();
    $resultDocuments = $stmtDocuments->get_result();
    $documents = $resultDocuments->fetch_all(MYSQLI_ASSOC);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['codeutilisateur'])) {
    $codeutilisateur = $_POST['codeutilisateur'];
    $full_name = $_POST['full_name'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $country = $_POST['country'];
    $city = $_POST['city'];

    // Mettre à jour les informations de l'utilisateur
    $sql = "UPDATE formulaire_immigration_session1 SET full_name = ?, preno = ?, email = ?, phone = ?, country = ?, city = ? WHERE codeutilisateur = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssss", $full_name, $prenom, $email, $phone, $country, $city, $codeutilisateur);

    if ($stmt->execute()) {
        echo "Mise à jour réussie.";
    } else {
        echo "Erreur lors de la mise à jour : " . $conn->error;
    }

    // Gérer les fichiers de documents
    $target_dir = "uploads/";

    foreach ($_FILES['documents']['name'] as $key => $filename) {
        if ($filename) {
            $documentId = $_POST['document_ids'][$key];
            $target_file = $target_dir . basename($filename);

            if (move_uploaded_file($_FILES['documents']['tmp_name'][$key], $target_file)) {
                // Supprimer l'ancien fichier
                $sqlGetOldFile = "SELECT documents FROM formulaire_immigration_session2 WHERE id = ?";
                $stmtGetOldFile = $conn->prepare($sqlGetOldFile);
                $stmtGetOldFile->bind_param("i", $documentId);
                $stmtGetOldFile->execute();
                $resultGetOldFile = $stmtGetOldFile->get_result();
                $oldFile = $resultGetOldFile->fetch_assoc();

                $oldFilePath = $target_dir . $oldFile['documents'];
                if (file_exists($oldFilePath)) {
                    unlink($oldFilePath);
                }

                // Mettre à jour les documents dans la base de données
                $sqlUpdateDocument = "UPDATE formulaire_immigration_session2 SET documents = ?, datet = NOW() WHERE id = ?";
                $stmtUpdateDocument = $conn->prepare($sqlUpdateDocument);
                $stmtUpdateDocument->bind_param("si", $filename, $documentId);
                $stmtUpdateDocument->execute();
            }
        }
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier Utilisateur</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .form-group label {
            font-weight: bold;
        }
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }
        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }
        .file-container {
            margin-top: 20px;
        }
        .file-container table {
            width: 100%;
            border-collapse: collapse;
        }
        .file-container th, .file-container td {
            border: 1px solid #dee2e6;
            padding: 8px;
            text-align: left;
        }
        .file-container th {
            background-color: #f8f9fa;
        }
        .file-container input[type="file"] {
            display: none;
        }
    </style>
    <script>
        function replaceDocument(inputElement, fileNameContainer) {
            inputElement.click();
            inputElement.onchange = function() {
                updateFileNames(inputElement, fileNameContainer);
            };
        }

        function updateFileNames(inputElement, containerElement) {
            containerElement.innerHTML = ''; // Clear existing file names
            const files = inputElement.files;
            for (let i = 0; i < files.length; i++) {
                const fileName = files[i].name;
                const fileItem = document.createElement('div');
                fileItem.textContent = fileName;
                containerElement.appendChild(fileItem);
            }
        }
    </script>
</head>
<body class="container my-5">
    <h2 class="mb-4">Modifier Utilisateur</h2>
    <?php if (isset($user)): ?>
        <form method="post" action="" enctype="multipart/form-data">
            <input type="hidden" name="codeutilisateur" value="<?php echo htmlspecialchars($user['codeutilisateur']); ?>">
            <div class="form-group">
                <label>Nom Complet</label>
                <input type="text" name="full_name" class="form-control" value="<?php echo htmlspecialchars($user['full_name']); ?>" required>
            </div>
            <div class="form-group">
                <label>Prénom</label>
                <input type="text" name="prenom" class="form-control" value="<?php echo htmlspecialchars($user['preno']); ?>" required>
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($user['email']); ?>" required>
            </div>
            <div class="form-group">
                <label>Téléphone</label>
                <input type="text" name="phone" class="form-control" value="<?php echo htmlspecialchars($user['phone']); ?>" required>
            </div>
            <div class="form-group">
                <label>Pays</label>
                <input type="text" name="country" class="form-control" value="<?php echo htmlspecialchars($user['country']); ?>" required>
            </div>
            <div class="form-group">
                <label>Ville</label>
                <input type="text" name="city" class="form-control" value="<?php echo htmlspecialchars($user['city']); ?>" required>
            </div>
            
            <div class="file-container">
                <h5>Documents Existants</h5>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Nom du Document</th>
                            <th>Options</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($documents as $index => $document): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($document['documents']); ?></td>
                                <td>
                                    <input type="hidden" name="document_ids[]" value="<?php echo $document['id']; ?>">
                                    <label class="btn btn-sm btn-primary" onclick="replaceDocument(document.getElementById('file<?php echo $index; ?>'), document.getElementById('fileNameContainer<?php echo $index; ?>'))">
                                        Modifier
                                        <input type="file" id="file<?php echo $index; ?>" name="documents[]" />
                                    </label>
                                    <div id="fileNameContainer<?php echo $index; ?>"></div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
        </form>
    <?php else: ?>
        <p>Utilisateur non trouvé.</p>
    <?php endif; ?>
</body>
</html>
