<?php
namespace App\Service\Tabor;

use App\Model\Tabor as ModelTabor;

class Sum
{
    protected $model;

    public function __construct(ModelTabor\Sum $sum)
    {
        $this->model = $sum;
    }

    public function getSum(int $idemVal=0, bool $idemKey=true):int
    {
        $res = 0;

        //Получаем результат который уже был выдан на аналогичное значение
        if($idemKey) $res = $this->model->getSumByVal($idemVal);
        
        //Если имподентное значение не найдено или запрос не имподентный то добавляем 
        //новое значение и возвращаем новый результат
        if(!$res || !$idemKey) $res = $this->model->addNewVal($idemVal, $idemKey);

        return $res;
    }
}