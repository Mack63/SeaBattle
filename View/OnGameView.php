<?php
class OnGameView{
	public function OnGameViewR($arrayFieldMe, $arrayFieldBot, $playerName, $playerName2, $shootResult){
		
		if(!$shootResult){
			$disabledShoot = 'disabled = "disabled"';
			$disabledCheck = '';
		}else{
			$disabledCheck = 'disabled = "disabled"';
			$disabledShoot = '';
		}

		echo '<form method="get" action="index.php">';
		echo '<input id = "shoot" type="text" value = "" name = "deck" size="5">';
		echo '<input value = "makeShoot" type="hidden" name = "action" size="20">';
		echo '<p><b>Имя игрока:</b><br>';
		echo '<input value = '.$playerName.' type="text" name = "playerName" size="20">';
		echo '<p><b>Имя игрока:</b><br>';
		echo '<input value = '.$playerName2.' type="text" name = "playerName2" size="20">';
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
		echo '<script type="text/javascript" src="/js/seabattleAction.js?127345578"></script>';
		
		echo '<table id = "myField" >';
		foreach ($arrayFieldMe as $array1) {
			echo '<tr>';
				foreach ($array1 as $array2 => $value) {
					echo "<td id = $value >$array2<td>";
				}
			echo '</tr>';
		}
		echo '<table>';
		
		echo '<table id = "shootField" >';
		foreach ($arrayFieldBot as $array1) {
			echo '<tr>';
				foreach ($array1 as $array2 => $value) {
					echo "<td class = $value id = $array2 >$array2<td>";
				}
			echo '</tr>';
		}
		echo '<table>';
		
		
		}
	
}