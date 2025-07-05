<?php
	error_reporting(E_ALL);
	ini_set('display_errors', 1);

	session_start();
	if (isset($_SESSION['ls'])) $ls = $_SESSION['ls'];
?>

<!DOCTYPE html>
<!--[if lt IE 7]> <html class="lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>    <html class="lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>    <html class="lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html> <!--<![endif]-->
<head>
	<title>InsanPOS</title>
	<link rel="shortcut icon" type="image/png" href="pos.png">
	
	<!-- Meta -->
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	
	<!-- Bootstrap -->
	<link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" />
	<link href="bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet" />
	
	<!-- Glyphicons -->
	<link rel="stylesheet" href="theme/css/glyphicons.css" />
	
	<!-- JQueryUI v1.9.2 -->
	<link rel="stylesheet" href="https://code.jquery.com/ui/1.14.1/themes/base/jquery-ui.css">

	<!-- JQuery -->
	<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
  	<script src="https://code.jquery.com/ui/1.14.1/jquery-ui.js"></script>

	<!-- google-code-prettify -->
	<link href="https://cdnjs.cloudflare.com/ajax/libs/prettify/r298/prettify.css" type="text/css" rel="stylesheet" />
	
	<!-- Theme -->
	<link rel="stylesheet" href="theme/css/style.min.css?1362656653" />
	
	<!-- LESS 2 CSS -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/less.js/3.9.0/less.js"></script>

	<!-- DataTables -->
	<link rel="stylesheet" media="screen" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" />
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.18/b-1.5.4/sl-1.2.6/datatables.min.css"/>

	<!-- DataTables -->
	<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
	<script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.18/b-1.5.4/sl-1.2.6/datatables.min.js"></script>

    <!-- Moment.js -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.js"></script>

	<!-- SELECT2 -->
	<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
	<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

	<style>
		label, input { display:block; }
		input.text { margin-bottom:12px; width:95%; padding: .4em; }
		fieldset { padding:0; border:0; margin-top:25px; }
		h1 { font-size: 1.2em; margin: .6em 0; }
		.ui-dialog .ui-state-error { padding: .3em; }
		.validateTips { border: 1px solid transparent; padding: 0.3em; }
		.iconcolor { color: #7A7474; }
	</style>

	<style>
		.ui-widget-header{
			border-width: 0px 0px 1px 0px;
			border-color: #dddddd;
			background: #fff;
			color: #000;
		}
		p {
			font-size: 15px;
		}
	</style>

	<script>
		$( document ).ready(function() {
			window.sessionStorage.setItem("onhold_order_no", "");
			window.sessionStorage.setItem("isrecalled", "0");
		}); // document ready
	</script>

</head>
<body>

<!-- Start Content -->
<div class="container-fluid">
	<div id="wrapper">
		<!-- Header -->
        <div class="row-fluid" style="background-color: #4e8f64;">
            <div>
                <p style="font-weight: 900; font-size: 150%; color: #fff; padding: 9px 0px 0px 15px;">InsanPOS <span style="font-size: 60%;">v.1.00</span></p>
            </div>
        </div>
		<!-- End of Header -->

        <div id="content" style="background: #fff;"> 

			<div class="row-fluid">
                <div id="login">
                    <form class="form-signin" method="post" action="chklogin.php">
                        <h3 class="glyphicons unlock form-signin-heading"><i></i> Sign in</h3>
                        <div class="uniformjs">
                            <input type="text" name="myuserid" class="input-block-level" placeholder="User ID" autofocus> 
                            <input type="password" name="mypassword" class="input-block-level" placeholder="Password"> 
                            <hr>
							<?php
								if (isset($_SESSION['ls'])) 
                    				if ($ls == "nok") echo '<p style="color:#FF0000">User Id and/or Password wrong!</p>';
                    		?>
                        </div>
                        <button class="btn btn-large btn-warning" type="submit">Sign in</button>
						<a href="admin" target="blank_" class="btn btn-large btn-success">Admin</a>
                    </form>
		    	</div>
            </div>
		
		</div> <!-- content -->
		
	</div> <!-- wrapper -->

</div> 
<!-- container-fluid -->

</body>

</html>

<script>
function toggleFullScreen() {
    if (!document.fullscreenElement) {
        document.documentElement.requestFullscreen();
    } else {
        if (document.exitFullscreen) {
            document.exitFullscreen();
        }
    }
}
</script>