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
        <textarea name="reply-content"></textarea>
    </td>
    <td><input type="submit" value="Submit reply" /></td>
    </form>
    </tfoot>
    </table>';
    ?>
    </form>
	</section>

    <?php include "footer.php"?>

</body>

</html>