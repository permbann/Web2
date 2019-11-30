<?php
session_start();
if (!isset( $_SESSION[ 'userid' ] ) ) {
  header( "Location: start.php" );
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
    <?php include "sidebar.php"?>
    <div id="posts">
	<section>
	
    <?php
$showFormular = true; 

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
        $sql = "SELECT
                    cat_id,
                    cat_name,
                    cat_desc
                FROM
                    categories";
        $result = $pdo->query($sql);
        
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
         
                echo '<form method="post" action="" class="topic_form">
                    <div class="subject">
                        Subject: <input class="input" type="text" name="topic_subject" />
                        &emsp;&emsp;
                        Category: '; 
                echo '<select class="input" name="topic_cat">';
                    foreach ($pdo->query($sql) as $row) {
                        echo '<option';
                        if(isset($_GET['id']))
                        {
                            if($_GET['id'] == $row['cat_id'])
                            {
                                echo ' selected="selected"';
                            }
                        }
                        echo ' value="' . $row['cat_id'] . '">' . $row['cat_name'] . '</option>';
                    }                    
                echo'</select>
                    </div>'; 
                     
                echo '<div class="post">
                        Message: <textarea name="post_content" /></textarea>
                    </div>
                    <input class="button" type="submit" value="Create topic" />
                    </form>';
                }
            }
    }
    else
    {
        //start the transaction
        
        //$query  = "BEGIN WORK;";
        $trans = $pdo->beginTransaction();  
        if(!$trans)
        {
            //Damn! the query failed, quit
            echo 'An error occured while creating your topic. Please try again later.';
        }
        else
        {
            
            //the form has been posted, so save it
            //insert the topic into the topics table first, then we'll save the post into the posts table
            $statement = $pdo->prepare("INSERT INTO topics(topic_subject,
                                        topic_date,
                                        topic_cat,
                                        topic_by)
                            VALUES(:topic_s,:topic_d,:topic_c,:topic_b)" );
            $result = $statement->execute(array( 'topic_s' => addslashes($_POST['topic_subject']), 'topic_d' => date('Y-m-d H:i:s'), 'topic_c' => addslashes($_POST['topic_cat']), 'topic_b' => $_SESSION['userid']));
            if(!$result)
            {
                //something went wrong, display the error
                echo 'An error occured while inserting your data. Please try again later.';
                $pdo->rollBack();
            }
            else
            {
                //retrieve the id of the freshly created topic for usage in the posts query
                $date = date('Y-m-d H:i:s');
                $result = $pdo->query("SELECT MAX(topic_id) as topic_id FROM topics WHERE topic_by =".$_SESSION['userid']);
                $f = $result->fetch();
                $topicid = $f['topic_id'];

                $statement = $pdo->prepare("INSERT INTO posts(post_content,
                                                        post_date,
                                                        post_topic,
                                                        post_by)
                            VALUES(:post_c,:post_d,:post_t,:post_b)");
            $result = $statement->execute(array( 'post_c' => addslashes($_POST['post_content']),'post_d' => $date, 'post_t' => $topicid, 'post_b' => $_SESSION['userid']));

                if(!isset($result))
                {
                    //something went wrong, display the error
                    echo 'An error occured while inserting your post. Please try again later.';
                    $pdo->rollBack();
                }
                else
                {
                    $pdo->commit();
                    echo 'You have successfully created <a href="topic_view.php?id='. $topicid . '">your new topic</a>.';
                }
            }
        }
}
?>
        <?php
        } //Ende von if($showFormular)
        ?>
	</section>
    </div>
    <?php include "footer.php"?>

</body>

</html>