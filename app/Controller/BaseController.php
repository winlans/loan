<?php
/**
 * Created by PhpStorm.
 * User: raye
 * Date: 18/1/18
 * Time: 11:21
 */
namespace App\Controller;

use App\Application;
use App\Doctrine\ORM\EntityManager;
use App\Service\EmFactory;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

abstract class BaseController{

    /** @var  ContainerBuilder */
    private $container;

    /** @var  EntityManager */
    private $entityManger;

    /** @var  string */
    private $environment;

    /** @var  array */
    private $domain;

    /** @var Logger */
    private $logger;

    /**
     * @return EntityManager
     */
    protected function getEntityManager()
    {
        if (!$this->entityManger) {
            /** @var EmFactory $biEmFactory */
            $biEmFactory = $this->get('app.em_factory');
            $this->entityManger = $biEmFactory->getEntityManager();
        }
        return $this->entityManger;
    }

    protected function getContainer()
    {
        if (!$this->container)
            $this->container = Application::$_singleton['container'];
        return $this->container;
    }

    /**
     * @param string $entityName
     * @return \App\Repository\BaseRepository
     */
    public function getRepository($entityName)
    {
        return $this->getEntityManager()->getRepository($entityName);
    }

    /**
     * Get logger instance
     * @return \Monolog\Logger
     */
    public function getLogger() {
        if (!($this->logger instanceof Logger)) {
            $this->logger = new Logger('Detection');
            $base_name = 'api.base.log';
            $file = __DIR__ . DIRECTORY_SEPARATOR . '../../logs/' . $base_name;
            $this->logger->pushHandler(new StreamHandler($file, logger::DEBUG));
        }
        return $this->logger;
    }

    /**
     * @return string
     */
    public function getEnvironment()
    {
        if (!$this->environment)
            $this->environment = $this->container->getParameter('environment');
        return $this->environment;
    }

    protected function getDomain($site)
    {
        if (!$this->domain)
            $this->domain = $this->container->getParameter('domain');

        if (!isset($this->domain[$this->getEnvironment()][$site]))
            return null;

        return $this->domain[$this->getEnvironment()][$site];
    }

    /**
     * Returns a RedirectResponse to the given URL.
     *
     * @param string $url The URL to redirect to
     * @param int $status The status code to use for the Response
     *
     * @return RedirectResponse
     */
    protected function redirect($url, $status = 302)
    {
        return new RedirectResponse($url, $status);
    }

    /**
     * Returns a NotFoundHttpException.
     *
     * This will result in a 404 response code. Usage example:
     *
     *     throw $this->createNotFoundException('Page not found!');
     *
     * @param string $message A message
     * @param \Exception|null $previous The previous exception
     *
     * @return NotFoundHttpException
     */
    protected function createNotFoundException($message = 'Not Found', \Exception $previous = null)
    {
        return new NotFoundHttpException($message, $previous);
    }

    /**
     * Gets a container service by its id.
     *
     * @param string $id The service id
     *
     * @return object The service
     */
    protected function get($id)
    {
        return $this->getContainer()->get($id);
    }

    /**
     * Gets a container configuration parameter by its name.
     *
     * @param string $name The parameter name
     *
     * @return mixed
     */
    protected function getParameter($name)
    {
        return $this->getContainer()->getParameter($name);
    }

    /**
     * @return Session
     */
    public function getSession()
    {
        return $this->get('session');
    }

}