<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8">
        <title>IZISHOPE</title>
        <meta content="width=device-width, initial-scale=1.0" name="viewport">
        <meta content="" name="keywords">
        <meta content="" name="description">

        <!-- Google Web Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&family=Raleway:wght@600;800&display=swap" rel="stylesheet"> 
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">

        <!-- Icon Font Stylesheet -->
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css"/>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

        <!-- Libraries Stylesheet -->
        <link href="lib/lightbox/css/lightbox.min.css" rel="stylesheet">
        <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">


        <!-- Customized Bootstrap Stylesheet -->
        <link href="css/bootstrap.min.css" rel="stylesheet">

        <!-- Template Stylesheet -->
        <link href="css/style.css" rel="stylesheet">
    </head>

    <body>

        <!-- Spinner Start -->
        <div id="spinner" class="show w-100 vh-100 bg-white position-fixed translate-middle top-50 start-50  d-flex align-items-center justify-content-center">
            <div class="spinner-grow text-primary" role="status"></div>
        </div>
        <!-- Spinner End -->


        <!-- Navbar start -->
        <div class="container-fluid fixed-top">
            <div class="container topbar bg-primary d-none d-lg-block">
                <div class="d-flex justify-content-between">
                    <div class="top-info ps-2">
                        <small class="me-3"><i class="fas fa-map-marker-alt me-2 text-secondary"></i> <a href="#" class="text-white">123 Quebeck, Canada</a></small>
                        <small class="me-3"><i class="fas fa-envelope me-2 text-secondary"></i><a href="#" class="text-white">contact@uricanada.com</a></small>
                    </div>
                    <div class="top-link pe-2">
                        <a href="#" class="text-white"><small class="text-white mx-2">Courtier immobilier</small>/</a>
                        <a href="#" class="text-white"><small class="text-white mx-2">Entrepreneur</small>/</a>
                        <a href="#" class="text-white"><small class="text-white ms-2">Commerçant</small></a>
                    </div>
                </div>
            </div>
            <div class="container px-0">
                <nav class="navbar navbar-light bg-white navbar-expand-xl">
                    <a href="index.html" class="navbar-brand"><h1 class="text-primary display-6">IZISHOPE</h1></a>
                    <button class="navbar-toggler py-2 px-3" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                        <span class="fa fa-bars text-primary"></span>
                    </button>
<div class="collapse navbar-collapse bg-white" id="navbarCollapse">
    <div class="navbar-nav mx-auto">
        <a href="index.html" class="nav-item nav-link active">Accueil</a>
        <a href="shop.html" class="nav-item nav-link">Boutique</a>
        <a href="shop-detail.html" class="nav-item nav-link">Détails de la boutique</a>
        <div class="nav-item dropdown">
            <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Pages</a>
            <div class="dropdown-menu m-0 bg-secondary rounded-0">
                <a href="cart.php" class="dropdown-item">Panier</a>
                <a href="checkout.html" class="dropdown-item">Paiement</a>
                <a href="testimonial.html" class="dropdown-item">Témoignages</a>
            </div>
        </div>
        <a href="contact.html" class="nav-item nav-link">Contact</a>
    </div>

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

$userId = 1; // Remplacez par l'ID de l'utilisateur connecté
$countCartQuery = "SELECT COUNT(*) AS cart_count FROM cart WHERE user_id = ?";
$stmt = $conn->prepare($countCartQuery);
$stmt->bind_param('i', $userId);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$cartCount = $row['cart_count'];

?>
    <div class="d-flex m-3 me-0">
        <button class="btn-search btn border border-secondary btn-md-square rounded-circle bg-white me-4" data-bs-toggle="modal" data-bs-target="#searchModal">
            <i class="fas fa-search text-primary"></i>
        </button>
        <a href="cart.php" class="position-relative me-4 my-auto">
            <i class="fa fa-shopping-bag fa-2x"></i>
            <span class="position-absolute bg-secondary rounded-circle d-flex align-items-center justify-content-center text-dark px-1" style="top: -5px; left: 15px; height: 20px; min-width: 20px;"><?php echo $cartCount; ?></span>
        </a>
        <a href="cart.php" class="my-auto">
            <i class="fas fa-user fa-2x"></i>
        </a>
    </div>
