<?php
include('templates/basic/includes/header.php');

($ccpage->module->type === "blog" && $ccpage->subslug === "rss") 
? include(include($ccsite->ccroot . 'parts/comic-rss.php')) 
: $ccpage->module->display(); 

include('templates/basic/includes/footer.php');