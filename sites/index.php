<?php
// Démarrer la session
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['id'])) {
    // Rediriger vers la page de connexion si la session n'est pas active
    header("Location: ../acceuil.php");
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

// Vérifier la connexion à la base de données
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fonction pour générer un code unique
function generateUniqueCode($conn) {
    $code = bin2hex(random_bytes(4)); // Générer un code aléatoire de 8 caractères
    $sql = "SELECT COUNT(*) as count FROM referencestto WHERE code_unique = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $code);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    
    // Vérifier si le code est unique
    while ($row['count'] > 0) {
        $code = bin2hex(random_bytes(4)); // Générer un nouveau code si celui-ci existe déjà
        $stmt->bind_param("s", $code);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
    }

    return $code;
}

// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Générer un code unique pour cet enregistrement complet
    $code_unique = generateUniqueCode($conn);
    
    // Préparer la requête d'insertion avec des paramètres liés
    $sql = "INSERT INTO referencestto (nom_agent, numero_permis, agence, complete_par, region, ville, categorie, nom, contact, telephone, code_unique, user_id)
            VALUES ('1', '1', '1', '1', '1', '1', ?, ?, ?, ?, ?,  $userId)";
    
    // Préparer l'instruction SQL
    $stmt = $conn->prepare($sql);
    
    // Vérifier si la préparation a réussi
    if ($stmt === false) {
        echo "Erreur lors de la préparation de la requête : " . $conn->error;
    } else {

        // Préparer les tableaux pour les catégories
        $categories = [
            'Notaire', 'Courtiers', 'Inspecteurs', 'Évaluateurs', 'Arpenteurs géomètres',
            'Entrepreneur général', 'Assurances', 'Déménageur', 'Designer intérieur',
            'Armoires de cuisine', 'Électricien', 'Plomberie', 'Puits artésiens'
        ];
        
        // Préparer les tableaux pour les noms, contacts et téléphones correspondants
        $noms = [
            $_POST['notaire_nom'], $_POST['courtiers_nom'], $_POST['inspecteurs_nom'],
            $_POST['evaluateurs_nom'], $_POST['arpenteurs_nom'], $_POST['entrepreneur_nom'],
            $_POST['assurances_nom'], $_POST['demenageur_nom'], $_POST['designer_nom'],
            $_POST['armoires_nom'], $_POST['electricien_nom'], $_POST['plomberie_nom'],
            $_POST['puits_nom']
        ];
        
        $contacts = [
            $_POST['notaire_contact'], $_POST['courtiers_contact'], $_POST['inspecteurs_contact'],
            $_POST['evaluateurs_contact'], $_POST['arpenteurs_contact'], $_POST['entrepreneur_contact'],
            $_POST['assurances_contact'], $_POST['demenageur_contact'], $_POST['designer_contact'],
            $_POST['armoires_contact'], $_POST['electricien_contact'], $_POST['plomberie_contact'],
            $_POST['puits_contact']
        ];
        
        $telephones = [
            $_POST['notaire_telephone'], $_POST['courtiers_telephone'], $_POST['inspecteurs_telephone'],
            $_POST['evaluateurs_telephone'], $_POST['arpenteurs_telephone'], $_POST['entrepreneur_telephone'],
            $_POST['assurances_telephone'], $_POST['demenageur_telephone'], $_POST['designer_telephone'],
            $_POST['armoires_telephone'], $_POST['electricien_telephone'], $_POST['plomberie_telephone'],
            $_POST['puits_telephone']
        ];
        
        // Binder les paramètres et exécuter la requête pour chaque catégorie
        foreach ($categories as $index => $categorie) {
            $nom = $noms[$index];
            $contact = $contacts[$index];
            $telephone = $telephones[$index];
            
            // Binder les paramètres à l'instruction SQL
            $stmt->bind_param("sssss", $categorie, $nom, $contact, $telephone, $code_unique);
            
            // Exécuter la requête
            if ($stmt->execute() === false) {
                echo "Erreur lors de l'insertion : " . $stmt->error;
            }
        }
        
        // Fermer la déclaration préparée
        
    }
    
    // Fermer la connexion à la base de données
 
}
?>