</div>

                </nav>
            </div>
        </div>
        <!-- Navbar End -->


        <!-- Modal Search Start -->
        <div class="modal fade" id="searchModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-fullscreen">
                <div class="modal-content rounded-0">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Recherche</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body d-flex align-items-center">
                        <div class="input-group w-75 mx-auto d-flex">
                            <input type="search" class="form-control p-3" placeholder="keywords" aria-describedby="search-icon-1">
                            <span id="search-icon-1" class="input-group-text p-3"><i class="fa fa-search"></i></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal Search End -->
    







    <style>
        .hero-header {
            background-color: #f8f9fa;
            padding: 20px 0;
        }

        .ticker-wrap {
            width: 100%;
            overflow: hidden;
            background-color: #f1f1f1;
            padding: 10px 0;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            position: relative;
            display: flex;
            justify-content: center;
        }

        .ticker {
            display: flex;
            animation: ticker 20s linear infinite;
        }

        .ticker__item {
            flex: 0 0 auto;
            padding: 0 2rem;
            white-space: nowrap;
        }

        .ticker__item img {
            width: 200px; /* Largeur des images */
            height: auto;
            transition: transform 0.5s ease-in-out;
            opacity: 0.6; /* Réduire l'opacité des images non actives */
        }

        .ticker__item img.active {
            transform: scale(1.2);
            opacity: 1; /* Opacité maximale pour l'image active */
        }

        @keyframes ticker {
            0% {
                transform: translateX(0);
            }
            100% {
                transform: translateX(-50%);
            }
        }

        .banner {
            background-color: #f8b400;
            padding: 20px;
            text-align: center;
            color: white;
            font-size: 24px;
            font-weight: bold;
            position: relative;
            margin: 20px 0;
        }

        .banner button {
            background-color: #ff4500;
            border: none;
            color: white;
            padding: 10px 20px;
            cursor: pointer;
            font-size: 18px;
            position: absolute;
            right: 20px;
            top: 50%;
            transform: translateY(-50%);
        }

        .banner p {
            margin: 0;
        }

        .banner img {
            width: 200px;
            height: auto;
            vertical-align: middle;
            margin-left: 60%;
        }
    </style>

<div class="container-fluid py-5 mb-5 hero-header">
    <div class="ticker-wrap">
        <div class="ticker">
            <div class="ticker__item"><img src="img/1.jpg" alt="Première image"></div>
            <div class="ticker__item"><img src="img/2.jpg" alt="Deuxième image"></div>
            <div class="ticker__item"><img src="img/3.jpg" alt="Troisième image"></div>
            <div class="ticker__item"><img src="img/4.jpg" alt="Quatrième image"></div>
            <div class="ticker__item"><img src="img/5.jpg" alt="Cinquième image"></div>
            <div class="ticker__item"><img src="img/6.jpg" alt="Sixième image"></div>
            <!-- Ajoutez plus d'images ici -->
        </div>
        <div class="ticker">
            <div class="ticker__item"><img src="img/7.jpg" alt="Première image"></div>
            <div class="ticker__item"><img src="img/8.jpg" alt="Deuxième image"></div>
            <div class="ticker__item"><img src="img/9.jpg" alt="Troisième image"></div>
            <div class="ticker__item"><img src="img/10.jpg" alt="Quatrième image"></div>
            <div class="ticker__item"><img src="img/1.jpg" alt="Cinquième image"></div>
            <div class="ticker__item"><img src="img/2.jpg" alt="Sixième image"></div>
            <!-- Ajoutez plus d'images ici -->
        </div>
    </div>

    <div class="banner">
        <img src="img/6.jpg" alt="Offre spéciale">
        <p style="margin-top: -50px;">Votre lit capitonné 70% de réduction</p>
        <button>Shop now</button>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    const images = $('.ticker__item img');
    const tickerWidth = $('.ticker-wrap').width();
    const imageWidth = images.eq(0).outerWidth(true);

    function updateActiveImage() {
        images.removeClass('active');
        let middlePoint = tickerWidth / 2;
        let closestIndex = 0;
        let closestDistance = Math.abs(images.eq(0).offset().left - middlePoint);

        images.each(function(index) {
            let imageMidPoint = $(this).offset().left + $(this).width() / 2;
            let distance = Math.abs(imageMidPoint - middlePoint);
            if (distance < closestDistance) {
                closestDistance = distance;
                closestIndex = index;
            }
        });

        images.eq(closestIndex).addClass('active');
    }

    setInterval(updateActiveImage, 100); // Vérifiez régulièrement pour mettre à jour l'image active

    updateActiveImage(); // Appel initial pour définir la première image active
});
</script>















        <!-- Hero End -->


        <!-- Featurs Section Start -->
