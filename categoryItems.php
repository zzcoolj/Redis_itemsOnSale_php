<?php

require 'vendor/autoload.php';

$redis = new Predis\Client([
	'scheme' => 'tcp',
	'host' => '127.0.0.1',
	'port' => 6379,
	]);

$selectedCategories = $_GET['selectedCategories'];


$selectedCategories = explode(",",$selectedCategories);
$selectedCategoriesString = $selectedCategories[0];
$selectedCategoriesQuantity = sizeof($selectedCategories);
for($i=0; $i<$selectedCategoriesQuantity; $i++) {
	showProductsOfCategory($redis, $selectedCategories[$i]);
	if($i != 0) {
		$selectedCategoriesString = $selectedCategoriesString.','.$selectedCategories[$i];
	}
}

$selectedKeywords = $_GET['keywords'];
$selectedKeywords = explode(",",$selectedKeywords);
$selectedKeywordsQuantity = sizeof($selectedKeywords);
$productsResult = array();
for($i=0; $i<$selectedKeywordsQuantity; $i++) {
	array_push($productsResult, 'keyword:'.$selectedKeywords[$i]);
}
$result = $redis->sinter($productsResult);
showProductsByKeyWords($redis, $result, $selectedKeywords);


function showProductsOfCategory($redis, $category) {
	$categoryId = explode(":", $category);
	$categoryId = $categoryId[2];
	?> Products of category <?php echo $redis->get('category:'.$categoryId); ?>: <?php
	if($redis->exists($category)){
		$productsSet = $redis->smembers($category);
		$productsQuantity = sizeof($productsSet);
		for($i=0; $i<$productsQuantity; $i++) {

			$productKey = $productsSet[$i];
			$productInfo = $redis->hgetall($productKey);
			?>
			<table border="2">
				<?php
				foreach ($productInfo as $key => $value) {
					?>
					<tr>
						<td><?php echo $key ?></td>
						<td><?php echo $value ?></td>
					</tr>
					<?php
				}
				?> </table> <br> <?php
			}
		} else {
			echo "There is no products.<br>";
		}
	}

function showProductsByKeyWords($redis, $productsKeySet, $selectedKeywords) {
	$productsKeyWordsQuantity = sizeof($productsKeySet);
	?> Products of keywords <?php print_r($selectedKeywords); ?>: <?php
	if($productsKeyWordsQuantity != 0){
		for($i=0; $i<$productsKeyWordsQuantity; $i++) {
			$productKey = $productsKeySet[$i];
			$productInfo = $redis->hgetall($productKey);
			?>
			<table border="2">
				<?php
				foreach ($productInfo as $key => $value) {
					?>
					<tr>
						<td><?php echo $key ?></td>
						<td><?php echo $value ?></td>
					</tr>
					<?php
				}
				?> </table> <br> <?php
		}
	} else {
		echo "There is no products.";
	}
}

?>

 <script type="text/javascript">
	window.location.href='categorySubscribe.php?selectedCategories='+ '<? echo $selectedCategoriesString; ?>';
</script>



