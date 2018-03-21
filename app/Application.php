<?php
/**
 * Created by PhpStorm.
 * User: raye
 * Date: 18/1/18
 * Time: 10:33
 */
namespace App;

use App\Service\EmFactory;
use App\Service\Security;
use App\HttpFoundation\JsonResponse;
use Closure;
use Silex\Provider\SessionServiceProvider;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Storage\Handler\NativeFileSessionHandler;
use Symfony\Component\Routing\Loader\YamlFileLoader as RoutingYmlFileLoader;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader as DIYmlFileLoader;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\DependencyInjection\ContainerBuilder;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use User\Repository\UsersRepository;


class Application extends \Silex\Application
{
    /** @var  FileLocator */
    private $fileLocator;

    /** @var  bool */
    protected $isDev;

    /** @var  Application */
    public static $_singleton;

    public $siteRoot;
    public $cacheDir = "/var/cache/appCache";
    public $sessionDir = "/var/session";


    public static function create($is_dev = false)
    {
        self::$_singleton = new static();
        self::$_singleton->bootstrap($is_dev);
        return self::$_singleton;
    }

    protected function bootstrap($isDev)
    {
        $this->siteRoot = __DIR__ . '/..';
        $this->isDev = $isDev;
        $this->initialize();

        $this['debug'] = $isDev;
    }

    protected function initialize()
    {
        $this->initRoutes();

        $this->initContainer();

        $this->initSession();

        $this->initErrorHandler();
    }

    protected function initRoutes()
    {
        $this->initCacheableObject('routes', function() {
            $routingLoader = new RoutingYmlFileLoader($this->getFileLocator());
            $routeCollection = new RouteCollection();
            foreach(glob($this->getConfigPath() . '/routing*.yml') as $routingFile) {
                $routeCollection->addCollection($routingLoader->load($routingFile));
            }
            return $routeCollection;
        });
    }

    protected function initContainer()
    {
        $this->initCacheableObject('container', function() {
            $container = new ContainerBuilder();
            $diLoader = new DIYmlFileLoader($container, $this->getFileLocator());
            $diLoader->load('parameters.yml');
            $diLoader->load('services.yml');
            return $container;
        });
    }

    protected function initSession()
    {
        /** @var ContainerBuilder $container */
        $container = $this['container'];
        $sessionDir = $this->siteRoot . '/' . $this->sessionDir;
        if (!file_exists($sessionDir)) {
            mkdir($sessionDir, 0777, true);
        }
        $sessionHandler = new NativeFileSessionHandler($sessionDir);

        $this->register(new SessionServiceProvider(), array('session.storage.handler' => $sessionHandler));
        $this['session.storage.options'] = array('cookie_domain' => $container->getParameter('cookie_domain'),'serialize_handler'=>'php_serialize');
        $container->set('session', $this['session']);
    }

    protected function initErrorHandler()
    {
        set_error_handler(array($this, 'handler'), E_ALL);
        register_shutdown_function( array($this, 'fatalHandler') );
    }

    protected function initObjectFromCache($objName)
    {
        $cacheFile = $this->siteRoot . $this->cacheDir . '/' . $objName;
        if (!file_exists($cacheFile)) {
            return false;
        }
        $obj = unserialize(file_get_contents($cacheFile));
        $this[$objName] = $obj;
        return (bool)$obj;
    }

    protected function setIsDev($val)
    {
        $this->isDev = $val;
    }

    protected function getFileLocator()
    {
        if (!$this->fileLocator)
            $this->fileLocator = new FileLocator(array($this->getConfigPath()));
        return $this->fileLocator;
    }

    protected function getConfigPath()
    {
        return $this->siteRoot . '/config';
    }

    protected function initCacheableObject($objectName, closure $function)
    {
        if ($this->isDev) {
            $this[$objectName] = $function();
            return;
        }

        if ($this->initObjectFromCache($objectName)) {
            return;
        }

        $this[$objectName] = $function();
        $cacheDir = $this->siteRoot . $this->cacheDir;
        if (!file_exists($cacheDir)) {
            mkdir($cacheDir, 0777, true);
        }
        $cacheFile = $cacheDir . DIRECTORY_SEPARATOR . $objectName;
        file_put_contents($cacheFile, serialize($this[$objectName]));
    }

    /**
     * @param Request $request
     * @return JsonResponse|bool
     */
    public function sessionSecurityCheck(Request $request)
    {
        /** @var ContainerBuilder $container */
        $container = $this['container'];
        $securityUrls = $container->getParameter('security');
        $currentUri = $request->getPathInfo();
        /** @var Security $security */
        $security = $container->get('app.security');
        if (preg_match('/^\/test\/.+$/', $currentUri)) {
            return true;
        }
        if($security->isLoggedIn()){
            if(in_array($currentUri, $securityUrls['unauthenticated_only'])){
                return new JsonResponse(JsonResponse::STATUS_LOGOUT_REQUIRED,
                    'The url you requested is restricted to unauthenticated user.');
            }
        }elseif ( $userCode = $request->get('userCode')) {
            // 由于这个后台暂时没有登陆逻辑， 所以根据userCode自动登陆
            /** @var EmFactory $em */
            $em = $container->get('app.em_factory');
            /** @var UsersRepository $userInfoRep */
            $userInfoRep = $em->getEntityManager()->getRepository('User:UserInfo');
            if ( $userInfo = $userInfoRep->findOneBy(['userCode' => $userCode]) ) {
                $security->login($userInfo);
            }else{
                return new JsonResponse(JsonResponse::STATUS_LOGIN_REQUIRED,
                    'The url you requested is restricted to authenticated user.');
            }
        } elseif(!in_array($currentUri, $securityUrls['unauthenticated_area'])){
            return new JsonResponse(JsonResponse::STATUS_LOGIN_REQUIRED,
                'The url you requested is restricted to authenticated user.');
        }

        return true;
    }

    /**
     * PHP error handler
     */
    public function handler($errno , $errstr, $errline, $errfile)
    {
        // 捕获notice级别日志
        if ($errno === E_NOTICE) {
            $logger = new Logger('Entrance');
            $file = $this->siteRoot . '/logs/php.error.notice-level.log';
            $logger->pushHandler(new StreamHandler($file, logger::NOTICE));
            $logger->addRecord(logger::NOTICE, $errline .':'. $errfile .':'. $errstr);
        }
    }

    public function fatalHandler()
    {
        $error = error_get_last();
        if ( !empty( $error ) ) {
            $request = Request::createFromGlobals();
            $this->terminate($request, Response::create());
        }
    }
}