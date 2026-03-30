<?php

if (($install ?? null) === null) {
    http_response_code(403);
    exit;
}

$options = array();

$options['timezone'] = $install['install-timezone'];
$options['sitetitle'] = htmlspecialchars($install['install-sitetitle']);
$options['commentname'] = $install['install-shortname'];
$options['root'] = $install['install-siteroot'];
$options['relativepath'] = $install['install-relativepath'];
$options['dateformat'] = $install['install-dateformat'];
$options['timeformat'] = $install['install-timeformat'];
$options['ccroot'] = $install['install-ccpath'];
$options['version'] = "4.2.9";
$options['language'] = $install['install-language'];
$options['jquery'] = "https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js";
$options['hammerjs'] = "https://ajax.googleapis.com/ajax/libs/hammerjs/2.0.8/hammer.min.js";
$options['homepage'] = 1;
$options['comments'] = "disqus";
$options['description'] = htmlspecialchars($install['install-description']);
$options['updatechecked'] = 0;
$options['newestversion'] = "4.2.9";

$query = "INSERT INTO cc_" . $tableprefix . "options(optionname,optionvalue) VALUES(:optionname,:optionvalue)";
$stmt = $cc->prepare($query);

foreach($options as $optionname => $optionvalue){
	$stmt->execute(['optionname' => $optionname, 'optionvalue' => $optionvalue]);
}
	
//build the htaccess for the root directory
$query = "SELECT * FROM cc_" . $tableprefix . "htaccess ORDER BY id ASC";
$stmt = $cc->prepare($query);
$stmt->execute();
$parts = $stmt->fetchAll();

$prepend = array_shift($parts);
$append = array_shift($parts);
$mainhtaccess = array_shift($parts);

$stmt = $cc->prepare("SELECT * FROM cc_" . $tableprefix . "options WHERE optionname='relativepath'");
$stmt->execute();
$root = $stmt->fetch();

$htaccesstext = $prepend['content'] . PHP_EOL . $mainhtaccess['content'] . PHP_EOL . $append['content'];
file_put_contents('../.htaccess', $htaccesstext);

$ccsite = new CC_Site();
