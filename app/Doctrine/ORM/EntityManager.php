<?php
/**
 * Created by PhpStorm.
 * User: raye
 * Date: 18/1/18
 * Time: 10:53
 */
namespace App\Doctrine\ORM;

use Doctrine\Common\EventManager;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\Configuration;
use Doctrine\ORM\EntityManager as DoctrineEntityManager;
use Doctrine\ORM\ORMException;

class EntityManager extends DoctrineEntityManager{


    /**
     * @param mixed $conn
     * @param Configuration $config
     * @param EventManager $eventManager
     * @return EntityManager
     * @throws ORMException
     * @throws \Doctrine\DBAL\DBALException
     */
    public static function create($conn, Configuration $config, EventManager $eventManager = null)
    {
        if ( ! $config->getMetadataDriverImpl()) {
            throw ORMException::missingMappingDriverImpl();
        }

        switch (true) {
            case (is_array($conn)):
                $conn = DriverManager::getConnection(
                    $conn, $config, ($eventManager ?: new EventManager())
                );
                break;

            case ($conn instanceof Connection):
                if ($eventManager !== null && $conn->getEventManager() !== $eventManager) {
                    throw ORMException::mismatchedEventManager();
                }
                break;

            default:
                throw new \InvalidArgumentException("Invalid argument: " . $conn);
        }

        return new self($conn, $config, $conn->getEventManager());
    }

    /**
     * @param string $entityName
     * @return \Doctrine\ORM\EntityRepository
     */
    public function getRepository($entityName)
    {
        if(preg_match('/^([A-Z]\w*)(\\\Entity\\\|:)([A-Z]\w*)$/', $entityName, $matches)){
            $repositoryClass = $matches[1] . '\\Repository\\' . $matches[3] . 'Repository';
            if(class_exists($repositoryClass))
                return new $repositoryClass($this, $this->getClassMetadata('App\\Entity\\' . $matches[3]));

            $repositoryClass = 'App\\Repository\\' . $matches[3] . 'Repository';
            if(class_exists($repositoryClass))
                return new $repositoryClass($this, $this->getClassMetadata('App\\Entity\\:' . $matches[3]));
        }

        return parent::getRepository($entityName);
    }


}