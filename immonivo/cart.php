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
                <a href="cart.html" class="dropdown-item">Panier</a>
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
        <a href="#" class="position-relative me-4 my-auto">
            <i class="fa fa-shopping-bag fa-2x"></i>
            <span class="position-absolute bg-secondary rounded-circle d-flex align-items-center justify-content-center text-dark px-1" style="top: -5px; left: 15px; height: 20px; min-width: 20px;"><?php echo $cartCount; ?></span>
        </a>
        <a href="#" class="my-auto">
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
    
        <!-- Modal Search End -->


        <!-- Single Page Header start -->
        <div class="container-fluid page-header py-5">
            <h1 class="text-center text-white display-6">Cart</h1>
            <ol class="breadcrumb justify-content-center mb-0">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item"><a href="#">Pages</a></li>
                <li class="breadcrumb-item active text-white">Cart</li>
            </ol>
        </div>
        <!-- Single Page Header End -->











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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $serviceId = $_POST['service_id'];
    $userId = 1; // Remplacez ceci par l'ID de l'utilisateur connecté

    // Supprimer l'élément du panier
    $deleteQuery = "DELETE FROM cart WHERE service_id = ? AND user_id = ?";
    $stmt = $conn->prepare($deleteQuery);
    $stmt->bind_param('ii', $serviceId, $userId);
    $stmt->execute();

    // Rediriger vers la page du panier
    header('Location: cart.php');
}

$conn->close();
?>



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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $serviceId = $_POST['service_id'];
    $quantity = $_POST['quantity'];
    $userId = 1; // Remplacez ceci par l'ID de l'utilisateur connecté

    // Mettre à jour la quantité dans le panier
    $updateQuery = "UPDATE cart SET quantity = ? WHERE service_id = ? AND user_id = ?";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param('iii', $quantity, $serviceId, $userId);
    $stmt->execute();

    // Calculer le nouveau total du panier
    $totalQuery = "SELECT SUM(servicess.price * cart.quantity) AS total_price 
                   FROM cart 
                   JOIN servicess ON cart.service_id = servicess.id 
                   WHERE cart.user_id = ?";
    $stmt = $conn->prepare($totalQuery);
    $stmt->bind_param('i', $userId);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    
    echo json_encode(['new_total' => number_format($result['total_price'], 2)]);
}

$conn->close();
?>







        <!-- Cart Page Start -->
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

// ID de l'utilisateur en session (à adapter selon votre logique de gestion de session)
$userId = 1; // Remplacez ceci par l'ID de l'utilisateur connecté, par exemple $_SESSION['user_id']

// Récupérer les éléments du panier
$cartQuery = "SELECT cart.*, servicess.name, servicess.price, servicess.images 
              FROM cart 
              JOIN servicess ON cart.service_id = servicess.id 
              WHERE cart.user_id = ?";
$stmt = $conn->prepare($cartQuery);
$stmt->bind_param('i', $userId);
$stmt->execute();
$cartResult = $stmt->get_result();
?>