<div class="container-fluid features py-5">
    <div class="container py-5">
        <div class="row g-4">
            <div class="col-md-6 col-lg-3">
                <div class="features-item text-center rounded bg-light p-4">
                    <div class="features-icon btn-square rounded-circle bg-secondary mb-5 mx-auto">
                        <i class="fas fa-car-side fa-3x text-white"></i>
                    </div>
                    <div class="features-content text-center">
                        <h5>Livraison Gratuite</h5>
                        <p class="mb-0">Gratuit pour toute commande de plus de 300€</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="features-item text-center rounded bg-light p-4">
                    <div class="features-icon btn-square rounded-circle bg-secondary mb-5 mx-auto">
                        <i class="fas fa-user-shield fa-3x text-white"></i>
                    </div>
                    <div class="features-content text-center">
                        <h5>Paiement Sécurisé</h5>
                        <p class="mb-0">Paiement 100% sécurisé</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="features-item text-center rounded bg-light p-4">
                    <div class="features-icon btn-square rounded-circle bg-secondary mb-5 mx-auto">
                        <i class="fas fa-exchange-alt fa-3x text-white"></i>
                    </div>
                    <div class="features-content text-center">
                        <h5>Retour Sous 30 Jours</h5>
                        <p class="mb-0">Garantie de remboursement sous 30 jours</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="features-item text-center rounded bg-light p-4">
                    <div class="features-icon btn-square rounded-circle bg-secondary mb-5 mx-auto">
                        <i class="fa fa-phone-alt fa-3x text-white"></i>
                    </div>
                    <div class="features-content text-center">
                        <h5>Support 24/7</h5>
                        <p class="mb-0">Support rapide et disponible à tout moment</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

        <!-- Featurs Section End -->




















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
// Récupérer les catégories
$categoriesQuery = "SELECT * FROM categories22";
$categoriesResult = $conn->query($categoriesQuery);

if (!$categoriesResult) {
    die("Erreur lors de la récupération des catégories : " . $conn->error);
}

// Récupérer les services
$servicesQuery = "SELECT * FROM servicess";
$servicesResult = $conn->query($servicesQuery);

if (!$servicesResult) {
    die("Erreur lors de la récupération des services : " . $conn->error);
}

$servicesByCategory = [];

// Organiser les services par sous-catégorie
while ($service = $servicesResult->fetch_assoc()) {
    $servicesByCategory[$service['category_id']][] = $service;
}

// Ajouter un service au panier
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    $serviceId = $_POST['service_id'];
    $userId = 1; // Remplacez par l'ID de l'utilisateur connecté

    $cartInsertQuery = "INSERT INTO cart (user_id, service_id, quantity) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($cartInsertQuery);
    $quantity = 1;
    $stmt->bind_param('iii', $userId, $serviceId, $quantity);

    if ($stmt->execute()) {
        echo "<script>alert('Service ajouté au panier avec succès!');</script>";
    } else {
        echo "<script>alert('Erreur lors de l\'ajout au panier.');</script>";
    }
}
?>

