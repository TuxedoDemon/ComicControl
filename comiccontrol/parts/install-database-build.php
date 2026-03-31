<?php

if (($install ?? null) === null) {
    http_response_code(403);
    exit;
}

$dbhost = $install['install-dbhost'];
$dbname = $install['install-dbname'];
$dbuser = $install['install-dbuser'];
$dbpass = $install['install-dbpass'];
$tableprefix = $install['install-tableprefix'];
$charset = "utf8mb4";

//CONNECT TO DATABASE
$dsn = "mysql:host={$dbhost};dbname={$dbname};charset={$charset}";
$opt = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
	PDO\Mysql::ATTR_FOUND_ROWS => true
];

$failed = false;

try {
    $pdotest = new PDO($dsn, $dbuser, $dbpass, $opt);
} catch (PDOException $error) {
	error_log($error);
    $failed = true;
}

if (!$failed) {

	$creds = [$dbhost, $dbname, $dbuser, $dbpass];
    $tableprefix = str_replace(['$', '"'], ["\\$", "\\\""], $tableprefix);
    $creds = str_replace(['$', '"'], ["\\$", "\\\""], $creds); // escaping any dollar signs/double quotes that might be hiding in the
                                              // provided credentials so PHP doesn't mistake them for variables/ends of strings
	$dbconfigtxt = '<?php
	//dbconfig.php - connects to database

	//DATABASE INFO
	$dbhost = "' . $creds[0] . '";
	$dbname = "' . $creds[1] . '";
	$dbuser = "' . $creds[2] . '";
	$dbpass = "' . $creds[3] . '";
	$charset = "utf8mb4";

	//CONNECT TO DATABASE
	$dsn = "mysql:host={$dbhost};dbname={$dbname};charset={$charset}";
	$opt = [
		PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
		PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
		PDO::ATTR_EMULATE_PREPARES   => false,
		PDO\Mysql::ATTR_FOUND_ROWS => true
	];

	$cc = new PDO($dsn, $dbuser, $dbpass, $opt);
	$tableprefix = "' . $tableprefix . '";
    ';

	file_put_contents('includes/dbconfig.php', $dbconfigtxt);

	include('includes/dbconfig.php');
	
	$sqlquery = file_get_contents("install.sql");
	$sqlquery = str_replace("_temp_","_{$tableprefix}", $sqlquery);
	
	$cc->setAttribute(PDO::ATTR_EMULATE_PREPARES, 0);

	try {
		$cc->exec($sqlquery);
	} catch (PDOException $e) {
		echo $e->getMessage();
		die();
	}
	
	unlink('install.sql');

}
