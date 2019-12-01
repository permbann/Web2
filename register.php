<?php
session_start();
if ( isset( $_SESSION[ 'userid' ] ) ) {
  header( "Location: help.php" );
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

    $showFormular = true;

    if ( isset( $_GET[ 'register' ] ) ) 
    {
      $error = false;
      $username = addslashes( $_POST[ 'username' ] );
      $email = addslashes( $_POST[ 'email' ] );
      $passwort = $_POST[ 'passwort' ];
      $passwort2 = $_POST[ 'passwort2' ];

      //checks:

      if ( !filter_var( $email, FILTER_VALIDATE_EMAIL ) ) 
      {
        echo '<p class="fail">Please enter valid email adress<p>';
        $error = true;
      }

      if ( strlen( $username ) == 0 ) {
        echo '<p class="fail">Username missing<p>';
        $error = true;
      }

      if ( strlen( $passwort ) == 0 ) {
        echo '<p class="fail">password missing<p>';
        $error = true;
      }

      if ( $passwort != $passwort2 ) {
        echo '<p class="fail">passwords must match<p>';
        $error = true;
      }

      //Check for email in db
      if ( !$error ) 
      {
        $statement = $pdo->prepare( "SELECT * FROM users WHERE user_email = :email" );
        $result = $statement->execute( array( 'email' => $email ) );
        $user = $statement->fetch();

      if ( $user !== false ) 
      {
        echo '<p class="fail">There is an account with this E-Mail<p>';
        $error = true;
      }
    }

    //No Errors, User can be registrated
    if ( !$error ) 
    {
      $passwort_hash = password_hash( $passwort, PASSWORD_DEFAULT );
      $statement = $pdo->prepare("INSERT INTO users (user_email, user_pass, u_name, user_date, user_level) 
                                 VALUES (:email, :passwort, :username, :regtime, :lvl)" );
      $result = $statement->execute( array( 'email' => $email, 'passwort' => $passwort_hash, 'username' => $username, 'regtime' => date('Y-m-d H:i:s'), 'lvl' => 0 ) );
    
      if ( $result ) 
      {
        $showFormular = false;
        //mail sending
        $message = "Thank you for your registration at Forum_place";
        $headers = "From: automail@forumplace.project";
        mail( $email, "Forum_place registrierung", $message, $headers );
        header( "Location: login.php" );
        die();
      } 
      else 
     {
        echo 'Beim Abspeichern ist leider ein Fehler aufgetreten<br>';
      }
    }
    }
    if ( $showFormular ) {
    ?>

  <div class="regform">
    <p>By registring to this site you will be able to contribute and share away.</p>
    <form id="contact-form" action="?register=1" method="post">
    <ul style="list-style: none;">
      <li>
        <div class="label">
          <label class="loginlabel">E-Mail:</label>
        </div>
        <div class="field">
          <input type="email" size="40" maxlength="250" name="email" class="text">
        </div>
        <div class="label">
          <label class="loginlabel">Username:</label>
        </div>
        <div class="field">
          <input type="text" size="40" maxlength="250" name="username" class="text">
        </div>
        <div class="label">
          <label >Password:</label>
        </div>
        <div class="field">
          <input type="password" size="40" maxlength="250" name="passwort" class="text">
        </div>
        <div class="label">
          <label >Repeat Password:</label>
        </div>
        <div class="field">
          <input type="password" size="40" maxlength="250" name="passwort2" class="text">
        </div>
        <div class="label">
          <label > Continue to <a class="loginlink" href="loginscreen.php">Login</a>!</label>
        </div>                
        <div class="submit">
          <input type="submit" value="Register" class="button">
        </div>
      </li>
    </ul>
  </form>
</div>

<?php
} //end of if($showFormular)
?>

</section>
</div>
<?php include "footer.php"?>
</body>

</html>