<?php
session_start();
if ( !isset( $_SESSION[ 'userid' ] ) ) {
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
    <?php include "sidebar.php"?>
    <div id="posts">
	<section>

<?php

//first select the category based on $_GET['cat_id']
$sql = "SELECT
            cat_id,
            cat_name,
            cat_desc
        FROM
            categories
        WHERE
            cat_id = " . $_GET['id'];

$result = $pdo->query($sql);




if(!isset($result))
{
    echo 'The category could not be displayed, please try again later.';
}
else
{
    if($result->columnCount() == 0)
    {
        echo 'This category does not exist.';
    }
    else
    {
        foreach ( $pdo->query($sql) as $row) {
            echo '<h2>Topics in ' . $row['cat_name'] . '</h2>';
         }
     
        //do a query for the topics
        $sql = "SELECT  
                    topic_id,
                    topic_subject,
                    topic_date,
                    topic_cat
                FROM
                    topics
                WHERE
                    topic_cat = " . $_GET['id'];
         
        $result = $pdo->query($sql)->fetch();
        if(!$result)
        {
            echo 'The topics could not be displayed, please try again later.';
        }
        else
        {
                //prepare the table
                echo '<table class="darkTable">
                      <thead>
                      <tr>
                        <th>Topic</th>
                        <th>Created at</th>
                      </tr>
                      </thead>';
                if($_SESSION['user_level'] == 5)
                {
                    echo '<tfoot>
                            <td>Create a new</td>
                            <td><a href="create_cat.php">Category</a></td>
                            </tfoot>';
                }
                echo '<tbody>'; 
                foreach ($pdo->query($sql) as $row)
                {               
                    echo '<tr>';
                        echo '<td class="leftpart">';
                            echo '<h3><a href="topic_view.php?id=' . $row['topic_id'] . '">' . $row['topic_subject'] . '</a><h3>';
                        echo '</td>';
                        echo '<td class="rightpart">';
                            echo date('d-m-Y', strtotime($row['topic_date']));
                        echo '</td>';
                    echo '</tr>';
                }
                echo '</tbody>
                </table>';
        }
    }
}
?>
	</section>
</div>
<?php include "footer.php"?>

</body>

</html>