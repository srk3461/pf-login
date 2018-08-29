<?php
ob_start();
session_start();
global $brand, $hotelName, $hotelSite, $identificator;
global $today, $build, $usrname, $usrpass;
global $confirmationCode;
global $language, $validLanguages;
global $zone, $redirurl;

$brand="Free Internet";
$language='en';
$lang='en';
$build="v1" ;
$validLanguages='en';
$hotelSite='www.google.com';
// Config file
//include "captiveportal-config.php";

// Get IP and mac address
$ipAddress=$_SERVER['REMOTE_ADDR'];
#run the external command, break output into lines
$arp=`arp $ipAddress`;
$lines = explode(" ", $arp);
$badCheck = false;

if (!empty($lines[3]))
	$macAddress = $lines[3]; // Works on FreeBSD
else
	$macAddress = "fa:ke:ma:c:ad:dr"; // Fake MAC on dev station which is probably not FreeBSD
?>
<!DOCTYPE html>
<!--<?php echo $build . "\n"; ?>-->
<html lang="<?php echo $language; ?>">
<head>
    <meta charset="utf-8">
    <title>Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <link href="captiveportal-bootstrap.min.css" media="screen" rel="stylesheet" type="text/css"/>

    <style type="text/css">
        body {
            background: url('captiveportal-background.jpg') fixed center #2266DD;
            background-repeat: no-repeat;
            background-size: 100%;
        }

        body, html {
            height: 100%;
        }

        .btn {
            font-weight: 700;
            height: 36px;
            -moz-user-select: none;
            -webkit-user-select: none;
            user-select: none;
            cursor: default;
        }

        .form-signin input[type=email],
        .form-signin input[type=password],
        .form-signin input[type=text],
        .form-signin button {
            width: 100%;
            display: block;
            margin-bottom: 10px;
            z-index: 1;
            position: relative;
            -moz-box-sizing: border-box;
            -webkit-box-sizing: border-box;
            box-sizing: border-box;
        }

        .form-signin .form-control:focus {
            border-color: rgb(104, 145, 162);
            outline: 0;
            -webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, .075), 0 0 8px rgb(104, 145, 162);
            box-shadow: inset 0 1px 1px rgba(0, 0, 0, .075), 0 0 8px rgb(104, 145, 162);
        }

        .btn.btn-signin {
            background-color: #FB0E0E;
            padding: 5px;
            font-weight: 700;
            font-size: 14px;
            height: 36px;
            -moz-border-radius: 3px;
            -webkit-border-radius: 3px;
            border-radius: 3px;
            border: 1px;
            -o-transition: all 0.218s;
            -moz-transition: all 0.218s;
            -webkit-transition: all 0.218s;
            transition: all 0.218s;
            color: #FFFFFF;
        }

        .btn.btn-signin:hover,
        .btn.btn-signin:active,
        .btn.btn-signin:focus {
            background-color: #FA6C1D;
        }

        .martop10p {
            margin-top: 10%;
        }

        .uppercase {
            text-transform: uppercase;
        }

        .messagebox {
            /*background-color: #EEEEEE;*/
            background: rgba(238, 238, 238, 0.7);

        }

        .formulaire {
            color: #FFFFFF;
            background-color: #8BB6C8;
            direction: ltr;
            height: 44px;
            font-size: 16px;
        }

        .form-control::-webkit-input-placeholder {
            color: white;
        }

        .form-control:-moz-placeholder {
            color: white;
        }

        .form-control::-moz-placeholder {
            color: white;
        }

        .form-control:-ms-input-placeholder {
            color: white;
        }

        .form-control {
            margin-bottom: 3px;
        }

        .padding10 {
            padding: 10px;
        }

        .curpointer {
            cursor: pointer;
        }

        .logo {
            position: absolute;
            float: left;
            left: 10px;
            opacity: 0.7;
            height: 100%;
        }

        .vertical-text {
            -moz-transform-origin: 0 50%;
            -moz-transform: rotate(-90deg) translate(-50%, 50%);
            -webkit-transform-origin: 0 50%;
            -webkit-transform: rotate(-90deg) translate(-50%, 50%);
            -o-transform-origin: 0 50%;
            -o-transform: rotate(-90deg) translate(-50%, 50%);
            -ms-transform-origin: 0 50%;
            -ms-transform: rotate(-90deg) translate(-50%, 50%);
            transform-origin: 0 50%;
            transform: rotate(-90deg) translate(-50%, 50%);
            position: absolute;
            top: 0;
            bottom: 0;
            left: -50px;
            height: 2em; /* line-height of .wrapper div:first-child span */
            margin: auto;
            font-weight: bold;
            line-height: 2em; /* Copy to other locations */
            color: #FFF;
            opacity: 0.7;
            font-size: 15vh;
        }

        .right {
            float: right;
        }

        label {
            font-weight: normal;
        }

        /* Nice CSS checkbox */
        input[type="checkbox"] {
            opacity: 0.9;
            position: absolute;
            left: -9999px;
        }

        input[type="checkbox"] + label span {
            display: inline-block;
            left: 14px;
            width: 19px;
            height: 19px;
            margin: -1px 4px 0 0;
            vertical-align: middle;
            background: url(captiveportal-check_radio_sheet.png) left top no-repeat;
            cursor: pointer;
        }

        input[type="checkbox"]:checked + label span {
            background: url(captiveportal-check_radio_sheet.png) -19px top no-repeat;
        }

        .modal .modal-body {
            max-height: 420px;
            overflow-y: auto;
            background-color: #EEEEEE;
        }

        .modal-content {
            background-color: #FA6C1D;
          }

      </style>
    <!--  <script type="text/javascript" src="captiveportal-jquery-1.11.3.min.js"></script>
      <script type="text/javascript" src="captiveportal-bootstrap.min.js"></script> -->
  </head>
  <body>
  <!-- Terms Of Use Modal -->

  <div class="container" class="modal fade">
    <div class="vertical-text"><?php echo $brand; ?></div>
      <!--<img class="logo" src="captiveportal-sidelogo.png"> Obsolete png logo -->
      <div class="col-md-2"></div>
      <div class="col-md-8 martop10p">
        <div class="row messagebox padding10">
              <div class="col-md-6">
                  <form class="login-form pad_30" action="" method="post">
                    <input type="text" class="form-control formulaire" id="emailAddress" name="usrname" value=""
                           placeholder="Email" data-original-title="" title="" required>
                    <input type="password" class="form-control formulaire" id="password" name="usrpass" value=""
                           placeholder="Password" data-original-title="" title="" required>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 pad_top_30">
                        <!--<a href="#" class="btn btn-block btn-success manual center-block" title="Sign In">Sign In</a>-->
                        <input type="submit" class="btn btn-signin right btn-primary" name="login" value="Login"
                               data-original-title="" title="">
                        <!--<h4 class="text-center">Forgot Password ? Click  <a href="forget_password.php" style="color: red;">here</a></h4>-->
                    </div>
                </form>
                <a href="ozy-captive.php" class="btn-link">I Don't Have An Account</a>
                <?php
                if (isset($_POST['login'])) {
                  $usrname = $_POST["usrname"];
                  $usrpass = $_POST["usrpass"];

                    global $con;
                    $db_database = 'radius';
                    $db_hostname = 'localhost';
                    $db_username = 'radius';
                    $db_password = 'radpass';
                    $con = mysqli_connect("$db_hostname", "$db_username", "$db_password", "$db_database");
                    if ($con->connect_error) {
                        die("Connection failed: " . $con->connect_error);
                    }

                    $query = mysqli_query($con, "select * from radcheck where (username='$usrname') AND value='$usrpass'") or die(mysqli_error());
                    $_SESSION['usrname'] = $usrname;
                    $rows = mysqli_num_rows($query);
                    $sql = mysqli_fetch_array($query);
                    $_SESSION['usrname'] = $usrname;
                    if ($rows == 1) {
                    ?>
                    <!DOCTYPE html>
                    <html>
                    	<!-- Do not modify anything in this form as pfSense needs it exactly that way -->
                    	<body>
                    			<form name="loginForm" method="post" action="$PORTAL_ACTION$">
                    			<input name="auth_user" type="hidden" value="<?php echo $surName; ?>">
                    			<input name="auth_pass" type="hidden" value="<?php echo $usrpass; ?>">
                    			<input name="zone" type="hidden" value="$PORTAL_ZONE$">
                    			<input name="redirurl" type="hidden" value="$PORTAL_REDIRURL$">
                        </form>
                    		<!-- <script type="text/javascript">
                    			document.getElementById("submitbtn").click();
                    		</script> -->
                    	</body>
                    </html>
                    <?php
                    }   //    <!-- header("location:captiveportal-dashboard.php"); -->
       //     header("location:captiveportal-dashboard.php");
                                     elseif ($rows != 1) {
                                              $login_error = "Username or Password is invalid";
                                              echo "<SCRIPT type='text/javascript'>alert('$login_error');</script>";
                                            }
                                            //mysqli_close($con);
                                        }
                                        ?>
            </div>
        </div>
    </div>
</div>
</body>
</html>
