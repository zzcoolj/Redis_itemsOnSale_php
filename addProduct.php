<?php
/**
 * Created by IntelliJ IDEA.
 * User: yachironi
 * Date: 19/09/15
 * Time: 22:14
 */
require 'vendor/autoload.php';

$redis = new Predis\Client([
    'scheme' => 'tcp',
    'host' => '127.0.0.1',
    'port' => 6379
]);

$productName = $_POST['productName'];
$productPrice = $_POST['productPrice'];
$productCategory = $_POST['productCategory'];
$productDescription = $_POST['productDescription'];

// Create a unique ID
$uid = hash("md5", $productName.$productPrice.$productCategory.$productDescription);
$productKey = 'product:'.$uid;

// Store the product information
$redis->hmset($productKey, [
    'name' => $productName,
    'price' => $productPrice,
    'category' => $productCategory,
    'description' => $productDescription
]);

// Transform the description to an array of words ( simple split by spaces)
$keywords = explode(' ', $productDescription);

// Indexing the product by keywords
// Each keyword is a "Redis SET structure" that contain the list of product key that have the keyword in their description
foreach ($keywords as $key) {
    $redis->sadd('keyword:'.$key, $productKey);
}

echo $productKey;