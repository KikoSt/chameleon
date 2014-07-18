<?php

$container = new GfxContainer();
$container->setSource('example.svg');
// ...
$container->parse();
$container->setTarget('SWF');
$container->render();
$container->setTarget('GIF');
$container->render();

