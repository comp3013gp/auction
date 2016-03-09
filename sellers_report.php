<?php
  require_once(realpath(dirname(__FILE__) . "/resources/dbconnection.php"));
  require_once(realpath(dirname(__FILE__) . "/resources/email.php"));

  echo(date("Y-m-d H:i:s")." sellers_report.php : \n");

  $query = "select user_id,email_address from user where user_type='seller'";
  $sellers = mysqli_query($connection, $query);

  while ($seller = mysqli_fetch_array($sellers)) {
    $message = '';
    $query = "select a.end_date, a.current_price, a.reserve_price, a.auction_id, a.view_count, i.name
              from auction as a
              left join item as i
              on a.item_id = i.item_id
              where a.seller_id = '".$seller['user_id']."' and a.end_date > now()";
    $auctions = mysqli_query($connection, $query);
    while ($auction = mysqli_fetch_array($auctions)) {
      $query = "select * from bid where auction_id='".$auction['auction_id']."'";
      $bids = mysqli_query($connection, $query);
      $bid_count = mysqli_num_rows($bids);
      $message .= 'Auction (<a href="http://ec2-52-58-25-40.eu-central-1.compute.amazonaws.com/auction.php?auction='.$auction['auction_id'].'">'.$auction['name'].'</a>)<br>
                   End Date: '.$auction['end_date'].'<br>
                   Current Price: '.$auction['current_price'].'<br>
                   Reserve Price: '.$auction['reserve_price'].'<br>
                   Bid Count: '.$bid_count.' bid(s)<br>
                   View Count: '.$auction['view_count'].' view(s)<br>
                   <br>';
    }
    
    if ($message!='') {
      $sender = new email_sender();
      $sender->send_with_log($seller['email_address'], 'Your Current Auction Report!!', $message);
    }
     
  }
  
?>
