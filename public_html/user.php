<?php
  session_start();

  require_once("../resources/dbconnection.php");
  require_once("../resources/config.php");

  if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
  }

  $query = "select * from rating where rated_by='".$_SESSION['user_id']."' and is_pending='1'";
  $result = mysqli_query($connection, $query);
  if ($rating = mysqli_fetch_array($result)) {
    header("Location: rating.php?id=".$rating['rating_id']);
  }

  if (!isset($_GET['user']) || $_GET['user'] == '') {
    header("Location: search.php");
  }

  if ($_SESSION['user_id'] == $_GET['user']) {
    $user_self = true;
  } else {
    $user_self = false;
  }

  $query = "select * from user where user_id='".$_GET['user']."'";
  $result = mysqli_query($connection, $query);
  $user = mysqli_fetch_array($result);

  $query = "select * from rating where user_id='".$_GET['user']."' and is_pending='0' order by updated_at desc";
  $ratings = mysqli_query($connection, $query);
  
  $query = "select round(avg(case rating
                             when '5' then 5
                             when '4' then 4
                             when '3' then 3
                             when '2' then 2
                             when '1' then 1
                             else null end),1) avg_rating
             from rating
             where user_id='".$_GET['user']."' and is_pending='0'";
  $avg_rating = mysqli_query($connection, $query);
  $avg_rating = mysqli_fetch_array($avg_rating);
  $avg_rating = $avg_rating['avg_rating'];

  require_once(TEMPLATES_PATH . '/top_bar.php');
?>
<h1 id="user-h1">
  <?php echo $user['name'];?>
</h1>
<h2 id="user-h2">
  (registered as <?php echo $user['user_type'];?>)
</h2>
<?php
  if (mysqli_num_rows($ratings) > 0) {
    echo "<span id='rating-span'>Ratings (Average: ".$avg_rating." out of 5)</span>
      <ul class='list-group' id='rating-list'>";
    while($rating = mysqli_fetch_array($ratings)) {
      $query = "select * from user where user_id='".$rating['rated_by']."'";
      $rated_by_result = mysqli_query($connection, $query);
      $rated_by = mysqli_fetch_array($rated_by_result);
      $query = "select * from auction where auction_id='".$rating['auction_id']."'";
      $auction_result = mysqli_query($connection, $query);
      $auction = mysqli_fetch_array($auction_result);
      $query = "select * from item where item_id='".$auction['item_id']."'";
      $item_result = mysqli_query($connection, $query);
      $item = mysqli_fetch_array($item_result);
      echo "
        <li class='list-group-item rating-item'>
          <span class='rating-info-inline' id='rating-val'>".$rating['rating']."/5</span>
          <span class='rating-info-inline' id='rated_by'>(rated by <a href='user.php?user=".$rated_by['user_id']."'>".$rated_by['name']."</a> at ".$rating['updated_at'].")</span>
          <span class='rating-info'>Auction: <a href='auction.php?auction=".$auction['auction_id']."'>".$item['name']."</a></span>
          <span class='rating-info'>Comment: ".$rating['comment']."</span>
        </li>
      "; 
    }
    echo "</ul>";
  } else {
    echo "<span id='rating-span'>Has No Ratings Yet.</span>";
  }
?>
<?php
  require_once(TEMPLATES_PATH . '/bottom_bar.php');
?>
