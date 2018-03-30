<?php
/**
 * Created by PhpStorm.
 * User: raye
 * Date: 2018/3/30
 * Time: 15:06
 */
namespace Advert\Repository;

use App\Entity\Advertising;
use App\Repository\BaseRepository;

class AdvertisingRepository extends BaseRepository
{
    public function insertSingle(array $data)
    {
        $ad = new Advertising();
        $ad->setTitle($data['title'])
            ->setCreated(new \DateTime())
            ->setUpdated(new \DateTime())
            ->setEnd($data['end'])
            ->setIsShow($data['is_show'])
            ->setLogoPath($data['logo_path'])
            ->setSkipPath($data['skip_path'])
            ->setSort($data['sort'])
            ->setStart($data['start']);

        $this->persist($ad);
        $this->flush();
        return $ad->getId();
    }

    /**
     * @param array $data
     * @return bool
     */
    public function update(array $data)
    {
        /** @var Advertising $ad */
        $ad = $this->find($data['id']);
        if (!$ad) return false;

        isset($data['title']) && $ad->setTitle($data['title']);
        isset($data['end']) && $ad->setTitle($data['end']);
        isset($data['is_show']) && $ad->setTitle($data['is_show']);
        isset($data['logo_path']) && $ad->setTitle($data['logo_path']);
        isset($data['skip_path']) && $ad->setTitle($data['skip_path']);
        isset($data['sort']) && $ad->setTitle($data['sort']);
        isset($data['start']) && $ad->setTitle($data['start']);

        $this->persist($ad);
        $this->flush();
        return true;
    }

    public function delete($id)
    {
        $ad = $this->find($id);
        if ( !$ad ) return false;

        $this->remove($ad);
        $this->flush();
        return true;
    }

    public function fetchAdvertById($id)
    {
        return $this->find($id);
    }

    public function paging($offset, $size){
        $sql = 'SELECT * FROM advertising WHERE status = 1 LIMIT ?, ?';

        $statement = $this->getEntityManager()->getConnection()->executeQuery($sql, array($offset, $size)
        , array(\PDO::PARAM_INT, \PDO::PARAM_INT));

        return $statement->fetchAll();
    }
}