<!-- Cart Page Start -->
<div class="container-fluid py-5">
    <div class="container py-5">
        <div class="table-responsive">
            <table class="table">
                <thead>
                  <tr>
                    <th scope="col">Products</th>
                    <th scope="col">Name</th>
                    <th scope="col">Price</th>
                    <th scope="col">Quantity</th>
                    <th scope="col">Total</th>
                    <th scope="col">Handle</th>
                  </tr>
                </thead>
                <tbody>
                    <?php
                    $totalPrice = 0;
                    while ($item = $cartResult->fetch_assoc()):
                        $serviceId = $item['service_id'];
                        $quantity = $item['quantity'];
                        $price = $item['price'];
                        $total = $price * $quantity;
                        $totalPrice += $total;
                        $images = explode('; ', $item['images']);
                        ?>
                    <tr>
                        <th scope="row">
                            <div class="d-flex align-items-center">
                                <img src="https://admin.izishope.com/uploads/services/<?php echo htmlspecialchars($images[0]); ?>" class="img-fluid me-5 rounded-circle" style="width: 80px; height: 80px;" alt="<?php echo htmlspecialchars($item['name']); ?>">
                            </div>
                        </th>
                        <td>
                            <p class="mb-0 mt-4"><?php echo htmlspecialchars($item['name']); ?></p>
                        </td>
                        <td>
                            <p class="mb-0 mt-4">$<?php echo number_format($price, 2); ?></p>
                        </td>
                        <td>
                            <div class="input-group quantity mt-4" style="width: 100px;">
                                <div class="input-group-btn">
                                    <button class="btn btn-sm btn-minus rounded-circle bg-light border">
                                    <i class="fa fa-minus"></i>
                                    </button>
                                </div>
                                <input type="text" class="form-control form-control-sm text-center border-0" value="<?php echo htmlspecialchars($quantity); ?>">
                                <div class="input-group-btn">
                                    <button class="btn btn-sm btn-plus rounded-circle bg-light border">
                                        <i class="fa fa-plus"></i>
                                    </button>
                                </div>
                            </div>
                        </td>
                        <td>
                            <p class="mb-0 mt-4">$<?php echo number_format($total, 2); ?></p>
                        </td>
                        
<!-- Bouton supprimer dans la boucle des produits -->
<td>
    <form method="post" action="" class="delete-item-form">
        <input type="hidden" name="service_id" value="<?php echo htmlspecialchars($serviceId); ?>">
        <button type="submit" class="btn btn-md rounded-circle bg-light border mt-4 delete-item-btn">
            <i class="fa fa-times text-danger"></i>
        </button>
    </form>
</td>


                      
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>



        <div class="mt-5">
            <input type="text" class="border-0 border-bottom rounded me-5 py-3 mb-4" placeholder="Coupon Code">
            <button class="btn border-secondary rounded-pill px-4 py-3 text-primary" type="button">Apply Coupon</button>
        </div>
        <div class="row g-4 justify-content-end">
            <div class="col-8"></div>
            <div class="col-sm-8 col-md-7 col-lg-6 col-xl-4">
                <div class="bg-light rounded">
                    <div class="p-4">
                        <h1 class="display-6 mb-4">Cart <span class="fw-normal">Total</span></h1>
                        <div class="d-flex justify-content-between mb-4">
                            <h5 class="mb-0 me-4">Subtotal:</h5>
                            <p class="mb-0">$<?php echo number_format($totalPrice, 2); ?></p>
                        </div>
                        <div class="d-flex justify-content-between">
                            <h5 class="mb-0 me-4">Shipping</h5>
                            <div class="">
                                <p class="mb-0">Flat rate: $3.00</p>
                            </div>
                        </div>
                        <p class="mb-0 text-end">Shipping to Ukraine.</p>
                    </div>
                    <div class="py-4 mb-4 border-top border-bottom d-flex justify-content-between">
                        <h5 class="mb-0 ps-4 me-4">Total</h5>
                        <p class="mb-0 pe-4">$<?php echo number_format($totalPrice + 3, 2); ?></p>
                    </div>
                    <button class="btn border-secondary rounded-pill px-4 py-3 text-primary text-uppercase mb-4 ms-4" type="button">Proceed Checkout</button>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Cart Page End -->

<?php
// Fermer la connexion
$conn->close();
?>

        <!-- Cart Page End -->
<script type="text/javascript">
$(document).on('click', '.btn-plus, .btn-minus', function () {
    var $button = $(this);
    var quantityInput = $button.closest('.input-group').find('input');
    var quantity = parseInt(quantityInput.val());
    var serviceId = $button.closest('tr').data('service-id');

    if ($button.hasClass('btn-plus')) {
        quantity += 1;
    } else {
        quantity = Math.max(1, quantity - 1);
    }
    
    quantityInput.val(quantity);

    $.ajax({
        url: 'update_cart_quantity.php',
        method: 'POST',
        data: {
            service_id: serviceId,
            quantity: quantity
        },
        success: function (response) {
            // Mettre à jour le total
            $('#cart-total').text(response.new_total);
        }
    });
});

</script>





























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