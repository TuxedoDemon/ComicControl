<?php

if (($install ?? null) === null) {
    http_response_code(403);
    exit;
}

$pagetitle = htmlspecialchars($install['install-pagetitle']);

//if it's a comic, just make the slug comic
if($install['install-moduletype'] === "comic") {
    $slugfinal = "comic";
} else {
    //find available slug
    $slug = toSlug($install['install-pagetitle']);

    while(strpos($slug, '--') !== false){
        $slug = str_replace('--','-',$slug);
    }

    $stmt = $cc->prepare("SELECT * FROM cc_" . $tableprefix . "modules WHERE slug=:slug LIMIT 1");
    $stmt->execute(['slug' => $slug]);
    $count = 2;
    $slugfinal = $slug;
    while($stmt->fetch()){
        $slugfinal = $slug . '-' . $count;
        $stmt->execute(['slug' => $slugfinal]);
        $count++;
    }

}

//add the module to the database
$query = "INSERT INTO cc_" . $tableprefix . "modules(title,moduletype,template,language,slug) VALUES(:title,:moduletype,:template,:language,:slug)";
$stmt = $cc->prepare($query);
$stmt->execute(['title' => $pagetitle, 'moduletype' => $install['install-moduletype'], 'template' => $install['install-template'], 'language' => $install['install-language'],  'slug' => $slugfinal]);

//get the new module id for adding options
$moduleid = $cc->lastInsertId();

//add default options depending on the module
$option = array();

switch($_POST['install-moduletype']){
	case 'comic':
		$option['displaytags'] = "on";
		$option['newsmode'] = "eachpost";
		$option['clickaction'] = "next";
		$option['comicwidth'] = 900;
		$option['navaux'] = "rss";
		$option['thumbwidth'] = 200;
		$option['thumbheight'] = 200;
		$option['touchaction'] = "hovertext";
		$option['navorder'] = "first|prev|aux|next|last";
		$option['perpage'] = 15;
		$option['displaytranscript'] = "off";
		$option['displaycomments'] = "on";
		$option['contentwarnings'] = "off";
		$option['chapterthumbs'] = "on";
		$option['pagethumbs'] = "off";
		$option['pagetitles'] = "off";
		$option['transcriptclick'] = "on";
		$option['firsttext'] = "";
		$option['prevtext'] = "";
		$option['nexttext'] = "";
		$option['lasttext'] = "";
		$option['auxtext'] = "";
		$option['arrowkey'] = "off";
		break;
	case 'blog':
		$option['perpage'] = 10;
		$option['displaycomments'] = "on";
		$option['displaytags'] = "on";
		$option['archiveorder'] = "DESC";
		break;
	case 'gallery':
		$option['showTitle'] = "on";
		$option['showDescription'] = "on";
		$option['thumbwidth'] = 200;
		$option['thumbheight'] = 200;
		break;
	case 'text':
		$option['showTitle'] = "on";
		break;
}

$query = "INSERT INTO cc_" . $tableprefix . "modules_options(moduleid,optionname,value) VALUES(:moduleid,:optionname,:value)";
$stmt = $cc->prepare($query);
foreach($option as $optionname => $value){
	$stmt->execute(['moduleid' => $moduleid, 'optionname' => $optionname, 'value' => $value]);
}

if($_POST['install-moduletype'] == "text" || $_POST['install-moduletype'] == "gallery"){
	//if text module or gallery module, add to text table
	$query = "INSERT INTO cc_" . $tableprefix . "text(id,content) VALUES(:id,'')";
	$stmt = $cc->prepare($query);
	$stmt->execute(['id' => $moduleid]);
}

//make sure this is set as the home page
$query = "UPDATE cc_" . $tableprefix . "options SET optionvalue=:optionvalue WHERE optionname=:optionname LIMIT 1";
$stmt = $cc->prepare($query);
$stmt->execute(['optionname' => 'homepage','optionvalue' => $moduleid]);

$installed = "complete";
?>