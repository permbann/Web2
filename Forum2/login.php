<?php
session_start();
$pdo = new PDO( 'mysql:host=localhost;dbname=content', 'root' );

if ( isset( $_GET[ 'login' ] ) ) 
{
  $email = $_POST[ 'email' ];
  $passwort = $_POST[ 'passwort' ];

//call user by email
  $statement = $pdo->prepare( "SELECT * FROM users WHERE user_email = :email" );
  $result = $statement->execute( array( 'email' => $email ) );
  $user = $statement->fetch();

//check Password
  if ( $user !== false && password_verify( $passwort, $user[ 'user_pass' ] ) ) 
  {
    $_SESSION[ 'userid' ] = $user[ 'user_id' ];
    $_SESSION['user_level'] = $user['user_level'];
    $url = 'cat_over.php';
    header( "Location: $url" );
  } 
  else 
  {
    $errorMessage = "E-Mail or Passwod was wrong<br>";
  }

}
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

    if ( isset( $errorMessage ) ) 
    {
      echo $errorMessage;
    }
  ?>

  <div>
    <form id="contact-form" action="?login=1" method="post">
      <ul style="list-style: none;">
       <li>
          <div class="label">
            <label>E-Mail:</label>
          </div>
          <div class="field">
            <input type="email" size="30" maxlength="250" name="email" class="text">
          </div>
          <div class="label">
            <label>Passwod:</label>
          </div>
          <div class="field">
            <input type="password" size="30" maxlength="250" name="passwort" class="text">
          </div>
          <div class="label">
            <label>No Account?<a class="loginlink" href="register.php">Register</a>!</label>
         </div>
          <div class="submit">
           <input type="submit" value="Login" class="button">
          </div>
        </li>
      </ul>
    </form>
  </div>

</section>
</div>

<?php include "footer.php"?>

</body>

</html>