<?php
//проверяем передана ли переменная методом POST и не пустая ли она
if (isset($_POST['url']) && ($_POST['url'] != null)) {
	//если переданная переменная ссылки с обозначением протокола, то используем её. 
	if (preg_match('#(http:\/\/|https:\/\/).*#', $_POST['url']))
	{
		$url = $_POST['url'];
	}
	else
	//если без обозначения протокола - добавим протокол http
	{
		$url = "http://".$_POST['url'];
	};
	//если пользователь ввел свою короткую ссылку
	if (isset($_POST['sh_url']) && ($_POST['sh_url'] != null)) 
	{
		//используем пользовательское сокращение
		//проверим есть ли в списке переменная короткой ссылки
		//загрузим список из файла
		$mass = file ('url.txt');
		for ($x=0;$x<=count($mass);$x++) 
		{
			foreach (unserialize($mass[$x]) as $sh=>$u)
			{
				if ($_POST['sh_url'] == $sh)
				{
					//если есть - выведем сообщение об ошибке и завершаем скрипт
					die("Ошибка! Сокращение уже есть."); 
				}
				elseif (preg_match('#[^a-zA-Z0-9]+#', $_POST['sh_url']))//ищем не латиницу и не цифры
				{
					//если найдем - выведем сообщение об ошибке и завершаем скрипт
					die("Ошибка! Неверные символы.");
				}
			}	
					//если ссылки нет в списке - используем её
					$sh_url = $_POST['sh_url'];			
		}
		//конец проверки

	}
	//если пользователь не ввел свое сокращение	
	else 
	{
		// - генерируем случайное число
		do 
		{
			$sh_url = rand();
			//проверим есть ли в списке сгенерированный ключ
			//загрузим список из файла
			$mass = file ('url.txt');
			for ($x=0;$x<=count($mass);$x++) 
			{
				foreach (unserialize($mass[$x]) as $sh=>$u)
				{
					if ($sh_url == $sh) 
					{
						//если ключ есть - нужно будет ещё раз генерировать, вызовем повтор этого цикла
						$log = true;
					}
				}	
			}
			//конец проверки
		}
		while($log);//конец цикла если ключа нет в списке, инече повтор
	}	
	if (!isset($err) or ($err !=1)) 
	{
		//запишем ссылку и ключ в файл
		$str= array ($sh_url => $url);
		$file = fopen('url.txt', 'a+');
		fwrite ($file, serialize($str).PHP_EOL);
		fclose ($file);
		//выведем на экран ссылку
		echo "<a href='http://".$_SERVER['SERVER_NAME']."?".$sh_url."'>".$_SERVER['SERVER_NAME']."?".$sh_url."</a>";
	}
}

//print_r($_SERVER);
//print_r(unserialize($mass['1']));
//print_r($mass);

?>
