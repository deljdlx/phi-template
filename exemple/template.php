<?php

require(__DIR__.'/_autoload.php');


$template = new \Phi\Template\Template();


$buffer = '<h1>{{title}}</h1>';
$template->loadTemplate($buffer);
$template->set('title', 'hello mustache');
echo $template->render();




$buffer = '<h1>PHP : <?php echo $title;?></h1>';
$template->loadTemplate($buffer);
$template->set('title', 'hello PHP');
echo $template->render();