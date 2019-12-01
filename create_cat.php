<?php
session_start();
if (!isset( $_SESSION[ 'userid' ] ) ) {
  header( "Location: start.php" );
  die();
}
$pdo = new PDO( 'mysql:host=localhost;dbname=content', 'root' ); 
?>

<!DOCTYPE HTML>
<html>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>ForumPlace</title>
    <link rel="stylesheet" href="css/classic.css">
</head>

<body>

<?php include "header.php"?>

  <div id="posts">
    <section>
	  <?php

    if((int)$_SESSION['user_level'] > 1)
      { 
        $showFormular = true;
      }
    else
      {
        $showFormular = false;
      } 
    if ( isset( $_GET[ 'catadd' ] ) ) 
      { 
        $error = false;
        $cat_name = addslashes($_POST['cat_name']);
        $cat_desc = addslashes($_POST['cat_desc']);

        //No Errors, user can be registrated
          if ( !$error ) 
            {
              $statement = $pdo->prepare("INSERT INTO categories(cat_name, cat_desc)
              VALUES(:catname,:catdesc)" );
              $result = $statement->execute(array( 'catname' => $cat_name, 'catdesc' => $cat_desc));
              echo("Category " . $cat_name . " has been Created! back to <a href='cat_over.php'>Overview</a>");
            }
      }

      if ( $showFormular ) 
      {
        ?>
        <div class="regform">
          <form id="cat-form" action="?catadd=1" method="post">
            <div class="create_catdiv">
              <label >Category name:</label>
              <input id="create_catname" type="text" size="40" maxlength="50" name="cat_name" class="text"></li>
            </div>
            <div class="create_catdiv">
              <label >Category decription:</label>
              <textarea id="create_cattext" size="80" maxlength="250"name='cat_desc' ></textarea></li>
            </div>
            <div class="submit">
                <input type="submit" value="add_category" class="button">
            </div>
          </form>
        </div>
      <?php
    } //End of if($showFormular)
    else
      {
        echo 'no permission to create Categories';
      }
      ?>

    </section>
    </div>
    <?php include "footer.php"?>
</body>
</html>