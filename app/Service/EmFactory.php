<?php
/**
 * Created by PhpStorm.
 * User: raye
 * Date: 18/1/18
 * Time: 11:10
 */

namespace App\Service;

use App\Application;
use App\Doctrine\ORM\EntityManager;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Doctrine\Common\Cache\FilesystemCache;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use Doctrine\ORM\Tools\Setup;

class EmFactory{
    /** @var  array */
    private $dbConfig;

    /** @var  EntityManager */
    private static $em;

    function __construct($dbConfig)
    {
        $this->dbConfig = $dbConfig;
    }

    /**
     *
     * @return EntityManager
     */
    public function getEntityManager(){
        if(!self::$em){
            $isDevMode = Application::$_singleton['debug'];

            $doctrineCacheDir = Application::$_singleton->siteRoot . Application::$_singleton->cacheDir  . '/doctrine';
            if(!file_exists($doctrineCacheDir))
                mkdir($doctrineCacheDir, 0777, true);
            $cache = new FilesystemCache($doctrineCacheDir);

            $config = Setup::createConfiguration($isDevMode, null, $cache);
            $driver = new AnnotationDriver(new AnnotationReader());
            AnnotationRegistry::registerLoader('class_exists');
            $config->setMetadataDriverImpl($driver);

            $dbNumber = rand(1, $this->dbConfig['num_dbs']);
            $params = $this->dbConfig['db' . $dbNumber];
            $params['wrapperClass'] = 'Doctrine\DBAL\Connections\MasterSlaveConnection';

            self::$em = EntityManager::create($params, $config);
        }
        return self::$em;
    }

    function __destruct()
    {
        if(self::$em)
            self::$em->getConnection()->close();

        gc_collect_cycles();
    }

}