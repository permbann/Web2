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
<?php include "sidebar.php"?>

    <div id="posts">	
    <section>
        <?php

        //select categories with last created topic in each category
        $sql = "SELECT cat_id, cat_name, cat_desc, topic_subject, topic_id, lastdate
        FROM categories left JOIN 
        (
            SELECT topic_subject, A.topic_cat as tc, topic_id, lastdate
            FROM topics JOIN
            (
                SELECT topic_cat, MAX(topic_date) AS lastdate
                FROM topics 
                GROUP by topic_cat
            ) as A   
            WHERE topic_date = lastdate
        ) as B
        ON tc = cat_id or lastdate is NULL     
        ;";
        $result = $pdo->query($sql);

        if(!$result)
        {
            echo 'The categories could not be displayed, please try again later.';
            if($_SESSION['user_level'] == 5)
            {
                echo '<a href="create_cat.php"> Create Category</a>';
            }
        }
        else
        {   
            if($result->columnCount() == 0)
            {
                echo 'No categories defined yet.';
            }
            else
            {
                //prepare the table
                echo '<table class="darkTable">
                    <thead>
                    <tr>
                        <th>Category</th>
                        <th>Last topic</th>
                    </tr>
                    </thead>';
                if($_SESSION['user_level'] == 5) //if admin
                {
                    echo '<tfoot>
                        <td><a href="create_cat.php">Create new category</a></td>
                        <td></td>
                        </tfoot>';
                }
            echo '<tbody>'; 
            foreach ($pdo->query($sql) as $row) 
                {               
                    echo '<tr>';
                        echo '<td class="leftpart">';
                            echo("<h3><a href='category_view.php?id=".$row['cat_id']."'>".$row['cat_name'] ."</a></h3>".$row['cat_desc']);
                        echo '</td>';
                
                        echo '<td class="rightpart">';
                        if(!isset($row['topic_id']))
                        {
                            echo 'no topic jet ;)';
                        }
                        else
                        {
                            echo '<h3><a href="topic.php?id="'.$row['topic_id'].'">'.$row['topic_subject'].'</a></h3><p> at '.$row['lastdate'].'</p>';
                        }
                        echo '</td>';
                    echo '</tr>';
            
                }

                echo '</tbody>
                </table>';
            }
        }
        ?>
    </section>
    </div>

<?php include "footer.php"?>
</body>
</html>