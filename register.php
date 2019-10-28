<?php
session_start();
if ( isset( $_SESSION[ 'userid' ] ) ) {
  header( "Location: login.php" );
  die();
}
$pdo = new PDO( 'mysql:host=localhost;dbname=users', 'root' );
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
        $showFormular = true; //Variable ob das Registrierungsformular anezeigt werden soll
        if ( isset( $_GET[ 'register' ] ) ) {
          $error = false;
          $username = addslashes( $_POST[ 'username' ] );
          $email = addslashes( $_POST[ 'email' ] );
          $passwort = $_POST[ 'passwort' ];
          $passwort2 = $_POST[ 'passwort2' ];
          $birthdate = $_POST['bday'];

          if ( !filter_var( $email, FILTER_VALIDATE_EMAIL ) ) {
            echo '<p class="fail">Bitte eine gültige E-Mail-Adresse eingeben<p>';
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

          //Überprüfe, dass die E-Mail-Adresse noch nicht registriert wurde
          if ( !$error ) {
            $statement = $pdo->prepare( "SELECT * FROM entries WHERE email = :email" );
            $result = $statement->execute( array( 'email' => $email ) );
            $user = $statement->fetch();

            if ( $user !== false ) {
              echo '<p class="fail">There is an account with this E-Mail<p>';
              $error = true;
            }

          }
          //Keine Fehler, wir können den Nutzer registrieren
          if ( !$error ) {
            $passwort_hash = password_hash( $passwort, PASSWORD_DEFAULT );

            $statement = $pdo->prepare( "INSERT INTO entries (email, p_word, b_date, u_name, grp) VALUES (:email, :passwort, :bday, :username, :grp)" );
            $result = $statement->execute( array( 'email' => $email, 'passwort' => $passwort_hash, 'bday' => $birthdate, 'username' => $username, 'grp' => "base" ) );
            var_dump($birthdate);
            if ( $result ) {
              //echo '<div class="regform">Registration successful. <a href="loginscreen.php">Login</a></div>';
              $showFormular = false;

              //mail sending
              $message = "Thank you for your registration at Forum_place";
              $headers = "From: automail@forumplace.project";
              mail( $email, "Forum_place registrierung", $message, $headers );

              header( "Location: login.php" );
              die();

            } else {
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
                    <label >Birthdate:</label>
                </div>
                <div class="field">
                  <input type="date" name="bday">
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
        } //Ende von if($showFormular)
        ?>
	</section>

    <?php include "footer.php"?>

</body>

</html>