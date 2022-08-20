<?php
namespace Tests\Model\Tabor;

use PHPUnit\Framework\TestCase;
use App;

class Sum extends TestCase
{
    public function test_getSumByVal()
    {
        $container = new App\Container;
        $obj = $container->get(App\Model\Tabor\Sum::class);
        $this->assertInstanceOf(App\Model\Tabor\Sum::class, $obj);

        for($i=0;$i<10;$i++){
            $val = rand(0,100);
            $res1 = $obj->getSumByVal($val);
            $res2 = $this->getValueFromDB($val);
            $this->assertEquals($res1, $res2);
        }
    }

    public function test_addNewVal()
    {
        $container = new App\Container;
        $obj = $container->get(App\Model\Tabor\Sum::class);
        $this->assertInstanceOf(App\Model\Tabor\Sum::class, $obj);

        $countBefore = $this->tableRowCount();

        $obj->addNewVal(1,1);

        $countAfter = $this->tableRowCount();

        $this->assertLessThan($countAfter, $countBefore);
    }

    private function getValueFromDB($val)
    {
        $db = App\Application::$db;

        $sql = "
            SELECT `sum`
            FROM `tabor_sum`
            WHERE `value` = ?
            AND `idempotence` = '1'
            ORDER BY `created_at` DESC
            LIMIT 1
        ";

        $stmt = $db->prepare($sql);
        $stmt->bindValue(1, $val);
        $resultSet = $stmt->executeQuery();

        return (int)$resultSet->fetchOne();
    }

    private function tableRowCount()
    {
        $db = App\Application::$db;

        $sql = "
            SELECT COUNT(*)
            FROM `tabor_sum`
        ";

        $stmt = $db->prepare($sql);
        $resultSet = $stmt->executeQuery();

        return (int)$resultSet->fetchOne();
    }

}