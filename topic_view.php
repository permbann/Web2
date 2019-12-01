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
    $topid = $_GET['id'];
    $sql = "SELECT
                topic_id,
                topic_subject
            FROM
                topics
            WHERE
                topics.topic_id = " . $topid;
    $sql2 ="SELECT
                posts.post_topic,
                posts.post_content,
                posts.post_date,
                posts.post_by,
                users.user_id,
                users.u_name
            FROM
                posts
            LEFT JOIN
                users
            ON
                posts.post_by = users.user_id
            WHERE
                posts.post_topic = " . $topid;

    $result = $pdo->query($sql);
    $f = $result->fetch();
    $result = $f['topic_subject'];

//prepare table
echo '<table class="darkTable">
<thead>
<tr>
  <th>'.$result.'</th>
  <th>Poster</th>
</tr>
</thead>';
echo '<tbody>'; 
foreach ($pdo->query($sql2) as $row)
{              
    echo '<tr>';
        echo '<td class="leftpart">';
            echo $row['post_content'];
        echo '</td>';
        echo '<td class="rightpart">';
            echo $row['u_name'] . '   ' . $row['post_date'];
        echo '</td>';
    echo '</tr>';
}
            
echo '</tbody>
<tfoot>
<form method="post" action="reply.php?id='.$_GET['id'].'">
<td>
    <textarea id="replyarea" name="reply-content"></textarea>
</td>
<td><input class="button" type="submit" value="Comment" /></td>
</form>
</tfoot>
</table>';

?>

</form>

</section>
</div>

<?php include "footer.php"?>

</body>

</html>