
<?php

require 'vendor/autoload.php';
$redis = new Predis\Client([
	'scheme' => 'tcp',
	'host' => '127.0.0.1',
	'port' => 6379
	]);

$single_server = array(
	'scheme' => 'tcp',
	'host'     => '127.0.0.1',
	'port'     => 6379,
	'database' => 15
	);
$client = new Predis\Client($single_server + array('read_write_timeout' => 0));
// Initialize a new pubsub context
$pubsub = $client->pubSubLoop();

$selectedCategories = $_GET['selectedCategories'];
$selectedCategories = explode(",",$selectedCategories);
echo "<script language='javascript' type='text/javascript'>";  
echo "var selectedCategories = new Array();";    
echo "</script>";

$categoriesQuantity = sizeof($selectedCategories);
for($i=0; $i<$categoriesQuantity; $i++){
	$categoryId = explode(":",$selectedCategories[$i]);
	$categoryId = $categoryId[2];
	$categoryKey = 'category:'.$categoryId;
	$categoryInfo = $redis->get($categoryKey);
	$category_channel = 'category_'.$categoryInfo.'_channel';
	echo $category_channel;

	$pubsub->subscribe($category_channel, 'notifications');
	echo "<script language='javascript' type='text/javascript'>";  
	echo "selectedCategories.push('category:products:'+'$categoryId');";  
	echo "</script>";
}

//In redis-cli, enter command like this: PUBLISH category_bike_channel GiantBike
foreach ($pubsub as $message) {
	switch ($message->kind) {
		case 'subscribe':
 		//echo "Subscribed to {$message->channel}\n";
		break;
		case 'message':

		$pubsub->unsubscribe();
		$listenChannel = false;
		for($i=0; $i<categoriesQuantity; $i++) {

		}
		//unset($pubsub);
		echo "<script language='javascript' type='text/javascript'>";  
		$categoryName = explode("_", $message->channel);
		$categoryName = $categoryName[1];
		echo "alert('A new product('+'$message->payload'+') has been added in the category '+'$categoryName'+'.');";  
		echo "</script>";
		break;
	}
}

unset($pubsub);
?>
<button onclick='pageChange();' id="button">Submit</button>
<script type="text/javascript">
	document.getElementById("button").click();
	function pageChange(){
		window.location.href='categoryItems.php?selectedCategories='+selectedCategories;
	}
</script>