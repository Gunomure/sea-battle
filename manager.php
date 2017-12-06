<?php
	require_once("Ships.php");
	require_once('Check.php');

    if(isset($_POST['shipKoordA']))
    {
    	//проверяем, первый корабль или нет
    	$fd = fopen("ships_a.txt", 'r');
    	$temp = htmlentities(fgets($fd));
		fclose($fd);

        $req = $_POST['shipKoordA'];
        $nstr = "";
        if($temp != NULL)
        {
        	$nstr .= "\n";
        }
        $nstr .= strlen($req)/2;
        if(allCheck($req, "storeTableA", "ships_a.txt"))
        {
        	file_put_contents ("ships_a.txt", $nstr, FILE_APPEND);
        	file_put_contents ("ships_a.txt", "\r\n".$req, FILE_APPEND);
    	}
    }

    if(isset($_POST['shipKoordB']))
    {
    	$fd = fopen("ships_b.txt", 'r');
    	$temp = htmlentities(fgets($fd));
		fclose($fd);

        $req = $_POST['shipKoordB'];
        $nstr = "";
        if($temp != NULL)
        {
            $nstr .= "\n";
        }
        $nstr .= strlen($req)/2;
        if(allCheck($req, "storeTableB", "ships_b.txt"))
        {
           file_put_contents ("ships_b.txt", $nstr, FILE_APPEND);
           file_put_contents ("ships_b.txt", "\r\n".$req, FILE_APPEND);
        }
    }

	if(isset($_POST['fireA']))
	{
		$res = $_POST['fireA'];
		$len = $n = strlen($res) / 2;
		//если это новые координаты выстрела
		if(fireNewKoord($res, "tryFired_b.txt")
		&& checkEmpty($res)
		&& checkCharInt($res)
		&& checkOutside($res)
		&& ($len == 1))
		{
			$s = file_get_contents('storeB');
			$shipsB = unserialize($s);

			//если А попал в корабль В, то он ходит следующим
            if($shipsB->tryFire($res))
            {
                file_put_contents ("whoPlay.txt", "A");
            }
            else //иначе ходит В
            {
                file_put_contents ("whoPlay.txt", "B");
            }
            file_put_contents ("tryFired_b.txt", "\r\n".$res, FILE_APPEND);
        }
	}

	if(isset($_POST['fireB']))
	{
		$res = $_POST['fireB'];
		$len = $n = strlen($res) / 2;
		//если это новые координаты выстрела
		if(fireNewKoord($res, "tryFired_a.txt")
		&& checkEmpty($res)
		&& checkCharInt($res)
		&& checkOutside($res)
		&& ($len == 1))
		{
			$s = file_get_contents('storeA');
			$shipsA = unserialize($s);
			if($shipsA->tryFire($res))
           	{
               	file_put_contents ("whoPlay.txt", "B");
           	}
           	else
           	{
	            file_put_contents ("whoPlay.txt", "A");
    	    }
            file_put_contents ("tryFired_a.txt", "\r\n".$res, FILE_APPEND);
        }
	}

    include 'server.php';
?>