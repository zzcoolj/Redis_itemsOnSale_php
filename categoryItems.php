<?php

require 'vendor/autoload.php';


$redis = new Predis\Client([
	'scheme' => 'tcp',
	'host' => '127.0.0.1',
	'port' => 6379
	]);


$selectedCategories = $_GET['selectedCategories'];
?> The category(categories) you have selected is(are): <?
$selectedCategories = explode(",",$selectedCategories);
print_r($selectedCategories);


showProductsOfCategory($redis, 'category:products:1');


function showProductsOfCategory($redis, $category) {
	?> Products: <?
	if($redis->exists($category)){
		$productsSet = $redis->smembers($category);
		$productsQuantity = sizeof($productsSet);
		for($i=0; $i<$productsQuantity; $i++) {
			$productKey = 'product:'.$i;
			$productInfo = $redis->hgetall($productKey);
			?>
			<table border="2">
			<?php
			foreach ($productInfo as $key => $value) {
				?>
				<tr>
					<td><? echo $key ?></td>
					<td><? echo $value ?></td>
				</tr>
				<?php
			}
			?> </table> <br> <?php
		}
	} else {
		echo "There is no products.";
	}
}

