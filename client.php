 <?php

 require 'vendor/autoload.php';

 $single_server = array(
 	'scheme' => 'tcp',
 	'host'     => '127.0.0.1',
 	'port'     => 6379,
 	'database' => 15
 	);

 $redis = new Predis\Client([
 	'scheme' => 'tcp',
 	'host' => '127.0.0.1',
 	'port' => 6379
 	]);

 $client = new Predis\Client($single_server + array('read_write_timeout' => 0));



// Initialize a new pubsub context
 $pubsub = $client->pubSubLoop();
// Subscribe to your channels
 $pubsub->subscribe('control_channel', 'notifications');
// Start processing the pubsup messages. Open a terminal and use redis-cli
// to push messages to the channels. Examples:
//   ./redis-cli PUBLISH notifications "this is a test"
//   ./redis-cli PUBLISH control_channel quit_loop

// !!! Not wait, it publish contro_channel data_initialized by it self. If we use itemsManager.php, remove this line below.
 $redis->publish('control_channel', 'data_initialized');

 foreach ($pubsub as $message) {
 	switch ($message->kind) {
 		case 'subscribe':
 		//echo "Subscribed to {$message->channel}\n";
 		break;
 		case 'message':
 		if ($message->channel == 'control_channel') {
 			switch($message->payload) {
 				case 'data_initialized':

 				
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


 			$pubsub->unsubscribe();
 				// categories
 			echo "Please select categories to which you want to subscribe: <br>";
 			$categorySet = $redis->smembers('categorySetKey');
 			$categoriesQuantity = sizeof($categorySet);
 			for($i=0; $i<$categoriesQuantity; $i++){
 				$categoryInfo = $redis->get('category:'.$i);
 				echo "<input type='checkbox' id='category:$i'>";
 				echo $categoryInfo ;
 				echo "</input>";
 			}
 			echo "<br><br>"; 
 				// keywords
 			echo "Please select keywords to which you want to subscribe: <br>";
 			$keywordsSet = $redis->keys("keyword:*");
 			foreach ($keywordsSet as $keywordKey) {
 				echo "<input type='checkbox' id=$keywordKey >";
 				$keyworkShown = explode(":", $keywordKey);
 				$keyworkShown = $keyworkShown[1];
 				echo $keyworkShown;
 				echo "</input><br>";
 			}

 			
 			echo "</div>";
 			echo "<br><button class='btn btn-default' onclick='pageChange();'>Submit</button>";
 				echo "</div>";

 				echo '<script src="js/jquery.min.js"></script>';
 				echo '<script src="js/bootstrap.min.js"></script>';
 				echo '<script src="js/scripts.js"></script>';
 			echo "</body>";
 			

 			 
 		}

 			/*
 			if ($message->payload == 'quit_loop') {
 				echo "Aborting pubsub loop...\n";
 				echo "
 				<script type=\"text/javascript\">
 					alert('haha');
 					window.location.href='test.php';
 					alert('done');
 				</script>
 				";
 				$pubsub->unsubscribe();
 			} else {
 				echo "Received an unrecognized command: {$message->payload}.\n";
 			}
 			*/
 		} else {
 			echo "Received the following message from {$message->channel}:\n",
 			"  {$message->payload}\n\n";
 		}
 		break;
 	}
 }


// Always unset the pubsub context instance when you are done! The
// class destructor will take care of cleanups and prevent protocol
// desynchronizations between the client and the server.
 unset($pubsub);
// Say goodbye :-)
 $info = $client->info();
// print_r("Goodbye from Redis v{$info['redis_version']}!\n");
 ?>
 <script type="text/javascript">
 	function pageChange(){
 		var selectedCategories = new Array();
 		var selectedKeywords = new Array();

 		<?php
 		for($i=0; $i<$categoriesQuantity; $i++){
 			$categoryInfo = $redis->get('category:'.$i);
 			?>
 			//alert("<? echo $categoryInfo ?>");
 			if(document.getElementById("<?php echo 'category:'.$i ?>").checked == true){
 				selectedCategories.push("<?php echo 'category:products:'.$i ?>");
 			}
 			<?php
 		}
 		foreach ($keywordsSet as $keywordKey) {
 			$keywordValue = explode(":", $keywordKey);
 			$keywordValue = $keywordValue[1];
 			?>
 			if(document.getElementById("<?php echo $keywordKey ?>").checked == true){
 				selectedKeywords.push("<?php echo $keywordValue ?>");
 			}
 			<?php	
 		}
 		?>
 		window.location.href='categoryItems.php?selectedCategories='+selectedCategories+'&keywords='+selectedKeywords;
 	}
 </script>

