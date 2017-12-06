<link rel="stylesheet" type="text/css" href="style.css">

<?php

$sumbols = ['a','b','c','d','e','f','g','h','i','j'];

function allCheck($strKoord, $pathFile, $pathStore)
{
	//pathFile - storeTableA/B
	//pathStore - storeA/B
	//echo "<BR>allCheck";

	//echo "<br>проверка пустой строки";
	if(!checkEmpty($strKoord)){
		echo '<p style = "color: red;">проверка пустой строки не прошла';
		return false;
	}

	//echo "<br>проверка правильности задания координат";
	if(!checkCharInt($strKoord))
	{
		echo '<p style = "color: red;">проверка правильности задания координат не прошла';
		return false;
	}

	//echo "<br>проверка, что координаты в пределах таблицы";
	if(!checkOutside($strKoord))
	{
		echo '<p style = "color: red;">проверка, что координаты в пределах таблицы не прошла';
		return false;
	}

	//echo "<BR>проверка количества палуб";
	if(!checkNumDeck($strKoord))
	{
		echo '<p style = "color: red;">проверка количества палуб не прошла';
		return false;
	}

	//echo "<BR>проверка что n палубных кораблей не слишком много";
	if(!checkDecks($strKoord, $pathStore))
	{
		//echo "<BR>проверка что n палубных кораблей не слишком много не прошла";
		return false;
	}

	//echo "<br>проверка, что корабль не разрывается и не изгибается";
	if(!checkTornBending($strKoord))
	{
		echo '<p style = "color: red;">проверка, что корабль не разрывается и не изгибается не прошла';
		return false;
	}

	$masKoords;
	$s = file_get_contents($pathFile);
	$masKoords = unserialize($s);

	if($masKoords == NULL)
	{
		//echo "<br>Сериализация дала NULL";
		for($i = 0;$i < 10; $i++)
		{
			for($j = 0;$j < 10; $j++)
			{
				$masKoords[$i][$j] = 0;
			}
		}
	}

	//echo "<BR>проверка, что корабли не касаются";
	if(!checkAroundShips($strKoord, $masKoords))
	{
		echo '<p style = "color: red;">проверка, что корабли не касаются не прошла';
		return false;
	}

	$masKoords = fillAroundKoord($strKoord, $masKoords);

	// global $sumbols;
	// echo '<table border="1" cellpadding="5px">';
	// for($i = 0; $i < 10; $i++)
 //        {
 //            echo '<tr>';
 //            for($j = 0; $j < 10; $j++)
 //            {
 //                {
 //                    echo '<th>'.$masKoords[$i][$j].'</th>';
 //                }
 //            }
 //            echo '</tr>';
 //        }
 //        echo '</table>';

	file_put_contents($pathFile, serialize($masKoords));
	return true;
}

//заполняем клетки вокруг кораблей
function fillAroundKoord($strKoord, $masKoords)
{
	// echo "<br>fillAroundKoord";
	// echo "<br>len = ".(strlen($strKoord) / 2);
	$len = strlen($strKoord) / 2;
	for($k = 0;$k < $len;$k++)
	{
		$i = getKey($strKoord{0});
		$j = $strKoord{1};
		//echo "<br>i = $i, j = $j";
		$masKoords[$i][$j] = 1;
		if($i > 0)
		{$masKoords[$i - 1][$j] = 1;}
		if($i < 9)
		{$masKoords[$i + 1][$j] = 1;}
		if($j > 0)
		{$masKoords[$i][$j - 1] = 1;}
		if($j < 9)
		{$masKoords[$i][$j + 1] = 1;}
		if($i > 0 && $j > 0)
		{$masKoords[$i - 1][$j - 1] = 1;}
		if($i > 0 && $j < 9)
		{$masKoords[$i - 1][$j + 1] = 1;}
		if($i < 9 && $j > 0)
		{$masKoords[$i + 1][$j - 1] = 1;}
		if($i < 9 && $j < 9)
		{$masKoords[$i + 1][$j + 1] = 1;}

		$strKoord = substr($strKoord, 2);
	}

	return $masKoords;
}

//корабль не ставится впритык к другим
function checkAroundShips($strKoord, $masKoords)
{
	$len = strlen($strKoord) / 2;
	for($k = 0;$k < $len;$k++)
	{
		$i = getKey($strKoord{0});
		$j = $strKoord{1};
		//echo "<br>i = $i, j = $j";
		if($masKoords[$i][$j] == 1)
		{return false;}

		$strKoord = substr($strKoord, 2);
	}

	return true;
}

//не пустая строка
function checkEmpty($strKoord)
{
	if($strKoord == ""){ return false; echo "Пустая строка";}
	return true;
}

//координаты в пределах таблицы
function checkOutside($strKoord)
{
	global $sumbols;
	$len = strlen($strKoord) / 2;
	for($k = 0;$k < $len;$k++)
	{
		$i = $strKoord{0};
		$j = $strKoord{1};
		if(!in_array($i, $sumbols)) return false;
		if($j < 0 || $j > 9) return false;
	}

	return true;
}

