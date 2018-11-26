
<?
require("Controllers/Player.php");
class Controller{
	private $player;

	function __construct() {
       $this->$player = new Player();
    }
 
	public function Action($params){
		if(!isset($params['action'])){
			$this->$player->SetPlayer();
		}elseif($params['action'] == 'createPlayer'){
			$this->$player->CreatPlayer($params);
		}elseif($params['action'] == 'startGame'){
			$this->$player->StartGame($params);
		}elseif($params['action'] == 'makeShoot'){
			$this->$player->GetBattle($params);
		}elseif($params['action'] == 'checkShoot'){
			$this->$player->CheckShoot($params);
	}
}
}
