<?php

$pdo = new PDO( 'mysql:host=localhost;dbname=content', 'root' );


$statement = $pdo->prepare("INSERT INTO topics(topic_subject,
                                        topic_date,
                                        topic_cat,
                                        topic_by)
                            VALUES(:topic_s,:topic_d,:topic_c,:topic_b)" );
            $result = $statement->execute(array( 'topic_s' => "testnr1", 'topic_d' => date('Y-m-d H:i:s'), 'topic_c' => 3, 'topic_b' => 3));
            //echo("Category " . $cat_name . " has been Created!");
            echo("topic reated");

            $topicid = 1;

                $statement = $pdo->prepare("INSERT INTO posts(post_content,
                                                        post_date,
                                                        post_topic,
                                                        post_by)
                            VALUES(:post_c,:post_d,:post_t,:post_b)");
            $result = $statement->execute(array( 'post_d' => date('Y-m-d H:i:s'), 'post_t' => $topicid, 'post_b' => 3));
?>