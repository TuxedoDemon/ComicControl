<?php

global $redirect;
$install = false;

$getRowCount = function (string $table) {

    global $tableprefix;
    global $cc;

    $query = "SELECT * FROM cc_" . $tableprefix . "{$table} LIMIT 1";
    $stmt = $cc->prepare($query);
    $stmt->execute();

    return $stmt->rowCount();

};

// CREATE SITE SETTINGS

if (($ccsite ?? null) === null) {
    // if someone sends a request here for some reason, block it
    http_response_code(403);
    exit;
}

if ($ccsite->sitetitle === null) {
    $install = true;
    switch($reqmethod) {
        case "GET":
            require_once('parts/install-site.php');
        return; // we are using returns throughout this instead of breaks so that when an installation form is included, the next one doesn't also get included.
        case "POST":
            if(isset($_POST['install-sitetitle']) && $_POST['install-sitetitle'] !== ""){
                $install = $_POST;
                require_once('parts/install-site-build.php');
                ($ccsite->sitetitle) ? $redirect() : require_once('parts/install-site.php');
            }
        return;
        default:
            http_response_code(405);
            exit;
    }
}

$userCheck = $getRowCount("users");
$moduleCheck = $getRowCount("modules");

// INSTALL USER ACCOUNT

if ($userCheck < 1) { // user check
    $install = true;
    switch($reqmethod) {
        case "GET":
            require_once('parts/install-user.php');
        return;
        case "POST":
            if(isset($_POST['install-username']) && $_POST['install-username'] !== ""){
                $install = $_POST;
                require_once('parts/install-user-build.php');
                ($getRowCount("users") >= 1) ? $redirect() : require_once('parts/install-user.php');
            }
        return;
        default:
            http_response_code(405);
            exit;	
    }
}

require_once('languages/' . $ccuser->language . '.php'); // include the user's language file; default is English

// INSTALL FIRST MODULE

if ($moduleCheck < 1) { // module check
    $install = true;
    switch($reqmethod) {
        case "GET":
            require_once('parts/install-module.php');
        return;
        case "POST":			
            if(isset($_POST['install-pagetitle']) && $_POST['install-pagetitle'] != ""){
                $install = $_POST;
                require_once('parts/install-module-build.php');
                if($getRowCount("modules") >= 1){
                    // if there's a variable indicating the installation is complete, give install complete message
                    if (isset($installed) && $installed === "complete") require_once('parts/install-complete.php');
                } else {
                    require_once('parts/install-module.php');
                }
            }
        return;
        default:
            http_response_code(405);
            exit;		
    }
}

unset($getRowCount, $moduleCheck, $userCheck);
