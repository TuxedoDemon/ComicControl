<?php
if ($ccpage->module->type === "blog" && $ccpage->subslug === "rss") {
    include($ccsite->ccroot . 'parts/blog-rss.php');
    return;
}

include('templates/basic/includes/header.php');

$ccpage->module->display();

include('templates/basic/includes/footer.php');
