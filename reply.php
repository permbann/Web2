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
 
if($_SERVER['REQUEST_METHOD'] != 'POST')
{
    //someone is calling the file directly, which we don't want
    echo 'This file cannot be called directly.';
}
else
{
        //a real user posted a real reply
        $statement = $pdo->prepare("INSERT INTO posts(post_content,
                                                        post_date,
                                                        post_topic,
                                                        post_by)
                            VALUES(:post_c,:post_d,:post_t,:post_b)" );
            $result = $statement->execute(array( 'post_c' => addslashes($_POST['reply-content']), 'post_d' => date('Y-m-d H:i:s'), 'post_t' => addslashes($_GET['id']), 'post_b' => $_SESSION['userid']));                 
        if(!$result)
        {
            echo 'Your reply has not been saved, please try again later.';
        }
        else
        {
            echo 'Your reply has been saved, check out <a href="topic_view.php?id=' . htmlentities($_GET['id']) . '">the topic</a>.';
        }
}
?>
	</section>

<?php include "footer.php"?>

</body>

</html>