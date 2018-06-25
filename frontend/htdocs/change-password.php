<!doctype html>
<html>

<head>
<title>Profile - <?= $CFG->exchange_name; ?></title>

<meta property="viewport" name="viewport" content="width=device-width, initial-scale=1.0">

<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="css/new-style.css" rel="stylesheet" />
<link href="css/dashboard.css" rel="stylesheet" />
<link href="css/profile.css" rel="stylesheet" />
<!-- <link rel="stylesheet" href="css/style.css?v=20160204" type="text/css" /> -->
    <link rel="stylesheet" href="css/font-awesome/css/font-awesome.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script>
$("div").click(function() {
window.location = $(this).find("a").attr("href");
return false;
});
</script>

<meta name="viewport" content="width=device-width, initial-scale=1.0" data-react-helmet="true">
<style>
.messages{
    top: 7em;
}
</style>
</head>

<body class="app signed-in static_application index" data-controller-name="static_application" data-action-name="index" data-view-name="Coinbase.Views.StaticApplication.Index" data-account-id="">

<?php

include '../lib/common.php';

if (User::$awaiting_token)
	Link::redirect('verify-token?email_auth=1&authcode='.urlencode($_REQUEST['authcode']));
elseif (!User::isLoggedIn())
	Link::redirect('login');

$authcode1 = (!empty($_REQUEST['authcode'])) ? urldecode($_REQUEST['authcode']) : false;
$authcode_valid = false;
$uniq1 = (!empty($_REQUEST['settings'])) ? $_REQUEST['settings']['uniq'] : $_REQUEST['uniq'];
$token1 = (!empty($_REQUEST['token'])) ? preg_replace("/[^0-9]/", "",$_REQUEST['token']) : false;
$request_2fa = false;

/*
if (!empty($_REQUEST['ex_request'])) {
	$_REQUEST = unserialize(urldecode($_REQUEST['ex_request']));
}
*/

// check for authcode or redirect if invalid
if ($authcode1) {
	API::add('User','getSettingsChangeRequest',array(urlencode($authcode1)));
	$query = API::send();
	$authcode_valid = $query['User']['getSettingsChangeRequest']['results'][0];
}

// if (!$authcode1 || !$authcode_valid) {
// 	User::logOut(true);
// 	Link::redirect('login.php');
// 	exit;
// }

// check if form submitted and process
if (!empty($_REQUEST['settings'])) {
	$match = preg_match_all($CFG->pass_regex,$_REQUEST['settings']['pass'],$matches);
	$_REQUEST['settings']['pass'] = preg_replace($CFG->pass_regex, "",$_REQUEST['settings']['pass']);
	$too_few_chars = (mb_strlen($_REQUEST['settings']['pass'],'utf-8') < $CFG->pass_min_chars);
}

API::add('User','getInfo',array($_SESSION['session_id']));
$query = API::send();

$personal = new Form('settings',false,false,'form1','site_users');
$personal->verify();
$personal->get($query['User']['getInfo']['results'][0]);

if (!empty($_REQUEST['settings']) && $_SESSION['cp_uniq'] != $uniq1)
		$personal->errors[] = 'Page expired.';

if (!empty($match))
	$personal->errors[] = htmlentities(str_replace('[characters]',implode(',',array_unique($matches[0])),Lang::string('login-pass-chars-error')));
if (!empty($too_few_chars))
	$personal->errors[] = Lang::string('login-password-error');

// check if we should request 2fa
/*
if (!empty($_REQUEST['settings']) && !$token1 && !is_array($personal->errors) && !is_array(Errors::$errors)) {
	if (!empty($_REQUEST['request_2fa'])) {
		if (!($token1 > 0)) {
			$no_token = true;
			$request_2fa = true;
			Errors::add(Lang::string('security-no-token'));
		}
	}

	if (User::$info['verified_authy'] == 'Y' || User::$info['verified_google'] == 'Y') {
		if ($_REQUEST['send_sms'] || User::$info['using_sms'] == 'Y') {
			if (User::sendSMS()) {
				$sent_sms = true;
				Messages::add(Lang::string('withdraw-sms-sent'));
			}
		}
		$request_2fa = true;
	}
}
*/

