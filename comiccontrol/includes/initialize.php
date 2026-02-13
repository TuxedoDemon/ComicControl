<?php
//initialize.php - builds base classes and objects for both front and backend

//initialize the lang array
$lang = array();

require_once('universal-functions.php');
require_once('classes.php');

//create objects
$ccsite = new CC_Site();
$ccuser = new CC_User();
date_default_timezone_set($ccsite->timezone);

//quick access URL string
$siteurl = $ccsite->root;
$ccurl = $ccsite->root.$ccsite->ccroot;
