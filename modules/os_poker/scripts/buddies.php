<?php


$relationship = new stdClass();
$relationship->requester_id = 100526;
$relationship->rtid = 1;
$relationship->flags = 0;
$relationship->create_at = $relationship->updated_at = time();

$results = db_query("SELECT uid FROM users WHERE uid NOT IN (SELECT requestee_id FROM user_relationships WHERE requester_id = 100526 AND approved = 1) LIMIT 150");
while ($user = db_fetch_array($results)) {
  //var_dump($uid);
  unset($relationship->rid);
  $relationship->requestee_id = $user['uid'];
  if (user_relationships_save_relationship($relationship, 'approve')) {
    drupal_set_message("$uid is not a buddy of 100526", 'success');
  }  
  else {
    drupal_set_message("Cannot make $uid a buddy of 100526", 'error');
  }
}

