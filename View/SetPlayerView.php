
<?php

class SetPlayerView{

	public function ViewSetPlayer(){
			
		echo '<form name="test" method="get" action="index.php">';
		echo '<p><b>Палубы для создания флота:</b><br>';
		echo '<input id = "decks" type="text" name = "decks" size="20"><br>';
		echo '<input value = "createPlayer" type="hidden" name = "action" size="20">';
		echo '<p><b>Имя игрока:</b><br>';
		echo '<input id = "playerName" type="text" name = "playerName" size="5"><br>';
		
		echo '<p><b>Имя другого игрока:</b><br>';
		echo '<input id = "atherPlayerName" type="text" name = "atherPlayerName" size="5"><br>';
		
		echo '</p>';
		echo '<input type="submit" id = "btn" value="Создать флот"></button>';
		echo '</form>';
		
		echo '<link rel="stylesheet" type="text/css" href="/css/setPlayer.css" >';
		echo '<script type="text/javascript" src="/js/seabattle.js?1273455236"></script>';
		
		echo '<br>';
		echo '<table id = "SetShipField" border = "1">';
		for ($i=0; $i<9;$i++) {
			echo '<tr>';
				for ($j=0;$j<9;$j++) {
					echo '<td id = '."$i$j".' class = "shootCell"><td>';
            }
			echo '</tr>';
		}
		echo '</table>';
		
	}


}	