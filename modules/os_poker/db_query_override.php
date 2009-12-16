function db_query_overridable()
{
  global $db_query_override;  
  if ($db_query_override == NULL) {
    $db_query_override = db_query;
  }
  $args = func_get_args();  
  call_user_func_array(db_query, $args);
}
