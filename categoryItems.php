<?php

require 'vendor/autoload.php';

$redis = new Predis\Client([
	'scheme' => 'tcp',
	'host' => '127.0.0.1',
	'port' => 6379,
	]);

// Interface Graphic
echo "<head>";
echo '<meta charset="utf-8">';
echo '	<meta http-equiv="X-UA-Compatible" content="IE=edge">';
echo '	<meta name="viewport" content="width=device-width, initial-scale=1">';

echo "	<title>POC - REDIS (PHP)</title>";

echo '	<meta name="description" content="Source code generated using layoutit.com">';
echo '	<meta name="author" content="LayoutIt!">';

echo '	<link href="css/bootstrap.min.css" rel="stylesheet">';
echo '	<link href="css/style.css" rel="stylesheet">';

echo "</head>";
echo "<body>";

echo '<div class="container-fluid">';
echo '<div class="row">';
echo '<div class="col-md-12">';
echo '<div class="row">';
echo '<div class="col-md-12">';
echo '<nav class="navbar navbar-default" role="navigation">';
echo '<div class="navbar-header">';

echo '<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">';
echo '<span class="sr-only">Toggle navigation</span><span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span>';
echo "</button>";
echo '<a class="navbar-brand" href="#">POC - Redis (PHP)</a>';
echo "</div>";

echo '<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">';
echo '<ul class="nav navbar-nav">';
echo '<li >';
echo '<a href="index.php">Products</a>';
echo "</li>";
echo '<li class="active">';
echo '<a href="client.php">Client</a>';
echo "</li>";
echo "</ul>";
echo "</div>";

echo "</nav>";
echo "</div>";
echo "</div>";
echo "</div>";
echo "</div>";
echo "<div>";


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

echo "</div>";
echo "</div>";

echo '<script src="js/jquery.min.js"></script>';
echo '<script src="js/bootstrap.min.js"></script>';
echo '<script src="js/scripts.js"></script>';
echo "</body>";

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
		$selectedKeywordsString = implode(",",$selectedKeywords);
		?> Products of keywords <?php print_r($selectedKeywordsString); ?>: <?php
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