// display errors or send pass change request
if (!empty($_REQUEST['settings']) && !empty($personal->errors)) {
	$errors = array();
	foreach ($personal->errors as $key => $error) {
		if (stristr($error,'login-required-error')) {
			$errors[] = Lang::string('settings-'.str_replace('_','-',$key)).' '.Lang::string('login-required-error');
		}
		elseif (strstr($error,'-')) {
			$errors[] = Lang::string($error);
		}
		else {
			$errors[] = $error;
		}
	}
	Errors::$errors = $errors;
}
elseif (!empty($_REQUEST['settings']) && empty($personal->errors)) {
	if (empty($no_token) && !$request_2fa) {
		//$authcode2 = (User::$info['verified_authy'] == 'Y' || User::$info['verified_google'] == 'Y') ? false : $authcode1;
		//API::settingsChangeId($authcode2);
        //API::token($token1);
        if($authcode1){
        API::settingsChangeId($authcode1);
        API::add('User','changePassword',array($personal->info['pass']));
        }
        else{
            API::add('User','firstLoginPassChange',array($personal->info['pass']));
        }
        $query = API::send();
		if (!empty($query['error'])) {
			if ($query['error'] == 'security-com-error')
				Errors::add(Lang::string('security-com-error'));
				
			if ($query['error'] == 'authy-errors')
				Errors::merge($query['authy_errors']);
				
			if ($query['error'] == 'request-expired')
				Errors::add(Lang::string('settings-request-expired'));
				
			if ($query['error'] == 'security-incorrect-token')
				Errors::add(Lang::string('security-incorrect-token'));
		}
		if (!is_array(Errors::$errors)) {
			$_SESSION["cp_uniq"] = md5(uniqid(mt_rand(),true));
			Link::redirect('userprofile?message=settings-personal-message');
		}
		else
			$request_2fa = true;
	}
}
else {
	$personal->info['pass'] = false;
}

$_SESSION["cp_uniq"] = md5(uniqid(mt_rand(),true));
$page_title = Lang::string('change-password');

?>
<style>
em {
	display:none ;
}
</style>
<div id="root">
<div class="Flex__Flex-fVJVYW iJJJTg">
<div class="Flex__Flex-fVJVYW iJJJTg">
<div class="Toasts__Container-kTLjCb jeFCaz"></div>
<div class="Layout__Container-jkalbK gCVQUv Flex__Flex-fVJVYW bHipRv">
<div class="LayoutDesktop__AppWrapper-cPGAqn WhXLX Flex__Flex-fVJVYW bHipRv">
    
    <? include 'includes/topheader.php'; ?>

    <div class="LayoutDesktop__ContentContainer-cdKOaO cpwUZB Flex__Flex-fVJVYW bHipRv">
        
    <? include 'includes/menubar.php'; ?>
     <div class="banner">
	      <div class="container content">
	          <h1>Change Password</h1>
	      </div>
	  </div>
    <div class="LayoutDesktop__Wrapper-ksSvka fWIqmZ Flex__Flex-fVJVYW cpsCBW">
        <div class="LayoutDesktop__Content-flhQBc bRMwEm Flex__Flex-fVJVYW gkSoIH">
            <div class="Dashboard__FadeFlex-bFoDXs cYFmKg Flex__Flex-fVJVYW iDqRrV">
                <div class="Flex__Flex-fVJVYW bHipRv">
                    <div></div>
                    <div class="Dashboard__Panels-getBDx fJxaut Flex__Flex-fVJVYW iDqRrV">
                        <div class="Flex__Flex-fVJVYW bHipRv">
                            <div class="Flex__Flex-fVJVYW gsOGkq">

                               <div class="Dashboard__ChartContainer-bKDMTA kjRPPr Flex__Flex-fVJVYW iDqRrV" style="height: auto;">
                                    <div class="Flex__Flex-fVJVYW gsOGkq" style="width: 100%;border-right: none;">
                                        <div id="page" class="jdmxYg" style="width: 100%;">
                                     <!--  <style>
                                          .message-box-wrap{
                                              position: relative !important;
                                          }
                                          </style> -->
                                           <? 
            Errors::display(); 
            Messages::display();
            ?>
            <?= (!empty($notice)) ? '<div class="notice"><div class="message-box-wrap">'.$notice.'</div></div>' : '' ?>
            <div class="row profile-image-errors" style="display:none;">
                <div>
                    <div class="alert"></div>
                </div>
            </div>
                                        <div class="row">
                                       
