<?php include("db_connect.php")
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Каталог</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v6.6.0/css/all.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/main.css">
</head>

<body>

    <div class="wrapper">
        <header class="header">
            <div class="header-top py-1">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-6 col-sm-4">
                            <div class="header-top-phone d-flex align-items-center h-100">
                                <i class="fa-solid fa-mobile-screen"></i>
                                <a href="tel:+1234567890" class="ms-2">123-456-7890</a>
                            </div>
                        </div>
                        <div class="col-sm-4 d-none d-sm-block">
                            <ul class="social-icons d-flex justify-content-center">
                                <li><a href="#"><i class="fa-brands fa-youtube"></i></a></li>
                                <li><a href="#"><i class="fa-brands fa-vk"></i></i></a></li>
                                <li><a href="#"><i class="fa-brands fa-telegram"></i></a></li>
                            </ul>
                        </div>
                        <div class="col-6 col-sm-4">
                            <div class="header-top-account d-flex justify-content-end">
                                <?php if (isset($_SESSION['user']['nick'])) { ?>
                                    <p>Добро пожаловать <a href="user.php"><?php echo $_SESSION['user']['nick']; ?></a>
                                        <a href="logout.php"> Выйти</a>
                                    </p>
                                <?php } else { ?>
                                    <div class="btn-group me-2">
                                        <div class="dropdown">
                                            <button class="btn btn-sm dropdown-toggle" type="button"
                                                data-bs-toggle="dropdown" aria-expanded="false">
                                                Аккаунт
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li>
                                                    <a class="dropdown-item" href="auth.html">Авторизация</a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item" href="reg.html">Регистрация</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                            <!-- ./header-top-account -->
                        </div>
                    </div>
                </div>
            </div>
            <!--header-top-->

            <div class="header-midle bg-white py-4">
                <div class="container-fluid">
                    <div class="row align-items-center">

                        <div class="col-sm-6 col-lg-4">
                            <a href="index.php" class="header-logo h1">Electro-Shop</a>
                        </div>

                        <div class="col-lg-4 order-md-2 cart-buttons text-end d-none d-lg-block">
                            <button class="btn p-1" type="button" data-bs-toggle="offcanvas"
                                data-bs-target="#offcanvasCart" aria-controls="offcanvasCart">
                                <i class="fa-solid fa-cart-shopping"></i>
                            </button>
                        </div>
                        <div class="col-lg-4 col-sm-6 order-md-1 mt-2 mt-md-0">
                            <form action="">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="serch" placeholder="Поиск..."
                                        aria-label="Searching..." aria-describedby="button-search">
                                    <button class="btn btn-outline-info" type="submit" id="button-search"><i
                                            class="fa-solid fa-magnifying-glass"></i></button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- header-middle-->
            <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasCart" aria-labelledby="offcanvasCartLabel">
                <div class="offcanvas-header">
                    <h5 class="offcanvas-title" id="offcanvasCartLabel">Cart</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
                <div class="offcanvas-body">
                    <div class="table-responsive">
                        <table class="table offcanvasCart-table">
                            <tbody>
                                <?php
                                if (isset($_SESSION['user']['nick'])) {
                                    $name_user = $_SESSION['user']['nick'];
                                    $result = mysqli_query($db, "SELECT id_user FROM users WHERE login = '$name_user'");
                                    if ($result) {
                                        $row = mysqli_fetch_assoc($result);
                                        if ($row) {
                                            $id_user = $row['id_user'];
                                        } else {
                                            echo "Пользователь не найден.";
                                            exit();
                                        }
                                    } else {
                                        echo "Ошибка выполнения запроса: " . mysqli_error($db);
                                        exit();
                                    }
                                    $sql = "SELECT products.id_product, cart_products.quantity, products.category, products.name_product, products.price, MIN(images.img) AS img, products.description 
                                    FROM products 
                                    JOIN images ON products.id_product = images.id_product
                                    JOIN cart_products ON products.id_product = cart_products.id_product
                                    JOIN carts ON cart_products.id_cart = carts.id_cart
                                    WHERE carts.id_user = $id_user
                                    GROUP BY products.id_product";
                                    $result = mysqli_query($db, $sql);
                                    while ($myrow = mysqli_fetch_array($result)) { ?>
                                        <tr>
                                            <td class="product-img-td"><img src="<?php echo $myrow["img"] ?>" alt="">
                                            </td>
                                            <td><a href="product.php?id=<?php echo $myrow["id_product"] ?>"><?php echo $myrow["name_product"] ?></a>
                                            </td>
                                            <td><?php echo $myrow["price"] ?></td>
                                            <td>&times;<?php echo $myrow["quantity"] ?></td>
                                            <td><button class="btn btn-danger"><i class="fa-regular fa-circle-xmark"></i></button>
                                            </td>
                                        </tr>
                                    <?php } ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="4" class="text-end">Total:</td>
                                    <td><?php
                                        $sql1 = "SELECT SUM(products.price * cart_products.quantity) AS total_sum FROM products
                                        JOIN cart_products ON products.id_product = cart_products.id_product
                                        JOIN carts ON cart_products.id_cart = carts.id_cart
                                        WHERE carts.id_user = $id_user";

                                        $result1 = mysqli_query($db, $sql1);
                                        $total = mysqli_fetch_array($result1);

                                        echo $total["total_sum"];
                                        ?></td>
                                </tr>
                            </tfoot>
                        <?php } else { ?>
                            <p>Чтобы добавить товары в корзину необходимо авторизоваться <a href="auth.html">Авторизация</a></p>
                        <?php } ?>
                        </table>
                    </div>

                    <div class="text-end mt-3">
                        <a href="cart.html" class="btn btn-outline-warning">Корзина</a>
                        <a href="checkout.html" class="btn btn-outline-secondary">К оформлению</a>
                    </div>
                </div>
            </div>
        </header>
        <div class="header-bottom sticky-top" id="header-nav">
            <nav class="navbar navbar-expand-lg bg-dark" data-bs-theme="dark">
                <div class="container-fluid">
                    <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas"
                        data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar" aria-expanded="false"
                        aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="offcanvas offcanvas-start" id="offcanvasNavbar" tabindex="-1"
                        aria-labelledby="offcanvasNavbarLabel">
                        <div class="offcanvas-header">
                            <h5 class="offcanvas-title" id="offcanvasNavbarLabel">Каталог</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="offcanvas"
                                aria-label="Close"></button>
                        </div>
                        <div class="offcanvas-body">
                            <ul class="navbar-nav">
                                <li class="nav-item">
                                    <a class="nav-link" href="index.php">Главная страница</a>
                                </li>
                                <li class="nav-item dropdown">
                                    <a class="nav-link" href="products.php">Каталог товаров</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="contacts.html">Контакты</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="d-block d-lg-none">
                        <button class="btn p-1" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasCart"
                            aria-controls="offcanvasCart">
                            <i class="fa-solid fa-cart-shopping"></i>
                        </button>
                    </div>
                </div>
            </nav>
        </div>
        <?php $id_product = intval($_REQUEST["id"]);
        $sql = "SELECT products.id_product, products.category, products.name_product, products.price, images.img AS img, products.description 
        FROM products 
        JOIN images ON products.id_product = images.id_product 
        WHERE products.id_product = $id_product";
        $result = mysqli_query($db, $sql);
        $myrow = mysqli_fetch_array($result); ?>
        <main class="main">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <nav class="breadcrumbs">
                            <ul>
                                <li><a href="index.php">Главная страница</a></li>
                                <li><a href="products.php">Каталог</a></li>
                                <li><span><?php echo $myrow["name_product"] ?></span></li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>

            <div class="container">
                <div class="row">
                    <div class="col-md-5 col-lg-4 mb-3">
                        <div class="bg-white h-100">
                            <div id="carousel" class="carousel carousel-dark slide carousel-fade">
                                <div class="carousel-inner">
                                    <?php
                                    if (isset($_REQUEST["id"])) {
                                        $sql = "SELECT products.id_product, products.category, products.name_product, products.price, images.img AS img, products.description 
                                        FROM products 
                                        JOIN images ON products.id_product = images.id_product 
                                        WHERE products.id_product = $id_product";
                                        $result = mysqli_query($db, $sql);

                                        // Проверка на наличие результатов
                                        if (mysqli_num_rows($result) > 0) {
                                            while ($res = mysqli_fetch_array($result)) { ?>
                                                <div class="carousel-item <?php echo $result ? 'active' : ''; ?>">
                                                    <img src="<?php echo $res["img"] ?>" class="d-block w-100" alt="...">
                                                </div>
                                    <?php }
                                        }
                                    } ?>
                                </div>
                                <button class="carousel-control-prev" type="button" data-bs-target="#carousel" data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Предыдущий</span>
                                </button>
                                <button class="carousel-control-next" type="button" data-bs-target="#carousel" data-bs-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Следующий</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-7 col-lg-8 mb-3">
                        <div class="bg-white product-content p-3 h-100">
                            <h1 class="section-title h5"><span><?php echo $myrow["name_product"] ?></span></h1>

                            <div class="product-rating">
                                <i class="fa-solid fa-star active"></i>
                                <i class="fa-solid fa-star active"></i>
                                <i class="fa-solid fa-star active"></i>
                                <i class="fa-solid fa-star active"></i>
                                <i class="fa-solid fa-star"></i>
                                <span class="count-reviews">10 reviews</span>
                            </div>

                            <div class="product-price">
                                <?php echo $myrow["price"] ?>
                            </div>

                            <p><?php echo $myrow["description"] ?></p>

                            <div class="product-add2cart">
                                <div class="input-group">
                                    <?php
                                    // Проверяем, существует ли сессия и массив пользователя
                                    if (!isset($_SESSION['user']) || !isset($_SESSION['user']['nick'])) { ?>
                                        <p>Чтобы добавить товар в корзину необходимо авторизоваться <a href="auth.html">Авторизация</a></p>
                                        <?php } else {
                                        $name_user = $_SESSION['user']['nick'];
                                        $result = mysqli_query($db, "SELECT id_user FROM users WHERE login = '$name_user'");
                                        if ($result) {
                                            $row = mysqli_fetch_assoc($result);
                                            if ($row) {
                                                $id_user = $row['id_user'];
                                            } else {
                                                echo "Пользователь не найден.";
                                                exit; // Завершаем выполнение скрипта, если пользователь не найден
                                            }
                                        }

                                        $sql1 = mysqli_query($db, "SELECT cart_products.*, carts.id_user FROM cart_products
                                        JOIN carts ON cart_products.id_cart = carts.id_cart
                                        WHERE cart_products.id_product = $id_product AND carts.id_user = $id_user");

                                        if (mysqli_num_rows($sql1) == 0) { ?>
                                            <form action="add_product.php" method="post">
                                                <input type="number" class="form-control" name="quantity" min="1">
                                                <input type="hidden" name="id_product" value="<?php echo $id_product ?>">
                                                <button class="btn btn-warning" type="submit" name="add_button_cart">
                                                    <i class="fas fa-shopping-cart"></i> Добавить в корзину
                                                </button>
                                            </form>
                                        <?php } else {
                                            $sql = "SELECT * FROM carts where id_user = $id_user";
                                            $sql_query = mysqli_query($db, $sql);
                                            $myrow = mysqli_fetch_array($sql_query);
                                        ?>
                                            <p>Товар уже добавлен в корзину</p>
                                            <a href="cart.php?id=<?php echo $myrow["id_cart"] ?>">Перейти в корзину</a>
                                    <?php }
                                    } ?>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-lg-4 mb-2">
                                    <div class="card h-100">
                                        <div class="card-body">
                                            <h5 class="card-title"><i class="fa-solid fa-shield-halved"></i> Гарантия
                                            </h5>
                                            <ul class="list-unstyled">
                                                <li>Гарантия 1 год</li>
                                                <li>Возвращение товара в течение 14 дней</li>
                                                <li>Гарантия качества</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-4 mb-2">
                                    <div class="card h-100">
                                        <div class="card-body">
                                            <h5 class="card-title"><i class="fa-solid fa-truck-fast"></i> Доставка</h5>
                                            <ul class="list-unstyled">
                                                <li>Доставка по всей стране</li>
                                                <li>Доставка почтой</li>
                                                <li>Самовывоз</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-4 mb-2">
                                    <div class="card h-100">
                                        <div class="card-body">
                                            <h5 class="card-title"><i class="fa-regular fa-credit-card"></i> Оплата</h5>
                                            <ul class="list-unstyled">
                                                <li>Наличный рассчет</li>
                                                <li>Безналичный рассчет</li>
                                                <li>VISA/MasterCard</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </main>

        <footer class="footer">
            <div class="container-fluid">
                <div class="row justify-content-end">
                    <h4>Навигация</h4>
                    <ul class="list-unstyled">
                        <li><a href="index.php">Главная</a></li>
                        <li><a href="contacts.html">Контакты</a></li>
                    </ul>
                </div>
            </div>
        </footer>
    </div>
    <button id="top">
        <i class="fa-solid fa-arrow-up"></i>
    </button>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/main.js"></script>
</body>

</html>