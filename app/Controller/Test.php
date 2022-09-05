<?php
namespace App\Controller;

use App\Http;

class Test extends Common
{
    private $conn = [];
    protected function GET():Http\ResponseInterface
    {
        $data = [
            'user'=>$this->request->get('user')
        ];

        $matrix = [
            [1,2,3],[4,5,6],[7,8,9]
    ];
        //$r= $this->diagonalSort($grid);
        $r = $this->rotate($matrix);


        echo '<pre>'; print_r($matrix);die;

        $this->response->setTemplate('index',$data);

        return $this->response;
    }

    function rotate(&$matrix) {
        $yc = count($matrix[0])-1;
        $complite = [];
        foreach($matrix as $x=>$row){
            foreach($row as $y=>$val){
                if(isset($complite[$x][$y]) && $complite[$x][$y]) continue;
                //1
                $nx = $y;
                $ny = $yc-$x;
                $save = isset($matrix[$nx][$ny])?$matrix[$nx][$ny]:'';
                if(!isset($matrix[$nx])) $matrix[$nx] = [];
                $matrix[$nx][$ny] = $val;

                if(!isset($complite[$nx])) $complite[$nx] = [];
                $complite[$nx][$ny] = true;
                //2
                $val = $save;
                $tx = $nx;
                $nx = $ny;
                $ny = $yc - $tx;
                $save = isset($matrix[$nx][$ny])?$matrix[$nx][$ny]:'';
                if(!isset($matrix[$nx])) $matrix[$nx] = [];
                $matrix[$nx][$ny] = $val;

                if(!isset($complite[$nx])) $complite[$nx] = [];
                $complite[$nx][$ny] = true;
                //3
                $val = $save;
                $tx = $nx;
                $nx = $ny;
                $ny = $yc - $tx;
                $save = isset($matrix[$nx][$ny])?$matrix[$nx][$ny]:'';
                if(!isset($matrix[$nx])) $matrix[$nx] = [];
                $matrix[$nx][$ny] = $val;

                if(!isset($complite[$nx])) $complite[$nx] = [];
                $complite[$nx][$ny] = true;
                //4
                $val = $save;
                $tx = $nx;
                $nx = $ny;
                $ny = $yc - $tx;
                $save = isset($matrix[$nx][$ny])?$matrix[$nx][$ny]:'';
                if(!isset($matrix[$nx])) $matrix[$nx] = [];
                $matrix[$nx][$ny] = $val;

                if(!isset($complite[$nx])) $complite[$nx] = [];
                $complite[$nx][$ny] = true;
            }
        }
    }

    function rot90($x, $y, $val){

    }

    function diagonalSort($grid) {

        $islands = []; //array of islands;
        //$connected = [];
        foreach($grid as $x=>$row){
            foreach($row as $y=>$val){

                if(!$val || $val == '0') continue;

                $myIsd = 0;
                if($grid[$x][$y] !== "1" && $grid[$x][$y] !== 1){
                    $myIsd = $grid[$x][$y];
                }

                //top
                //$myIsd = $this->search($grid,$islands, $x,$y-1, $connected,$myIsd);
                $myIsd = $this->search($grid,$islands, $x,$y-1, $myIsd);

                //right
                //$myIsd = $this->search($grid,$islands, $x+1,$y, $connected,$myIsd);
                $myIsd = $this->search($grid,$islands, $x+1,$y, $myIsd);

                //bottom
                //$myIsd = $this->search($grid,$islands, $x,$y+1, $connected,$myIsd);
                $myIsd = $this->search($grid,$islands, $x,$y+1, $myIsd);

                //left
                //$myIsd = $this->search($grid,$islands, $x-1,$y, $connected,$myIsd);
                $myIsd = $this->search($grid,$islands, $x-1,$y, $myIsd);
     
                if(!$myIsd){  //нет соседей - назначаем новый остров
                    $max = count($islands)?(max($islands)+1):2;
                    $myIsd = $max;
                    $islands[] = $max;
                }
                $grid[$x][$y] = $myIsd;

                echo 'x'.$x.'y'.$y.'con:<pre>'.print_r($this->conn,1);
            }
        }
            
        echo '<pre>';
        print_r($islands);
        print_r($this->conn);
        $ik = array_flip($islands);
        //krsort($connected);
        foreach($this->conn as $key=>$arr){
            sort($arr);
            for($i=1;$i<count($arr);$i++) if(isset($ik[$arr[$i]])) unset($ik[$arr[$i]]);
        }

        return count($ik);
    }

    function search(&$grid, &$islands, $x,$y,$myIsd)
    {
        if(!isset($grid[$x][$y]) || !$grid[$x][$y] || $grid[$x][$y] == '0') return $myIsd; // Пропускаем если нет сосeда

        if(!$myIsd && $grid[$x][$y] == '1'){
            $max = count($islands)?(max($islands)+1):2;
            $myIsd = $max;
            $grid[$x][$y] = $max;
            $islands[] = $max;
            
        }elseif(!$myIsd && $grid[$x][$y] > 1){
            $myIsd = $grid[$x][$y];
        }elseif($myIsd && $grid[$x][$y] == '1'){
            $grid[$x][$y] = $myIsd;
        }elseif($myIsd && $grid[$x][$y] > 1 && $myIsd != $grid[$x][$y]){
            $m = -1;
            $g = -1;
            foreach($this->conn as $k=>$v){
                if(in_array($myIsd, $v)) $m = $k;
                if(in_array($grid[$x][$y], $v)) $g = $k;
            }

            if($m != -1 && $g != -1 && $m != $g){
                $this->conn[$m] = array_merge($this->conn[$m], $this->conn[$g]);
                unset($this->conn[$g]);
            }

            if($m == -1 && $g != -1){
                if(!isset($this->conn[$g])) $this->conn[$g] = [];
                $this->conn[$g][] = $myIsd;
            }
            if($m != -1 && $g == -1){
                if(!isset($this->conn[$m])) $this->conn[$m] = [];
                $this->conn[$m][] = $grid[$x][$y];
            }
            if($m == -1 && $g == -1) $this->conn[] = [0=>$grid[$x][$y], 1=>$myIsd];
        }
        return $myIsd;
    }

    

    // Проверить являются ли соседние квадраты морем или островом

    // Найти остров к которому относится соседний квадрат "не море"

    // Если есть совпадение с островом то добавить текущий квадрат к острову

    // Если 2-4 совпадения с разными островами то соединить острова

}