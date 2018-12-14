<?php

class StartGameView{
	
	public function ViewStartGame($arrayField, $playerName, $playerName2, $turnCheck){
		
		if(!$turnCheck){
			$disabledShoot = 'disabled = "disabled"';
			$disabledCheck = '';
		}else{
			$disabledCheck = 'disabled = "disabled"';
			$disabledShoot = '';
		}
		
		echo '<form method="get" action="index.php">';
		echo '<input id = "shoot" type="text" name = "deck" size="5">';
		echo '<input value = "makeShoot" type="hidden" name = "action" size="20">';
		echo '<p><b>Имя игрока:</b><br>';
		echo '<input value = '.$playerName.' type="text" name = "playerName" size="20"><br>';
		echo '<p><b>Имя второго игрока:</b><br>';
		echo '<input value = '.$playerName2.' type="text" name = "playerName2" size="20"><br>';
		echo '<input type="submit" id = "makeShoot" value="Сделать выстрил" '.$disabledShoot.'></button>';
		echo '</form>';
		echo '<br>';
		echo '<form method="get" action="index.php">';
		echo '<input value = "checkShoot" type="hidden" name = "action" size="20">';
		echo '<input value = '.$playerName.' type="hidden" name = "playerName" size="20">';
		echo '<input value = '.$playerName2.' type="hidden" name = "playerName2" size="20">';
		echo '<input type="submit" id = "checkShoot" value="Проверить попадание" '.$disabledCheck.'></button>';
		echo '</form>';
		
		echo '<link rel="stylesheet" type="text/css" href="/css/startGame.css" >';
		echo '<script type="text/javascript" src="/js/seabattleAction.js?1273455236"></script>';
		echo '<table id = "myField" >';
		foreach ($arrayField as $array1) {
			echo '<tr>';
				foreach ($array1 as $array2 => $value) {
					echo "<td id = $value >$array2<td>";
				}
			echo '</tr>';
		}
		echo '<table>';
		
		echo '<table id = "shootField">';
		for ($i=0; $i<9;$i++) { // кол-во строк
			echo '<tr>';
				for ($j=0;$j<9;$j++) { // кол-во столбцов
					echo '<td id = '."$i$j".' class = "shootCell"><td>';
				}
			echo '</tr>';
		}
		echo '</table>';
		
		}
	
}