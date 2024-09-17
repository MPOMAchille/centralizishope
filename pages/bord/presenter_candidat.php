<?php
$servername = "4w0vau.myd.infomaniak.com";
$username = "4w0vau_dreamize";
$password = "Pidou2016";
$dbname = "4w0vau_dreamize";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("La connexion a échoué : " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        if ($_POST['action'] === 'fetch_data') {
            $filterValue = isset($_POST['filter']) ? $_POST['filter'] : '';
            $category = isset($_POST['category']) ? $_POST['category'] : 'ALL';

            // Requête pour obtenir les candidats
            $sql = "SELECT * FROM candidats";
            if ($category !== 'ALL') {
                $sql .= " WHERE categorie = '$category'";
            }
            if ($filterValue) {
                $sql .= ($category === 'ALL' ? " WHERE" : " AND") . " prof LIKE '%$filterValue%'";
            }
            $result = $conn->query($sql);

            $data = array();
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $data['candidats'][] = $row;
                }
            }

            // Requête pour obtenir les compétences
            $sql2 = "SELECT competence1.Nom, competence1.prenom, competence1.tel, competence1.mail, competence1.agen, competence1.agent, competence1.codeutilisateur, competence1.pays, competence1.ville, competence1.id,
                            competence2.skillTitle, competence2.description, competence2.tools, competence2.references
                    FROM competence1
                    LEFT JOIN competence2 ON competence1.codeutilisateur = competence2.codeutilisateur";
            if ($category !== 'ALL') {
                $sql2 .= " WHERE competence1.categorie = '$category'";
            }
            $sql2 .= " GROUP BY competence1.id, competence2.skillTitle";
            $result2 = $conn->query($sql2);

            if ($result2->num_rows > 0) {
                while ($row2 = $result2->fetch_assoc()) {
                    $data['competences'][] = $row2;
                }
            }

            // Retourner les données au format JSON
            echo json_encode($data);
            exit();
        } elseif ($_POST['action'] === 'fetch_categories') {
            // Requête pour obtenir les catégories distinctes des deux tables
            $categories = array();

            $sql = "SELECT DISTINCT categorie FROM candidats";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $categories[] = $row['categorie'];
                }
            }

            $sql2 = "SELECT DISTINCT categorie FROM competence1";
            $result2 = $conn->query($sql2);
            if ($result2->num_rows > 0) {
                while ($row2 = $result2->fetch_assoc()) {
                    if (!in_array($row2['categorie'], $categories)) {
                        $categories[] = $row2['categorie'];
                    }
                }
            }

            // Retourner les catégories au format JSON
            echo json_encode(['categories' => $categories]);
            exit();
        } elseif ($_POST['action'] === 'place_order') {
            $selectedCandidates = isset($_POST['candidates']) ? $_POST['candidates'] : array();
            $selectedCompetences = isset($_POST['competences']) ? $_POST['competences'] : array();

            $nomm = isset($_POST['nomm']) ? $_POST['nomm'] : '';
            $telephone = isset($_POST['telephone']) ? $_POST['telephone'] : '';
            $couriel = isset($_POST['couriel']) ? $_POST['couriel'] : '';
            $villee = isset($_POST['villee']) ? $_POST['villee'] : '';
            $date = isset($_POST['date']) ? $_POST['date'] : '';
            $procedures = isset($_POST['procedures']) ? $_POST['procedures'] : '';
            $exigences = isset($_POST['exigences']) ? $_POST['exigences'] : '';

            if (empty($selectedCandidates) && empty($selectedCompetences)) {
                echo json_encode(array('status' => 'error', 'message' => 'Veuillez sélectionner au moins un candidat.'));
                exit();
            }

            $candidates = implode(',', $selectedCandidates);
            $competences = implode(',', $selectedCompetences);

            $sql = "INSERT INTO commandes22 (nomm, telephone, couriel, villee, date, procedures, exigences, candidats, competence_id)
                    VALUES ('$nomm', '$telephone', '$couriel', '$villee', '$date', '$procedures', '$exigences', '$candidates', '$competences')";
            if ($conn->query($sql) === TRUE) {
               echo json_encode([
    'status' => 'success',
    'message' => 'Votre réservation a été enregistrée avec succès. Veuillez noter que la réservation des candidats ne garantie pas qu\'ils soient promis à votre entreprise. Elle sera confirmée que lorsque les contrats  sont signés et  procédures entièrement réglées conformément aux critères de notre plateforme Üri Canada.  Sachez que l\'arrivée des employés est en fonction de la durée des procédures et du gouvernementales ainsi que le pays de provenance.
    '
]);

            } else {
                echo json_encode(['status' => 'error', 'message' => 'Erreur lors de la commande : ' . $conn->error]);
            }
            exit();
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
    <title>Réservation des Candidats</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-image: url(17.jpg);
            background-size: cover;
            background-attachment: fixed;
            background-position: center;
        }
        .container-fluid {
            padding: 0;
        }
        .row {
            margin: 0;
        }
        .col-md-3 {
            padding: 0;
        }
        .filter-container {
            margin-bottom: 20px;
        }
        .card-columns {
            column-count: 3;
        }
        .card {
            margin-bottom: 20px;
        }
        .card-body input[type="checkbox"] {
            margin-right: 10px;
        }
        #categoryMenu {
            list-style: none;
            padding: 0;
            position: fixed;
            width: 25%;
        }
        #categoryMenu li {
            cursor: pointer;
            padding: 5px 10px;
            margin: 5px 0;
            background-color: #f8f9fa;
            border: 1px solid #ddd;

        }
        #categoryMenu li.active {
            background-color: #007bff;
            color: white;
        }
        .btn-selected {
            background-color: green;
            color: white;
        }
    </style>

