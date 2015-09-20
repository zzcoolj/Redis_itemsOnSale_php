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

// Store the product in the certian category
if ($redis->exists('categorySetKey')) {
	
	$categoryAlreadyExist = false;

	$categoriesSet = $redis->smembers('categorySetKey');
	$categoriesQuantity = sizeof($categoriesSet);
	for ($i = 0; $i < $categoriesQuantity; $i++) {
		$categoryKey = 'category:' . $i;
		$categoryInfo = $redis->get($categoryKey);
		if($productCategory == $categoryInfo){
			$categoryAlreadyExist = true;
			$categoryProductsKey = "category:products:".$i; 
			$redis->sadd($categoryProductsKey, $productKey);
		}
	}
	if($categoryAlreadyExist == false){
		$categorySet = $redis->smembers('categorySetKey');
 		$categoriesQuantity = sizeof($categorySet);
 		$categoryKey = "category:".$categoriesQuantity;
 		$redis->sadd('categorySetKey', $categoryKey);
 		$redis->set($categoryKey, $productCategory);
 		$categoryProductsKey = "category:products:".$categoriesQuantity; 
		$redis->sadd($categoryProductsKey, $productKey);
	}
} else {
 	$redis->sadd('categorySetKey', 'category:0');
	$redis->set('category:0', $productCategory);
	$redis->sadd('category:products:0', $productKey);
}

// Transform the description to an array of words ( simple split by spaces)
$keywords = explode(' ', $productDescription);

// Indexing the product by keywords
// Each keyword is a "Redis SET structure" that contain the list of product key that have the keyword in their description
foreach ($keywords as $key) {
    $redis->sadd('keyword:'.$key, $productKey);
}

//publish in the certain channel, for exemple: PUBLISH category_bike_channel GiantBike
$categoryChannel = 'category_'.$productCategory.'_channel';
$categoryProductName = $productName;
$redis->publish($categoryChannel, $categoryProductName);

echo $productKey;