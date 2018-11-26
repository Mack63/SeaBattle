<?
require_once("View/SetPlayerView.php");
require_once("View/StartGameView.php");
require("View/OnGameView.php");

class Player{
	
	private function ChangeValueSet($serchKey,&$arr, $field){
		if(array_key_exists($serchKey,$arr)){
			$arr[$serchKey] = $field;
			return true;
		}
			return false;
	}
	private function ChangeValueShoot($serchKey, $array, &$shootResult){
		if(array_key_exists($serchKey, $array)){
			if($array[$serchKey] == 'deck'){
					$array[$serchKey] = 'hit';
					echo 'Попадание';
					$shootResult = true;
				}elseif($array[$serchKey] == 'empty'){
					$array[$serchKey] = 'miss';
					echo 'Мимо';
					$shootResult = false;
				}
		}
			return $array;
	}
	
	private function CheckWinner($playerName, $playerName2){
		$json = file_get_contents($playerName.'.json');
		$arrayField = json_decode($json, true);
		
		$json = file_get_contents($playerName2.'.json');
		$arrayField2 = json_decode($json, true);
		
		$hitDeck = 0;
		foreach ($arrayField as $array1) {
            foreach ($array1 as $array2 => $v) {
                if($v === 'hit'){
					$hitDeck++;	
				};
            }
		}
		$hitDeck2 = 0;
		foreach ($arrayField2 as $array1) {
            foreach ($array1 as $array2 => $v) {
                if($v === 'hit'){
					$hitDeck2++;	
				};
            }
		}
		if($hitDeck === 20 && $hitDeck2 < 20){
			echo '<b>Ты проиграл</b>';
		}
		if($hitDeck2 === 20 && $hitDeck < 20){
			echo '<b>Ты выиграл</b>';
		}
	}
	
	public function SetPlayer(){
		$playerSetView = new SetPlayerView();
		$playerSetView->ViewSetPlayer();
	}
	
	public function CreatPlayer($params){
		$decks = explode(" ", trim($params['decks']));
		$playerName = trim($params['playerName']);
		$PlayerName2 = trim($params['atherPlayerName']);
		
		$arrayTest;
		for ($i=0; $i<9;$i++) {
			$arrayTest[$i] = array();
            for ($j=0;$j<9;$j++) {
                $arrayTest[$i][$i.$j] = 'empty';
            }
			foreach($decks as $deck){
				$this->ChangeValueSet($deck, $arrayTest[$i], 'deck');
			}
		}
	
		$jsonData = json_encode($arrayTest);
		$file = fopen($playerName.'.json', 'w');
		$write = fwrite($file, $jsonData);

		if($write) echo "Данные успешно записаны!<br>";
		else 
		echo "Не удалось записать данные!<br>";

		fclose($file);
		
		echo '<form name="test" method="get" action="index.php">';		
		echo '<input value = "startGame" type="hidden" name = "action" size="20">';
		echo '<p><b>Имя игрока:</b><br>';
		echo '<input value = '.$playerName.' type="text" name = "nameOfPlayer" size="20"><br>';
		echo '<p><b>Имя второго игрока:</b><br>';
		echo '<input value = '.$PlayerName2.' type="text" name = "nameOfPlayer2" size="20"><br>';
		echo '<input type="submit" id = "btn" value="Начать игру"></button>';
		echo '</form>';
	}
	
	public function StartGame($params){
		
		$playerName = $params['nameOfPlayer'];
		$playerName2 = $params['nameOfPlayer2'];
		$json = file_get_contents($playerName.'.json');
		$arrayField = json_decode($json, true);
		
		$fileTurn = 'turn.json';

		if (!file_exists($fileTurn)) {
			$jsonData = json_encode($playerName);
			$file = fopen($fileTurn, 'w');
			$write = fwrite($file, $jsonData);
			fclose($file);
			$turnCheck = true;
		}else{
			$json = file_get_contents('turn.json');
			$turn = json_decode($json, true);

			if($turn == $playerName2){
				$turnCheck = false;
			}elseif($turn == $playerName){
				$turnCheck = true;
			}
		}
		
		$startGameView = new StartGameView();
		$startGameView->ViewStartGame($arrayField, $playerName, $playerName2, $turnCheck);
		
	}
	
	public function GetBattle($params){
		$deck = trim($params['deck']);
		$playerName = trim($params['playerName']);
		$playerName2 = trim($params['playerName2']);
		
		$json = file_get_contents($playerName2.'.json');
		$arrFieldBot = json_decode($json, true);
		$shootResult = false;
		
		$i = 0;
		$arrayFieldBotNew;
		foreach ($arrFieldBot as $array1) {
			$arrayFieldBotNew[$i] = array(); 
			$arrayFieldBotNew[$i] = $this->ChangeValueShoot($deck, $array1, $shootResult);
			$i++;
		}
		
		$jsonData = json_encode($arrayFieldBotNew);
		$file = fopen($playerName2.'.json', 'w');
		$write = fwrite($file, $jsonData);
		fclose($file);
		
		if($write) echo $shootResult.'<br>';
		else 
		echo "Не удалось записать данные!<br>";
		
		$json = file_get_contents($playerName.'.json');
		$arrayFieldMe = json_decode($json, true);
		
		$json = file_get_contents($playerName2.'.json');
		$arrFieldBot = json_decode($json, true);

		if($shootResult){
			$jsonData = json_encode($playerName);
			$file = fopen('turn.json', 'w');
			$write = fwrite($file, $jsonData);
			fclose($file);
		}else{
			$jsonData = json_encode($playerName2);
			$file = fopen('turn.json', 'w');
			$write = fwrite($file, $jsonData);
			fclose($file);
		}
		
		$onGameView = new OnGameView();
		$onGameView->OnGameViewR($arrayFieldMe, $arrayFieldBotNew, $playerName, $playerName2, $shootResult);
		$this->CheckWinner($playerName, $playerName2);
	}
	
	public function CheckShoot($params){
		$playerName = $params['playerName'];
		$playerName2 = $params['playerName2'];
		$json = file_get_contents($playerName.'.json');
		$arrayFieldMe = json_decode($json, true);
		
		$json = file_get_contents($playerName2.'.json');
		$arrayFieldBot = json_decode($json, true);
		
		$json = file_get_contents('turn.json');
		$turn = json_decode($json, true);

		if($turn == $playerName){
			$shootResult = true;
		}else{
			$shootResult = false;
		}
		$onGameView = new OnGameView();
		$onGameView->OnGameViewR($arrayFieldMe, $arrayFieldBot, $playerName, $playerName2, $shootResult);
		$this->CheckWinner($playerName, $playerName2);
	}
	
}