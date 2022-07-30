<?php
namespace App\Model\PrizeDrawing;

use App\Model;

/**
 * Класс для работы с таблицей призов
 */
class Prizes extends Model\Common
{
    protected $table = 'prizes';
    protected $fields = [
        'id',
        'name',
        'prize_type_id',
        'draw_id',
        'amount',
        'multiplexer'
    ];

    protected $winners;

    public function __construct(Winners $win)
    {
        $this->winners = $win;
    }

    /**
     * Получение списка призов с мультиплексом значений для
     * рассчета розыгрыша по остаткам
     */
    public function getPrizeValue(int $draw_id):array
    {
        if(!$draw_id) return [];

        $db = $this->getDb();

        $prizes = $this->getPrizeByDraw($draw_id);

        $residue = $this->winners->getResidueByDraw($draw_id);

        $multiplexer = 0; //cумма всех мультиплексоров призов * остаток
        foreach($prizes as $k=>$prize){
            if(!isset($residue[$prize['id']])) continue;

            //остаток
            $prizes[$k]['residue'] = $prize['amount'] - $residue['count'];
            
            if($prizes[$k]['residue'] <= 0) continue;

            //мультиплексор
            $prizes[$k]['mult_min'] = $multiplexer;
            $multiplexer += $prizes[$k]['residue'] * $prize['multiplexer'];
            $prizes[$k]['mult_max'] = $multiplexer-1;

        }
        $res['prizes'] = $prizes;
        $res['multiplexer'] = $multiplexer;

        return $res;
    }

    /**
     * Получение списка призов, учавствующих в розыгрыше
     */
    public function getPrizeByDraw(int $draw_id):array
    {
        if(!$draw_id) return [];

        $db = $this->getDb();

        $sql = "
            SELECT * FROM $this->table
            WHERE `draw_id` = ?
            ORDER BY `id`
        ";

        $stmt = $db->prepare($sql);
        $stmt->bindValue(1, $draw_id);
        $resultSet = $stmt->executeQuery();

        $res = $resultSet->fetchAllAssociative();

        if(!$res) return [];
        return $res;
    }

}