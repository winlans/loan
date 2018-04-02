<?php
/**
 * Created by PhpStorm.
 * User: raye
 * Date: 2018/4/2
 * Time: 9:58
 */
namespace Analyzer\Repository;

use App\Entity\AccessRecord;
use App\Repository\BaseRepository;

class AccessRecordRepository extends BaseRepository
{
    public function insert(array $data){
        $ob = new AccessRecord();
        $ob->setProductId($data['product_id']);
        $ob->setProductName($data['product_name']);
        $ob->setUserId($data['user_id']);
        $ob->setDCount(1);
        $ob->setACount(0);
        $ob->setCreated($data['created']);
        $this->persist($ob);
        $this->flush();
        return $ob->getId();
    }

    /**
     * 更新产品的访问记录， 存在则更新， 不存在就创建
     * @param array $data
     */
    public function update(array $data)
    {
        $filter = [
            'user_id' => $data['user_id'],
            'product_id' => $data['product_id'],
            'created' => $data['created']
        ];
        /** @var AccessRecord $ob */
        $ob = $this->findOneBy($filter);
        if ($ob){
            if ($data['deepth'] == 1) {
                $ob->setDCount($ob->getDCount()+1);
            } elseif ($data['deepth'] == 2) {
                $ob->setACount($ob->getDCount()+1);
            }
            $this->persist($ob);
            $this->flush();
        }else{
            $this->insert($data);
        }
    }

    /**
     * 获取所有产品的访问数量
     * @param $offset
     * @param $limit
     * @return array
     * @throws \Doctrine\DBAL\DBALException
     */
    public function fetchAccessByDay($offset, $limit)
    {
        $statement = $this->getEntityManager()->getConnection()->executeQuery('
         SELECT
            SUM(d_count) deepth1_pv,
            SUM(a_count) deepth2_pv,
            SUM(CASE WHEN d_count <> 0 THEN 1 ELSE 0 END) deepth1_uv,
            SUM(CASE WHEN a_count <> 0 THEN 1 ELSE 0 END) deepth2_uv,
            created
        FROM
            `access_record`
        GROUP BY
            `created`
        LIMIT ?, ?
        ', array($offset, $limit), array(\PDO::PARAM_INT, \PDO::PARAM_INT));

        return $statement->fetchAll();
    }

    /**
     * 统计每个产品的访问量
     * @param $offset
     * @param $limit
     * @return array
     * @throws \Doctrine\DBAL\DBALException
     */
    public function fetchAccessByDayAndProduct($offset, $limit)
    {
        $statement = $this->getEntityManager()->getConnection()->executeQuery('
         SELECT
            product_name,
            SUM(d_count) deepth1_pv,
            SUM(a_count) deepth2_pv,
            SUM(CASE WHEN d_count <> 0 THEN 1 ELSE 0 END) deepth1_uv,
            SUM(CASE WHEN a_count <> 0 THEN 1 ELSE 0 END) deepth2_uv,
            created
        FROM
            `access_record`
        GROUP BY
            `created`,
            `product_id`
        LIMIT ?, ?
        ', array($offset, $limit), array(\PDO::PARAM_INT, \PDO::PARAM_INT));

        return $statement->fetchAll();

    }


    /**
     * 生成测试记录
     */
    public function testRecord(){
        for ($i = 0; $i < 10000; $i++){
            $pid = rand(0, 25);
            $d = rand(0, 50);
            $a = rand(0, $d);
            $date = strtotime((new \DateTime())->setDate('2018', rand(5, 11), rand(0, 10))->format('Y-m-d'));
            $data = [
                'p_id' => $pid,
                'p_name' => range('a', 'z')[$pid],
                'user_id' => rand(1, 100),
                'd' => $d,
                'a' => $a,
                'date' => $date
            ];

            $ob = new AccessRecord();
            $ob->setProductId($data['p_id']);
            $ob->setProductName($data['p_name']);
            $ob->setUserId($data['user_id']);
            $ob->setDCount($data['d']);
            $ob->setACount($data['a']);
            $ob->setCreated($data['date']);
            $this->persist($ob);

            if ($i%20 == 0){
                $this->flush();
            }
        }
    }
}