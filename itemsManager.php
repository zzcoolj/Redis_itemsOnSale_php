<?php

require 'vendor/autoload.php';


$redis = new Predis\Client([
	'scheme' => 'tcp',
	'host' => '127.0.0.1',
	'port' => 6379
	]);

//$redis->flushall();

$productsSetKey = "products";
$redis->sadd($productsSetKey, ['product:0', 'product:1', 'product:2']);

$redis->hmset('product:0', [
	'name' => 'aquarium1',
	'length' => '60',
	'width' => '30',
	'height' => '40',
	'price' => '50',
	'address:city' => 'Palaiseau',
	'address:phone' => '0132547698',
	'category' => 'aquarium',
	'description' => 'Sed posuere consectetur est at lobortis. Donec ullamcorper nulla non metus auctor fringilla. Maecenas faucibus mollis interdum. Praesent commodo cursus magna, vel scelerisque nisl consectetur et. Cras mattis consectetur purus sit amet fermentum. Aenean lacinia bibendum nulla sed consectetur. Donec sed odio dui.'
	]);
$redis->hmset('product:1', [
	'name' => 'aquarium2',
	'liters' => '55',
	'price' => '30',
	'address:city' => 'Palaiseau',
	'address:phone' => '0123456789',
	'category' => 'aquarium',
	'description' => 'Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Aenean lacinia bibendum nulla sed consectetur. Aenean eu leo quam. Pellentesque ornare sem lacinia quam venenatis vestibulum. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus.'
	]);
$redis->hmset('product:2', [
	'name' => 'bike1',
	'size' => '53',
	'price' => '75',
	'city' => 'Orsay',
	'category' => 'bike',
	'description' => 'Maecenas faucibus is interdum. Cras justo odio, dapibus ac facilisis in, egestas eget quam. Nullam quis risus eget urna mollis ornare vel eu leo. Nullam id dolor id nibh ultricies vehicula ut id elit. Lorem ipsum dolor sit amet, consectetur adipiscing elit.'
	]);

$redis->sadd('categorySetKey', ['category:0', 'category:1']);
$redis->set('category:0', 'aquarium');
$redis->set('category:1', 'bike');

$redis->sadd('category:products:0', ['product:0', 'product:1']);
$redis->sadd('category:products:1', ['product:2']);

$redis->publish('control_channel', 'data_initialized');


showProducts($redis);

function showProducts($redis)
{
	echo "Products:";
	if ($redis->exists('products')) {
		$productsSet = $redis->smembers('products');
		$productsQuantity = sizeof($productsSet);
		for ($i = 0; $i < $productsQuantity; $i++) {
			$productKey = 'product:' . $i;
			$productInfo = $redis->hgetall($productKey);
			echo "<table border='2'>";

			foreach ($productInfo as $key => $value) {

				echo "<tr>";
				echo "<td> $key </td>";
				echo "<td> $value</td>";
				echo "</tr>";

			}
			echo "</table> <br>";
		}
	} else {
		echo "There is no products.";
	}
}
?>

<html>
<body>
	<p>You could add a new product below:</p>
	<form action="newProduct.php" method="get">
		<p>Name: <input type="text" name="name" /></p>
		<p>Price: <input type="text" name="price" /></p>
		<p>Category: 
		<?
		$categorySet = $redis->smembers('categorySetKey');
		$categoriesQuantity = sizeof($categorySet);
		echo "<select name='category'>";
		for($i=0; $i<$categoriesQuantity; $i++){
			$categoryInfo = $redis->get('category:'.$i);
			echo "<option value='$i'>";
			echo $categoryInfo ;
			echo "</option>";
		}
		echo "</select>";
		?>
		</p>
		<p>Description: <input type="text" name="description" /></p>
		<input type="submit" value="Submit" />
	</form>

</body>
</html>