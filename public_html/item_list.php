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

  require_once(TEMPLATES_PATH . '/top_bar.php');
?>

<h1 id="item-list-h1">
 	Items I Own
</h1>
<?php
	$query = "select * from item where owner_id='".$_SESSION['user_id']."'";
	$item_result = mysqli_query($connection, $query);
	$item = mysqli_fetch_array($item_result);

	if (!$item) {
		echo "<span id='not-found-message'>You don't own any items.</span>";
	}
	else {
		echo "<ul class='list-group' id='result-list'>";
		do {
			echo "
			<li class='list-group-item result-item'>
			<span class='item-name'>".$item['name']."</span>
			<span class='auction-info'>".$item['description']."</span>
			</li>
			";
		} while ($item = mysqli_fetch_array($item_result));
		echo "</ul>";
	}
?>

<?php
  require_once(TEMPLATES_PATH . '/bottom_bar.php');
?>
