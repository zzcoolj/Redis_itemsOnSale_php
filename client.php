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
 foreach ($pubsub as $message) {
 	switch ($message->kind) {
 		case 'subscribe':
 		//echo "Subscribed to {$message->channel}\n";
 		break;
 		case 'message':
 		if ($message->channel == 'control_channel') {
 			switch($message->payload) {
 				case 'data_initialized':
 				$pubsub->unsubscribe();
 				echo "Please select categories to which you want to subscribe: <br>";
 				$categorySet = $redis->smembers('categorySetKey');
 				$categoriesQuantity = sizeof($categorySet);
 				for($i=0; $i<$categoriesQuantity; $i++){
 					$categoryInfo = $redis->get('category:'.$i);
 					echo "<input type='checkbox' id='category:$i'>";
 					echo $categoryInfo ;
 					echo "</input>";
 				}
 				echo "<br>"; 
 				echo "<button onclick='pageChange();'>Submit</button>"; 
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
 		?>
		window.location.href='categoryItems.php?selectedCategories='+selectedCategories;
	}
</script>

