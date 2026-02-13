<?php

//index.php - handles the whole site.  Builds page based on the URL slugs.

//start output buffering so we can set cookies whenever we feel like it
ob_start();
error_reporting(E_ALL & ~E_NOTICE);

require_once('includes/formfunctions.php');

$reload = false;
$reqmethod = strtoupper($_SERVER['REQUEST_METHOD']); // is also used in "populate-database.php"
$configfile = file_exists('includes/dbconfig.php');

// INSTALL DATABASE

if(!$configfile) {
    $reload = true;
    switch($reqmethod){
        case "GET":
            require_once('parts/install-database.php');
        return;
        case "POST":
            if(isset($_POST['install-dbname']) && $_POST['install-dbname'] !== ""){
                require_once('parts/install-database-build.php');
                // if the installation was unsuccessful, give the "install-database" form again. Else, set $configfile to true.
                $failed ? require_once('parts/install-database.php') : $configfile = true;
            }
            // if there is no dbconfig file yet, do not load anything else beyond this point.
            if (!$configfile) return;
        break;
        default:
            http_response_code(405);
            exit;
    }
}

require_once('includes/dbconfig.php');
require_once('includes/initialize.php');

// refreshes the page for the user throughout the rest of the installation process.
$redirect = function () {

    global $ccurl;

    \header("Location: {$ccurl}");
    exit;

};

$reload && $redirect(); // refreshes the page to get the user out of $_SERVER['REQUEST_METHOD'] === "POST" mode before continuing installation
require_once('parts/populate-database.php'); // run data checks, install things if they're missing
if ($install) return; // if we're still installing, don't load anything else. Gets set in "populate-database.php".

unset($reload, $redirect, $reqmethod, $configfile, $install); // clean up installation variables

// build the page
$ccpage = new CC_Page($_SERVER["REQUEST_URI"], "admin");

// delete cookies and session if logout requested, but only if the user is actually logged in at all.
// TODO: Move this into the CC_User class and make it into a dedicated "logout" method. Probably also refactor the user class.
if ($ccuser->authlevel > 0 && $ccpage->slugarr[1] === "logout") {
    $stmt = $cc->prepare("SELECT * FROM cc_" . $tableprefix . "users WHERE username=:username LIMIT 1");
    $stmt->execute(['username' => $ccuser->username]);
    $userinfo = $stmt->fetch();
    $loginhash = sha1($userinfo['username'] . $userinfo['salt'] . $ccuser->loginhash);
    $options = [
        "path" => "/",
        "expires" => 1,
        "httponly" => true,
        "secure" => true,
    ];
    foreach(['username', 'loginhash'] as $value){
        setcookie($value, 'bye', $options);
        unset($_COOKIE[$value]);
    }
    $stmt = $cc->prepare("DELETE FROM cc_" . $tableprefix . "sessions WHERE userid=:userid AND loginhash=:loginhash");
    $stmt->execute(["userid" => $userinfo['id'], "loginhash" => $loginhash]);
    header("Location: {$ccurl}");
    exit;
}

//get navigation selection slug
$navslug = getSlug(1);

//create quick links array
$links = array();

//include page header
require_once('includes/header.php');

//include login or password reset for non-authorized user
if ($ccuser->authlevel === 0) {
    if ($navslug === "password-reset") {
        require_once('parts/password-reset.php');
        return;
    }
    require_once('parts/login.php');
    return;
}

//build the sidebar and the top bar if authorized
    require_once('includes/sidebar.php');
    require_once('includes/breadcrumbs.php');
    echo '<section id="rightside">';
        switch($navslug){
            case "modules":
                require_once('parts/module.php');
            break;
            case "image-library":
                require_once('parts/image-library.php');
            break;
            case "site-options":
                require_once('parts/site-options.php');
            break;
            case "manage-modules":
                require_once('parts/manage-modules.php');
            break;
            case "users":
                require_once('parts/users.php');
            break;
            case "templates":
                require_once('parts/templates.php');
            break;
            case "update-check":
                require_once('parts/update-check.php');
            break;
            case "upgrade":
                require_once('parts/upgrade.php');
            break;
            case "plugins":
                require_once('parts/plugins.php');
            break;
            default:
                require_once('parts/home.php');
    }
    echo '</section>';

	//include the page footer
	require_once('includes/footer.php'); 

ob_end_flush();
