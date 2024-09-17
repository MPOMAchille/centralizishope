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

// Vérifier la connexion à la base de données
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fonction pour générer un code aléatoire
function generateRandomCode($length = 10) {
    return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Générer un code aléatoire
    $randomCode = generateRandomCode();

    // Récupérer les données du formulaire
    $categorie = $_POST['categorie'];
    $nom = $_POST['name'];
    $prenom = $_POST['surname'];
    $email = $_POST['email'];
    $prof = $_POST['prof'];
    $sexe = $_POST['gender'];
    $pays = $_POST['country'];


    $city = $_POST['city'];
    $exp = $_POST['exp'];
    $region = $_POST['region'];
    $specail = $_POST['specail'];
    $ecrit = $_POST['ecrit'];
    $parle = $_POST['parle'];

    $permi = $_POST['permi'];
    $enfant = $_POST['enfant'];  
    // Insérer les informations personnelles dans la table candidats
    $stmt = $conn->prepare("INSERT INTO candidats (categorie, nom, prenom, email, prof, sexe, pays, city, exp, region, specail, ecrit, parle, permi, enfant, user_id, code) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssssssssssssis", $categorie, $nom, $prenom, $email, $prof, $sexe, $pays, $city, $exp, $region, $specail, $ecrit, $parle, $permi, $enfant, $userId, $randomCode);

    if ($stmt->execute()) {
        $candidat_id = $stmt->insert_id; // Récupérer l'ID du candidat inséré

        // Insérer le parcours académique
        $stmt_academique = $conn->prepare("INSERT INTO parcours_academique (candidat_id, diplome, institution, date_obtention, user_id, code) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt_academique->bind_param("isssis", $candidat_id, $diplome, $institution, $date_obtention, $userId, $randomCode);

        if (isset($_POST['degree']) && is_array($_POST['degree'])) {
            foreach ($_POST['degree'] as $index => $diplome) {
                $institution = $_POST['institution'][$index];
                $date_obtention = $_POST['graduationDate'][$index];
                $stmt_academique->execute();
            }
        }

        // Insérer le parcours professionnel
        $stmt_professionnel = $conn->prepare("INSERT INTO parcours_professionnel (candidat_id, poste, entreprise, periode, pays, user_id, code) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt_professionnel->bind_param("issssis", $candidat_id, $poste, $entreprise, $periode, $job_country, $userId, $randomCode);

        if (isset($_POST['position']) && is_array($_POST['position'])) {
            foreach ($_POST['position'] as $index => $poste) {
                $entreprise = $_POST['company'][$index];
                $periode = $_POST['period'][$index];
                $job_country = $_POST['jobCountry'][$index];
                $stmt_professionnel->execute();
            }
        }

        $message = "Informations soumises avec succès!";
    } else {
        $message = "Error: " . $stmt->error;
    }
}

$targetDir = "uploads/";
if (!file_exists($targetDir)) {
    mkdir($targetDir, 0777, true);
}

$mandatoryFiles = ["diplome", "passeport", "certificat_naissance", "mandat_representation", "certificat_scolarite"];
$optionalFiles = ["cv", "attestation_etude", "plan_cadre", "attestation_enregistrement", "releve_note", "experience_professionnelle", "permis_conduire", "acte_mariage"];

$files = array_merge($mandatoryFiles, $optionalFiles);

$fileNames = [];
foreach ($files as $file) {
    if ($file == "releve_note" && isset($_FILES[$file])) {
        // Gestion de plusieurs fichiers pour "releve_note"
        $fileNames[$file] = [];
        foreach ($_FILES[$file]['name'] as $key => $name) {
            if ($_FILES[$file]['error'][$key] == 0) {
                $uniqueName = time() . '_' . uniqid() . '_' . basename($name);
                $targetFilePath = $targetDir . $uniqueName;
                move_uploaded_file($_FILES[$file]["tmp_name"][$key], $targetFilePath);
                $fileNames[$file][] = $uniqueName;
            }
        }
        $fileNames[$file] = implode(",", $fileNames[$file]);
    } else {
        if (isset($_FILES[$file]) && $_FILES[$file]['error'] == 0) {
            $uniqueName = time() . '_' . uniqid() . '_' . basename($_FILES[$file]["name"]);
            $targetFilePath = $targetDir . $uniqueName;
            move_uploaded_file($_FILES[$file]["tmp_name"], $targetFilePath);
            $fileNames[$file] = $uniqueName;
        } else {
            $fileNames[$file] = null;
        }
    }
}

// Préparer et lier
$stmt = $conn->prepare("INSERT INTO documentss (conseiller, nom, tel, courriel, diplome, cv, certificat_naissance, certificat_scolarite, passeport, attestation_etude, plan_cadre, attestation_enregistrement, releve_note, experience_professionnelle, permis_conduire, mandat_representation, acte_mariage, code) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("isssssssssssssssss", $userId, $nom, $prenom, $email, $fileNames['diplome'], $fileNames['cv'], $fileNames['certificat_naissance'], $fileNames['certificat_scolarite'], $fileNames['passeport'], $fileNames['attestation_etude'], $fileNames['plan_cadre'], $fileNames['attestation_enregistrement'], $fileNames['releve_note'], $fileNames['experience_professionnelle'], $fileNames['permis_conduire'], $fileNames['mandat_representation'], $fileNames['acte_mariage'], $randomCode);

// Exécuter la requête
if ($stmt->execute()) {
    echo "Nouvelle entrée créée avec succès";
} else {
    echo "Erreur : " . $stmt->error;
}

// Fermer la connexion
$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Collecte d'Informations</title>
    <style>
        h1 {
            text-align: center;
        }

        form {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        fieldset {
            border: 1px solid #ddd;
            margin-bottom: 20px;
            padding: 10px;
        }

        legend {
            padding: 0 10px;
            font-weight: bold;
        }

        label {
            display: inline-block;
            width: 120px;
            margin-right: 10px;
        }

        input[type="text"], input[type="email"] {
            width: calc(100% - 140px);
            padding: 5px;
            margin-bottom: 10px;
        }

        select {
            width: calc(100% - 140px);
            padding: 5px;
            margin-bottom: 10px;
        }

        button[type="button"] {
            display: inline-block;
            margin-top: 10px;
            margin-bottom: 10px;
        }

        button[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button[type="submit"]:hover {
            background-color: #45a049;
        }

        button {
            cursor: pointer;
        }

        .academic-entry, .professional-entry {
            margin-bottom: 10px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
        }

        .form-group input[type="text"], .form-group input[type="file"] {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
        }

        .form-group input[type="file"] {
            padding: 3px;
        }

        .button-group {
            text-align: center;
            margin-top: 20px;
        }

        .button-group button {
            padding: 10px 20px;
            margin: 0 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f4f4f4;
        }
    </style>

    
    <title>Collecte d'Informations</title>
</head>
<body><br><br>
    <h1>Enregistrement d'un candidat</h1>
    <?php if ($message): ?>
        <p><?php echo $message; ?></p>
    <?php endif; ?>
    <form id="infoForm" method="POST" action="" enctype="multipart/form-data">


    <fieldset>
        <legend>Informations Personnelles</legend>

            
<label for="categorie">Catégorie:</label>
<select id="nacategorieme" name="categorie">

    <option value="Cadres supérieures">Cadres supérieures</option>
    <option value="Affaires, finance et administration">Affaires, finance et administration</option>
    <option value="Construction">Construction</option>
    <option value="Santé">Santé</option>
    <option value="Informatique">Informatique</option>
    <option value="Enseignement, droit et services sociaux">Enseignement, droit et services sociaux</option>
    <option value="Arts, culture, sports et loisirs">Arts, culture, sports et loisirs</option>
    <option value="Restauration">Restauration</option>
    <option value="Vente et services">Vente et services</option>
    <option value="Aéronautique, transport, machinerie et domaines apparentés">Aéronautique, transport, machinerie et domaines apparentés</option>
    <option value="Ressources naturelles, agriculture et production connexe">Ressources naturelles, agriculture et production connexe</option>
    <option value="Fabrication et services d'utilité publique">Fabrication et services d'utilité publique</option>
    <option value="Autre">Autre</option>


</select>

 
        <div class="form-group">
            <label for="name">Nom:</label>
            <input type="text" id="name" name="name">

            <label for="surname">Prénom:</label>
            <input type="text" id="surname" name="surname">

            <label for="email">Email:</label>
            <input type="email" id="email" name="email">

            <label for="prof">Profession:</label>
            <input type="text" id="prof" name="prof">

            <label for="specail">Spécialité:</label>
            <input type="text" id="specail" name="specail">

            <label for="exp">Années d’expérience :</label>
            <input type="number" id="exp" name="exp">

            <label for="gender">Sexe:</label>
            <select id="gender" name="gender">
                <option value="male">Masculin</option>
                <option value="female">Féminin</option>
                <option value="other">Autre</option>
            </select>

            <label for="country">Pays:</label>
            <input type="text" id="country" name="country">

            <label for="city">Ville:</label>
            <input type="text" id="city" name="city">

<label for="region">Code régional:</label>
<select id="region" name="region" class="form-control">
    <option value="+1 USA/Canada">+1 USA/Canada</option>
    <option value="+7 Russie">+7 Russie</option>
    <option value="+20 Égypte">+20 Égypte</option>
    <option value="+27 Afrique du Sud">+27 Afrique du Sud</option>
    <option value="+30 Grèce">+30 Grèce</option>
    <option value="+31 Pays-Bas">+31 Pays-Bas</option>
    <option value="+32 Belgique">+32 Belgique</option>
    <option value="+33 France">+33 France</option>
    <option value="+34 Espagne">+34 Espagne</option>
    <option value="+36 Hongrie">+36 Hongrie</option>
    <option value="+39 Italie">+39 Italie</option>
    <option value="+40 Roumanie">+40 Roumanie</option>
    <option value="+41 Suisse">+41 Suisse</option>
    <option value="+43 Autriche">+43 Autriche</option>
    <option value="+44 Royaume-Uni">+44 Royaume-Uni</option>
    <option value="+45 Danemark">+45 Danemark</option>
    <option value="+46 Suède">+46 Suède</option>
    <option value="+47 Norvège">+47 Norvège</option>
    <option value="+48 Pologne">+48 Pologne</option>
    <option value="+49 Allemagne">+49 Allemagne</option>
    <option value="+51 Pérou">+51 Pérou</option>
    <option value="+52 Mexique">+52 Mexique</option>
    <option value="+53 Cuba">+53 Cuba</option>
    <option value="+54 Argentine">+54 Argentine</option>
    <option value="+55 Brésil">+55 Brésil</option>
    <option value="+56 Chili">+56 Chili</option>
    <option value="+57 Colombie">+57 Colombie</option>
    <option value="+58 Venezuela">+58 Venezuela</option>
    <option value="+60 Malaisie">+60 Malaisie</option>
    <option value="+61 Australie">+61 Australie</option>
    <option value="+62 Indonésie">+62 Indonésie</option>
    <option value="+63 Philippines">+63 Philippines</option>
    <option value="+64 Nouvelle-Zélande">+64 Nouvelle-Zélande</option>
    <option value="+65 Singapour">+65 Singapour</option>
    <option value="+66 Thaïlande">+66 Thaïlande</option>
    <option value="+81 Japon">+81 Japon</option>
    <option value="+82 Corée du Sud">+82 Corée du Sud</option>
    <option value="+84 Vietnam">+84 Vietnam</option>
    <option value="+86 Chine">+86 Chine</option>
    <option value="+90 Turquie">+90 Turquie</option>
    <option value="+91 Inde">+91 Inde</option>
    <option value="+92 Pakistan">+92 Pakistan</option>
    <option value="+93 Afghanistan">+93 Afghanistan</option>
    <option value="+94 Sri Lanka">+94 Sri Lanka</option>
    <option value="+95 Myanmar">+95 Myanmar</option>
    <option value="+98 Iran">+98 Iran</option>
    <option value="+211 Soudan du Sud">+211 Soudan du Sud</option>
    <option value="+212 Maroc">+212 Maroc</option>
    <option value="+213 Algérie">+213 Algérie</option>
    <option value="+216 Tunisie">+216 Tunisie</option>
    <option value="+218 Libye">+218 Libye</option>
    <option value="+220 Gambie">+220 Gambie</option>
    <option value="+221 Sénégal">+221 Sénégal</option>
    <option value="+222 Mauritanie">+222 Mauritanie</option>
    <option value="+223 Mali">+223 Mali</option>
    <option value="+224 Guinée">+224 Guinée</option>
    <option value="+225 Côte d'Ivoire">+225 Côte d'Ivoire</option>
    <option value="+226 Burkina Faso">+226 Burkina Faso</option>
    <option value="+227 Niger">+227 Niger</option>
    <option value="+228 Togo">+228 Togo</option>
    <option value="+229 Bénin">+229 Bénin</option>
    <option value="+230 Maurice">+230 Maurice</option>
    <option value="+231 Libéria">+231 Libéria</option>
    <option value="+232 Sierra Leone">+232 Sierra Leone</option>
    <option value="+233 Ghana">+233 Ghana</option>
    <option value="+234 Nigéria">+234 Nigéria</option>
    <option value="+235 Tchad">+235 Tchad</option>
    <option value="+236 République Centrafricaine">+236 République Centrafricaine</option>
    <option value="+237 Cameroun">+237 Cameroun</option>
    <option value="+238 Cap-Vert">+238 Cap-Vert</option>
    <option value="+239 Sao Tomé-et-Principe">+239 Sao Tomé-et-Principe</option>
    <option value="+240 Guinée Équatoriale">+240 Guinée Équatoriale</option>
    <option value="+241 Gabon">+241 Gabon</option>
    <option value="+242 République du Congo">+242 République du Congo</option>
    <option value="+243 République Démocratique du Congo">+243 République Démocratique du Congo</option>
    <option value="+244 Angola">+244 Angola</option>
    <option value="+245 Guinée-Bissau">+245 Guinée-Bissau</option>
    <option value="+248 Seychelles">+248 Seychelles</option>
    <option value="+249 Soudan">+249 Soudan</option>
    <option value="+250 Rwanda">+250 Rwanda</option>
    <option value="+251 Éthiopie">+251 Éthiopie</option>
    <option value="+252 Somalie">+252 Somalie</option>
    <option value="+253 Djibouti">+253 Djibouti</option>
    <option value="+254 Kenya">+254 Kenya</option>
    <option value="+255 Tanzanie">+255 Tanzanie</option>
    <option value="+256 Ouganda">+256 Ouganda</option>
    <option value="+257 Burundi">+257 Burundi</option>
    <option value="+258 Mozambique">+258 Mozambique</option>
    <option value="+260 Zambie">+260 Zambie</option>
    <option value="+261 Madagascar">+261 Madagascar</option>
    <option value="+263 Zimbabwe">+263 Zimbabwe</option>
    <option value="+264 Namibie">+264 Namibie</option>
    <option value="+265 Malawi">+265 Malawi</option>
    <option value="+266 Lesotho">+266 Lesotho</option>
    <option value="+267 Botswana">+267 Botswana</option>
    <option value="+268 Swaziland">+268 Swaziland</option>
    <option value="+269 Comores">+269 Comores</option>
    <option value="+290 Sainte-Hélène">+290 Sainte-Hélène</option>
    <option value="+291 Érythrée">+291 Érythrée</option>
    <option value="+297 Aruba">+297 Aruba</option>
    <option value="+298 Îles Féroé">+298 Îles Féroé</option>
    <option value="+299 Groenland">+299 Groenland</option>
</select>
 
            <label for="ecrit">Langue écrite:</label>
            <select id="ecrit" name="ecrit">
<option value="français">Français</option>
    <option value="anglais">Anglais</option>
    <option value="espagnol">Espagnol</option>
    <option value="allemand">Allemand</option>
    <option value="italien">Italien</option>
    <option value="portugais">Portugais</option>
    <option value="russe">Russe</option>
    <option value="chinois">Chinois</option>
    <option value="japonais">Japonais</option>
    <option value="coréen">Coréen</option>
    <option value="arabe">Arabe</option>
    <option value="hindi">Hindi</option>
    <option value="bengali">Bengali</option>
    <option value="ourdou">Ourdou</option>
    <option value="indonésien">Indonésien</option>
    <option value="turc">Turc</option>
    <option value="swahili">Swahili</option>
    <option value="grec">Grec</option>
    <option value="hébreu">Hébreu</option>
    <option value="polonais">Polonais</option>
    <option value="néerlandais">Néerlandais</option>
    <option value="thaï">Thaï</option>
    <option value="vietnamien">Vietnamien</option>
    <option value="suédois">Suédois</option>
    <option value="norvégien">Norvégien</option>
    <option value="finnois">Finnois</option>
    <option value="danois">Danois</option>
    <option value="hongrois">Hongrois</option>
    <option value="tchèque">Tchèque</option>
    <option value="slovaque">Slovaque</option>
    <option value="roumain">Roumain</option>
    <option value="bulgare">Bulgare</option>
    <option value="serbe">Serbe</option>
    <option value="croate">Croate</option>
    <option value="bosniaque">Bosniaque</option>
    <option value="macédonien">Macédonien</option>
    <option value="albanais">Albanais</option>
    <option value="slovène">Slovène</option>
    <option value="lituanien">Lituanien</option>
    <option value="letton">Letton</option>
    <option value="estonien">Estonien</option>
    <option value="islandais">Islandais</option>
    <option value="maltais">Maltais</option>
    <option value="irlandais">Irlandais</option>
    <option value="gaélique écossais">Gaélique écossais</option>
    <option value="gallois">Gallois</option>
    <option value="breton">Breton</option>
    <option value="catalan">Catalan</option>
    <option value="basque">Basque</option>
    <option value="galicien">Galicien</option>
    <option value="luxembourgeois">Luxembourgeois</option>
    <option value="valaque">Valaque</option>
    <option value="kazakh">Kazakh</option>
    <option value="ouïghour">Ouïghour</option>
    <option value="amharique">Amharique</option>
    <option value="zoulou">Zoulou</option>
    <option value="xhosa">Xhosa</option>
    <option value="haoussa">Haoussa</option>
    <option value="yoruba">Yoruba</option>
    <option value="igbo">Igbo</option>
    <option value="somali">Somali</option>
    <option value="kiswahili">Kiswahili</option>
    <option value="afrikaans">Afrikaans</option>
    <option value="azéri">Azéri</option>
    <option value="arménien">Arménien</option>
    <option value="géorgien">Géorgien</option>
    <option value="tadjik">Tadjik</option>
    <option value="ouzbek">Ouzbek</option>
    <option value="kirghiz">Kirghiz</option>
    <option value="turkmène">Turkmène</option>
    <option value="mongol">Mongol</option>
    <option value="népalais">Népalais</option>
    <option value="singhalais">Singhalais</option>
    <option value="tamoul">Tamoul</option>
    <option value="birman">Birman</option>
    <option value="khmer">Khmer</option>
    <option value="laotien">Laotien</option>
    <option value="filipino">Filipino</option>
    <option value="tagalog">Tagalog</option>
    <option value="samoan">Samoan</option>
    <option value="tongan">Tongan</option>
    <option value="maori">Maori</option>
    <option value="fidjien">Fidjien</option>
    <option value="palaos">Palaos</option>
    <option value="kiribatien">Kiribatien</option>
    <option value="marshallais">Marshallais</option>
    <option value="nauruan">Nauruan</option>
    <option value="niuéen">Niuéen</option>
    <option value="tuvaluan">Tuvaluan</option>
    <option value="vanuatuan">Vanuatuan</option>
    <option value="maldivien">Maldivien</option>
            </select>

            <label for="parle">Langue parlée:</label>
            <select id="parle" name="parle">
                <option value="français">Français</option>
                <option value="anglais">Anglais</option>
    <option value="espagnol">Espagnol</option>
    <option value="allemand">Allemand</option>
    <option value="italien">Italien</option>
    <option value="portugais">Portugais</option>
    <option value="russe">Russe</option>
    <option value="chinois">Chinois</option>
    <option value="japonais">Japonais</option>
    <option value="coréen">Coréen</option>
    <option value="arabe">Arabe</option>
    <option value="hindi">Hindi</option>
    <option value="bengali">Bengali</option>
    <option value="ourdou">Ourdou</option>
    <option value="indonésien">Indonésien</option>
    <option value="turc">Turc</option>
    <option value="swahili">Swahili</option>
    <option value="grec">Grec</option>
    <option value="hébreu">Hébreu</option>
    <option value="polonais">Polonais</option>
    <option value="néerlandais">Néerlandais</option>
    <option value="thaï">Thaï</option>
    <option value="vietnamien">Vietnamien</option>
    <option value="suédois">Suédois</option>
    <option value="norvégien">Norvégien</option>
    <option value="finnois">Finnois</option>
    <option value="danois">Danois</option>
    <option value="hongrois">Hongrois</option>
    <option value="tchèque">Tchèque</option>
    <option value="slovaque">Slovaque</option>
    <option value="roumain">Roumain</option>
    <option value="bulgare">Bulgare</option>
    <option value="serbe">Serbe</option>
    <option value="croate">Croate</option>
    <option value="bosniaque">Bosniaque</option>
    <option value="macédonien">Macédonien</option>
    <option value="albanais">Albanais</option>
    <option value="slovène">Slovène</option>
    <option value="lituanien">Lituanien</option>
    <option value="letton">Letton</option>
    <option value="estonien">Estonien</option>
    <option value="islandais">Islandais</option>
    <option value="maltais">Maltais</option>
    <option value="irlandais">Irlandais</option>
    <option value="gaélique écossais">Gaélique écossais</option>
    <option value="gallois">Gallois</option>
    <option value="breton">Breton</option>
    <option value="catalan">Catalan</option>
    <option value="basque">Basque</option>
    <option value="galicien">Galicien</option>
    <option value="luxembourgeois">Luxembourgeois</option>
    <option value="valaque">Valaque</option>
    <option value="kazakh">Kazakh</option>
    <option value="ouïghour">Ouïghour</option>
    <option value="amharique">Amharique</option>
    <option value="zoulou">Zoulou</option>
    <option value="xhosa">Xhosa</option>
    <option value="haoussa">Haoussa</option>
    <option value="yoruba">Yoruba</option>
    <option value="igbo">Igbo</option>
    <option value="somali">Somali</option>
    <option value="kiswahili">Kiswahili</option>
    <option value="afrikaans">Afrikaans</option>
    <option value="azéri">Azéri</option>
    <option value="arménien">Arménien</option>
    <option value="géorgien">Géorgien</option>
    <option value="tadjik">Tadjik</option>
    <option value="ouzbek">Ouzbek</option>
    <option value="kirghiz">Kirghiz</option>
    <option value="turkmène">Turkmène</option>
    <option value="mongol">Mongol</option>
    <option value="népalais">Népalais</option>
    <option value="singhalais">Singhalais</option>
    <option value="tamoul">Tamoul</option>
    <option value="birman">Birman</option>
    <option value="khmer">Khmer</option>
    <option value="laotien">Laotien</option>
    <option value="filipino">Filipino</option>
    <option value="tagalog">Tagalog</option>
    <option value="samoan">Samoan</option>
    <option value="tongan">Tongan</option>
    <option value="maori">Maori</option>
    <option value="fidjien">Fidjien</option>
    <option value="palaos">Palaos</option>
    <option value="kiribatien">Kiribatien</option>
    <option value="marshallais">Marshallais</option>
    <option value="nauruan">Nauruan</option>
    <option value="niuéen">Niuéen</option>
    <option value="tuvaluan">Tuvaluan</option>
    <option value="vanuatuan">Vanuatuan</option>
    <option value="maldivien">Maldivien</option>
                <option value="other">Autre</option>
            </select>

            <label for="permi">Permis de conduire:</label>
            <input type="text" id="permi" name="permi">

            <label for="enfant">Nombre d'enfant:</label>
            <input type="number" id="enfant" name="enfant">
        </div>
    </fieldset>
        <fieldset>
            <legend>Parcours Académique</legend>
            <div id="academicInfo">
                <div class="academic-entry">
                    <label for="degree">Diplôme:</label>
                    <input type="text" class="degree" name="degree[]" >
                    <label for="institution">Institution:</label>
                    <input type="text" class="institution" name="institution[]" >
                    <label for="graduationDate">Date d'obtention:</label>
                    <input type="text" class="graduationDate" name="graduationDate[]" >
                    <button type="button" onclick="removeAcademicEntry(this)">Supprimer</button>
                </div>
            </div>
            <button type="button" id="addAcademic">Ajouter un diplôme</button>
        </fieldset>
        <fieldset>
            <legend>Parcours Professionnel</legend>
            <div id="professionalInfo">
                <div class="professional-entry">
                    <label for="position">Poste:</label>
                    <input type="text" class="position" name="position[]" >
                    <label for="company">Entreprise:</label>
                    <input type="text" class="company" name="company[]" >
                    <label for="period">Période:</label>
                    <input type="text" class="period" name="period[]" >
                    <label for="jobCountry">Pays:</label>
                    <input type="text" class="jobCountry" name="jobCountry[]" >
                    <button type="button" onclick="removeProfessionalEntry(this)">Supprimer</button>
                </div>
            </div>
            <button type="button" id="addProfessional">Ajouter une expérience professionnelle</button>
        </fieldset>
        <fieldset>
            <legend>Documents Obligatoires</legend>
            <label for="diplome">Diplôme:</label>
            <input type="file" id="diplome" name="diplome" ><br>
            <label for="passeport">Passeport:</label>
            <input type="file" id="passeport" name="passeport" ><br>
            <label for="certificat_naissance">Certificat de naissance:</label>
            <input type="file" id="certificat_naissance" name="certificat_naissance" ><br>
            <label for="certificat_scolarite">Certificat de scolarité:</label>
            <input type="file" id="certificat_scolarite" name="certificat_scolarite" ><br>
            <label for="mandat_representation">Mandat de représentation:</label>
            <input type="file" id="mandat_representation" name="mandat_representation" ><br>
        </fieldset>
        <fieldset>
            <legend>Documents Optionnels</legend>
            <label for="cv">CV:</label>
            <input type="file" id="cv" name="cv"><br>
            <label for="attestation_etude">Attestation d'étude:</label>
            <input type="file" id="attestation_etude" name="attestation_etude"><br>
            <label for="plan_cadre">Plan de cadre:</label>
            <input type="file" id="plan_cadre" name="plan_cadre"><br>
            <label for="attestation_enregistrement">Attestation d'enregistrement:</label>
            <input type="file" id="attestation_enregistrement" name="attestation_enregistrement"><br>
            <label for="releve_note">Relevé de notes:</label>
            <input type="file" id="releve_note" name="releve_note[]" multiple><br>
            <label for="experience_professionnelle">Expérience professionnelle:</label>
            <input type="file" id="experience_professionnelle" name="experience_professionnelle"><br>
            <label for="permis_conduire">Permis de conduire:</label>
            <input type="file" id="permis_conduire" name="permis_conduire"><br>
            <label for="acte_mariage">Acte de mariage:</label>
            <input type="file" id="acte_mariage" name="acte_mariage"><br>
        </fieldset>
        <button type="submit">Soumettre</button>
    </form>
    <script>
        document.getElementById('addAcademic').addEventListener('click', function() {
            var newEntry = document.createElement('div');
            newEntry.classList.add('academic-entry');
            newEntry.innerHTML = `
                <label for="degree">Diplôme:</label>
                <input type="text" class="degree" name="degree[]" >
                <label for="institution">Institution:</label>
                <input type="text" class="institution" name="institution[]" >
                <label for="graduationDate">Date d'obtention:</label>
                <input type="text" class="graduationDate" name="graduationDate[]" >
                <button type="button" onclick="removeAcademicEntry(this)">Supprimer</button>
            `;
            document.getElementById('academicInfo').appendChild(newEntry);
        });

        document.getElementById('addProfessional').addEventListener('click', function() {
            var newEntry = document.createElement('div');
            newEntry.classList.add('professional-entry');
            newEntry.innerHTML = `
                <label for="position">Poste:</label>
                <input type="text" class="position" name="position[]" >
                <label for="company">Entreprise:</label>
                <input type="text" class="company" name="company[]" >
                <label for="period">Période:</label>
                <input type="text" class="period" name="period[]" >
                <label for="jobCountry">Pays:</label>
                <input type="text" class="jobCountry" name="jobCountry[]" >
                <button type="button" onclick="removeProfessionalEntry(this)">Supprimer</button>
            `;
            document.getElementById('professionalInfo').appendChild(newEntry);
        });

        function removeAcademicEntry(button) {
            button.parentElement.remove();
        }

        function removeProfessionalEntry(button) {
            button.parentElement.remove();
        }
    </script>
</body>
</html>
