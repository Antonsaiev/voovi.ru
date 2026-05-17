<?php
include 'conf.php';

// Устанавливаем заголовок Content-Type для JSON ответа
header('Content-Type: application/json');

// Проверка на наличие запроса GET
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Получение данных из строки запроса
    $queryParams = $_GET;
    if ($_GET['tip'] === 'shipping_invoice') {
        $ns = $_GET['ns'];
        $response = ['status' => False];
        // ... existing code ...
    } else if ($_GET['tip'] === 'shipping_invoice_test') {
        // New condition for "отгрузка счета"
        $response = ['status' => False];
    }
}


?>