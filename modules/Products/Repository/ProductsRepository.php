<?php
/**
 * Created by PhpStorm.
 * User: raye
 * Date: 2018/3/21
 * Time: 11:14
 */
namespace Products\Repository;
use App\Entity\Products;
use App\Repository\BaseRepository;

class ProductsRepository extends BaseRepository
{
    public function insert(array $data){
        $product = new Products();
        $product->setName($data['name']);
        $product->setStatus(1);
        $product->setAmountLimitShow($data['amount_limit_show']);
        $product->setApplications($data['applications'] ?? mt_rand(1000, 3000));
        $product->setApplyCond($data['apply_cond']);
        $product->setApplyPath($data['apply_path'])
            ->setAuerbach($data['auerbach'])
            ->setDescr($data['descr'])
            ->setFee($data['fee'])
            ->setSale($data['sale'])
            ->setRate($data['rate'])
            ->setMaxLoans($data['max_loans'])
            ->setMinLoans($data['min_loans'])
            ->setLogo($data['logo'] ?? null)
            ->setIsNew($data['is_new'])
            ->setLoanPeriodMax($data['loan_period_max'])
            ->setLoanPeriodMin($data['loan_period_min'])
            ->setLoanTimeShow($data['loan_time_show'])
            ->setLoanTimeMin($data['loan_time_min'])
            ->setLoanTimeMax($data['loan_time_max'])
            ->setNotShow($data['not_show']);
    }

    public function update(array $data) {
        /** @var Products $product */
        if ( !$product = $this->find($data['id']) ) {
            return false;
        }

        isset($data['name']) && $product->setName($data['name']);
        isset($data['amount_limit_show']) && $product->setName($data['amount_limit_show']);
        isset($data['applications']) && $product->setName($data['applications']);
        isset($data['apply_cond']) && $product->setName($data['apply_cond']);
        isset($data['apply_path']) && $product->setName($data['apply_path']);
        isset($data['auerbach']) && $product->setName($data['auerbach']);
        isset($data['descr']) && $product->setName($data['descr']);
        isset($data['fee']) && $product->setName($data['fee']);
        isset($data['sale']) && $product->setName($data['sale']);
        isset($data['rate']) && $product->setName($data['rate']);
        isset($data['max_loans']) && $product->setName($data['max_loans']);
        isset($data['min_loans']) && $product->setName($data['min_loans']);
        isset($data['logo']) && $product->setName($data['logo']);
        isset($data['is_new']) && $product->setName($data['is_new']);
        isset($data['loan_period_max']) && $product->setName($data['loan_period_max']);
        isset($data['loan_period_min']) && $product->setName($data['loan_period_min']);
        isset($data['loan_time_show']) && $product->setName($data['loan_time_show']);
        isset($data['loan_time_min']) && $product->setName($data['loan_time_min']);
        isset($data['loan_time_max']) && $product->setName($data['loan_time_max']);
        isset($data['not_show']) && $product->setName($data['not_show']);

        $this->persist($product);
        $this->flush();
        return true;
    }

    public function delete(int $id){
        if ($product = $this->find($id)){
            $this->remove($product);
            $this->flush();
        }
    }

    public function fetchList($status = 1){
        $statement = $this->getEntityManager()->createQuery('
            select * from products WHERE status = :status
        ')->setParameter('status', $status);

        return $statement->execute();
    }
}