<div class="container" style="width:100%">
	<div class="content_right">
		<div class="testimonials-4">
			<!-- <? 
            Errors::display(); 
            Messages::display();
            ?> -->
            <? if (!$request_2fa) { ?>
            <div class="text"><p><?= Lang::string('change-password-explain') ?></p></div>
            <div class="content">
                <div class="clear"></div>
                <?
                $personal->passwordInput('pass',Lang::string('settings-pass'),true);
                $personal->passwordInput('pass2',Lang::string('settings-pass-confirm'),true,false,false,false,false,false,'pass');
                $personal->HTML('<div class="form_button"><input type="submit" name="submit" value="'.Lang::string('settings-save-password').'" class="but_user" /></div>');
                $personal->hiddenInput('uniq',1,$_SESSION["cp_uniq"]);
                $personal->HTML('<input type="hidden" name="authcode" value="'.urlencode($authcode1).'" />');
                $personal->display();
                ?>
            	<div class="clear"></div>
            </div>
            <? } else { ?>
            <div class="content">
				<h3 class="section_label">
					<span class="left"><i class="fa fa-mobile fa-2x"></i></span>
					<span class="right"><?= Lang::string('security-enter-token') ?></span>
				</h3>
				<form id="enable_tfa" action="change-password.php" method="POST">
					<input type="hidden" name="request_2fa" value="1" />
					<input type="hidden" name="authcode" value="<?= urlencode($authcode1) ?>" />
					<input type="hidden" name="uniq" value="<?= $_SESSION["cp_uniq"] ?>" />
					<input type="hidden" name="ex_request" value="<?= urlencode(serialize($_REQUEST)) ?>" />
					<div class="buyform">
						<div class="one_half">
							<div class="spacer"></div>
							<div class="spacer"></div>
							<div class="spacer"></div>
							<div class="param">
								<label for="token"><?= Lang::string('security-token') ?></label>
								<input name="token" id="token" type="text" value="<?= $token1 ?>" />
								<div class="clear"></div>
							</div>
							 <div class="mar_top2"></div>
							 <ul class="list_empty">
								<li><input type="submit" name="submit" value="<?= Lang::string('security-validate') ?>" class="but_user" /></li>
								<? if (User::$info['using_sms'] == 'Y') { ?>
								<li><input type="submit" name="sms" value="<?= Lang::string('security-resend-sms') ?>" class="but_user" /></li>
								<? } ?>
							</ul>
						</div>
					</div>
				</form>
                <div class="clear"></div>
			</div>
            <? } ?> 
            <div class="mar_top8"></div>
        </div>
	</div>
</div>
</div>
                                    </div>
                                </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
	
        <!-- Footer Section Starts Here -->
        <? include 'includes/footer.php'; ?>
        <!-- Footer Section Ends Here -->
        <div class="Backdrop__LayoutBackdrop-eRYGPr cdNVJh"></div>
    </div>
</div>
</div>
</div>
<div></div>
</div>
</div>
<script>
$(document).ready(function(){
$(".Header__DropdownButton-dItiAm").click(function(){
$(".DropdownMenu__Wrapper-ieiZya.kwMMmE").toggleClass("show-menu");
});
});
</script>
</body>

</html>