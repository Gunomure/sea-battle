<html>
<head>

</head>
<body>
<?php

//пример работы с объектами
//$shipsA = new Ships("Player A");
//$ships[0] = new Ship(1, "a1");
//$ships->saveShip(1, "a1");
//$shipsA->drawTable(1);

require_once('Ships.php');
require_once('drawFiredTable.php');

$shipsA = new Ships("Player A");
$shipsB = new Ships("Player B");
//количество записанных кораблей
$numOfShipsA = 0;
$numOfShipsB = 0;

//sleep(1);

//считываем и создаем корабли игрока А
$fd = fopen("ships_a.txt", 'r') or die("не удалось открыть файл");
while(!feof($fd))
{
    $n = htmlentities(fgets($fd));
    $str = htmlentities(fgets($fd));

    if($n != NULL && $n != "")
    {
        //удаляет из строки все,что подходит по маску
        $n = trim($n, " \t\n\r\0\x0B");
        $str = trim($str, " \t\n\r\0\x0B");

        $ship = new Ship($n, $str);
        $res = $shipsA->saveShip($ship);
        if($res)
        {
            $numOfShipsA++;
        }
    }
}
fclose($fd);
//считываем и создаем корабли игрока В
$fd = fopen("ships_b.txt", 'r') or die("не удалось открыть файл");
while(!feof($fd))
{
    $n = htmlentities(fgets($fd));
    //удаляет из строки все,что подходит по маску
    $str = htmlentities(fgets($fd));
    if($n != NULL && $n != "")
    {
        $n = trim($n, " \t\n\r\0\x0B");
        $str = trim($str, " \t\n\r\0\x0B");
        $ship = new Ship($n, $str);
        $res = $shipsB->saveShip($ship);
        if($res)
        {
            $numOfShipsB++;
        }
    }
}
fclose($fd);

//устанавливаем,по каким кораблям стреляли
$fd = fopen("tryFired_a.txt", 'r') or die("не удалось открыть файл tryFired_a.txt");
while(!feof($fd))
{
    $firedKoord = htmlentities(fgets($fd));
    $firedKoord = trim($firedKoord, " \t\n\r\0\x0B");
    if($firedKoord != NULL && $firedKoord != "")
    {
        $shipsA->tryFire($firedKoord);
    }
}
fclose($fd);
//если все корабли мертвы,то победил игрок B
if(($numOfShipsA >= 10) && $shipsA->checkAllDies())
{
    echo "<H1>Победил игрок B";
    exit(0);
}
$fd = fopen("tryFired_b.txt", 'r') or die("не удалось открыть файл");
while(!feof($fd))
{
    $firedKoord = htmlentities(fgets($fd));
    $firedKoord = trim($firedKoord, " \t\n\r\0\x0B");
    if($firedKoord != NULL && $firedKoord != "")
    {
        $shipsB->tryFire($firedKoord);
    }
}
fclose($fd);
//если все корабли мертвы,то победил игрок A
if(($numOfShipsB >= 10) && $shipsB->checkAllDies())
{
    echo "<H1>Победил игрок A";
    exit(0);
}

//узнаем кто ходит
$fd = fopen("whoPlay.txt", 'r') or die("не удалось открыть файл");
$who = htmlentities(fgets($fd));
fclose($fd);

//echo "<BR>сериализуем корабли";
file_put_contents('storeA', serialize($shipsA));
file_put_contents('storeB', serialize($shipsB));

//логика, если кораблей недостаточно
$name = "";
if($numOfShipsA < 10)
    {
        echo "<h1>Игрок А вводит координаты корабля";
        $name = "A";
    }
else if($numOfShipsB < 10)
    {
        echo "<h1>Игрок B вводит координаты корабля";
        $name = "B";
    }
if($numOfShipsA < 10 || $numOfShipsB < 10)
{
echo '<form action="manager.php" method = "post" style="margin-top: 10px;">
    <input type="text" name="shipKoord'.$name.'">
    <input type="submit" name="" value="Добавить">
</form>';

$name == "A" ? $shipsA->drawTable(1) : $shipsB->drawTable(1);
}

//логика, если игроки заполнили корабли
if($numOfShipsA >= 10 && $numOfShipsB >= 10)
{
    echo '<h1>Ходит игрок '.$who.'</h1>';
    
    echo '<form action="manager.php" method = "post" style="margin-top: 10px;">
    <input type="text" name="fire'.$who.'">
    <input type="submit" name="" value="Выстрелить">
    </form>';
    if($who == "A")
    {
        $shipsA->drawTable(1);
        drawFiredTable("tryFired_b.txt", "storeTableB");
    }
    else if($who == "B")
    {
        $shipsB->drawTable(1);
        drawFiredTable("tryFired_a.txt", "storeTableA");
    }
}

?>
</body>
</html>