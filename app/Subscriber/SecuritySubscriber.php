<?php
/**
 * Created by PhpStorm.
 * User: raye
 * Date: 18/1/18
 * Time: 11:58
 */
namespace App\Subscriber;

use App\Application;
use App\Exception\MissParamException;
use App\HttpFoundation\JsonResponse;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class SecuritySubscriber implements EventSubscriberInterface
{
    const PLATFORM_IOS = 'i';
    const PLATFORM_ANDROID = 'a';
    const PLATFORM_TOUCH = 't';
    const PLATFORM_WEB = null;

    private $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    static function getSubscribedEvents()
    {
        return array(
            KernelEvents::REQUEST => array(
                array('checkSession', -128), // priority must after session being started.
                array('ascertainPlatform', -127)
            ),
            KernelEvents::EXCEPTION => array(
                array('catchException', -127)
            ),

        );
    }

    function checkSession(GetResponseEvent $event)
    {
        $request = $event->getRequest();

        if ($sessionId = $request->get('session_id'))
            $request->getSession()->setId($sessionId);

        $request->getSession()->start();

        /** @var ContainerBuilder $container */
        $container = $this->app['container'];
        $container->set('symfony.http_foundation.request', $request);

        if (true !== ($checkResult = $this->app->sessionSecurityCheck($request)))
            $event->setResponse($checkResult);
    }

    /**
     * Determine the platform and version based on the field 'platform' that comes from the front end
     * @param GetResponseEvent $event
     */
    function ascertainPlatform(GetResponseEvent $event)
    {
        $request = $event->getRequest()->request;

        $info = trim($request->get('platform'));
        if (empty($info)){
            $request->set('platform', self::PLATFORM_WEB);
        }else{
            $platform = $info[0];
            $version = (float)substr($info, 1);

            switch ($platform){
                case self::PLATFORM_IOS:
                    $platform = self::PLATFORM_IOS;
                    break;
                case self::PLATFORM_ANDROID:
                    $platform = self::PLATFORM_ANDROID;
                    break;
                case self::PLATFORM_TOUCH:
                    $platform = self::PLATFORM_TOUCH;
                    break;
                default:
                    $platform = self::PLATFORM_WEB;
            }

            $request->set('platform', $platform);
            $request->set('version', $version);
        }
    }

    public function catchException(GetResponseForExceptionEvent $event){
        if (is_a($event->getException(), MissParamException::class)){
            return $event->setResponse((new JsonResponse($event->getException()->getCode(), $event->getException()->getMessage())));
        }
    }
}