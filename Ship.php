<?php
/**
 * Created by PhpStorm.
 * User: Dmitry
 * Date: 20.11.2017
 * Time: 16:35
 */

class Ship
{
    private $deck;
    private $created;
    private $raspolog;
    private $fired;
    private $die;

    function __construct($deck1, $strKoord) {
        $this->deck = $deck1;
        for($i = 0; $i < $this->deck; $i++)
        {
            $koord = [$strKoord{0},$strKoord{1}];
            $this->raspolog[$i] = $koord;
            $strKoord = substr($strKoord, 2);
        }
        
        $this->created = true;
        $this->created = false;
    }

    public function getKoord()
    {
        return $this->raspolog;
    }

    function getDeck()
    {
        return $this->deck;
    }

    public function getDie()
    {
        return $this->die;
    }

    public function getFired($koord)
    {
        //echo "getFired koord = ";var_dump($koord);
        //echo "fired koords: "; var_dump($this->fired);
        if(isset($this->fired[$koord]))
        {
            if($this->fired[$koord] == 1)
            {
                return true;
            }
        }
        return false;
    }

    public function tryFire($koordFire)
    {
        $koord = [$koordFire{0}, $koordFire{1}];
        //echo "<br>koord Ship fire = ";var_dump($koord);
        //echo "koord = ";var_dump($koord);
            if(in_array($koord, $this->raspolog))
            {
                $this->setFire($koordFire);
                return true;
            }
        return false;
    }

    private function setFire($koordFire)
    {
                $this->fired[$koordFire] = 1;
                //echo "setFire: ";var_dump($this->fired);
                $this->checkDie();
    }

    private function checkDie()
    {
        if($this->deck == count($this->fired))
        {
            $this->die = true;
            return true;
        }
        return false;
    }
}
?>