<!-- Fruits Shop Start -->
<div class="container-fluid fruite py-5">
    <div class="container py-5">
        <div class="tab-class text-center">
            <div class="row g-4">
                <div class="col-lg-4 text-start">
                    <h1>Liste des produits</h1>
                </div>
                <div class="col-lg-8 text-end">
                    <ul class="nav nav-pills d-inline-flex text-center mb-5">
                        <li class="nav-item">
                            <a class="d-flex m-2 py-2 bg-light rounded-pill active" data-bs-toggle="pill" href="#tab-all">
                                <span class="text-dark" style="width: 130px;">Tous les Produits</span>
                            </a>
                        </li>
                        <?php while ($category = $categoriesResult->fetch_assoc()): ?>
                        <li class="nav-item">
                            <a class="d-flex py-2 m-2 bg-light rounded-pill" data-bs-toggle="pill" href="#tab-<?php echo $category['id']; ?>">
                                <span class="text-dark" style="width: 130px;"><?php echo htmlspecialchars($category['sous_categorie']); ?></span>
                            </a>
                        </li>
                        <?php endwhile; ?>
                    </ul>
                </div>
            </div>

            <div class="tab-content">
                <div id="tab-all" class="tab-pane fade show p-0 active">
                    <div class="row g-4">
                        <div class="col-lg-12">
                            <div class="row g-4">
                                <?php
                                // Afficher tous les services
                                foreach ($servicesByCategory as $services):
                                    foreach ($services as $service):
                                        $images = explode('; ', $service['images']);
                                ?>
                                <div class="col-md-6 col-lg-4 col-xl-3">
                                    <div class="rounded position-relative fruite-item">
                                        <div id="carousel-<?php echo $service['id']; ?>" class="carousel slide" data-bs-ride="carousel">
                                            <div class="carousel-inner">
                                                <?php foreach ($images as $index => $image): ?>
                                                <div class="carousel-item <?php echo $index === 0 ? 'active' : ''; ?>">
                                                    <img src="https://admin.izishope.com/uploads/services/<?php echo htmlspecialchars($image); ?>" class="d-block w-100" alt="">
                                                </div>
                                                <?php endforeach; ?>
                                            </div>
                                            <button class="carousel-control-prev" type="button" data-bs-target="#carousel-<?php echo $service['id']; ?>" data-bs-slide="prev">
                                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                                <span class="visually-hidden">Previous</span>
                                            </button>
                                            <button class="carousel-control-next" type="button" data-bs-target="#carousel-<?php echo $service['id']; ?>" data-bs-slide="next">
                                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                                <span class="visually-hidden">Next</span>
                                            </button>
                                        </div>
                                        <div class="text-white bg-secondary px-3 py-1 rounded position-absolute" style="top: 10px; left: 10px;">
                                            <?php echo htmlspecialchars($service['name']); ?>
                                        </div>
                                        <div class="p-4 border border-secondary border-top-0 rounded-bottom">
                                            <h4><?php echo htmlspecialchars($service['name']); ?></h4>
                                            <p><?php echo htmlspecialchars($service['description']); ?></p>
                                            <div class="d-flex justify-content-between flex-lg-wrap">
                                                <p class="text-dark fs-5 fw-bold mb-0">$<?php echo htmlspecialchars($service['price']); ?> / kg</p>
                                                <form method="post" action="">
                                                    <input type="hidden" name="service_id" value="<?php echo $service['id']; ?>">
                                                    <button type="submit" name="add_to_cart" class="btn border border-secondary rounded-pill px-3 text-primary">
                                                        <i class="fa fa-shopping-bag me-2 text-primary"></i> Ajouter au panier
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <?php
                // Afficher les services par sous-catégorie
                $categoriesResult->data_seek(0); // Revenir au début du résultat
                while ($category = $categoriesResult->fetch_assoc()):
                ?>
                <div id="tab-<?php echo $category['id']; ?>" class="tab-pane fade show p-0">
                    <div class="row g-4">
                        <div class="col-lg-12">
                            <div class="row g-4">
                                <?php
                                if (isset($servicesByCategory[$category['id']])):
                                    foreach ($servicesByCategory[$category['id']] as $service):
                                        $images = explode('; ', $service['images']);
                                ?>
                                <div class="col-md-6 col-lg-4 col-xl-3">
                                    <div class="rounded position-relative fruite-item">
                                        <div id="carousel-<?php echo $service['id']; ?>" class="carousel slide" data-bs-ride="carousel">
                                            <div class="carousel-inner">
                                                <?php foreach ($images as $index => $image): ?>
                                                <div class="carousel-item <?php echo $index === 0 ? 'active' : ''; ?>">
                                                    <img src="https://admin.izishope.com/uploads/services/<?php echo htmlspecialchars($image); ?>" class="d-block w-100" alt="">
                                                </div>
                                                <?php endforeach; ?>
                                            </div>
                                            <button class="carousel-control-prev" type="button" data-bs-target="#carousel-<?php echo $service['id']; ?>" data-bs-slide="prev">
                                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                                <span class="visually-hidden">Previous</span>
                                            </button>
                                            <button class="carousel-control-next" type="button" data-bs-target="#carousel-<?php echo $service['id']; ?>" data-bs-slide="next">
                                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                                <span class="visually-hidden">Next</span>
                                            </button>
                                        </div>
                                        <div class="text-white bg-secondary px-3 py-1 rounded position-absolute" style="top: 10px; left: 10px;">
                                            <?php echo htmlspecialchars($service['name']); ?>
                                        </div>
                                        <div class="p-4 border border-secondary border-top-0 rounded-bottom">
                                            <h4><?php echo htmlspecialchars($service['name']); ?></h4>
                                            <p><?php echo htmlspecialchars($service['description']); ?></p>
                                            <div class="d-flex justify-content-between flex-lg-wrap">
                                                <p class="text-dark fs-5 fw-bold mb-0">$<?php echo htmlspecialchars($service['price']); ?> / kg</p>
                                                <form method="post" action="">
                                                    <input type="hidden" name="service_id" value="<?php echo $service['id']; ?>">
                                                    <button type="submit" name="add_to_cart" class="btn border border-secondary rounded-pill px-3 text-primary">
                                                        <i class="fa fa-shopping-bag me-2 text-primary"></i> Add to cart
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>
        </div>
    </div>
</div>
<!-- Fruits Shop End -->












































        <!-- Featurs Start -->
        <div class="container-fluid service py-5">
            <div class="container py-5">
                <div class="row g-4 justify-content-center">
                    <div class="col-md-6 col-lg-4">
                        <a href="#">
                            <div class="service-item bg-secondary rounded border border-secondary">
                                <img src="img/7.jpg" class="img-fluid rounded-top w-100" alt="">
                                <div class="px-4 rounded-bottom">
                                    <div class="service-content bg-primary text-center p-4 rounded">
                                        <h5 class="text-white">Appartement moderne</h5>
                                        <h3 class="mb-0">20% OFF</h3>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <a href="#">
                            <div class="service-item bg-dark rounded border border-dark">
                                <img src="img/21.jpg" class="img-fluid rounded-top w-100" alt="">
                                <div class="px-4 rounded-bottom">
                                    <div class="service-content bg-light text-center p-4 rounded">
                                        <h5 class="text-primary">Voiture en vente</h5>
                                        <h3 class="mb-0">31% OFF</h3>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <a href="#">
                            <div class="service-item bg-primary rounded border border-primary">
                                <img src="img/23.jpeg" class="img-fluid rounded-top w-100" alt="">
                                <div class="px-4 rounded-bottom">
                                    <div class="service-content bg-secondary text-center p-4 rounded">
                                        <h5 class="text-white">Iphone</h5>
                                        <h3 class="mb-0">41% OFF</h3>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <!-- Featurs End -->













