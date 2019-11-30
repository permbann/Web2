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
//$sql = "SELECT
//            cat_id,
//            cat_name,
//            cat_desc
//        FROM
//            categories";
$sql = "SELECT cat_id, cat_name, cat_desc, topic_subject, topic_id, MAX(topic_date) AS lastdate
FROM categories LEFT JOIN topics ON topic_cat = cat_id
GROUP BY cat_id;";
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
                    echo("<h3><a href='category_view.php?id=".$row['cat_id']."',".$row['cat_name'] ."</a></h3>".$row['cat_desc']);
                echo '</td>';
                
                echo '<td >';
                if(!isset($row['topic_id']))
                {
                    echo 'no topic jet ;)';
                }
                else
                {
                    echo '<p><a href="topic.php?id="'.$row['topic_id'].'">'.$row['topic_subject'].'</a> at '.$row['lastdate'].'</p>';
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