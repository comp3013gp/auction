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

  if ($_SESSION['user_type'] == "seller") {
    header("Location: main.php");
  }

  require_once(TEMPLATES_PATH . '/top_bar.php');
?>
<h1>
  Search By Category
</h1>
<ul class="list-group col-xs-3" id="category-list">
  <?php
    $query = "select * from category";
    $result = mysqli_query($connection, $query);
    while ($category = mysqli_fetch_array($result)) {
      echo "
        <li class='list-group-item'>
          <a href='search_result.php?category=".$category['category_id']."'>".$category['name']."</a>
        </li>
      ";
    }
  ?>
</ul>
<?php
  require_once(TEMPLATES_PATH . '/bottom_bar.php');
?>
