<?php
session_start();
if (!isset( $_SESSION[ 'userid' ] ) ) {
  header( "Location: login.php" );
  die();
}
$pdo = new PDO( 'mysql:host=localhost;dbname=content', 'root' ); //der Einfachheit halber keine sql nutzer mit passwort
?>

<!DOCTYPE HTML>

<html>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>SassTest</title>
    <link rel="stylesheet" href="css/classic.css">
</head>

<body>
    <?php include "header.php"?>
	
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
        if ( isset( $_GET[ 'catadd' ] ) ) {
          $error = false;

          $cat_name = addslashes($_POST['cat_name']);
          $cat_desc = addslashes($_POST['cat_desc']);
          
          //Keine Fehler, wir können den Nutzer registrieren
          if ( !$error ) {
            $statement = $pdo->prepare("INSERT INTO categories(cat_name, cat_desc)
            VALUES(:catname,:catdesc)" );
            $result = $statement->execute(array( 'catname' => $cat_name, 'catdesc' => $cat_desc));
            echo("Category " . $cat_name . " has been Created!");
          }
        }

        if ( $showFormular ) {
          ?>
        <div class="regform">
          <form id="contact-form" action="?catadd=1" method="post">
            <ul style="list-style: none;">
              <li>
                <div class="label">
                  <label >Category name:</label>
                </div>
                <div class="field">
                  <input type="text" size="40" maxlength="50" name="cat_name" class="text">
                </div>
                <div class="label">
                  <label >Category decription:</label>
                </div>
                <div class="field">
                <textarea size="80" maxlength="250"name='cat_desc' ></textarea>
                </div>
                <div class="label">
                  <label > Continue to <a class="loginlink" href="loginscreen.php">Login</a>!</label>
                </div>                
                <div class="submit">
                  <input type="submit" value="add_category" class="button">
                </div>
              </li>
            </ul>
          </form>
        </div>
        <?php
        } //Ende von if($showFormular)
        else
        {
          echo 'no permission to create Categories';
        }
        ?>
	</section>

    <?php include "footer.php"?>

</body>

</html>