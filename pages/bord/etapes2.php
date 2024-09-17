<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barre de Progression</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f3f3f3;
            padding: 20px;
        }
        .step-wrapper {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }
        .step {
            flex: 1;
            position: relative;
            margin: 10px;
            padding: 20px 10px;
            color: white;
            font-weight: bold;
            background-color: #d3d3d3;
            border-radius: 10px;
            min-height: 200px;
            text-align: center;
            transition: background-color 0.3s, transform 0.3s;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .step.completed {
            background-color: green;
            transform: scale(1.05);
        }
        .step .arrow {
            width: 0;
            height: 0;
            border-left: 20px solid transparent;
            border-right: 20px solid transparent;
            border-top: 20px solid currentColor;
            margin: 10px auto 0;
        }
        .step1 { background-color: #f9a825; }
        .step2 { background-color: #ef6c00; }
        .step3 { background-color: #00897b; }
        .step4 { background-color: #26a69a; }
        .step5 { background-color: #1565c0; }
        .step6 { background-color: #9c27b0; }
        .step.completed .arrow {
            border-top-color: green;
        }
        .details {
            margin-top: 10px;
            background: white;
            color: black;
            padding: 5px;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .step-title {
            margin-top: 10px;
            font-size: 1.1em;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="header">
        <h2>Les délais varient selon le pays de résidence et le pays de diplomation.</h2>
        <p>Le candidat n’est pas soumis aux étapes 3 et 4 s’il détient ou détiendra un permis de travail ouvert.</p>
    </div>

    <div class="step-wrapper">
        <div class="step step1 completed">
            <div>Étape 1</div>
            <div class="arrow"></div>
            <div class="step-title">SÉLECTION</div>
        </div>
        <div class="step step2">
            <div>Étape 2</div>
            <div class="arrow"></div>
            <div class="step-title">OBTENIR UN CONTRAT</div>
            <div class="details">Délai 1 à 3 mois</div>
        </div>
        <div class="step step3">
            <div>Étape 3</div>
            <div class="arrow"></div>
            <div class="step-title">OBTENIR L'ADMISSIBILITÉ</div>
            <div class="details">Délai 2 à 4 mois</div>
        </div>
        <div class="step step4">
            <div>Étape 4</div>
            <div class="arrow"></div>
            <div class="step-title">DÉMARCHES POUR OBTENIR LE CAQ</div>
            <div class="details">Délai 3 à 6 mois</div>
        </div>
        <div class="step step5">
            <div>Étape 5</div>
            <div class="arrow"></div>
            <div class="step-title">DÉMARCHES POUR OBTENTION DU PERMIS DE TRAVAIL</div>
            <div class="details">Délai 2 à 4 mois</div>
        </div>
        <div class="step step6">
            <div>Étape 6</div>
            <div class="arrow"></div>
            <div class="step-title">INTÉGRATION EN EMPLOI</div>
        </div>
    </div>
</div>

<script>
    // Exemple pour marquer une étape comme complétée
    function completeStep(stepNumber) {
        const steps = document.querySelectorAll('.step');
        steps[stepNumber - 1].classList.add('completed');
    }

    // Appel de la fonction pour marquer les étapes comme complétées
    completeStep(1);
</script>

</body>
</html>
