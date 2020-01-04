<?php

require(__DIR__.'/_autoload.php');


$template = new \Phi\Template\Template();

$template->loadFile(__DIR__.'/asset/template.php');

$template->set('title', 'hello PHP');
echo $template->render();