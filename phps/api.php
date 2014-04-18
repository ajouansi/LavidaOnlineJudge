<?
  require_once("../include/db_info.inc.php");
  require_once("../include/apikey.php");

  function check_user_id($user_id) {
    $sql="select count(*) from users where user_id='$user_id'";
    $tmp=@mysql_query($sql);
    $res=@mysql_fetch_row($tmp);

    return $res[0];
  }

  function get_password($user_id) {
    $sql="select password from users where user_id='$user_id'";
    $tmp=@mysql_query($sql);
    $res=@mysql_fetch_row($tmp);

    return $res[0];
  }

  function get_submit_problems($user_id) {
    $sql="select problem_id, result from solution where user_id='$user_id'";
    $tmp=@mysql_query($sql);

    $submit_problems=array();
    while( $res=@mysql_fetch_row($tmp) ) {
      $submit_problems[$res[0]] = $res[1];
    }

    return $submit_problems;
  }
  $key=$_GET['key'];

  if ($key==$api_key){
    $task=$_GET['task'];

    if ($task=='account') {

      $user_id=$_GET['user_id'];

      if( check_user_id($user_id) == 0 ) { //no user
        echo json_encode( array('error' => 'no_such_user') );
        exit;
      }

      $password=get_password($user_id);

      $json = array('password' => $password);
      echo json_encode($json);

    }
    else if ($task=='submit_problems') {
      $user_id=$_GET['user_id'];

      if( check_user_id($user_id) == 0 ) { //no user
        echo json_encode( array('error' => 'no_such_user') );
        exit;
      }

      $submit_problems=get_submit_problems($user_id);

      echo json_encode( array( 'submit_problems' => $submit_problems ) );
    }
    else {
      echo json_encode( array('error' => 'no_such_task') );
    }
  }
  else {
    echo json_encode(array('error' => 'Go away.') );
  }
?>
