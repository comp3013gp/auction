<?php

require_once(realpath(dirname(__FILE__) . "/resources/dbconnection.php"));
require_once(realpath(dirname(__FILE__) . "/resources/email.php"));

function recommend($dbconnection, $user_id)
{
    $query = "SELECT * FROM auction WHERE
	  auction.auction_id IN( SELECT bid.auction_id FROM bid
	  WHERE bid.bidder_id IN( SELECT bid.bidder_id FROM bid
	  WHERE bid.bidder_id <> $user_id AND bid.auction_id IN(
      SELECT bid.auction_id FROM bid WHERE bid.bidder_id = $user_id
      GROUP BY bid.auction_id) GROUP BY bid.bidder_id ) GROUP BY
	  bid.auction_id ) AND auction.has_ended = '0' LIMIT 10;";

    $recommends = mysqli_query($dbconnection, $query);
    if ($recommends->num_rows == 0) {
        return '';
    }

    $str_recommends = "You may be interested in:<br><br>";
    while ($result = mysqli_fetch_array($recommends)) {
        $get_item_name_query = "SELECT name FROM item WHERE item_id = " . $result['item_id'];
        $get_item_name=mysqli_query($dbconnection,$get_item_name_query);
        $item_name = mysqli_fetch_array($get_item_name)['name'];
        $str_recommends .= 'Auction (<a href="http://ec2-52-58-25-40.eu-central-1.compute.amazonaws.com/auction.php?auction=' . $result['auction_id'] . '">' .$item_name . '</a>)<br>
                   End Date: ' . $result['end_date'] . '<br>
                   Current Price: ' . $result['current_price'] . '<br>
                   <br>';
    }
    return $str_recommends;
}

echo(date("Y-m-d H:i:s") . " recommendation.php : \n");

$query = "SELECT user_id,email_address from user";
$users = mysqli_query($connection, $query);
$sender = new email_sender();
$counter = 0;
while ($user = mysqli_fetch_array($users)) {
    $str_recommends = recommend($connection, $user['user_id']);
    if ($str_recommends != '') {
        $counter++;
        $sender->send_with_log($user['email_address'], 'Recommended Items for You!!', $str_recommends);
    }
}

echo "$counter emails sent\n";


?>