<?php
// Connexion à la base de données
$servername = "4w0vau.myd.infomaniak.com";
$username = "4w0vau_dreamize";
$password = "Pidou2016";
$dbname = "4w0vau_dreamize";

$conn = new mysqli($servername, $username, $password, $dbname);

// Vérifier la connexion
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Ajouter un service au panier
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cartx'])) {
    $serviceId = $_POST['service_id'];
    $userId = 1; // Remplacez par l'ID de l'utilisateur connecté

    $cartInsertQuery = "INSERT INTO cart (user_id, service_id, quantity) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($cartInsertQuery);
    $quantity = 1;
    $stmt->bind_param('iii', $userId, $serviceId, $quantity);

    if ($stmt->execute()) {
        echo "<script>alert('Service ajouté au panier avec succès!');</script>";
    } else {
        echo "<script>alert('Erreur lors de l\'ajout au panier.');</script>";
    }
}

// Récupérer les légumes (services)
$vegetablesQuery = "SELECT * FROM servicess";
$vegetablesResult = $conn->query($vegetablesQuery);

if (!$vegetablesResult) {
    die("Erreur lors de la récupération des services : " . $conn->error);
}
?>

<!-- Vegetables Shop Start -->
<div class="container-fluid vesitable py-5">
    <div class="container py-5">
        <h1 class="mb-0">Produits de seconde gamme</h1>
        <div class="owl-carousel vegetable-carousel justify-content-center">
            <?php while ($vegetable = $vegetablesResult->fetch_assoc()): ?>
            <?php $images = explode('; ', $vegetable['images']); ?>
            <div class="border border-primary rounded position-relative vesitable-item">
                <div class="vesitable-img">
                    <div id="carousel-veg-<?php echo $vegetable['id']; ?>" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            <?php foreach ($images as $index => $image): ?>
                            <div class="carousel-item <?php echo $index === 0 ? 'active' : ''; ?>">
                                <img src="https://admin.izishope.com/uploads/services/<?php echo htmlspecialchars($image); ?>" class="img-fluid w-100 rounded-top" alt="<?php echo htmlspecialchars($vegetable['name']); ?>">
                            </div>
                            <?php endforeach; ?>
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#carousel-veg-<?php echo $vegetable['id']; ?>" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#carousel-veg-<?php echo $vegetable['id']; ?>" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    </div>
                </div>
                <div class="text-white bg-primary px-3 py-1 rounded position-absolute" style="top: 10px; right: 10px;">
                    <?php echo htmlspecialchars($vegetable['name']); ?>
                </div>
                <div class="p-4 rounded-bottom">
                    <h4><?php echo htmlspecialchars($vegetable['name']); ?></h4>
                    <p><?php echo htmlspecialchars($vegetable['description']); ?></p>
                    <div class="d-flex justify-content-between flex-lg-wrap">
                        <p class="text-dark fs-5 fw-bold mb-0">$<?php echo htmlspecialchars($vegetable['price']); ?> / kg</p>
                        <form method="post" action="">
                            <input type="hidden" name="service_id" value="<?php echo $vegetable['id']; ?>">
                            <button type="submit" name="add_to_cartx" class="btn border border-secondary rounded-pill px-3 text-primary">
                                <i class="fa fa-shopping-bag me-2 text-primary"></i> Add to cart
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </div>
</div>
<!-- Vegetables Shop End -->

<?php
// Fermer la connexion
$conn->close();
?>













        <!-- Vesitable Shop End -->


        <!-- Banner Section Start-->
        <div class="container-fluid banner bg-secondary my-5">
            <div class="container py-5">
                <div class="row g-4 align-items-center">
                    <div class="col-lg-6">
                        <div class="py-4">
                            <h1 class="display-3 text-white">Produit en promotion</h1>
                            <p class="fw-normal display-3 text-dark mb-4">Dans nos boutiques</p>
                            <p class="mb-4 text-dark">Vous pouvez passer vos commandes avec fièrté.</p>
                            <a href="#" class="banner-btn btn border-2 border-white rounded-pill text-dark py-3 px-5">Commander maintenant</a>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="position-relative">
                            <img style="width: 80%;" src="img/25.jpg" class="img-fluid w-100 rounded" alt="">
                            <div class="d-flex align-items-center justify-content-center bg-white rounded-circle position-absolute" style="width: 140px; height: 140px; top: 0; left: 0;">
                                <h1 style="font-size: 100px;">1</h1>
                                <div class="d-flex flex-column">
                                    <span class="h2 mb-0">50$</span>
                                    <span class="h4 text-muted mb-0">kg</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Banner Section End -->


