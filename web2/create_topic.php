<?php
session_start();
if ( isset( $_SESSION[ 'userid' ] ) ) {
  //header( "Location: login.php" );
  //die();
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
$showFormular = true; 
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
        <?php
//create_cat.php


 
echo '<h2>Create a topic</h2>';

    //the user is signed in
    if($_SERVER['REQUEST_METHOD'] != 'POST')
    {   
        //the form hasn't been posted yet, display it
        //retrieve the categories from the database for use in the dropdown
        echo("test");
        $sql = "SELECT
                    cat_id,
                    cat_name,
                    cat_desc
                FROM
                    categories";
        $result = $pdo->query($sql);
        foreach ($pdo->query($sql) as $row) {
            print $row['cat_id'] . "\t";
            print $row['cat_name'] . "\t";
            print $row['cat_desc'] . "\n";
        }
        //$result = mysql_query($sql);
         
        if(!$result)
        {
            //the query failed, uh-oh :-(
            echo 'Error while selecting from database. Please try again later.';
        }
        else
        {
            if(!isset($result))
            {
                //there are no categories, so a topic can't be posted
                if($_SESSION['user_level'] == 1)
                {
                    echo 'You have not created categories yet.';
                }
                else
                {
                    echo 'Before you can post a topic, you must wait for an admin to create some categories.';
                }
            }
            else
            {
         
                echo '<form method="post" action="">
                    Subject: <input type="text" name="topic_subject" />
                    Category:'; 
                 
                echo '<select name="topic_cat">';
                    //while($row = mysql_fetch_assoc($result))
                    var_dump($result);
                    foreach ($result as $row) {
                    {
                        echo '<option value="' . $row['cat_id'] . '">' . $row['cat_name'] . '</option>';
                    }
                echo'</select>'; 
                     
                echo 'Message: <textarea name="post_content" /></textarea>
                    <input type="submit" value="Create topic" />
                 </form>';
                }
            }
        }
    }
    else
    {
        //start the transaction
        $query  = "BEGIN WORK;";
        $result = $pdo->query($query);
         
        if(!$result)
        {
            //Damn! the query failed, quit
            echo 'An error occured while creating your topic. Please try again later.';
        }
        else
        {
     
            //the form has been posted, so save it
            //insert the topic into the topics table first, then we'll save the post into the posts table
            $sql = "INSERT INTO 
                        topics(topic_subject,
                               topic_date,
                               topic_cat,
                               topic_by)
                   VALUES(testsub,NOW(),2,1 )"; 
                               //. addslashes($_POST['topic_subject']) . ",
                               //NOW(),
                               //" . addslashes($_POST['topic_cat']) . ",
                               //" . $_SESSION['userid'] . "
                              
                    $sth = $pdo->prepare($sql);
                    $sth->execute();
                               
                    /* Exercise PDOStatement::fetch styles */
                    print("PDO::FETCH_ASSOC: ");
                    print("Return next row as an array indexed by column name\n");
                    $result = true;//$sth->fetch(PDO::FETCH_ASSOC);
                    $rows = $sth->fetchAll(PDO::FETCH_ASSOC);

                    foreach( $rows as $row )
                    {
                        print $row['topic_date'] . "\t";
                        print $row['topic_cat'] . "\t";
                        print $row['topic_by'] . "\n";
                    } 
            if(!$result)
            {
                //something went wrong, display the error
                echo 'An error occured while inserting your data. Please try again later.';
                $sql = "ROLLBACK;";
                $result = $pdo->query($sql);
            }
            else
            {
                //the first query worked, now start the second, posts query
                //retrieve the id of the freshly created topic for usage in the posts query
                $topicid = mysql_insert_id();
                 
                $sql = "INSERT INTO
                            posts(post_content,
                                  post_date,
                                  post_topic,
                                  post_by)
                        VALUES
                            ('" . mysql_real_escape_string($_POST['post_content']) . "',
                                  NOW(),
                                  " . $topicid . ",
                                  " . $_SESSION['user_id'] . "
                            )";
                $result = mysql_query($sql);
                 
                if(!$result)
                {
                    //something went wrong, display the error
                    echo 'An error occured while inserting your post. Please try again later.' . mysql_error();
                    $sql = "ROLLBACK;";
                    $result = mysql_query($sql);
                }
                else
                {
                    $sql = "COMMIT;";
                    $result = mysql_query($sql);
                     
                    //after a lot of work, the query succeeded!
                    echo 'You have successfully created <a href="topic.php?id='. $topicid . '">your new topic</a>.';
                }
            }
        }
}
 
include 'footer.php';
?>
        <?php
        } //Ende von if($showFormular)
        ?>
	</section>

    <?php include "footer.php"?>

</body>

</html>