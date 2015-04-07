<?php
/**
 * kipelovets <kipelovets@mail.ru>
 */

date_default_timezone_set('Europe/Moscow');

require_once __DIR__.'/app/bootstrap.php.cache';
require_once __DIR__.'/app/AppKernel.php';

$kernel = new AppKernel('prod', false);
$kernel->loadClassCache();
$kernel->boot();