<?php
// Démarrer la session
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['id'])) {
    // Rediriger vers la page de connexion si la session n'est pas active
    header("Location: ../acceuil.php");
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

// Vérifier la connexion à la base de données
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire en vérifiant leur existence
    $model = isset($_POST['model']) ? trim($_POST['model']) : '';
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';
    $country = isset($_POST['country']) ? trim($_POST['country']) : '';
    $city = isset($_POST['city']) ? trim($_POST['city']) : '';
    $quartier = isset($_POST['quartier']) ? trim($_POST['quartier']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $message = isset($_POST['message']) ? trim($_POST['message']) : '';
    $package = isset($_POST['package']) ? trim($_POST['package']) : '';

    // Vérifier si toutes les données sont présentes
    if (!empty($model) && !empty($name) && !empty($country) && !empty($city) && !empty($quartier) && !empty($email) && !empty($message) && !empty($package)) {
        // Préparer la requête SQL
        $stmt = $conn->prepare("INSERT INTO orders (model, name, country, city, quartier, email, message, package, status, user_id, code_unique) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'en attente', ?, ?)");
        
        if ($stmt === false) {
            die("Erreur lors de la préparation de la requête : " . $conn->error);
        }

        $stmt->bind_param("ssssssssis", $model, $name, $country, $city, $quartier, $email, $message, $package, $userId, $code_unique);

        if ($stmt->execute()) {
            echo "Nouvelle commande enregistrée avec succès";
        } else {
            echo "Erreur: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Veuillez remplir tous les champs.";
    }
}


?>












<?php
// Connexion à la base de données
$servername = "4w0vau.myd.infomaniak.com";
$username = "4w0vau_dreamize";
$password = "Pidou2016";
$dbname = "4w0vau_dreamize";

$conn = new mysqli($servername, $username, $password, $dbname);

// Vérifier la connexion à la base de données
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les valeurs des champs du formulaire
    $headerFormat = isset($_POST['headerFormat']) ? $_POST['headerFormat'] : '';
    $visualApproach = isset($_POST['visualApproach']) ? $_POST['visualApproach'] : '';
    $tone = isset($_POST['tone']) ? $_POST['tone'] : '';
    $propertiesDisplay = isset($_POST['propertiesDisplay']) ? $_POST['propertiesDisplay'] : '';
    $miniPub = isset($_POST['miniPub']) ? $_POST['miniPub'] : '';
    $logos = isset($_POST['logos']) ? $_POST['logos'] : '';
    $referenceList = isset($_POST['referenceList']) ? $_POST['referenceList'] : '';
    $advertisers = isset($_POST['advertisers']) ? $_POST['advertisers'] : '';
    $tools = isset($_POST['tools']) ? $_POST['tools'] : '';
    $boost = isset($_POST['boost']) ? $_POST['boost'] : '';
    $showAddress = isset($_POST['showAddress']) ? $_POST['showAddress'] : '';
    $showContacts = isset($_POST['showContacts']) ? $_POST['showContacts'] : '';
    $showHours = isset($_POST['showHours']) ? $_POST['showHours'] : '';  
    $annonce_info = isset($_POST['annonce_info']) ? $_POST['annonce_info'] : '';

    $homepageDisplay = isset($_POST['homepageDisplay']) ? $_POST['homepageDisplay'] : '';
    $weeklyDisplay = isset($_POST['weeklyDisplay']) ? $_POST['weeklyDisplay'] : '';  
    $document = isset($_POST['document']) ? $_POST['document'] : '';

    // Préparer et exécuter la requête SQL pour insérer les données
    $sql = "INSERT INTO formulaire_approche_visuelle (headerFormat, visualApproach, tone, propertiesDisplay, miniPub, logos, referenceList, advertisers, tools, boost, showAddress, showContacts, showHours, annonce_info, homepageDisplay, weeklyDisplay, document, user_id, code_unique)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, $userId, ?)";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssssssssssssss", $headerFormat, $visualApproach, $tone, $propertiesDisplay, $miniPub, $logos, $referenceList, $advertisers, $tools, $boost, $showAddress, $showContacts, $showHours, $annonce_info, $homepageDisplay, $weeklyDisplay, $document, $code_unique);

    if ($stmt->execute()) {
        echo "Les données ont été enregistrées avec succès.";
    } else {
        echo "Erreur : " . $stmt->error;
    }

    // Fermer la connexion
    $stmt->close();
    $conn->close();
} else {
    echo "Aucune donnée reçue.";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Fables">
    <meta name="author" content="Enterprise Development">
    <link rel="shortcut icon" href="assets/custom/images/shortcut.png">

    <title>Immonivo</title>
    
    <!-- animate.css-->  
    <link href="assets/vendor/animate.css-master/animate.min.css" rel="stylesheet">
    <!-- Load Screen -->
    <link href="assets/vendor/loadscreen/css/spinkit.css" rel="stylesheet">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- GOOGLE FONT -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i,800,800i" rel="stylesheet">
    <!-- Font Awesome 5 -->
    <link href="assets/vendor/fontawesome/css/fontawesome-all.min.css" rel="stylesheet">
    <!-- Fables Icons -->
    <link href="assets/custom/css/fables-icons.css" rel="stylesheet"> 
    <!-- Bootstrap CSS -->
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet"> 
    <!-- FANCY BOX -->
    <link href="assets/vendor/fancybox-master/jquery.fancybox.min.css" rel="stylesheet">
    <!-- OWL CAROUSEL  -->
    <link href="assets/vendor/owlcarousel/owl.carousel.min.css" rel="stylesheet">
    <link href="assets/vendor/owlcarousel/owl.theme.default.min.css" rel="stylesheet">
    <!-- Timeline -->
    <link rel="stylesheet" href="assets/vendor/timeline/timeline.css"> 
    <!-- FABLES CUSTOM CSS FILE -->
    <link href="assets/custom/css/custom.css" rel="stylesheet">
    <!-- FABLES CUSTOM CSS RESPONSIVE FILE -->
    <link href="assets/custom/css/custom-responsive.css" rel="stylesheet"> 
     
</head>


<body>

    
<!-- Loading Screen -->
    <div id="ju-loading-screen">
      <div class="sk-double-bounce">
        <div class="sk-child sk-double-bounce1"></div>
        <div class="sk-child sk-double-bounce2"></div>
      </div>
    </div>

     
<!-- Start Header -->
<div class="fables-header fables-after-overlay">
    <div class="container"> 
         <h2 class="fables-page-title fables-second-border-color">Les différents modèles des sites Immonivo</h2>
    </div>
</div>  
<!-- /End Header -->
     
<!-- Start Breadcrumbs -->
<div class="fables-light-background-color">
    <div class="container"> 
        <nav aria-label="breadcrumb">
          <ol class="fables-breadcrumb breadcrumb px-0 py-3">
            <li class="breadcrumb-item active" aria-current="page">Nos modèles</li>
          </ol>
        </nav> 
    </div>
</div>
<!-- /End Breadcrumbs -->
     
<!-- Start page content -->   

  
<div class="container">
    <div class="row my-5">
        <div class="col-12 col-sm-6 mb-5">
            <div class="image-container zoomIn-effect">
                <a href="https://allinone.izishope.com/immo1/index.html" target="_blank"><img src="assets/custom/demo/11.PNG" alt="" class="img-fluid"></a>
            </div>
            <h2 class="my-3"><a href="https://allinone.izishope.com/immo1/index.html" class="fables-main-text-color fables-second-hover-color font-26 semi-font" target="_blank">Modèle 1</a></h2>
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#orderModal" data-model="Modèle 1">Commander</button>
        </div>
        <div class="col-12 col-sm-6 mb-5">
            <div class="image-container zoomIn-effect">
                <a href="https://izishope.com/index.php" target="_blank"><img src="assets/custom/demo/immo55.PNG" alt="" class="img-fluid"></a>
            </div>
            <h2 class="my-3"><a href="https://izishope.com/index.php" class="fables-main-text-color fables-second-hover-color font-26 semi-font" target="_blank">Modèle 2</a></h2>
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#orderModal" data-model="Modèle 2">Commander</button>
        </div>
        <div class="col-12 col-sm-6 mb-5">
            <div class="image-container zoomIn-effect">
                <a href="https://admin.izishope.com/pub2.php" target="_blank"><img src="assets/custom/demo/ggg.PNG" alt="" class="img-fluid"></a>
            </div>
            <h2 class="my-3"><a href="https://admin.izishope.com/pub2.php" class="fables-main-text-color fables-second-hover-color font-26 semi-font" target="_blank">Modèle 3</a></h2>
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#orderModal" data-model="Modèle 3">Commander</button>
        </div>
        <div class="col-12 col-sm-6 mb-5">
            <div class="image-container zoomIn-effect">
                <a href="https://izishope.com/index2.php" target="_blank"><img src="assets/custom/demo/vvv.PNG" alt="" class="img-fluid"></a>
            </div>
            <h2 class="my-3"><a href="https://izishope.com/index2.php" class="fables-main-text-color fables-second-hover-color font-26 semi-font" target="_blank">Modèle 4</a></h2>
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#orderModal" data-model="Modèle 4">Commander</button>
        </div>

        <div class="col-12 col-sm-6 mb-5">
            <div class="image-container zoomIn-effect">
                <a href="https://izishope.com/index3.php" target="_blank"><img src="assets/custom/demo/imm6.PNG" alt="" class="img-fluid"></a>
            </div>
            <h2 class="my-3"><a href="https://izishope.com/index3.php" class="fables-main-text-color fables-second-hover-color font-26 semi-font" target="_blank">Modèle 5</a></h2>
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#orderModal" data-model="Modèle 5">Commander</button>
        </div>

        <div class="col-12 col-sm-6 mb-5">
            <div class="image-container zoomIn-effect">
                <a href="https://izishope.com/immo2/index.html" target="_blank"><img src="assets/custom/demo/model66.PNG" alt="" class="img-fluid"></a>
            </div>
            <h2 class="my-3"><a href="https://izishope.com/immo2/index.html" class="fables-main-text-color fables-second-hover-color font-26 semi-font" target="_blank">Modèle 6</a></h2>
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#orderModal" data-model="Modèle 6">Commander</button>
        </div>


            <div class="col-12 col-sm-6 mb-5">
            <div class="image-container zoomIn-effect">
                <a href="https://izishope.com/startup/index.html" target="_blank"><img src="assets/custom/demo/startup.PNG" alt="" class="img-fluid"></a>
            </div>
            <h2 class="my-3"><a href="https://izishope.com/startup/index.html" class="fables-main-text-color fables-second-hover-color font-26 semi-font" target="_blank">Modèle 7</a></h2>
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#orderModal" data-model="Modèle 7">Commander</button>
        </div>

                <div class="col-12 col-sm-6 mb-5">
            <div class="image-container zoomIn-effect">
                <a href="https://izishope.com/Construction/index.html" target="_blank"><img src="assets/custom/demo/construction.PNG" alt="" class="img-fluid"></a>
            </div>
            <h2 class="my-3"><a href="https://izishope.com/Construction/index.html" class="fables-main-text-color fables-second-hover-color font-26 semi-font" target="_blank">Modèle 8</a></h2>
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#orderModal" data-model="Modèle 8">Commander</button>
        </div>
    </div>
</div>

<!-- Modal -->
<div  class="modal fade" id="orderModal" tabindex="-1" role="dialog" aria-labelledby="orderModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div style="width: 250%; margin-left: -80%;" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="orderModalLabel">Commander <span id="model-name"></span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>




<div  class="modal-body">
    <form method="post" action="">



















 <style>
        body {
            font-family: Arial, sans-serif;
        }
        .tabs {
            display: flex;
            justify-content: space-around;
            background-color: #f1f1f1;
            padding: 10px;
            margin-bottom: 20px;
        }
        .tabs button {
            background-color: #ddd;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
        }
        .tabs button.active {
            background-color: rgb(0,0,64);
        }
        .section {
            display: none;
        }
        .section.active {
            display: block;
        }
        .navigation {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="tabs">
            <button style="color: black;" class="btn btn-secondary tab" onclick="showSection(0)">Informations générales</button>
            <button style="color: black;" class="btn btn-secondary tab" onclick="showSection(1)">Formulaire Principal</button>
            <button style="color: black;" class="btn btn-secondary tab" onclick="showSection(2)">Package</button>
            <button style="color: black;" class="btn btn-secondary tab" onclick="showSection(3)">Références</button>
        </div>
        <form action="" method="post">
            <div class="section active" id="section-0">
        <div class="form-group">
            <label for="model">Modèle</label>
            <input type="text" class="form-control" id="model" name="model" readonly>
        </div>
        <div class="form-group">
            <label for="name">Nom</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>
        <div class="form-group">
            <label for="country">Pays</label>
            <input type="text" class="form-control" id="country" name="country" required>
        </div>
        <div class="form-group">
            <label for="city">Ville</label>
            <input type="text" class="form-control" id="city" name="city" required>
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>

        <div class="form-group">
            <label for="quartier">Quartier</label>
            <input type="text" class="form-control" id="quartier" name="quartier" required>
        </div>

        <div class="form-group">
            <label for="message">Message</label>
            <textarea type="text" class="form-control" id="message" name="message" required>

        </textarea> 
        </div>


                <div class="navigation">
                    <button type="button" class="btn btn-primary" onclick="showSection(1)">Suivant</button>
                </div>
            </div>
            <div class="section" id="section-1">
                <h2>Formulaire Principal</h2>
                
                <h3>Format d’en-tête</h3>
                <div class="form-group">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="headerFormat" id="video" value="Vidéo">
                        <label class="form-check-label" for="video">Vidéo</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="headerFormat" id="carrousel" value="Carrousel">
                        <label class="form-check-label" for="carrousel">Carrousel</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="headerFormat" id="image" value="Image">
                        <label class="form-check-label" for="image">Image</label>
                    </div>
                </div>

                <h3>L’approche visuelle de votre site</h3>
                <div class="form-group">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="visualApproach" id="fun" value="Amusant / bande dessiné">
                        <label class="form-check-label" for="fun">Amusant / bande dessiné</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="visualApproach" id="bold" value="Audacieux et coloré">
                        <label class="form-check-label" for="bold">Audacieux et coloré</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="visualApproach" id="prestigious" value="Prestigieux">
                        <label class="form-check-label" for="prestigious">Prestigieux</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="visualApproach" id="classic" value="Classique">
                        <label class="form-check-label" for="classic">Classique</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="visualApproach" id="modern" value="Moderne & Simple">
                        <label class="form-check-label" for="modern">Moderne & Simple</label>
                    </div>
                </div>

                <h3>Le ton</h3>
                <div class="form-group">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="tone" id="realEstate" value="Immobilier">
                        <label class="form-check-label" for="realEstate">Immobilier</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="tone" id="furniture" value="Ameublement">
                        <label class="form-check-label" for="furniture">Ameublement</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="tone" id="family" value="Familiale">
                        <label class="form-check-label" for="family">Familiale</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="tone" id="urbanCity" value="Ville urbaine">
                        <label class="form-check-label" for="urbanCity">Ville urbaine</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="tone" id="nature" value="Nature et paysages">
                        <label class="form-check-label" for="nature">Nature et paysages</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="tone" id="other" value="Autre">
                        <label class="form-check-label" for="other">Autre</label>
                    </div>
                </div>

                <h3>Vous aimerez afficher sur votre site les propriétés de:</h3>
                <div class="form-group">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="propertiesDisplay" id="yourAgency" value="Votre agence">
                        <label class="form-check-label" for="yourAgency">Votre agence</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="propertiesDisplay" id="yourBanner" value="Votre bannière">
                        <label class="form-check-label" for="yourBanner">Votre bannière</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="propertiesDisplay" id="yourTeam" value="Votre équipe">
                        <label class="form-check-label" for="yourTeam">Votre équipe</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="propertiesDisplay" id="yourRegion" value="Votre région">
                        <label class="form-check-label" for="yourRegion">Votre région</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="propertiesDisplay" id="centris" value="Centris">
                        <label class="form-check-label" for="centris">Centris</label>
                    </div>
                </div>

                <h3>Mini pub</h3>
                <div class="form-group">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="miniPub" id="promo" value="Promotionnelle">
                        <label class="form-check-label" for="promo">Promotionnelle</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="miniPub" id="static" value="Statique">
                        <label class="form-check-label" for="static">Statique</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="miniPub" id="redirect" value="Avec redirection">
                        <label class="form-check-label" for="redirect">Avec redirection</label>
                    </div>
                </div>

                <h3>Logos</h3>
                <div class="form-group">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="logos" id="logoRedirect" value="Redirection">
                        <label class="form-check-label" for="logoRedirect">Redirection</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="logos" id="staticLogo" value="Statique, bande passante">
                        <label class="form-check-label" for="staticLogo">Statique, bande passante</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="logos" id="featured" value="En vedette">
                        <label class="form-check-label" for="featured">En vedette</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="logos" id="bannerRedirect" value="Bande passante avec redirection">
                        <label class="form-check-label" for="bannerRedirect">Bande passante avec redirection</label>
                    </div>
                </div>

                <h3>Vous voulez affichez les références</h3>
                <div class="form-group">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="referenceList" id="networkOnly" value="Votre réseau seulement">
                        <label class="form-check-label" for="networkOnly">Votre réseau seulement</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="referenceList" id="servedRegions" value="Vos régions desservies">
                        <label class="form-check-label" for="servedRegions">Vos régions desservies</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="referenceList" id="available" value="Ceux disponibles">
                        <label class="form-check-label" for="available">Ceux disponibles</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="referenceList" id="noMatter" value="Peu importe">
                        <label class="form-check-label" for="noMatter">Peu importe</label>
                    </div>
                </div>


                <h3>Les publicitaires</h3>
                <div class="form-group">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="advertisers" id="ecosystem" value="De votre écosystème">
                        <label class="form-check-label" for="ecosystem">De votre écosystème</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="advertisers" id="activityDomain" value="De votre domaine d’activité">
                        <label class="form-check-label" for="activityDomain">De votre domaine d’activité</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="advertisers" id="region" value="De votre région">
                        <label class="form-check-label" for="region">De votre région</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="advertisers" id="noMatterAdvertisers" value="Peu importe">
                        <label class="form-check-label" for="noMatterAdvertisers">Peu importe</label>
                    </div>
                </div>

                <h3>Outils</h3>
                <div class="form-group">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="tools" id="calendar" value="Intégration du calendrier">
                        <label class="form-check-label" for="calendar">Intégration du calendrier</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="tools" id="leads" value="Gestion des leads">
                        <label class="form-check-label" for="leads">Gestion des leads</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="tools" id="clientLoyalty" value="Fidélisation client">
                        <label class="form-check-label" for="clientLoyalty">Fidélisation client</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="tools" id="clientFollowUp" value="Relance client suivi client">
                        <label class="form-check-label" for="clientFollowUp">Relance client suivi client</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="tools" id="monthlyReport" value="Rapport mensuel">
                        <label class="form-check-label" for="monthlyReport">Rapport mensuel</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="tools" id="performanceReport" value="Rapport de performance">
                        <label class="form-check-label" for="performanceReport">Rapport de performance</label>
                    </div>
                </div>

                <h3>Coup de pouce</h3>
                <div class="form-group">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="boost" id="socialNetworks" value="Réseaux sociaux (FB-IG-YT-Tiktok-LinkedIn et autres)">
                        <label class="form-check-label" for="socialNetworks">Réseaux sociaux (FB-IG-YT-Tiktok-LinkedIn et autres)</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="boost" id="siteAutomation" value="Automatisation sur site internet, réseau sociaux (chatbot)">
                        <label class="form-check-label" for="siteAutomation">Automatisation sur site internet, réseau sociaux (chatbot)</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="boost" id="qaMessenger" value="Question / Réponse automatique / Messenger">
                        <label class="form-check-label" for="qaMessenger">Question / Réponse automatique / Messenger</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="boost" id="multiplatformCampaign" value="Campagne multiplateforme">
                        <label class="form-check-label" for="multiplatformCampaign">Campagne multiplateforme</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="boost" id="socialMediaManagement" value="Gestion des médias sociaux">
                        <label class="form-check-label" for="socialMediaManagement">Gestion des médias sociaux</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="boost" id="monitoringManagement" value="Gestion de veille">
                        <label class="form-check-label" for="monitoringManagement">Gestion de veille</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="boost" id="communityDevelopment" value="Développement communauté">
                        <label class="form-check-label" for="communityDevelopment">Développement communauté</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="boost" id="communityManagement" value="Gestion de communauté">
                        <label class="form-check-label" for="communityManagement">Gestion de communauté</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="boost" id="appointmentScheduling" value="Planification des Rendez-vous en ligne">
                        <label class="form-check-label" for="appointmentScheduling">Planification des Rendez-vous en ligne</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="boost" id="surveyForm" value="Formulaire de sondage">
                        <label class="form-check-label" for="surveyForm">Formulaire de sondage</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="boost" id="seoEvaluation" value="Indicatif et évaluation de performance (SEO)">
                        <label class="form-check-label" for="seoEvaluation">Indicatif et évaluation de performance (SEO)</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="boost" id="geolocation" value="Géolocalisation">
                        <label class="form-check-label" for="geolocation">Géolocalisation</label>
                    </div>
                </div>

                <h3>Voulez-vous afficher votre adresse?</h3>
                <div class="form-group">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="showAddress" id="yesAddress" value="Oui">
                        <label class="form-check-label" for="yesAddress">Oui</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="showAddress" id="noAddress" value="Non">
                        <label class="form-check-label" for="noAddress">Non</label>
                    </div>
                </div>

                <h3>Voulez-vous afficher les contacts?</h3>
                <div class="form-group">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="showContacts" id="yesContacts" value="Oui">
                        <label class="form-check-label" for="yesContacts">Oui</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="showContacts" id="noContacts" value="Non">
                        <label class="form-check-label" for="noContacts">Non</label>
                    </div>
                </div>

                <h3>Voulez-vous afficher vos heures d'ouverture?</h3>
                <div class="form-group">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="showHours" id="yesHours" value="Oui">
                        <label class="form-check-label" for="yesHours">Oui</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="showHours" id="noHours" value="Non">
                        <label class="form-check-label" for="noHours">Non</label>
                    </div>
                </div>

                <h3>Quelles informations voulez-vous afficher dans votre annonce?</h3>
                <div class="form-group">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="annonce_info" id="name" value="Nom">
                        <label class="form-check-label" for="name">Nom</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="annonce_info" id="category" value="Catégorie">
                        <label class="form-check-label" for="category">Catégorie</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="annonce_info" id="service" value="Service">
                        <label class="form-check-label" for="service">Service</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="annonce_info" id="description" value="Description">
                        <label class="form-check-label" for="description">Description</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="annonce_info" id="logoDisplay" value="Logo">
                        <label class="form-check-label" for="logoDisplay">Logo</label>
                    </div>
                </div>

                <h3>Voulez-vous que votre annonce soit affichée sur la page d'accueil?</h3>
                <div class="form-group">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="homepageDisplay" id="yesHomepage" value="Oui">
                        <label class="form-check-label" for="yesHomepage">Oui</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="homepageDisplay" id="noHomepage" value="Non">
                        <label class="form-check-label" for="noHomepage">Non</label>
                    </div>
                </div>

                <h3>Combien de fois voulez-vous que votre annonce soit affichée par semaine?</h3>
                <div class="form-group">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="weeklyDisplay" id="once" value="Une fois">
                        <label class="form-check-label" for="once">Une fois</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="weeklyDisplay" id="twice" value="Deux fois">
                        <label class="form-check-label" for="twice">Deux fois</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="weeklyDisplay" id="thrice" value="Trois fois">
                        <label class="form-check-label" for="thrice">Trois fois</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="weeklyDisplay" id="moreThanThrice" value="Plus de trois fois">
                        <label class="form-check-label" for="moreThanThrice">Plus de trois fois</label>
                    </div>
                </div>
            </section>

            <section id="section-33" class="form-section">
                <h2>Section 3: Documents</h2>
                <div class="form-group">
                    <label for="document">Télécharger le document:</label>
                    <input type="file" class="form-control-file" id="document" name="document">
                </div>
                <div class="form-group">
                    <label for="documentType">Type de document:</label>
                    <select class="form-control" id="documentType" name="documentType">
                        <option value="pièce d'identité">Pièce d'identité</option>
                        <option value="diplôme">Diplôme</option>
                        <option value="certificat">Certificat</option>
                        <option value="justificatif de domicile">Justificatif de domicile</option>
                    </select>
                </div>
            </section>


                <div class="navigation">
                    <button type="button" class="btn btn-secondary" onclick="showSection(0)">Précédent</button>
                    <button type="button" class="btn btn-primary" onclick="showSection(2)">Suivant</button>
                </div>
            </div>
            <div class="section" id="section-2">

                
        <!-- Packages Section -->
        <div class="form-group">
            <label for="package">Package</label>
            <select class="form-control" id="package" name="package" required>
                <option value="debutan">Débutant</option>
                <option value="simple">Simple</option>
                <option value="prenium">Premium</option>
                <option value="pro">Pro</option>
            </select>
        </div>
        
        <div id="package-description" class="form-group" style="display:none;">
            <label>Description du package</label>
            <div class="package-table">
                <table>
                    <thead>
                        <tr>
                            <th style="border: none; background-color: transparent;"></th>
                            <th    style="background-color: red; color: white;" class="debutant">Débutant</th>
                            <th    style="background-color: blue; color: white;" class="simple">Simple</th>
                            <th    style="background-color: green; color: white;" class="premium">Premium</th>
                            <th    style="background-color: rgb(0,0,64); color: white;" class="pro">Pro</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th style="border: none; background-color: transparent;"></th>
                            <td class="debutant">GRATUIT</td>
                            <td class="simple">À PARTIR DE 995.95$</td>
                            <td class="premium">À PARTIR DE 1495.95$</td>
                            <td class="pro">À PARTIR DE 1995.95$</td>
                        </tr>
                        <tr>
                            <td style="color: black;">Nombre de pages</td>
                            <td class="debutant"> 1</td>
                            <td class="simple"> 3</td>
                            <td class="premium"> 5</td>
                            <td class="pro"> 7</td>
                        </tr>
                        <tr>
                            <td style="color: black;">Page d'accueil</td>
                            <td class="debutant">Une image</td>
                            <td class="simple">Une image</td>
                            <td class="premium">3 images</td>
                            <td class="pro">Une vidéo</td>
                        </tr>

                        <tr>
                            <td style="color: black;">Email professionnelle</td>
                            <td class="debutant"> 1</td>
                            <td class="simple"> 3</td>
                            <td class="premium"> 5</td>
                            <td class="pro"> 7</td>
                        </tr>

                        <tr>
                            <td style="color: black;">Ajout du logo et liens vers compte réseaux sociaux</td>

                            <td class="debutant"><img style="width: 7%; height: 7%;" src="juste1.jpg"></td>
                            <td class="simple"><img style="width: 7%; height: 7%;" src="juste1.jpg"></td>
                            <td class="premium"><img style="width: 7%; height: 7%;" src="juste1.jpg"></td>
                            <td class="pro"><img style="width: 7%; height: 7%;" src="juste1.jpg"></td>
                        </tr>

                        <tr>
                            <td style="color: black;">Optimisation et référencement dans les moteur de recherche (Google,Bing)</td>

                            <td class="debutant"><img style="width: 7%; height: 7%;" src="juste1.jpg"></td>
                            <td class="simple"><img style="width: 7%; height: 7%;" src="juste1.jpg"></td>
                            <td class="premium"><img style="width: 7%; height: 7%;" src="juste1.jpg"></td>
                            <td class="pro"><img style="width: 7%; height: 7%;" src="juste1.jpg"></td>
                        </tr>

                        <tr>
                            <td style="color: black;">Tableau de bord administrateur avec les statistiques utilisateurs</td>
                            <td class="debutant"><img style="width: 7%; height: 7%;" src="juste1.jpg"></td>
                            <td class="simple"><img style="width: 7%; height: 7%;" src="juste1.jpg"></td>
                            <td class="premium"><img style="width: 7%; height: 7%;" src="juste1.jpg"></td>
                            <td class="pro"><img style="width: 7%; height: 7%;" src="juste1.jpg"></td>
                        </tr>

                        <tr>
                            <td style="color: black;">Hébergement et nom de domaine offert pendant un an</td>
                            <td class="debutant"><img style="width: 7%; height: 7%;" src="juste1.jpg"></td>
                            <td class="simple"><img style="width: 7%; height: 7%;" src="juste1.jpg"></td>
                            <td class="premium"><img style="width: 7%; height: 7%;" src="juste1.jpg"></td>
                            <td class="pro"><img style="width: 7%; height: 7%;" src="juste1.jpg"></td>
                        </tr>

                        <tr>
                            <td style="color: black;">Site web personnalisable et modifiable (Couleurs, images, dispositions)</td>
                            <td class="debutant"><img style="width: 7%; height: 7%;" src="juste1.jpg"></td>
                            <td class="simple"><img style="width: 7%; height: 7%;" src="juste1.jpg"></td>
                            <td class="premium"><img style="width: 7%; height: 7%;" src="juste1.jpg"></td>
                            <td class="pro"><img style="width: 7%; height: 7%;" src="juste1.jpg"></td>
                        </tr>

                        <tr>
                            <td style="color: black;">Statistique des consultations des propriétés</td>
                            <td class="debutant"><img style="width: 7%; height: 7%;" src="juste1.jpg"></td>
                            <td class="simple"><img style="width: 7%; height: 7%;" src="juste1.jpg"></td>
                            <td class="premium"><img style="width: 7%; height: 7%;" src="juste1.jpg"></td>
                            <td class="pro"><img style="width: 7%; height: 7%;" src="juste1.jpg"></td>
                        </tr>
                        <tr>
                            <td style="color: black;"><strong> Espace publicitaire </td>
                            <td class="debutant"><em style="color: black;"> Sans Primes</em> </td>
                            <td class="simple"> Avec Primes</td>
                            <td class="premium"> Avec Primes</td>
                            <td class="pro"> Avec Primes</td>
                        </tr>



                        <tr>
                            <td style="color: black;">Création des comptes utilisateurs</td>
                            <td class="debutant"><img style="width: 7%; height: 7%;" src="faux.jpg"></td>
                            <td class="simple"><img style="width: 7%; height: 7%;" src="juste1.jpg"></td>
                            <td class="premium"><img style="width: 7%; height: 7%;" src="juste1.jpg"></td>
                            <td class="pro"><img style="width: 7%; height: 7%;" src="juste1.jpg"></td>
                        </tr>


                        <tr>
                            <td style="color: black;">Outils de calcul (Calculatrice de crédit immobilier, Calcul de l’hypothèque, évaluation et alerte immobilière)</td>

                            <td class="debutant"><img style="width: 7%; height: 7%;" src="faux.jpg"></td>
                            <td class="simple"><img style="width: 7%; height: 7%;" src="faux.jpg"></td>
                            <td class="premium"><img style="width: 7%; height: 7%;" src="juste1.jpg"></td>
                            <td class="pro"><img style="width: 7%; height: 7%;" src="juste1.jpg"></td>
                        </tr>

                        <tr>
                            <td style="color: black;">Création des comptes pour courtiers et/ou agence immobilière</td>

                            <td class="debutant"><img style="width: 7%; height: 7%;" src="faux.jpg"></td>
                            <td class="simple"><img style="width: 7%; height: 7%;" src="faux.jpg"></td>
                            <td class="premium"><img style="width: 7%; height: 7%;" src="faux.jpg"></td>
                            <td class="pro"><img style="width: 7%; height: 7%;" src="juste1.jpg"></td>
                        </tr>

                        <tr>
                            <td style="color: black;">Intégration des outilsTableau de bord administrateur avec les statistiques utilisateurs, courtiers</td>

                            <td class="debutant"><img style="width: 7%; height: 7%;" src="faux.jpg"></td>
                            <td class="simple"><img style="width: 7%; height: 7%;" src="faux.jpg"></td>
                            <td class="premium"><img style="width: 7%; height: 7%;" src="faux.jpg"></td>
                            <td class="pro"><img style="width: 7%; height: 7%;" src="juste1.jpg"></td>
                        </tr>

                        <tr>
                            <td style="color: black;">Tracking Google</td>
                            <td class="debutant"><img style="width: 7%; height: 7%;" src="faux.jpg"></td>
                            <td class="simple"><img style="width: 7%; height: 7%;" src="faux.jpg"></td>
                            <td class="premium"><img style="width: 7%; height: 7%;" src="faux.jpg"></td>
                            <td class="pro"><img style="width: 7%; height: 7%;" src="juste1.jpg"></td>
                        </tr>



                        <!-- Add more rows as needed based on your image -->
                    </tbody>
                </table>



</strong></td></tr></tbody></table>
                <div class="navigation">
                    <button type="button" class="btn btn-secondary" onclick="showSection(1)">Précédent</button>
                    <button type="button" class="btn btn-primary" onclick="showSection(3)">Suivant</button>
                </div>
            </div>

</div></div>
            <div class="section" id="section-3">


 <h1>Références personnalisées </h1>
            <table>
                <thead>
                    <tr>
                        <th style="color: black;">Catégorie</th>
                        <th style="color: black;">Nom</th>
                        <th style="color: black;">Contact</th>
                        <th style="color: black;">Téléphone</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="color: black;">Notaire :</td>
                        <td><textarea type="text" id="notaire_nom" name="notaire_nom"> </textarea></td>
                        <td><textarea type="text" id="notaire_contact" name="notaire_contact"></textarea></td>
                        <td><textarea type="text" id="notaire_telephone" name="notaire_telephone"></textarea></td>
                    </tr>
                    <tr>
                        <td style="color: black;">Courtiers :</td>
                        <td><textarea type="text" id="courtiers_nom" name="courtiers_nom"></textarea></td>
                        <td><textarea type="text" id="courtiers_contact" name="courtiers_contact"></textarea></td>
                        <td><textarea type="text" id="courtiers_telephone" name="courtiers_telephone"></textarea></td>
                    </tr>
                    <tr>
                        <td style="color: black;">Inspecteurs :</td>
                        <td><textarea type="text" id="inspecteurs_nom" name="inspecteurs_nom"></textarea></td>
                        <td><textarea type="text" id="inspecteurs_contact" name="inspecteurs_contact"></textarea></td>
                        <td><textarea type="text" id="inspecteurs_telephone" name="inspecteurs_telephone"></textarea></td>
                    </tr>
                    <tr>
                        <td style="color: black;">Évaluateurs :</td>
                        <td><textarea type="text" id="evaluateurs_nom" name="evaluateurs_nom"></textarea></td>
                        <td><textarea type="text" id="evaluateurs_contact" name="evaluateurs_contact"></textarea></td>
                        <td><textarea type="text" id="evaluateurs_telephone" name="evaluateurs_telephone"></textarea></td>
                    </tr>
                    <tr>
                        <td style="color: black;">Arpenteurs géomètres :</td>
                        <td><textarea type="text" id="arpenteurs_nom" name="arpenteurs_nom"></textarea></td>
                        <td><textarea type="text" id="arpenteurs_contact" name="arpenteurs_contact"></textarea></td>
                        <td><textarea type="text" id="arpenteurs_telephone" name="arpenteurs_telephone"></textarea></td>
                    </tr>
                    <tr>
                        <td style="color: black;">Entrepreneur général :</td>
                        <td><textarea type="text" id="entrepreneur_nom" name="entrepreneur_nom"></textarea></td>
                        <td><textarea type="text" id="entrepreneur_contact" name="entrepreneur_contact"></textarea></td>
                        <td><textarea type="text" id="entrepreneur_telephone" name="entrepreneur_telephone"></textarea></td>
                    </tr>
                    <tr>
                        <td style="color: black;">Assurances :</td>
                        <td><textarea type="text" id="assurances_nom" name="assurances_nom"></textarea></td>
                        <td><textarea type="text" id="assurances_contact" name="assurances_contact"></textarea></td>
                        <td><textarea type="text" id="assurances_telephone" name="assurances_telephone"></textarea></td>
                    </tr>
                    <tr>
                        <td style="color: black;">Déménageur :</td>
                        <td><textarea type="text" id="demenageur_nom" name="demenageur_nom"></textarea></td>
                        <td><textarea type="text" id="demenageur_contact" name="demenageur_contact"></textarea></td>
                        <td><textarea type="text" id="demenageur_telephone" name="demenageur_telephone"></textarea></td>
                    </tr>
                    <tr>
                        <td style="color: black;">Designer intérieur :</td>
                        <td><textarea type="text" id="designer_nom" name="designer_nom"></textarea></td>
                        <td><textarea type="text" id="designer_contact" name="designer_contact"></textarea></td>
                        <td><textarea type="text" id="designer_telephone" name="designer_telephone"></textarea></td>
                    </tr>
                    <tr>
                        <td style="color: black;">Armoires de cuisine :</td>
                        <td><textarea type="text" id="armoires_nom" name="armoires_nom"></textarea></td>
                        <td><textarea type="text" id="armoires_contact" name="armoires_contact"></textarea></td>
                        <td><textarea type="text" id="armoires_telephone" name="armoires_telephone"></textarea></td>
                    </tr>
                    <tr>
                        <td style="color: black;">Électricien :</td>
                        <td><textarea type="text" id="electricien_nom" name="electricien_nom"></textarea></td>
                        <td><textarea type="text" id="electricien_contact" name="electricien_contact"></textarea></td>
                        <td><textarea type="text" id="electricien_telephone" name="electricien_telephone"></textarea></td>
                    </tr>
                    <tr>
                        <td style="color: black;">Plomberie :</td>
                        <td><textarea type="text" id="plomberie_nom" name="plomberie_nom"></textarea></td>
                        <td><textarea type="text" id="plomberie_contact" name="plomberie_contact"></textarea></td>
                        <td><textarea type="text" id="plomberie_telephone" name="plomberie_telephone"></textarea></td>
                    </tr>
                    <tr>
                        <td style="color: black;">Puits artésiens :</td>
                        <td><textarea type="text" id="puits_nom" name="puits_nom"></textarea></td>
                        <td><textarea type="text" id="puits_contact" name="puits_contact"></textarea></td>
                        <td><textarea type="text" id="puits_telephone" name="puits_telephone"></textarea></td>
                    </tr>
                </tbody>
            </table>




                <div class="navigation">
                    <button type="button" class="btn btn-secondary" onclick="showSection(2)">Précédeddnt</button>
                    <button type="submit" class="btn btn-success">Valider</button>
                </div>
            </div>



        </form>
    </div>









































    </form>
</div>

<script>
    document.getElementById('package').addEventListener('change', function() {
        document.getElementById('package-description').style.display = 'block';
    });
</script>

<style>
.package-table {
    width: 100%;
    margin-top: 20px;
}
.package-table table {
    width: 100%;
    border-collapse: collapse;
}
.package-table th, .package-table td {
    border: 1px solid #ddd;
    padding: 8px;
    text-align: center;
}
.package-table th {
    background-color: #f2f2f2;
    font-weight: bold;
}
</style>

<style>
.package-table {
    width: 100%;
    margin-top: 20px;
}
.package-table table {
    width: 100%;
    border-collapse: collapse;
    table-layout: fixed;
}
.package-table th, .package-table td {
    border: 1px solid #ddd;
    padding: 8px;
    text-align: center;
    color: #fff;
}
.package-table th {
    font-weight: bold;
}
.package-table .debutant {
    color: #f44336; /* Red */
}
.package-table .simple {
    color: #9c27b0; /* Purple */
}
.package-table .premium {
    color: #4caf50; /* Green */
}
.package-table .pro {
    color: #2196f3; /* Blue */
}
</style>


    <style type="text/css">

   
        .logo {
            width: 200px;
            height: auto;
        }

        .title {
            text-align: right;
        }

        .title h1 {
            margin: 0;
            color: #007bff;
        }

        .info {
            margin: 20px 0;
        }

        .info-item {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }

        .info-item label {
            width: 150px;
            font-weight: bold;
        }

        .info-item input {
            flex: 1;
            padding: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        table th,
        table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        table th {
            background-color: #f4f4f4;
            font-weight: bold;
        }

        button {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            border-radius: 5px;
        }

        button:hover {
            background-color: #0056b3;
        }

.info {
    display: flex;
    flex-wrap: wrap;
}

.info-item {
    flex: 1 1 45%; /* 45% de la largeur disponible pour chaque élément, permettant de garder un espace de 5% entre les colonnes */
    margin: 10px;
}

.info-item label {
    display: block;
    margin-bottom: 5px;
}

.info-item input {
    width: 100%;
    padding: 5px;
}

    </style>
        </div>
    </div>
</div>

<!-- Include jQuery and Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <!-- Lien Bootstrap JS et jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        function showSection(index) {
            let sections = document.querySelectorAll('.section');
            let tabs = document.querySelectorAll('.tab');
            sections.forEach((section, i) => {
                if (i === index) {
                    section.classList.add('active');
                    tabs[i].classList.add('active');
                } else {
                    section.classList.remove('active');
                    tabs[i].classList.remove('active');
                }
            });
        }
    </script>

<script>
$(document).ready(function(){
    $('#orderModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var model = button.data('model');
        var modal = $(this);
        modal.find('#model-name').text(model);
        modal.find('#model').val(model);
    });
});
</script>
    <script>

        $('#orderForm').on('submit', function (e) {
            e.preventDefault();
            // Vous pouvez ajouter ici le code pour soumettre le formulaire via AJAX
            alert('Commande soumise pour ' + $('#model-name').text());
            $('#orderModal').modal('hide');
        });
    </script>
<!-- /End page content -->
    
    

<div class="copyright fables-main-background-color mt-0 border-0 white-color">
        <ul class="nav fables-footer-social-links just-center fables-light-footer-links">
            <li><a href="#" target="_blank"><i class="fab fa-google-plus-square"></i></a></li>
            <li><a href="#" target="_blank"><i class="fab fa-facebook"></i></a></li>
            <li><a href="#" target="_blank"><i class="fab fa-instagram"></i></a></li>
            <li><a href="#" target="_blank"><i class="fab fa-pinterest-square"></i></a></li>
            <li><a href="#" target="_blank"><i class="fab fa-twitter-square"></i></a></li>
            <li><a href="#" target="_blank"><i class="fab fa-linkedin"></i></a></li>
        </ul>
        <p class="mb-0">Copyright © Immonivo 2018. Tous droits réservés</p> 

</div>
    
<!-- /End Footer 2 Background Image -->



<script src="assets/vendor/jquery/jquery-3.3.1.min.js"></script>
<script src="assets/vendor/loadscreen/js/ju-loading-screen.js"></script>
<script src="assets/vendor/jquery-circle-progress/circle-progress.min.js"></script>
<script src="assets/vendor/popper/popper.min.js"></script>
<script src="assets/vendor/WOW-master/dist/wow.min.js"></script>
<script src="assets/vendor/bootstrap/js/bootstrap.min.js"></script>
<script src="assets/vendor/owlcarousel/owl.carousel.min.js"></script> 
<script src="assets/custom/js/custom.js"></script>  
    
</body>
</html>