</head>
<body>
<div class="container-fluid">
    <div class="row">
        <div style="margin-top: 120px;" class="col-md-3">
            <ul id="categoryMenu" class="list-group">
                <!-- Les catégories seront insérées ici par JavaScript -->
            </ul>
        </div>
        <div class="col-md-9">
            <h1 class="text-center my-4" style="color: white;">Réservation des Candidats</h1>

            <div class="filter-container mb-4">
                <label for="filterSelect" style="color: white;"><strong>Rechercher par Profession :</strong> </label>
                <input type="text" id="filterSelect" class="form-control" placeholder="Entrez la profession">
            </div>

            <div class="card-columns" id="candidatesContainer">
                <!-- Les candidats seront insérés ici par JavaScript -->
            </div>

            <div class="card-columns" id="competencesContainer">
                <!-- Les compétences seront insérées ici par JavaScript -->
            </div>

            <button id="placeOrderBtn" class="btn btn-primary btn-block" data-toggle="modal" data-target="#orderModal" style="font-size: 40px;">Réserver</button>
        </div>
    </div><br><br><br>
</div>

<!-- Modal -->
<div class="modal fade" id="orderModal" tabindex="-1" role="dialog" aria-labelledby="orderModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="orderModalLabel">Détails de la Commande</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="orderForm">
                    <div class="form-group">
                        <label for="nomm">Nom de l'entreprise :</label>
                        <input type="text" class="form-control" id="nomm" required>
                    </div>
                    <div class="form-group">
                        <label for="telephone">Téléphone :</label>
                        <input type="text" class="form-control" id="telephone" required>
                    </div>
                    <div class="form-group">
                        <label for="couriel">Couriel :</label>
                        <input type="email" class="form-control" id="couriel" required>
                    </div>
                    <div class="form-group">
                        <label for="villee">Ville :</label>
                        <input type="text" class="form-control" id="villee" required>
                    </div>
                    <div class="form-group">
                        <label for="orderDate">Date d'embauche:</label>
                        <input type="date" class="form-control" id="orderDate" required>
                    </div>

                    <div class="form-group">
                        <label for="procedures">Voulez vous commencer votre procedure  dès maintenant ?</label>
                        <select class="form-control" id="procedures" required>
                       <option>OUI</option>
                       <option>NON</option>
                    </select>
                    </div>

                    <div class="form-group">
                        <label for="orderExigences">Exigences :</label>
                        <textarea class="form-control" id="orderExigences" required></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                <button type="button" class="btn btn-primary" id="submitOrderBtn">Passer la Commande</button>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
