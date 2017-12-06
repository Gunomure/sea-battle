<link rel="stylesheet" type="text/css" href="style.css">
<?php

function drawFiredTable($pathFileFired, $pathFileShips)
{
	$sumbols = ['a','b','c','d','e','f','g','h','i','j'];

	$k = 0;
	$firedMas = [];
	$fd = fopen($pathFileFired, 'r');
	while(!feof($fd))
	{
    	$firedKoord = htmlentities(fgets($fd));
    	$firedKoord = trim($firedKoord, " \t\n\r\0\x0B");
    	if($firedKoord != NULL && $firedKoord != "")
    	{
        	$firedMas[$k] = $firedKoord;
    	}
    	$k++;
	}
	fclose($fd);

	//десериализуем объект с кораблями, чтобы показывать где чувак попал по кораблю, а где просто выстрелил
	$s = file_get_contents('storeB');
	$Ships = unserialize($s);

    echo '<div class="right"><table border="1" cellpadding="5px" align="right"> ';
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
            $koord = [$sumbols[$i], $j - 1];
            $koordStr = $sumbols[$i].($j - 1);
            if($j == 0)
            {
                echo '<th>'.$sumbols[$i].'</th>';
            }
            // else if($Ships->getFiredAllShips($koordStr))
            // {
            //     echo '<th class="fired ship"</th>';
            // }
            else if((in_array($koordStr, $firedMas)) && ($Ships->checkExistShip($koord)))
            {
                echo '<th class="ship fired"></th>';
            }
            else if(in_array($koordStr, $firedMas))
            {
                echo '<th class="fired"></th>';
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

?>