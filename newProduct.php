<?php
echo 'This is a test';
echo $_GET["name"];
echo $_GET["price"];
echo $_GET["category"];
echo $_GET["description"];

require 'vendor/autoload.php';

$redis = new Predis\Client([
	'scheme' => 'tcp',
	'host' => '127.0.0.1',
	'port' => 6379
	]);
$productsSet = $redis->smembers('products');
$productsQuantity = sizeof($productsSet);
$productKey = 'product:'.$productsQuantity;//for exemple: product:3 
$redis->sadd('products', $productKey);
$productName = $_GET["name"];
$productPrice = $_GET["price"];
$productCategory = $redis->get('category:'.$_GET["category"]);//$_GET["category"] is just the number of category
$productDescription = $_GET["description"];
$redis->hmset($productKey, [
	'name' => $productName,
	'price' => $productPrice,
	'category' => $productCategory,
	'description' => $productDescription
	]);
$categoryProductsKey = 'category:products:'.$_GET["category"];
$redis->sadd($categoryProductsKey, $productKey);

//publish in the certain channel, for exemple: PUBLISH category_bike_channel GiantBike
$categoryChannel = 'category_'.$productCategory.'_channel';
$categoryProductName = $productName;
$redis->publish($categoryChannel, $categoryProductName);
 
?>
<button onclick='pageChange();' id="button">Submit</button>
<script type="text/javascript">
	document.getElementById("button").click();
 	function pageChange(){
		window.location.href='itemsManager.php';
	}
</script>