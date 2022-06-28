<?php

require_once('helpers.php');

$connect = db_set_connection();
$sql_hashtag_query = "SELECT hashtags.hashtag, hp.post_id
                      FROM hashtags
                            LEFT JOIN hashtags_posts hp ON hashtags.id = hp.hashtag_id
                            ORDER BY post_id";
$sql_hashtags = db_get_query('all', $connect, $sql_hashtag_query);

$hashtags = [];
foreach ($sql_hashtags as $sql_hashtag) {
    $post_id = $sql_hashtag['post_id'];
    if (empty($hashtags[$post_id])) {
        $hashtags[$post_id] = [];
    }
    array_push($hashtags[$post_id], $sql_hashtag['hashtag']);
}
