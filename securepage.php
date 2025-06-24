<?php
function chkpage($seconds)
{
    if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > $seconds)) 
    {
        session_unset();     // unset $_SESSION variable for the run-time 
        session_destroy();   // destroy session data in storage
        
            if( !headers_sent() )
            {
              header("Location: index.php");
            }
            else
            {
              ?>
              <script type="text/javascript">
              document.location.href="index.php";
              </script>
              Redirecting to <a href="index.php">Login page.</a>
              <?php
            }
            die();	    
    }
    
    $_SESSION['LAST_ACTIVITY'] = time(); // update last activity time stamp

    if( !isset($_SESSION['SESS_USER_NAME']) )
    {
        if( !headers_sent() )
        {
          header("Location: index.php");
        }
        else
        {
          ?>
          <script type="text/javascript">
          document.location.href="index.php";
          </script>
          Redirecting to <a href="index.php">Login page.</a>
          <?php
        }
        die();
    }

}
?>