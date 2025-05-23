<?php
include("db_connect.php");

if (isset($_POST["add_button_cart"])) {
    if (!isset($_SESSION['user']['nick'])) {
        echo "Авторизируйтесь";
        exit();
    }

    $id_product = $_POST["id_product"];
    $name_user = $_SESSION['user']['nick'];
    $quantity = $_POST["quantity"];

    // Получаем id_user по логину
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

    // Проверяем, существует ли уже корзина для этого пользователя
    $result = mysqli_query($db, "SELECT id_cart FROM carts WHERE id_user = '$id_user'");

    if (mysqli_num_rows($result) > 0) {
        // Если корзина существует
        $row = mysqli_fetch_assoc($result);
        $id_cart = $row['id_cart'];

        // Проверяем наличие записей в orders для данной корзины
        $order_check = mysqli_query($db, "SELECT COUNT(*) as count FROM orders WHERE id_cart = '$id_cart'");
        $order_row = mysqli_fetch_assoc($order_check);

        if ($order_row['count'] > 0) {
            // Если есть записи в orders, создаем новую корзину
            $sql = "INSERT INTO carts (id_user) VALUES ('$id_user')";
            if (mysqli_query($db, $sql)) {
                // Получаем id_cart после успешного добавления
                $id_cart = mysqli_insert_id($db);
                echo "Создана новая корзина с ID: $id_cart<br>";
            } else {
                die("Ошибка создания новой корзины: " . mysqli_error($db));
            }
        } else {
            echo "Корзина уже существует с ID: $id_cart<br>";
        }
    } else {
        // Если корзина не существует, создаем новую
        $sql = "INSERT INTO carts (id_user) VALUES ('$id_user')";
        if (mysqli_query($db, $sql)) {
            // Получаем id_cart после успешного добавления
            $id_cart = mysqli_insert_id($db);
            echo "Создана новая корзина с ID: $id_cart<br>";
        } else {
            die("Ошибка создания новой корзины: " . mysqli_error($db));
        }
    }

    // Теперь добавляем продукт в корзину
    $sql2 = "INSERT INTO cart_products (id_cart, id_product, quantity) VALUES ('$id_cart', '$id_product', '$quantity')";
    if (!mysqli_query($db, $sql2)) {
        die("Ошибка добавления продукта в корзину: " . mysqli_error($db));
    }

    echo "Продукт с ID: $id_product добавлен в корзину с ID: $id_cart<br>";

    // Перенаправляем на страницу корзины с id_cart
    header("Location: cart.php?id_cart=$id_cart");
    exit();
}
