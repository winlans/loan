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
    /**
     * 添加广告
     * @param $data
     * @return bool
     */
    public function add($data)
    {
        $filter = ['is_show', 'sort', 'start', 'end', 'title', 'logo_path', 'skip_path'];
        $this->verifyInputParams($data, $filter);

        /** @var AdvertisingRepository $adRep */
        $adRep = $this->getRepository('Advert:Advertising');
        $adRep->insertSingle($data);

        return true;
    }

    /**
     * 更新一则广告
     * @param $data
     * @return bool
     */
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

    /**
     * 删除一则广告
     * @param int $id
     * @return bool
     */
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

    /**
     * 分页展示广告
     * @param array $data
     * @return array|bool
     */
    public function paging(array $data)
    {
        $this->verifyInputParams($data, ['page']);

        $data['page'] = $data['page'] < 1 ? 1 : $data['page'];
        $data['size'] = $data['size'] ?? 20;

        $offset = ($data['page'] - 1) * $data['size'];
        /** @var AdvertisingRepository $adRep */
        $adRep = $this->getRepository('Advert:Advertising');

        return $adRep->paging($offset, $data['size']);
    }
}