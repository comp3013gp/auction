<?php
  
  require_once(realpath(dirname(__FILE__) . "/resources/dbconnection.php"));
  require_once(realpath(dirname(__FILE__) . "/resources/email.php"));

  function recommend($dbconnection, $user_id){
	  $query = "SELECT * FROM auction WHERE
	  auction.auction_id IN( SELECT bid.auction_id FROM bid
	  WHERE bid.bidder_id IN( SELECT bid.bidder_id FROM bid
	  WHERE bid.bidder_id <> $user_id AND bid.auction_id IN(
      SELECT bid.auction_id FROM bid WHERE bid.bidder_id = $user_id
      GROUP BY bid.auction_id) GROUP BY bid.bidder_id ) GROUP BY
	  bid.auction_id ) AND auction.has_ended = '0' LIMIT 10;";
	  
	  $recommends = mysqli_query($dbconnection, $query);
	  if($recommends->num_rows==0){
		  return '';
	  }
	  
	  $str_recommends = "You may interested in \n";
	  while ($result = mysqli_fetch_array($recommends)) {
		  $str_recommends = $str_recommends. "auction: ".$result['auction_id']." for item ".$result['item_id']." which will end at ".$result['end_date']."\n";
	  }
	  return $str_recommends;
  }
  
  $query="SELECT user_id,email_address from user";
  $users = mysqli_query($connection, $query);
  $sender = new email_sender();
  $counter=0;
  while ($user = mysqli_fetch_array($users)) {
		  $str_recommends = recommend($connection, $user['user_id']);
		  if ($str_recommends!=''){
			  $counter++;
			  $sender->send($user['email_address'],'Recommended Items for You!!',$str_recommends);
		  }
 }
 
 echo(date("Y-m-d H:i:s")." recommendation.php : $counter emails sent.\n");
        
?>