<!-- Bestsaler Product Start -->

























<?php
// Connexion à la base de données
$servername = "4w0vau.myd.infomaniak.com";
$username = "4w0vau_dreamize";
$password = "Pidou2016";
$dbname = "4w0vau_dreamize";

$conn = new mysqli($servername, $username, $password, $dbname);

// Vérifier la connexion
if ($conn->connect_error) {
    die("Connexion échouée: " . $conn->connect_error);
}

// Ajouter un service au panier
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cartt'])) {
    $serviceId = $_POST['service_id'];
    $userId = 1; // Remplacez par l'ID de l'utilisateur connecté

    $cartInsertQuery = "INSERT INTO cart (user_id, service_id, quantity) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($cartInsertQuery);
    $quantity = 1;
    $stmt->bind_param('iii', $userId, $serviceId, $quantity);

    if ($stmt->execute()) {
        echo "<script>alert('Service ajouté au panier avec succès!');</script>";
    } else {
        echo "<script>alert('Erreur lors de l\'ajout au panier.');</script>";
    }
}
// Requête pour récupérer les produits par catégorie
$sql = "
SELECT servicess.*, categories22.sous_categorie 
FROM servicess 
LEFT JOIN categories22 ON servicess.category_id = categories22.id
ORDER BY categories22.sous_categorie";

$result = $conn->query($sql);

$products_by_category = [];

// Organiser les produits par catégorie
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $products_by_category[$row['sous_categorie']][] = $row;
    }
}

// Fermer la connexion
$conn->close();
?>



    <!-- Bestsaler Product Start -->
    <div class="container-fluid py-5">
        <div class="container py-5">
            <div class="text-center mx-auto mb-5" style="max-width: 700px;">
                <h1 class="display-4">Produits de troisième gamme</h1>
                <p>Visitez et commandez vos produits de troisième  gamme.</p>
            </div>

            <?php foreach ($products_by_category as $sous_categorie => $products): ?>
                <h2 class="text-center my-5"><?php echo htmlspecialchars($sous_categorie); ?></h2>
                <div class="row g-4">
                    <?php foreach ($products as $product): 
                        // Récupérer la première image de la colonne images
                        $images = explode(';', $product['images']);
                        $main_image = trim($images[0]);
                    ?>
                    

                    <div class="col-lg-6 col-xl-4">
                        <div class="p-4 rounded bg-light">
                            <div class="row align-items-center">
                                <div class="col-6">
                                    <img src="https://admin.izishope.com/uploads/services/<?php echo htmlspecialchars($main_image); ?>" class="img-fluid rounded-circle w-100" alt="">
                                </div>
                                <div class="col-6">
                                    <a href="#" class="h5"><?php echo htmlspecialchars($product['name']); ?></a>
                                    <p class="h6"><?php echo htmlspecialchars($product['description']); ?></p>
                                    <div class="d-flex my-3">
                                        <i class="fas fa-star text-primary"></i>
                                        <i class="fas fa-star text-primary"></i>
                                        <i class="fas fa-star text-primary"></i>
                                        <i class="fas fa-star text-primary"></i>
                                        <i class="fas fa-star"></i>
                                    </div>
                                    <h4 class="mb-3"><?php echo number_format($product['price'], 2) . ' $'; ?></h4>
                                    
                                    <!-- Formulaire pour ajouter au panier -->
                                    <form method="post" action="">
                                        <input type="hidden" name="service_id" value="<?php echo $product['id']; ?>">
                                        <button type="submit" name="add_to_cartt" class="btn border border-secondary rounded-pill px-3 text-primary">
                                            <i class="fa fa-shopping-bag me-2 text-primary"></i> Ajouter au panier
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>










    <!-- Bestsaler Product End -->

    <script src="path/to/your/bootstrap.bundle.min.js"></script>
    <script src="path/to/your/fontawesome.min.js"></script>
</body>
</html>

    <!-- Bestsaler Product End -->

    <script src="path/to/your/bootstrap.bundle.min.js"></script>
    <script src="path/to/your/fontawesome.min.js"></script>
</body>
</html>


        <!-- Bestsaler Product End -->


        <!-- Fact Start -->