//корабль не разрывается и не изгибается
function checkTornBending($strKoord)
{
	$len = strlen($strKoord) / 2;

	if($len == 1) return true;
	else
	{
	$horizontal = false;
	$vertical = false;

	$oldChar = $strKoord{0};
	$oldInt = $strKoord{1};
	$strKoord = substr($strKoord, 2);
	$newChar = $strKoord{0};
	$newInt = $strKoord{1};
	// echo "<BR>oldChar = $oldChar";
	// echo "<BR>oldInt = $oldInt";
	// echo "<BR>newChar = $newChar";
	// echo "<BR>newInt = $newInt";
	if($oldChar == $newChar) $horizontal = true;
	if($oldInt == $newInt) $vertical = true;
	// echo "<BR>horizontal = $horizontal";
	// echo "<BR>vertical = $vertical";
	if(($horizontal && $vertical) || (!$horizontal && !$vertical))
	{
		//echo "<br>Неправильные координаты корабля.1";
		return false;
	}
		for($i = 1;$i < $len;$i++)
		{
			if($horizontal)
			{
				if((abs($oldInt - $newInt) != 1) || ($oldChar != $newChar))
				{
					//echo "<br>Неправильные координаты корабля.2";
					return false;
				}
			}
			else if($vertical)
			{
				if((abs(getKey($oldChar) - getKey($newChar)) != 1) || ($oldInt != $newInt))
				{
					//echo "<br>Неправильные координаты корабля.3";
					// echo "<BR>oldChar = $oldChar";
					// echo "<BR>newChar = $newChar";
					// echo "<BR>: ".getKey($newChar);
					// echo "<BR>($oldInt != $newInt)".($oldInt != $newInt);
					return false;
				}
			}
			if($i < $len - 1)
			{
				$oldChar = $newChar;
				$oldInt = $newInt;
				$strKoord = substr($strKoord, 2);
				$newChar = $strKoord{0};
				$newInt = $strKoord{1};
			}
		}
	}
	return true;
}

//возвращает номер буквы в массиве символов $sumbols
function getKey($char)
{
	global $sumbols;
	reset($sumbols);
	//echo "<BR>char = $char";
	while ($sumbol = current($sumbols))
	{
		//echo "<BR>sumbol = $sumbol";
    	if ($sumbol == $char)
    	{
    		//echo "<BR>key = ".key($sumbols);
        	return key($sumbols);
    	}
    next($sumbols);
	}
	//echo "<BR>return NULL";
	return NULL;
}

//нет буквы без числа и числа без буквы
function checkCharInt($strKoord)
{
	$lenStr = strlen($strKoord);
	if($lenStr % 2 != 0)
	{
		//echo "<br>Длина строки не четное число";
		return false;
	}
	else
	{
		$len = strlen($strKoord) / 2;

		for($i = 0;$i < $len;$i++)
		{
			$char = $strKoord{0};
			$numb = $strKoord{1};
			// echo "<BR>char = $char";
			// echo "<BR>numb = $numb";
			if((getKey($char) === NULL) || (!is_numeric($numb)))
			{
				// echo "<BR>getChar = ".getKey($char);
				// echo "<BR>!is_numeric = ".!is_numeric($numb);
				return false;
			}
			$strKoord = substr($strKoord, 2);
		}
	}
	return true;
}

//проверка количества палуб
function checkNumDeck($strKoord)
{
	$len = strlen($strKoord) / 2;
	if($len < 1 && $len > 4)
		return false;
	return true;
}

function fireNewKoord($strKoord, $pathFile)
{
	$i = 0;
	$firedMas = [];
	$fd = fopen($pathFile, 'r');
	while(!feof($fd))
	{
    	$firedKoord = htmlentities(fgets($fd));
    	$firedKoord = trim($firedKoord, " \t\n\r\0\x0B");
    	if($firedKoord != NULL && $firedKoord != "")
    	{
        	$firedMas[$i] = $firedKoord;
    	}
    	$i++;
	}
	fclose($fd);
	if(in_array($strKoord, $firedMas)) return false;

	return true;
}

    //проверка количетсва кораблей с данным количеством палуб
    function checkDecks($strKoord, $pathFile)
    {
    	$k = 0;
    	$mas = [];
    	$fd = fopen($pathFile, 'r');
    	while(!feof($fd))
		{
    		$n = htmlentities(fgets($fd));
    		$str = htmlentities(fgets($fd));
    		$n = trim($n, " \t\n\r\0\x0B");
    		//echo "<BR>n = $n";
    		if($n != NULL && $n != "")
    		{
    			$mas[$k] = $n;
    			$k++;
    		}
		}
		fclose($fd);
    	$n = strlen($strKoord) / 2;
    	$mas[$k] = $n;

        if($mas != NULL)
        {
        	//echo "<BR>count mas = ".count($mas);
         	$mas = array_count_values($mas);
            //echo "<BR>mas2 = ".var_dump($mas);
            //echo "<BR>n = $n";
            //если в массиве уже есть $n палубный корабль
            if(!array_key_exists($n, $mas))
            {
                return true;
            }
            else 
            {
            	//echo "<BR>enter else.";
                $decks = 4;
                //echo "<BR>корабли<BR>".var_dump($mas);
                for($j = 1;$j < 5;$j++)
                {
                    if(array_key_exists($j, $mas))
                    {
                    	//echo "<BR>mas[j] = $mas[$j]";
                    	//echo "<BR>decks = $decks";
                        if($mas[$j] > $decks)
                        {
                            //echo "<BR>2=";
                            echo '<p style = "color: red;">'.$j.' палубных кораблей уже достаточно';
                            return false;
                        }
                    }
                    $decks--;
                }
            }
        }
        return true;
    }

?>