$(document).ready(function() {
    // Chargement des catégories lors du chargement de la page
    $.ajax({
        url: '',
        type: 'POST',
        data: { action: 'fetch_categories' },
        dataType: 'json',
        success: function(response) {
            var categories = response.categories;
            var categoryMenu = $('#categoryMenu');
            categoryMenu.append('<li class="list-group-item active" data-category="ALL">ALL</li>');
            $.each(categories, function(index, category) {
                categoryMenu.append('<li class="list-group-item" data-category="' + category + '">' + category + '</li>');
            });
        }
    });

    // Chargement des données lors du chargement de la page
    loadData('');

    // Filtrage des candidats par profession
    $('#filterSelect').on('input', function() {
        var filterValue = $(this).val();
        loadData(filterValue);
    });

    // Changement de catégorie
    $(document).on('click', '#categoryMenu li', function() {
        $('#categoryMenu li').removeClass('active');
        $(this).addClass('active');
        var selectedCategory = $(this).data('category');
        var filterValue = $('#filterSelect').val();
        loadData(filterValue, selectedCategory);
    });

    // Fonction pour charger les données
    function loadData(filterValue, category = 'ALL') {
        $.ajax({
            url: '',
            type: 'POST',
            data: { action: 'fetch_data', filter: filterValue, category: category },
            dataType: 'json',
            success: function(response) {
                var candidates = response.candidats;
                var competences = response.competences;

                var candidatesContainer = $('#candidatesContainer');
                var competencesContainer = $('#competencesContainer');
                candidatesContainer.empty();
                competencesContainer.empty();

$.each(candidates, function(index, candidat) {
    var card = '<div class="card">' +
        '<div class="card-body">' +
        '<h5 class="card-title">Cand2024' + candidat.id + '</h5>' +
        '<h5 class="card-title">' + candidat.prof + '</h5>' +
        '<p class="card-text">Prenom : ' + candidat.prenom + '</p>' +
        '<p class="card-text">Spécialtité : ' + candidat.special + '</p>' + // Correction de candidat.special à candidat.email
        '<p class="card-text">Expérience : ' + candidat.exp + '</p>' + // Correction de candidat.exp à candidat.telephone
        '<p class="card-text">Langues - Parlé : ' + candidat.parle + ', Écrit : ' + candidat.ecrit + '</p>' + // Correction de candidate.parle à candidat.parle et candidate.ecrit à candidat.ecrit
        '<p class="card-text">Permis de conduire : ' + candidat.permis + '</p>' + // Correction de candidat.permi à candidat.permis
        '<button class="btn btn-primary select-candidate-btn" data-id="' + candidat.id + '">Sélectionner</button>' +
        '</div>' +
        '</div>';
    candidatesContainer.append(card);
});


                $.each(competences, function(index, competence) {
                    var card = '<div class="card">' +
                        '<div class="card-body">' +
                        '<h6 class="card-title">Cand2024' + competence.id + '</h6>' +
                        '<h5 class="card-title">' + competence.skillTitle + '</h5>' +
                        '<p class="card-text">Prénom : ' + competence.prenom + '</p>' +
                        '<p class="card-text">Réferences : ' + competence.references + '</p>' +
                       '<p class="card-text">Langues - Parlé : ' + competence.parle + ', Écrit : ' + competence.ecrit + '</p>' + // Correction de 
                       '<p class="card-text">Permis de conduire : ' + competence.permi + '</p>' +
                        '<button class="btn btn-primary select-competence-btn" data-id="' + competence.id + '">Sélectionner</button>' +
                        '</div>' +
                        '</div>';
                    competencesContainer.append(card);
                });
            }
        });
    }

    var selectedCandidates = [];
    var selectedCompetences = [];

    // Sélection des candidats
    $(document).on('click', '.select-candidate-btn', function() {
        var candidateId = $(this).data('id');
        selectedCandidates.push(candidateId);
        $(this).prop('disabled', true);
    });

    // Sélection des compétences
    $(document).on('click', '.select-competence-btn', function() {
        var competenceId = $(this).data('id');
        selectedCompetences.push(competenceId);
        $(this).prop('disabled', true);
    });

    // Soumission de la commande
    $('#submitOrderBtn').on('click', function() {
        var nomm = $('#nomm').val();
        var telephone = $('#telephone').val();
        var couriel = $('#couriel').val();
        var villee = $('#villee').val();
        var date = $('#orderDate').val();
        var exigences = $('#orderExigences').val();

        $.ajax({
            url: '',
            type: 'POST',
            data: {
                action: 'place_order',
                nomm: nomm,
                telephone: telephone,
                couriel: couriel,
                villee: villee,
                date: date,
                exigences: exigences,
                candidates: selectedCandidates,
                competences: selectedCompetences
            },
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    alert(response.message);
                    $('#orderModal').modal('hide');
                    selectedCandidates = [];
                    selectedCompetences = [];
                    loadData('');
                } else {
                    alert(response.message);
                }
            }
        });
    });
});
</script>
</body>
</html>
