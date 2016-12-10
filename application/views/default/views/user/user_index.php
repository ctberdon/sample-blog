<!DOCTYPE HTML>
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Login with Google Account by CodexWorld</title>
<style type="text/css">
h1
{
font-family:Arial, Helvetica, sans-serif;
color:#999999;
}
.wrapper{width:600px; margin-left:auto;margin-right:auto;}
.welcome_txt{
	margin: 20px;
	background-color: #EBEBEB;
	padding: 10px;
	border: #D6D6D6 solid 1px;
	-moz-border-radius:5px;
	-webkit-border-radius:5px;
	border-radius:5px;
}
.google_box{
	margin: 20px;
	background-color: #FFF0DD;
	padding: 10px;
	border: #F7CFCF solid 1px;
	-moz-border-radius:5px;
	-webkit-border-radius:5px;
	border-radius:5px;
}
.google_box .image{ text-align:center;}
</style>
</head>
<body>
<?php
if(!empty($google_auth_url)) {
	echo '<a href="'.$google_auth_url.'"><img src="'.base_url('themes/default/assets/images/google-signin/1x/btn_google_signin_dark_focus_web.png').'" alt=""/></a>';
}else{

?>
<div class="wrapper">
    <h1>Google Profile Details </h1>
    <?php
    echo '<div class="welcome_txt">Welcome <b>'.$userdata['first_name'].'</b></div>';
    echo '<div class="google_box">';
    echo '<p class="image"><img src="'.$userdata['picture_url'].'" alt="" width="300"/></p>';
    echo '<p><b>Google ID : </b>' . $userdata['oauth_uid'].'</p>';
    echo '<p><b>Name : </b>' . $userdata['first_name'].' '.$userdata['last_name'].'</p>';
    echo '<p><b>Email : </b>' . $userdata['email'].'</p>';
    echo '<p><b>Gender : </b>' . $userdata['gender'].'</p>';
    echo '<p><b>Locale : </b>' . $userdata['locale'].'</p>';
    echo '<p><b>Google+ Link : </b>' . $userdata['profile_url'].'</p>';
    echo '<p><b>You are login with : </b>Google</p>';
    echo '<p><b>Logout from <a href="'.base_url().'user/authentication/logout">Google</a></b></p>';
    echo '</div>';
    ?>
</div>
<?php } ?>
</body>
</html>