<div class="container-fluid py-5">
    <div class="container">
        <div class="bg-light p-5 rounded">
            <div class="row g-4 justify-content-center">
                <div class="col-md-6 col-lg-6 col-xl-3">
                    <div class="counter bg-white rounded p-5">
                        <i class="fa fa-users text-secondary"></i>
                        <h4>Clients satisfaits</h4>
                        <h1>1963</h1>
                    </div>
                </div>
                <div class="col-md-6 col-lg-6 col-xl-3">
                    <div class="counter bg-white rounded p-5">
                        <i class="fa fa-users text-secondary"></i>
                        <h4>Qualité du service</h4>
                        <h1>99%</h1>
                    </div>
                </div>
                <div class="col-md-6 col-lg-6 col-xl-3">
                    <div class="counter bg-white rounded p-5">
                        <i class="fa fa-users text-secondary"></i>
                        <h4>Certificats de qualité</h4>
                        <h1>33</h1>
                    </div>
                </div>
                <div class="col-md-6 col-lg-6 col-xl-3">
                    <div class="counter bg-white rounded p-5">
                        <i class="fa fa-users text-secondary"></i>
                        <h4>Produits disponibles</h4>
                        <h1>789</h1>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

        <!-- Fact Start -->

<div class="container-fluid testimonial py-5">
    <div class="container py-5">
        <div class="testimonial-header text-center">
            <h4 class="text-primary">Nos Témoignages</h4>
            <h1 class="display-5 mb-5 text-dark">Ce que disent nos clients !</h1>
        </div>
        <div class="owl-carousel testimonial-carousel">
            <div class="testimonial-item img-border-radius bg-light rounded p-4">
                <div class="position-relative">
                    <i class="fa fa-quote-right fa-2x text-secondary position-absolute" style="bottom: 30px; right: 0;"></i>
                    <div class="mb-4 pb-4 border-bottom border-secondary">
                        <p class="mb-0">Lorem Ipsum est simplement un texte factice de l'industrie de l'impression. Il a été le texte factice standard de l'industrie depuis les années 1500.</p>
                    </div>
                    <div class="d-flex align-items-center flex-nowrap">
                        <div class="bg-secondary rounded">
                            <img src="img/testimonial-1.jpg" class="img-fluid rounded" style="width: 100px; height: 100px;" alt="">
                        </div>
                        <div class="ms-4 d-block">
                            <h4 class="text-dark">Jean Dupont</h4>
                            <p class="m-0 pb-3">Directeur</p>
                            <div class="d-flex pe-5">
                                <i class="fas fa-star text-primary"></i>
                                <i class="fas fa-star text-primary"></i>
                                <i class="fas fa-star text-primary"></i>
                                <i class="fas fa-star text-primary"></i>
                                <i class="fas fa-star"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="testimonial-item img-border-radius bg-light rounded p-4">
                <div class="position-relative">
                    <i class="fa fa-quote-right fa-2x text-secondary position-absolute" style="bottom: 30px; right: 0;"></i>
                    <div class="mb-4 pb-4 border-bottom border-secondary">
                        <p class="mb-0">Lorem Ipsum est simplement un texte factice de l'industrie de l'impression. Il a été le texte factice standard de l'industrie depuis les années 1500.</p>
                    </div>
                    <div class="d-flex align-items-center flex-nowrap">
                        <div class="bg-secondary rounded">
                            <img src="img/testimonial-1.jpg" class="img-fluid rounded" style="width: 100px; height: 100px;" alt="">
                        </div>
                        <div class="ms-4 d-block">
                            <h4 class="text-dark">Marie Curie</h4>
                            <p class="m-0 pb-3">Scientifique</p>
                            <div class="d-flex pe-5">
                                <i class="fas fa-star text-primary"></i>
                                <i class="fas fa-star text-primary"></i>
                                <i class="fas fa-star text-primary"></i>
                                <i class="fas fa-star text-primary"></i>
                                <i class="fas fa-star text-primary"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="testimonial-item img-border-radius bg-light rounded p-4">
                <div class="position-relative">
                    <i class="fa fa-quote-right fa-2x text-secondary position-absolute" style="bottom: 30px; right: 0;"></i>
                    <div class="mb-4 pb-4 border-bottom border-secondary">
                        <p class="mb-0">Lorem Ipsum est simplement un texte factice de l'industrie de l'impression. Il a été le texte factice standard de l'industrie depuis les années 1500.</p>
                    </div>
                    <div class="d-flex align-items-center flex-nowrap">
                        <div class="bg-secondary rounded">
                            <img src="img/testimonial-1.jpg" class="img-fluid rounded" style="width: 100px; height: 100px;" alt="">
                        </div>
                        <div class="ms-4 d-block">
                            <h4 class="text-dark">Paul Martin</h4>
                            <p class="m-0 pb-3">Entrepreneur</p>
                            <div class="d-flex pe-5">
                                <i class="fas fa-star text-primary"></i>
                                <i class="fas fa-star text-primary"></i>
                                <i class="fas fa-star text-primary"></i>
                                <i class="fas fa-star text-primary"></i>
                                <i class="fas fa-star text-primary"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


        <!-- Tastimonial End -->


        <!-- Footer Start -->
