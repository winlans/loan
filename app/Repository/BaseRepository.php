<?php
/**
 * Created by PhpStorm.
 * User: raye
 * Date: 18/1/18
 * Time: 11:03
 */
namespace App\Repository;
use App\Entity\BaseEntity;
use Doctrine\DBAL\DBALException;
use Doctrine\ORM\EntityRepository;
use Closure;
use Doctrine\ORM\QueryBuilder;

/**
 * @method BaseEntity find($id)
 */
class BaseRepository extends EntityRepository
{

    /**
     * Hydrates an object graph. This is the default behavior.
     */
    CONST Q_HYDRATE_OBJECT = 1;

    /**
     * Hydrates an array graph.
     */
    CONST Q_HYDRATE_ARRAY  = 2;

    /**
     * Hydrates a flat, rectangular result set with scalar values.
     */
    CONST Q_HYDRATE_SCALAR = 3;

    /**
     * batch size used in batch operation
     * like batch insert, batch update, etc.
     */
    CONST BATCH_SIZE = 5;

    CONST RESULT_ARRAY = 1;
    CONST RESULT_OBJECT = 2;

    /**
     * result is array or objects
     * @var int
     */
    protected $resultFormat = self::RESULT_OBJECT;


    /**
     * @param int $format
     */
    public function want($format = self::RESULT_OBJECT)
    {
        $this->resultFormat = $format;
    }

    /**
     * @param QueryBuilder $qb
     * @return array
     */
    protected function getResult(QueryBuilder $qb)
    {
        return $this->resultFormat == self::RESULT_OBJECT ? $qb->getQuery()->getResult()
            : $qb->getQuery()->getArrayResult();
    }

    /**
     * Transaction
     * @param Closure $func
     * @return mixed
     * @throws DBALException
     */
    public function transaction(Closure $func)
    {
        $entityManager = $this->getEntityManager();
        $entityManager->beginTransaction();
        try {
            $result = $func();
            $entityManager->commit();
            return $result;
        } catch (DBALException $e) {
            $entityManager->rollback();
            throw $e;
        }
    }

    public function persist($entity)
    {
        $this->getEntityManager()->persist($entity);
    }

    public function flush($entity = null)
    {
        $this->getEntityManager()->flush($entity);
    }

    public function remove($entity)
    {
        $this->getEntityManager()->remove($entity);
    }

    /**
     * Extension for findBy function to support restricting return fields of table.
     * @param array $fields
     * @param array $criteria
     * @param null $orderBy
     * @param null $limit
     * @param null $offset
     * @param int $hydrationMode
     * @return array
     */
    public function findByWithFields(array $fields, array $criteria = array(), $orderBy = null, $limit = null, $offset = null, $hydrationMode = self::Q_HYDRATE_SCALAR)
    {
        // validation
        if (empty($fields)) {
            //@deprecated
            return $this->findBy($criteria, $orderBy, $limit, $offset);
        }

        $query = $this->getEntityManager()->createQueryBuilder();

        $_fields = array();
        foreach($fields as $k => $field) {
            $_fields[$k] = 'table.' . $field;
        }

        $query->select($_fields)->from($this->_entityName, 'table');

        if ($criteria) {
            foreach($criteria as $k => $v){
                $query->andWhere($query->expr()->eq('table.' . $k, ':' . $k));
                $query->setParameter($k, $v);
            }
        }

        // set range
        if ($orderBy) {
            $query->orderBy($orderBy);
        }

        if ($offset) {
            $query->setFirstResult((int)$offset);
        }

        if ($limit) {
            $query->setMaxResults((int)$limit);
        }

        // set format of return parameters
        if ($hydrationMode == self::Q_HYDRATE_ARRAY) {
            return $query->getQuery()->getArrayResult();
        } else if ( $hydrationMode == self::Q_HYDRATE_SCALAR ) {
            return $query->getQuery()->getScalarResult();
        } else {
            return $query->getQuery()->getResult();
        }
    }
}