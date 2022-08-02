<?php
namespace App\Migrations;
use App;

/**
 * Создание таблиц для розыгрыша призов
 */

class PrizeActionsFirstFill
{
    protected $app;
    private $prizeActions = [
        [
            "name"=>'Перевести на счет',
            "code"=>'transfer',
            "description"=>"Перевести деньги на указанный мною счет",
            "type_code"=>"rub"
        ],
        [
            "name"=>'Поменять на баллы',
            "code"=>'change_to_points',
            "description"=>"Поменять деньги на бонусные баллы и зачислить на сайт",
            "type_code"=>"rub"
        ],
        [
            "name"=>'Отказаться от приза',
            "code"=>'refusal',
            "description"=>"Отказаться от приза",
            "type_code"=>"rub"
        ],
        [
            "name"=>'Зачислить на счет',
            "code"=>'credit_to_account',
            "description"=>"Зачислить баллы на мой счет на сайте",
            "type_code"=>"points"
        ],
        [
            "name"=>'Отказаться от приза',
            "code"=>'refusal',
            "description"=>"Отказаться от приза",
            "type_code"=>"points"
        ],
        [
            "name"=>'Выслать по почте',
            "code"=>'send',
            "description"=>"Выслать приз на указанный мною адрес по почте",
            "type_code"=>"gift"
        ],
        [
            "name"=>'Отказаться от приза',
            "code"=>'refusal',
            "description"=>"Отказаться от приза",
            "type_code"=>"gift"
        ]
    ];

    private $types = [];

    public function handle()
    {
        $this->app = new App\Application;

        try{
            if(!isset($this->app::$db)) throw new \Exception('DB connection fail.');
            
            $drawId = $this->getDrawID();
            if(!$drawId) throw new \Exception('No prize drawing found');

            $id = $this->checkAction($drawId);

            if($id){
                echo "Example prize_actions have already filled.\n";
                return false;
            }

            $types = $this->getTypes();

            $this->addActions($drawId, $types);

        }catch(\Exception $e){
            echo "Migration ".__CLASS__." is failed!.\n";
            echo $e->getMessage()."\n";
        }
    }

    private function getDrawID()
    {
        $sql = "
            SELECT `id` FROM `prize_drawing`
            WHERE `code` = ?
            ORDER BY `id`
            LIMIT 1
        ";

        $id = $this->app::$db->fetchOne($sql,['example']);

        return $id;
    }

    private function getTypes()
    {
        $sql = "
            SELECT `code`,`id` FROM `prize_types`
        ";

        $res = $this->app::$db->fetchAllAssociative($sql);

        $result = [];
        foreach($res as $row){
            if($row['code'] == 'empty') continue;
            $result[$row['code']] = $row['id'];
        }

        return $result;
    }

    private function addActions($drawId, $types)
    {
        foreach($this->prizeActions as $action){
            $action['draw_id'] = $drawId;
            $action['prize_type_id'] = $types[$action['type_code']];
            unset($action['type_code']);

            $this->app::$db->insert('prize_actions', $action);
        }
    }

    private function checkAction($drawId)
    {
        $sql = "
            SELECT `id` FROM `prize_actions`
            WHERE `code` = ?
            AND `draw_id` = ?
            ORDER BY `id`
            LIMIT 1
        ";

        $id = $this->app::$db->fetchOne($sql,[$this->prizeActions[0]['code'], $drawId]);

        return $id;
    }

    private function getPrizeTypeId(string $code, $after_insert=false)
    {
        $sql = "
            SELECT `id` FROM `prize_types`
            WHERE `code` = ?
            ORDER BY `id`
            LIMIT 1
        ";

        $id = $this->app::$db->fetchOne($sql,[$code]);

        if($after_insert && !$id) throw new \Exception('Error of adding new values in prize_types');

        return $id;
    }

    private function addPrizeType($prize)
    {
        if(!isset($prize['name']) || !isset($prize['code']))
            throw new \Exception('Error of adding new prize type.');

        $this->app::$db->insert(
            'prize_types', 
            ['name'=>$prize['name'],'code'=>$prize['code']]
        );

        return $this->getPrizeTypeId($prize['code'], true);
    }

}