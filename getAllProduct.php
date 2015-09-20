<?php
/**
 * Created by IntelliJ IDEA.
 * User: yachironi
 * Date: 20/09/15
 * Time: 06:45
 */
require 'vendor/autoload.php';

$redis = new Predis\Client([
    'scheme' => 'tcp',
    'host' => '127.0.0.1',
    'port' => 6379
]);
$productsSet = $redis->keys("product:*");
$products = array();

foreach ($productsSet as $productKey) {
    array_push($products, $redis->hgetall($productKey));
}
echo json_encode($products);