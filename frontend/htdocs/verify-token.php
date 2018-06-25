<!doctype html>
<html>

<head>
<title>Authentication</title>

<meta property="viewport" name="viewport" content="width=device-width, initial-scale=1.0">

<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="css/new-style.css" rel="stylesheet" />
<link href="css/dashboard.css" rel="stylesheet" />
<link href="css/security.css" rel="stylesheet" />
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
</head>

<body class="app signed-in static_application index" data-controller-name="static_application" data-action-name="index" data-view-name="Coinbase.Views.StaticApplication.Index" data-account-id="">
<?php
// error_reporting(E_ALL); 
// ini_set('display_errors', 'On');
include '../lib/common.php';

if (User::isLoggedIn())
    Link::redirect('userprofile.php');
elseif (!User::$awaiting_token)
    Link::redirect('login.php');

$token1 = (!empty($_REQUEST['token'])) ? preg_replace("/[^0-9]/", "",$_REQUEST['token']) : false;
$dont_ask1 = !empty($_REQUEST['dont_ask']);
$authcode1 = (!empty($_REQUEST['authcode'])) ? urldecode($_REQUEST['authcode']) : false;

if (!empty($_REQUEST['step']) && $_REQUEST['step'] == 1) {
    if (!($token1 > 0))
        Errors::add(Lang::string('security-no-token'));
    
    if (!is_array(Errors::$errors)) {
        $verify = User::verifyToken($token1,$dont_ask1);
        if ($verify) {
            if (!empty($_REQUEST['email_auth']))
                Link::redirect('dashboard.php?authcode='.urlencode($_REQUEST['authcode']));
            else
                Link::redirect('dashboard.php');
            exit;
        }
    }
}

API::add('Content','getRecord',array('security-token-login'));
$query = API::send();

$content = $query['Content']['getRecord']['results'][0];
$page_title = Lang::string('verify-token');

?>
<div id="root">
<div class="Flex__Flex-fVJVYW iJJJTg">
<div class="Flex__Flex-fVJVYW iJJJTg">
<div class="Toasts__Container-kTLjCb jeFCaz"></div>
<div class="Layout__Container-jkalbK gCVQUv Flex__Flex-fVJVYW bHipRv">
<div class="LayoutDesktop__AppWrapper-cPGAqn WhXLX Flex__Flex-fVJVYW bHipRv">
    
    <? include 'includes/topheader.php'; ?>

    <div class="LayoutDesktop__ContentContainer-cdKOaO cpwUZB Flex__Flex-fVJVYW bHipRv">
        
    <? include 'includes/menubar.php'; ?>

    <div class="LayoutDesktop__Wrapper-ksSvka fWIqmZ Flex__Flex-fVJVYW cpsCBW">
        <div class="LayoutDesktop__Content-flhQBc bRMwEm Flex__Flex-fVJVYW gkSoIH">
            <div class="Dashboard__FadeFlex-bFoDXs cYFmKg Flex__Flex-fVJVYW iDqRrV">
                <div class="Flex__Flex-fVJVYW bHipRv">
                    <div></div>
                    <div class="Dashboard__Panels-getBDx fJxaut Flex__Flex-fVJVYW iDqRrV">
                        <div class="Flex__Flex-fVJVYW bHipRv">
                            <div class="Flex__Flex-fVJVYW gsOGkq">

                               <div class="Dashboard__ChartContainer-bKDMTA kjRPPr Flex__Flex-fVJVYW iDqRrV" style="height: auto;">
                                    <div class="Flex__Flex-fVJVYW gsOGkq" style="border: 1px solid #DAE1E9;width: 100%;border-right: none;">
                                        <div id="page" class="jdmxYg" style="width: 100%;padding-bottom: 2em;">

                                        <div class="row" style="margin: 0 !important;">
                                            <ul id="account_tabs" class="nav nav-tabs">
                                                <li <? if ($CFG->self == 'userprofile.php') { ?> class="active" <?php } ?>>
                                                    <a href="userprofile.php">Profile</a>
                                                </li>
                                                <li <? if ($CFG->self == 'bank-accounts.php') { ?> class="active" <?php } ?>>
                                                    <a href="bank-accounts.php">Bank</a>
                                                </li>
                                                <li <? if ($CFG->self == 'usersecurity.php') { ?> class="active" <?php } ?>>
                                                    <a href="usersecurity.php">Security</a>
                                                </li>
                                                <li>
                                                    <a href="userapi.php" <? if ($CFG->self == 'userapi.php') { ?> class="active" <?php } ?>>API</a>
                                                </li>

                                            </ul>
                                           
                        <div class="row fields">
            <? Errors::display(); ?>                        
        <legend><?= Lang::string('security-enter-token') ?></legend>
        <div class="text"><?= $content['content'] ?></div>
        <div class="span9 marginmobile" style="padding-bottom: 0;">
            <form id="enable_tfa" action="verify-token.php" method="POST">
                <input type="hidden" name="step" value="1" />
                <input type="hidden" name="email_auth" value="<?= !empty($_REQUEST['email_auth']) ?>" />
                <input type="hidden" name="authcode" value="<?= urlencode($authcode1) ?>" />
            <div class="control-group user-email">
                <span class="control-label formtexts" style="text-align: left;width: 190px;float: left;">
                    <label class="formlabel" for="user_email" style="font-weight: 600;margin-bottom: 0;line-height: 1.9em;color: #5a5f6d;"><?= Lang::string('security-token') ?></label>
                </span>
                <div class="controls">
                    <input type="text" name="token" id="token" value="<?= $token1 ?>" />
                <div>
                <input type="submit" name="submit" style="margin-bottom:1em;float: right;" value="<?= Lang::string('security-validate') ?>" class="btn trigger-challenge-2fa" />
          </div>
                </div>
            </div>
            </form>
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
        <?php include "includes/footer.php"; ?>
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
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script>
    document.getElementById('test').addEventListener('change', function () {
    var style = this.value == 1 || this.value == 2 ? 'block' : 'none';
    document.getElementById('hidden_div').style.display = style;

});
    </script>
    <script type="text/javascript" src="js/ops.js?v=20160210"></script>

</body>

</html>