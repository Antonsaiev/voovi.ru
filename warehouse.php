<?php
include 'conf.php';

// Устанавливаем заголовок Content-Type для JSON ответа
header('Content-Type: application/json');

// Проверка на наличие запроса POST или GET
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Получение данных из строки запроса
    $queryParams = $_GET;
    if ($_GET['tip'] === 'product-type') {
        $uslugi = $_GET['uslugi'];
        $query = "SELECT * FROM product_type WHERE del_product = '0' AND uslugi = ".$uslugi;
        $result = mysql_query($query);

        $product_types = [];
        while ($row = mysql_fetch_array($result)) {
            $product_types[] = $row;
        }
        $response = ['status' => 'success', 'data' => $product_types];
    } else if ($_GET['tip'] === 'get-product-model') {
        $productType = $_GET['product'];


    $query = "SELECT * FROM product_model WHERE product_type = ".$productType;
    $result = mysql_query($query);

    $models = [];
    while ($row = mysql_fetch_array($result)) {
        $models[] = $row;
    }
    $response = ['status' => 'success', 'data' => $models];
    } else if ($_GET['tip'] === 'get-vendor-product') {
        $idVendorProduct = $_GET['vendor_product'];

        $query = "SELECT * FROM `vendor_product` LEFT JOIN jurnalskzi ON jurnalskzi.vendor_product=vendor_product.id WHERE vendor_product.`product_model` = ". $idVendorProduct ." AND vendor_product IS NULL ";
        $result = mysql_query($query);

        $products = [];
        while ($row = mysql_fetch_array($result)) {
            $products[] = $row;
        }
        $response = ['status' => 'success', 'data' => $products];
    }

} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Получение данных из тела запроса
    $data = json_decode(file_get_contents('php://input'), true);

    // Обработка данных...
    // Например, $response = ['status' => 'success', 'data' => $data];
    $response = ['status' => 'success', 'data' => $data];


} else {
    // Обработка других типов запросов или возврат ошибки
    $response = ['status' => 'error', 'message' => 'Invalid request'];
}

echo json_encode($response);
?>