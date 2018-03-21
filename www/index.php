<?php
/**
 * Created by Tony Tao
 * Date: 2016/3/8
 * Time: 16:36
 */
require_once __DIR__ . '/../vendor/autoload.php';

$app = App\Application::create(true);

$app['dispatcher']->addSubscriber(new \App\Subscriber\SecuritySubscriber($app));

/** @see http://blog.csdn.net/luyou3415/article/details/7337322 */
// 增加一个订阅， 在用户请求结束时刷新session
$app->run();