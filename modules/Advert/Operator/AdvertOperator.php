<?php
/**
 * Created by PhpStorm.
 * User: raye
 * Date: 2018/3/30
 * Time: 15:05
 */

namespace Advert\Operator;

use Advert\Repository\AdvertisingRepository;
use App\Operator\BaseOperator;

class AdvertOperator extends BaseOperator
{
    public function add($data)
    {
        $filter = ['is_show', 'sort', 'start', 'end', 'title', 'logo_path', 'skip_path'];
        $this->verifyInputParams($data, $filter);

        /** @var AdvertisingRepository $adRep */
        $adRep = $this->getRepository('Advert:Advertising');
        $adRep->insertSingle($data);

        return true;
    }

    public function update($data)
    {
        $filter = ['id'];
        $this->verifyInputParams($data, $filter);

        /** @var AdvertisingRepository $adRep */
        $adRep = $this->getRepository('Advert:Advertising');
        if ( empty($adRep->fetchAdvertById($data['id'])) )
            return $this->ensure(false, 32, 'not found by this id');

        return $adRep->update($data);
    }

    public function delete(int $id)
    {
        if (!(int)$id)
            return $this->ensure(false, 32, 'param is error');

        /** @var AdvertisingRepository $adRep */
        $adRep = $this->getRepository('Advert:Advertising');
        if ( !$adRep->delete($id) )
            return $this->ensure(false, 21, 'not found by this id.');
        else
            return true;
    }

    public function paging(array $data)
    {
        $this->verifyInputParams($data, ['page', 'size']);

        if ($data['page'] < 1 || $data['size'] > 100)
            return $this->ensure(false, 34, 'param error');

        $offset = ($data['page'] - 1) * $data['size'];
        /** @var AdvertisingRepository $adRep */
        $adRep = $this->getRepository('Advert:Advertising');

        return $adRep->paging($offset, $data['size']);
    }
}