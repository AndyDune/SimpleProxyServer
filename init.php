<?php
/**
 * ----------------------------------------------
 * | Author: Andrey Ryzhov (Dune) <info@rznw.ru> |
 * | Site: www.rznw.ru                           |
 * | Phone: +7 (4912) 51-10-23                   |
 * | Date: 21.03.2018                            |
 * -----------------------------------------------
 *
 */
include(__DIR__ . '/vendor/autoload.php');
$config = include(__DIR__ . '/config.php');
$configLocalFile = __DIR__ . '/config.local.php';
if (file_exists($configLocalFile)) {
    $config = array_replace($config, include($configLocalFile));
}
$request = new \AndyDune\ArrayContainer\ArrayContainer($_REQUEST);

$condition = new \AndyDune\ConditionalExecution\ConditionHolder();
$condition->add($request[$config['secret_name']])
    ->add($request[$config['secret_name']] == $config['secret']);
$controller = new \AndyDune\SimpleProxyServer\Controller();
$condition->executeIfFalse(function () use ($controller) {
    $controller->actionBadSecret();
});
$condition->executeIfTrue(function () use ($controller) {
    $controller->actionProxy();

});
$condition->doIt();