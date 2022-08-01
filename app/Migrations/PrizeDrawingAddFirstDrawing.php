<?php
namespace App\Migrations;
use App;

/**
 * Создание таблиц для розыгрыша призов
 */

class PrizeDrawingAddFirstDrawing
{
    protected $app;
    private $newDraw = [
        'code'=>'example',
        'name'=>'Пример розыгрыша',
        'active'=>1
    ];
    private $prizes = [
        [
            "name"=>'рубли',
            "code"=>'rub',
            "items"=>[
                [
                    'name' => '1000 рублей',
                    'value' => '1000',
                    'amount' => '10',
                    'multiplexer' => '5'
                ],
                [
                    'name' => '2000 рублей',
                    'value' => '2000',
                    'amount' => '10',
                    'multiplexer' => '3'
                ]
            ]
        ],
        [
            "name"=>'бонусные баллы',
            "code"=>'points',
            "items"=>[
                [
                    'name' => '1000 баллов',
                    'value' => '1000',
                    'amount' => '10',
                    'multiplexer' => '10'
                ],
                [
                    'name' => '2000 баллов',
                    'value' => '2000',
                    'amount' => '10',
                    'multiplexer' => '5'
                ],
                [
                    'name' => '5000 баллов',
                    'value' => '5000',
                    'amount' => '10',
                    'multiplexer' => '3'
                ]
            ]
        ],
        [
            "name"=>'подарок',
            "code"=>'gift',
            "items"=>[
                [
                    'name' => 'кофеварка',
                    'value' => '1',
                    'amount' => '3',
                    'multiplexer' => '1'
                ],
                [
                    'name' => 'тостер',
                    'value' => '1',
                    'amount' => '3',
                    'multiplexer' => '3'
                ]
            ]
        ],
        [
            "name"=>'ничего',
            "code"=>'empty',
            "items"=>[
                [
                    'name' => 'нет выигрыша',
                    'value' => '1',
                    'amount' => '100',
                    'multiplexer' => '10'
                ]
            ]
        ]    
    ];

    public function handle()
    {
        $this->app = new App\Application;

        try{
            $draw_id = $this->getDrawID();
            if(!$draw_id) $draw_id = $this->addDraw();

            $this->addPrizes($draw_id);

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

        $id = $this->app::$db->fetchOne($sql,[$this->newDraw['code']]);

        return $id;
    }

    private function addDraw()
    {
        $this->app::$db->insert('prize_drawing', $this->newDraw);

        $sql = "
            SELECT `id` FROM `prize_drawing`
            WHERE `code` = ?
            ORDER BY `id`
            LIMIT 1
        ";

        $id = $this->app::$db->fetchOne($sql,[$this->newDraw['code']]);

        if(!$id) throw new \Exception('Error of adding new values in prize_drawing');

        return $id;
    }

    private function addPrizes($draw_id)
    {
        foreach($this->prizes as $prize){
            $prize_type_id = $this->getPrizeTypeId($prize['code']);

            if(!$prize_type_id) $prize_type_id = $this->addPrizeType($prize);

            foreach($prize['items'] as $item){
                $item['prize_type_id'] = $prize_type_id;
                $item['draw_id']= $draw_id;

                $this->app::$db->insert( 'prizes', $item );
            }
        }
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