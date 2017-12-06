<link rel="stylesheet" type="text/css" href="style.css">

<?php
require_once('Ship.php');

class Ships{
    private $ships;
    private $q;
    private $i;
    private $sumbols = ['a','b','c','d','e','f','g','h','i','j'];


    public function __construct($playerName)
    {
        //echo '<h1>'.$playerName.'</h1>';
        $this->i = 0;
    }

    public function saveShip(Ship $ship)
    {
        //если такого же корабля не существует
        if(!($this->checkExistShip($ship->getKoord())))
        {
            //var_dump($ship);
            $this->ships[$this->i] = $ship;
            //echo 'check die: '.$this->ships[$this->i]->checkDie();
            $this->i++;
            //если реально добавили,то вернем true
            return true;
        }
        //если уже был такой, то false
        return false;
    }

    public function drawTable($n)
    {
        //рисуем первую строку с цифрами
        if($n == 1){echo '<div class="left"><table border="1" cellpadding="5px">';}
        if($n == 2){echo '<div class="right"><table class="second" border="1" cellpadding="5px" align="right"> ';}
        echo '<tr><th></th>';
        for($i = 0;$i < 10;$i++)
        {
            echo '<th>'.($i).'</th>';
        }
        echo '</tr>';

        for($i = 0; $i < 10; $i++)
        {
            echo '<tr>';
            for($j = 0; $j < 11; $j++)
            {
                $koord = [$this->sumbols[$i], $j - 1];

                $koordStr = $this->sumbols[$i].($j - 1);
                //echo "koordStr = ".$koordStr;
                if($j == 0)
                {
                    echo '<th>'.$this->sumbols[$i].'</th>';
                }
                else if($this->getFiredAllShips($koordStr))
                {
                    echo '<th class="fired ship"</th>';
                }
                else if($this->checkExistShip($koord))
                {
                    echo '<th class="ship"></th>';
                }
                else
                {
                    echo '<th></th>';
                }
            }
            echo '</tr>';
        }
        echo '</table>';
        echo '</div>';
    }

    //располагается ли на данной точке вообще какой либо корабль
    public function checkExistShip($raspolog)
    {
        for($i = 0;$i < count($this->ships); $i++)
            {
                $myraspolog = $this->ships[$i]->getKoord();
                //var_dump($myraspolog);
                if(in_array($raspolog, $myraspolog))
                {
                    return true;
                }
            }
            return false;
    }

    public function checkAllDies()
    {
        $num = 0;
        for($i = 0;$i < count($this->ships);$i++)
        {
            if($this->ships[$i]->getDie())
            {
                $num++;
            }
        }
        return ($num == count($this->ships));
    }

    //пытаемся выстрелить по всем кораблям
    // и если по кому то попадем, сообщаем
    public function tryFire($koordFire)
    {
        foreach ($this->ships as $key) {
            if($key->tryFire($koordFire))
            {
                return true;
            }
        }
        return false;
    }

    private function getFiredAllShips($koord)
    {
        //echo "<br>".var_dump($this->ships);
        if($this->ships != NULL)
        {
        foreach ($this->ships as $key) {
            if($key->getFired($koord))
            {
                return true;
            }
        }
        return false;
        }
    }

    public function getShips()
    {
        return $this->ships;
    }
}
?>