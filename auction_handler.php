<?php 
  require_once(realpath(dirname(__FILE__) . "/resources/dbconnection.php"));
  require_once(realpath(dirname(__FILE__) . "/resources/email.php"));

  $query = "select * from auction where end_date <= now() and has_ended='0'";
  $auctions = mysqli_query($connection, $query);
  $counter = 0;
  
  if(!empty($auctions)){
    $sender = new email_sender();
  }
  
  while ($auction = mysqli_fetch_array($auctions)) {
	  $counter++;
      mysqli_query($connection, "update auction set has_ended='1' where auction_id=".$auction['auction_id']."");
      $query = "select b.price, u.user_id, u.name, u.email_address
                from bid as b
                left join user as u
                on b.bidder_id=u.user_id
                where b.auction_id=".$auction['auction_id']."
                order by b.price desc
                limit 1";
      $winner = mysqli_query($connection, $query);
      if ($winner = mysqli_fetch_array($winner)) {
        $winner_exists = true;
      } else {
        $winner_exists = false;
      }

      $query = "select * from item where item_id='".$auction['item_id']."'";
      $item = mysqli_query($connection, $query);
      $item = mysqli_fetch_array($item);

      $query = "select * from user where user_id=".$auction['seller_id']."";
      $seller = mysqli_query($connection, $query);
      $seller = mysqli_fetch_array($seller);

      if ($winner_exists) {
        if ($winner['price'] < $auction['reserve_price']) {
          $sender->send($winner['email_address'],
                        'Your Bid Did Not Meet Reserve Price!!',
                        'Your bid was the highest in the auction for "'.$item['name'].'", but we are sorry that you could not get the item as your bid did not meet the reserve price set by the seller.');
          $sender->send($seller['email_address'],
                        'Your Auction Ended But Below Reserve Price!!',
                        'Your auction "'.$item['name'].'" just ended, but the highest bid on the auction did not meet the reserve price you set.');
        } else {
          mysqli_query($connection, "update item set owner_id='".$winner['user_id']."' where item_id='".$item['item_id']."'");
          mysqli_query($connection, "insert into rating (user_id, rated_by, auction_id, created_at) values ('".$seller['user_id']."', '".$winner['user_id']."', '".$auction['auction_id']."', NULL)");
          mysqli_query($connection, "insert into rating (user_id, rated_by, auction_id, created_at) values ('".$winner['user_id']."', '".$seller['user_id']."', '".$auction['auction_id']."', NULL)");
          $sender->send($winner['email_address'],
                        'You Won an Auction!!',
                        'Congratulations!! You won the auction for "'.$item['name'].'"!! Now it is yours!! Go to the website and rate the auction seller!!');
          $sender->send($seller['email_address'],
                        'Your Auction Ended With a Winner!!',
                        'Congratulations!! Your auction "'.$item['name'].'" just ended with a winner and now your item is sold!! '.$winner['name'].' won the auction!! Go to the website and rate the winner!!');
        }
      } else {
        $sender->send($seller['email_address'],
                      'Your Auction Ended',
                      'I am sorry, but your auction "'.$item['name'].'" ended and no one bid on your item. But do not be discouraged, your great item remains in your possession!!');
      }
  }
  
  echo(date("Y-m-d H:i:s")." auction_handler.php : $counter auctions ended.\n");
?>
