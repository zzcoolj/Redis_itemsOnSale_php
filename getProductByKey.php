<?php
/**
 * Created by IntelliJ IDEA.
 * User: yachironi
 * Date: 20/09/15
 * Time: 07:03
 */

require 'vendor/autoload.php';

$redis = new Predis\Client([
    'scheme' => 'tcp',
    'host' => '127.0.0.1',
    'port' => 6379
]);

$productKey = $_POST['productKey'];
echo json_encode($redis->hgetall($productKey));