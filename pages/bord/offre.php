<!DOCTYPE html>
<html>
<head>
<?php
// Démarrez la session
session_start();

// Vérifiez si l'utilisateur est connecté
if (!isset($_SESSION['id'])) {
    // Redirigez l'utilisateur vers la page de connexion si la session n'est pas active
    header("Location: login.php");
    exit();
}

// Récupérez l'ID de l'utilisateur
$userId = $_SESSION['id'];

// Connexion à la base de données
$servername = "4w0vau.myd.infomaniak.com";
$username = "4w0vau_dreamize";
$password = "Pidou2016";
$dbname = "4w0vau_dreamize";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$successMessage = '';

// Vérifie si la requête est de type POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Vérifie si les clés du tableau $_POST sont définies
    if (isset($_POST['jobTitle'], $_POST['jobTitlee'], $_POST['jobTitleex'], $_POST['jobDescription'], $_POST['jobRequirements'], $_POST['stepStartDate'], $_POST['stepEndDate'], $_POST['stepTitle'])) {
        // Récupération des données du formulaire
        $jobTitle = $_POST['jobTitle'];
        $jobTitlee = $_POST['jobTitlee'];
        $jobTitleex = $_POST['jobTitleex'];
        $jobDescription = $_POST['jobDescription'];
        $jobRequirements = $_POST['jobRequirements'];
        $stepStartDates = $_POST['stepStartDate'];
        $stepEndDates = $_POST['stepEndDate'];
        $stepTitles = $_POST['stepTitle'];

        // Vérifie si les données ne sont pas vides
        if (!empty($jobTitle) && !empty($jobTitlee) && !empty($jobDescription) && !empty($jobRequirements) && count($stepStartDates) == $jobTitleex && count($stepEndDates) == $jobTitleex && count($stepTitles) == $jobTitleex) {
            // Préparation de la requête SQL d'insertion avec des paramètres
            $sql = "INSERT INTO offre (job_title, job_titlee, job_description, job_requirements, user_id, etapes) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);

            // Liaison des valeurs aux paramètres de la requête
            $stmt->bind_param("sssssi", $jobTitle, $jobTitlee, $jobDescription, $jobRequirements, $userId, $jobTitleex);

            // Exécution de la requête préparée
            if ($stmt->execute()) {
                // Récupération de l'ID de l'offre insérée
                $offerId = $stmt->insert_id;

                // Insertion des étapes dans une autre table
                $sqlStep = "INSERT INTO etapes (offer_id, start_date, end_date, title) VALUES (?, ?, ?, ?)";
                $stmtStep = $conn->prepare($sqlStep);
                
                foreach ($stepStartDates as $index => $startDate) {
                    $endDate = $stepEndDates[$index];
                    $title = $stepTitles[$index];
                    $stmtStep->bind_param("isss", $offerId, $startDate, $endDate, $title);
                    if (!$stmtStep->execute()) {
                        // Si une étape échoue à s'insérer, affiche un message d'erreur
                        echo "Erreur lors de l'insertion de l'étape: " . $stmtStep->error;
                        break;
                    }
                }

                // Si toutes les étapes sont insérées avec succès, définir le message de succès
                $successMessage = "Enregistrement OK";
            } else {
                echo "Erreur lors de l'insertion de l'offre: " . $stmt->error;
            }
        } else {
            echo "Veuillez remplir tous les champs du formulaire.";
        }
    } else {
        echo "Formulaire incomplet.";
    }
}

$conn->close();
?>


    <meta charset="UTF-8">
    <title>Enregistrer une offre d'emploi</title>
    <style type="text/css">
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        section {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #333;
            text-align: center;
            margin-bottom: 20px;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            font-weight: bold;
            color: #333;
            margin-bottom: 5px;
        }

        input[type="text"],
        input[type="number"],
        input[type="date"],
        textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        button[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        button[type="submit"]:hover {
            background-color: #45a049;
        }

        .step-fields {
            margin-bottom: 20px;
        }

        .step-fields label {
            font-weight: normal;
        }
    </style>
    <script type="text/javascript">
        function generateStepFields() {
            var stepContainer = document.getElementById('stepFields');
            stepContainer.innerHTML = ''; // Clear previous fields
            var numberOfSteps = document.getElementById('jobTitleex').value;
            
            for (var i = 1; i <= numberOfSteps; i++) {
                var stepDiv = document.createElement('div');
                stepDiv.className = 'step-fields';

                var periodLabel = document.createElement('label');
                periodLabel.innerHTML = 'Période de l\'étape ' + i + ' :';
                stepDiv.appendChild(periodLabel);
                stepDiv.appendChild(document.createElement('br'));

                var startDateLabel = document.createElement('label');
                startDateLabel.innerHTML = 'Date de début :';
                stepDiv.appendChild(startDateLabel);
                stepDiv.appendChild(document.createElement('br'));

                var startDateInput = document.createElement('input');
                startDateInput.type = 'date';
                startDateInput.name = 'stepStartDate[]';
                startDateInput.required = true;
                stepDiv.appendChild(startDateInput);
                stepDiv.appendChild(document.createElement('br'));

                var endDateLabel = document.createElement('label');
                endDateLabel.innerHTML = 'Date de fin :';
                stepDiv.appendChild(endDateLabel);
                stepDiv.appendChild(document.createElement('br'));

                var endDateInput = document.createElement('input');
                endDateInput.type = 'date';
                endDateInput.name = 'stepEndDate[]';
                endDateInput.required = true;
                stepDiv.appendChild(endDateInput);
                stepDiv.appendChild(document.createElement('br'));

                var titleLabel = document.createElement('label');
                titleLabel.innerHTML = 'Intitulé de l\'étape ' + i + ' :';
                stepDiv.appendChild(titleLabel);
                stepDiv.appendChild(document.createElement('br'));

                var titleInput = document.createElement('input');
                titleInput.type = 'text';
                titleInput.name = 'stepTitle[]';
                titleInput.required = true;
                stepDiv.appendChild(titleInput);
                stepDiv.appendChild(document.createElement('br'));

                stepContainer.appendChild(stepDiv);
            }
        }
    </script>
</head>
<body>
        <?php
    if (!empty($successMessage)) {
        echo "<p>$successMessage</p>";
    }
    ?>
<section>
    <!-- Formulaire pour enregistrer une offre d'emploi -->
    <h2>Enregistrer une offre d'emploi</h2>
    <form id="offerForm" method="post" action="">
        <label for="jobTitle">Titre de l'offre :</label>
        <input type="text" id="jobTitle" name="jobTitle" required="required">
        
        <label for="jobTitlee">Lieu de service :</label>
        <input type="text" id="jobTitlee" name="jobTitlee" required="required">
        
        <label for="jobTitleex">Nombre d'étapes :</label>
        <input type="number" id="jobTitleex" name="jobTitleex" required="required" onchange="generateStepFields()">
        
        <div id="stepFields"></div>
        
        <label for="jobDescription">Description de l'offre :</label>
        <textarea id="jobDescription" name="jobDescription" required="required"></textarea>
        
        <label for="jobRequirements">Exigences de l'offre :</label>
        <textarea id="jobRequirements" name="jobRequirements" required="required"></textarea>
        
        <button type="submit">Enregistrer</button>
    </form>
</section>
</body>
</html>
