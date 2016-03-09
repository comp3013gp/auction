<?php
require_once(realpath(dirname(__FILE__) . "/resources/dbconnection.php"));
require_once(realpath(dirname(__FILE__) . "/resources/email.php"));

$query = "select a.auction_id, i.name as item_name, i.item_id, u.user_id as seller_id, u.name as seller_name, u.email_address as seller_address, a.reserve_price
          from auction as a
          left join item as i
          on i.item_id = a.item_id
          left join user as u
          on u.user_id = a.seller_id
          where end_date <= now() and has_ended='0'";
$auctions = mysqli_query($connection, $query);
$counter = $auctions->num_rows;

if ($counter != 0) {
    $sender = new email_sender();
}

echo(date("Y-m-d H:i:s") . " auction_handler.php : $counter auctions ended.\n");

while ($auction = mysqli_fetch_array($auctions)) {
    mysqli_query($connection, "update auction set has_ended='1' where auction_id=" . $auction['auction_id'] . "");
    $query = "select b.price, u.user_id, u.name, u.email_address
                from bid as b
                left join user as u
                on b.bidder_id=u.user_id
                where b.auction_id=" . $auction['auction_id'] . "
                order by b.price desc
                limit 1";
    $winner = mysqli_query($connection, $query);
    if ($winner = mysqli_fetch_array($winner)) {
        $winner_exists = true;
    } else {
        $winner_exists = false;
    }
    
    if ($winner_exists) {
        if ($winner['price'] < $auction['reserve_price']) {
            $sender->send_with_log($winner['email_address'],
                'Your Bid Did Not Meet Reserve Price!!',
                'Your bid was the highest in the auction for <a href="http://ec2-52-58-25-40.eu-central-1.compute.amazonaws.com/auction.php?auction=' . $auction['auction_id'] . '">' . $auction['item_name'] . '</a>, but we are sorry that you could not get the item as your bid did not meet the reserve price set by the seller.');
            $sender->send_with_log($auction['seller_address'],
                'Your Auction Ended But Below Reserve Price!!',
                'Your auction for <a href="http://ec2-52-58-25-40.eu-central-1.compute.amazonaws.com/auction.php?auction=' . $auction['auction_id'] . '">' . $auction['item_name'] . '</a> just ended, but the highest bid on the auction did not meet the reserve price you set.');
        } else {
            mysqli_query($connection, "update item set owner_id='" . $winner['user_id'] . "' where item_id='" . $auction['item_id'] . "'");
            mysqli_query($connection, "insert into rating (user_id, rated_by, auction_id, created_at) values ('" . $auction['seller_id'] . "', '" . $winner['user_id'] . "', '" . $auction['auction_id'] . "', NULL)");
            mysqli_query($connection, "insert into rating (user_id, rated_by, auction_id, created_at) values ('" . $winner['user_id'] . "', '" . $auction['seller_id'] . "', '" . $auction['auction_id'] . "', NULL)");
            $sender->send_with_log($winner['email_address'],
                'You Won an Auction!!',
                'Congratulations!!<br>
                 You won the auction for <a href="http://ec2-52-58-25-40.eu-central-1.compute.amazonaws.com/auction.php?auction=' . $auction['auction_id'] . '">' . $auction['item_name'] . '</a>!!<br>
                 Now it is yours!!<br>
                 <a href="http://ec2-52-58-25-40.eu-central-1.compute.amazonaws.com/">Go to the website</a> and rate the seller!!');
            $sender->send_with_log($auction['seller_address'],
                'Your Auction Ended With a Winner!!',
                'Congratulations!!<br>
                 Your auction <a href="http://ec2-52-58-25-40.eu-central-1.compute.amazonaws.com/auction=' . $auction['auction_id'] . '">' . $auction['item_name'] . '</a> just ended with a winner and now your item is sold!!<br>
                 <a href="http://ec2-52-58-25-40.eu-central-1.compute.amazonaws.com/user.php?user=' . $winner['user_id'] . '">' . $winner['name'] . '</a> won the auction!!<br>
                 <a href="http://ec2-52-58-25-40.eu-central-1.compute.amazonaws.com/">Go to the website</a> and rate the winner!!');
        }
    } else {
        $sender->send_with_log($auction['seller_address'],
            'Your Auction Ended With No Bid',
            'I am sorry, but your auction <a href="http://ec2-52-58-25-40.eu-central-1.compute.amazonaws.com/auction.php?auction=' . $auction['auction_id'] . '">' . $auction['item_name'] . '</a> ended and no one bid on your item.<br>
             But do not be discouraged, your great item remains in your possession!!');
    }
}

?>
