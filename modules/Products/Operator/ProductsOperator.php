<?php
/**
 * Created by PhpStorm.
 * User: raye
 * Date: 2018/3/21
 * Time: 11:17
 */

namespace Products\Operator;

use App\Operator\BaseOperator;
use App\Util\ArrayUtil;
use Products\Repository\ProductsRepository;

class ProductsOperator extends BaseOperator
{
    public function add(array $data)
    {
        $fields = ['name', 'min_loans', 'rate', 'fee', 'loan_time_min', 'loan_time_max',
            'loan_period_min', 'loan_period_max', 'applications', 'sale', 'is_new', 'apply_path', 'amount_limit_show',
        'loan_time_show', 'rate_show', 'apply_cond', 'auerbach', 'descr', 'sort'];
        $this->verifyInputParams($data, $fields);

        /** @var ProductsRepository $rep */
        $rep = $this->getRepository('Products:Products');
        $rep->insert($data);
        return true;
    }

    public function update(array $data)
    {
        $fields = ['id'];
        $this->verifyInputParams($data, $fields);

        /** @var ProductsRepository $rep */
        $rep = $this->getRepository('Products:Products');
        if (false === $rep->update($data)){
            return $this->ensure(false, 0, 'not find by id');
        }

        return true;
    }

    public function delete($id){
        /** @var ProductsRepository $rep */
        $rep = $this->getRepository('Products:Products');
        $rep->delete((int)$id);
        return true;
    }

    public function listAll($filter = []){
        /** @var ProductsRepository $rep */
        $rep = $this->getRepository('Products:Products');

        $products = $rep->fetchList();
        ArrayUtil::filterField($products, $filter, 2);

        return $products;
    }
}