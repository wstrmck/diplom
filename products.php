<?php include("db_connect.php");
$sql = "SELECT products.id_product, products.name_product, products.price, cart_products.quantity, MIN(images.img) AS img
            FROM products
            JOIN cart_products ON products.id_product = cart_products.id_product
            JOIN carts ON cart_products.id_cart = carts.id_cart
            JOIN images ON products.id_product = images.id_product
            GROUP BY products.id_product, products.name_product, products.price, cart_products.quantity;";

$result = mysqli_query($db, $sql);
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
                                        <br>
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
                    <h5 class="offcanvas-title" id="offcanvasCartLabel">Корзина</h5>
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
                                    <a class="nav-link" href="products.php">Каталог</a>
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
        <main class="main">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <nav class="breadcrumbs">
                            <ul>
                                <li><a href="index.php">Главная</a></li>
                                <li><a href="products.php">Каталог</a></li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>

            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-3 col-md-4">
                        <div class="sidebar">

                            <button class="btn w-100 text-start collapse-filters-btn mb-3" type="button"
                                data-bs-toggle="collapse" data-bs-target="#collapseFilters" aria-expanded="false"
                                aria-controls="collapseExample">
                                <i class="fa-solid fa-filter"></i> Фильтры
                            </button>

                            <div class="collapse collapse-filters" id="collapseFilters">
                                <div class="filter-block">
                                    <h5 class="section-title"><span>По категории товара</span></h5>
                                    <?php
                                    // Запрос для получения уникальных категорий
                                    $sql_cat = "SELECT DISTINCT category FROM products";
                                    $sql_cat_query = mysqli_query($db, $sql_cat);

                                    // Проверка на наличие результатов
                                    if (mysqli_num_rows($sql_cat_query) > 0) {
                                        while ($myrow = mysqli_fetch_array($sql_cat_query)) {
                                    ?>
                                            <form action="" method="GET">
                                                <div class="form-check d-flex justify-content-between">
                                                    <div>
                                                        <input class="form-check-input" type="checkbox" name="categories[]" value="<?php echo htmlspecialchars($myrow['category']); ?>" id="<?php echo htmlspecialchars($myrow['category']); ?>">
                                                        <label class="form-check-label" for="<?php echo htmlspecialchars($myrow['category']); ?>">
                                                            <?php echo htmlspecialchars($myrow["category"]); ?>
                                                        </label>
                                                    </div>
                                                </div>
                                        <?php
                                        }
                                    } else {
                                        echo "<p>Нет категорий для отображения.</p>";
                                    } ?>
                                        <button type="submit" class="btn btn-primary me-2">Применить фильтр</button>
                                        <button type="button" class="btn btn-primary" onclick="resetFilters()">Сбросить фильтр</button>
                                            </form>
                                </div>
                                <script>
                                    function resetFilters() {
                                        const checkboxes = document.querySelectorAll('input[type="checkbox"]');
                                        checkboxes.forEach(checkbox => {
                                            checkbox.checked = false;
                                        });
                                        window.location.href = window.location.pathname; // Удаляем параметры из URL
                                    }
                                </script>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-9 col-md-8">
                        <div class="row mb-3">
                            <div class="col-12">
                                <h1 class="section-title h3"><span>Каталог товаров</span></h1>
                            </div>
                        </div>

                        <hr>
                        <?php
                        if (isset($_GET['categories'])) {
                            $selected_categories = $_GET['categories'];
                            // Экранируем значения для SQL-запроса
                            $escaped_categories = array_map(function ($category) use ($db) {
                                return mysqli_real_escape_string($db, $category);
                            }, $selected_categories);

                            $categories_list = "'" . implode("', '", $escaped_categories) . "'"; // Формируем список категорий

                            // Запрос для получения продуктов по выбранным категориям
                            $prod = "SELECT products.id_product, products.name_product, products.description, MIN(images.img) AS img, products.price 
                            FROM products 
                            JOIN images ON products.id_product = images.id_product 
                            WHERE category IN ($categories_list) 
                            GROUP BY products.id_product, products.name_product, products.description, products.price";
                        } else {
                            // Запрос для получения всех продуктов, если категория не выбрана
                            $prod = "SELECT products.id_product, products.name_product, products.description, MIN(images.img) AS img, products.price 
                            FROM products 
                            JOIN images ON products.id_product = images.id_product 
                            GROUP BY products.id_product, products.name_product, products.description, products.price";
                        }

                        $result = mysqli_query($db, $prod);
                        if (mysqli_num_rows($result) > 0) { ?>
                            <div class="row">
                                <?php while ($myrow = mysqli_fetch_array($result)) { ?>
                                    <div class="col-lg-4 col-sm-6 mb-3">
                                        <div class="product-card">
                                            <div class="product-thumb">
                                                <a href="product.php?id=<?php echo $myrow["id_product"] ?>">
                                                    <img src="<?php echo htmlspecialchars($myrow["img"]); ?>" alt="<?php echo htmlspecialchars($myrow["name_product"]); ?>">
                                                </a>
                                            </div>
                                            <div class="product-details">
                                                <h4>
                                                    <a href="product.php?id=<?php echo $myrow["id_product"] ?>"><?php echo htmlspecialchars($myrow["name_product"]); ?></a>
                                                </h4>
                                                <p class="product-excerpt"><?php echo htmlspecialchars($myrow["description"]); ?></p>
                                                <div class="product-bottom-details d-flex justify-content-between">
                                                    <div class="product-price">
                                                        <?php echo htmlspecialchars($myrow["price"]); ?>
                                                    </div>
                                                    <div class="product-links">
                                                        <a href="product.php?id=<?php echo $myrow["id_product"] ?>" class="btn btn-outline-secondary add-to-cart">
                                                            <i class="fas fa-shopping-cart"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                            <?php }
                            } else {
                                echo "<p>Нет продуктов для отображения.</p>";
                            } ?>
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