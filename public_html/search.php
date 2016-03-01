<?php
  session_start();

  require_once(realpath(dirname(__FILE__) . "/../resources/dbconnection.php"));
  require_once(realpath(dirname(__FILE__) . "/../resources/config.php"));

  if (!isset($_SESSION['user_id'])) {
    header("Location: /auction/public_html/login.php");
  }

  $query = "select * from rating where rated_by='".$_SESSION['user_id']."' and is_pending='1'";
  $result = mysqli_query($connection, $query);
  if ($rating = mysqli_fetch_array($result)) {
    header("Location: /auction/public_html/rating.php?id=".$rating['rating_id']);
  }

  if ($_SESSION['user_type'] == "seller") {
    header("Location: /auction/public_html/main.php");
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
          <a href='/auction/public_html/search_result.php?category=".$category['category_id']."'>".$category['name']."</a>
        </li>
      ";
    }
  ?>
</ul>
<?php
  require_once(TEMPLATES_PATH . '/bottom_bar.php');
?>