<div class="container-fluid bg-dark text-white-50 footer pt-5 mt-5">
    <div class="container py-5">
        <div class="pb-4 mb-4" style="border-bottom: 1px solid rgba(226, 175, 24, 0.5) ;">
            <div class="row g-4">
                <div class="col-lg-3">
                    <a href="#">
                        <h1 class="text-primary mb-0">IZISHOPE</h1>
                        <p class="text-secondary mb-0">Produits frais</p>
                    </a>
                </div>
                <div class="col-lg-6">
                    <div class="position-relative mx-auto">
                        <input class="form-control border-0 w-100 py-3 px-4 rounded-pill" type="number" placeholder="Votre Email">
                        <button type="submit" class="btn btn-primary border-0 border-secondary py-3 px-4 position-absolute rounded-pill text-white" style="top: 0; right: 0;">S'abonner</button>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="d-flex justify-content-end pt-3">
                        <a class="btn btn-outline-secondary me-2 btn-md-square rounded-circle" href=""><i class="fab fa-twitter"></i></a>
                        <a class="btn btn-outline-secondary me-2 btn-md-square rounded-circle" href=""><i class="fab fa-facebook-f"></i></a>
                        <a class="btn btn-outline-secondary me-2 btn-md-square rounded-circle" href=""><i class="fab fa-youtube"></i></a>
                        <a class="btn btn-outline-secondary btn-md-square rounded-circle" href=""><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
            </div>
        </div>
        <div class="row g-5">
            <div class="col-lg-3 col-md-6">
                <div class="footer-item">
                    <h4 class="text-light mb-3">Pourquoi les gens nous aiment !</h4>
                    <p class="mb-4">La mise en page, restant essentiellement inchangée. Elle a été popularisée dans les années 1960 avec des versions comme Aldus PageMaker incluant des passages de Lorem Ipsum.</p>
                    <a href="" class="btn border-secondary py-2 px-4 rounded-pill text-primary">En savoir plus</a>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="d-flex flex-column text-start footer-item">
                    <h4 class="text-light mb-3">Infos Boutique</h4>
                    <a class="btn-link" href="">À propos de nous</a>
                    <a class="btn-link" href="">Nous contacter</a>
                    <a class="btn-link" href="">Politique de confidentialité</a>
                    <a class="btn-link" href="">Termes & Conditions</a>
                    <a class="btn-link" href="">Politique de retour</a>
                    <a class="btn-link" href="">FAQs & Aide</a>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="d-flex flex-column text-start footer-item">
                    <h4 class="text-light mb-3">Compte</h4>
                    <a class="btn-link" href="">Mon compte</a>
                    <a class="btn-link" href="">Détails de la boutique</a>
                    <a class="btn-link" href="">Panier</a>
                    <a class="btn-link" href="">Liste de souhaits</a>
                    <a class="btn-link" href="">Historique des commandes</a>
                    <a class="btn-link" href="">Commandes internationales</a>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="footer-item">
                    <h4 class="text-light mb-3">Contact</h4>
                    <p>Adresse : 1429 Netus Rd, NY 48247</p>
                    <p>Email : Example@gmail.com</p>
                    <p>Téléphone : +0123 4567 8910</p>
                    <p>Paiement accepté</p>
                    <img src="img/payment.png" class="img-fluid" alt="">
                </div>
            </div>
        </div>
    </div>
</div>

        <!-- Footer End -->

        <!-- Copyright Start -->
<div class="container-fluid copyright bg-dark py-4">
    <div class="container">
        <div class="row">
            <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                <span class="text-light"><a href="#"><i class="fas fa-copyright text-light me-2"></i>IZISHOPE</a>, Tous droits réservés.</span>
            </div>
            <div class="col-md-6 my-auto text-center text-md-end text-white">
                <!--/*** Ce modèle est gratuit tant que vous conservez le lien de crédit/attribution de l'auteur ci-dessous. ***/-->
                <!--/*** Si vous souhaitez utiliser le modèle sans le lien de crédit/attribution de l'auteur ci-dessous, ***/-->
                <!--/*** vous pouvez acheter la licence de suppression de crédit sur "https://htmlcodex.com/credit-removal". ***/-->
                Conçu par <a class="border-bottom" href="http://achile.universbinaire.com/">HTML MBA</a> Distribué par <a class="border-bottom" href="http://achile.universbinaire.com/">IZISHOP</a>
            </div>
        </div>
    </div>
</div>

        <!-- Copyright End -->



        <!-- Back to Top -->
        <a href="#" class="btn btn-primary border-3 border-primary rounded-circle back-to-top"><i class="fa fa-arrow-up"></i></a>   

        
    <!-- JavaScript Libraries -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/lightbox/js/lightbox.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>

    <!-- Template Javascript -->
    <script src="js/main.js"></script>
    </body>

</html>