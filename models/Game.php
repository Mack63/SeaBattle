<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "game".
 *
 * @property int $id
 * @property int $left_gamer
 * @property int $right_gamer
 * @property string $attack_side
 *
 * @property User $leftGamer
 * @property User $rightGamer
 */
class Game extends \yii\db\ActiveRecord
{
    const SIDE_LEFT = 'left';
    const SIDE_RIGHT = 'right';
    const SIDE_MIDDLE = 'middle';

    /**
     * Масив, где ключи - сторогы а значения экземпляры класса Field
     * @var $fields array
     */
    protected $fields;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'game';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['left_gamer', 'right_gamer', 'middle_gamer'], 'required'],
            [['left_gamer', 'right_gamer', 'middle_gamer'], 'default', 'value' => null],
            [['left_gamer', 'right_gamer', 'middle_gamer'], 'integer'],
            [['attack_side'], 'default', 'value' => self::getFirstStepSide()],
            [['target_side'], 'default', 'value' => self::getFirstStepSideAttack()],
            [['attack_side', 'target_side'], 'string', 'max' => 255],
            [['left_gamer'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['left_gamer' => 'id']],
            [['right_gamer'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['right_gamer' => 'id']],
            [['middle_gamer'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['middle_gamer' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'left_gamer' => 'Left Gamer',
            'right_gamer' => 'Right Gamer',
            'attack_side' => 'Attack Side',
        ];
    }

    /**
     * возвращает все стороны игры
     * @return array
     */
    public static function getSides()
    {
        return [ self::SIDE_LEFT , self::SIDE_RIGHT ];
    }

    public static function getFirstStepSide()
    {
        return self::SIDE_LEFT;
    }
    
    public static function getFirstStepSideAttack()
    {
        return self::SIDE_MIDDLE;
    }

    /**
     * Отдает пользователя делающего текущий ход
     */
    public function getCurrentUser() : User
    {
        switch (trim($this->attack_side)){
            case self::SIDE_RIGHT;
                return $this->rightGamer;
            case self::SIDE_LEFT;
                return $this->leftGamer;
            case self::SIDE_MIDDLE;
                return $this->middleGamer;
        }
    }
    
    public function getUser($side)
    {
        switch ($side){
            case self::SIDE_RIGHT;
                return $this->rightGamer->firstName;
            case self::SIDE_LEFT;
                return $this->leftGamer->firstName;
            case self::SIDE_MIDDLE;
                return $this->middleGamer->firstName;
        }
    }
    
    public function getCurrentSide()
    {
        return $this->attack_side;
    }

    /**
     * Отдает текущее поле, по которому палит текущий пользователь
     */
    public function getAttackedField()
    {
        return $this->getField($this->getAttackedSide());
    }

    /**
     * Отдает название стороны, по которой палит текущий пользователь
     */
    public function getAttackedSide()
    {
        switch (trim($this->attack_side)){
            case self::SIDE_RIGHT;
                return self::SIDE_MIDDLE;
            case self::SIDE_LEFT;
                return self::SIDE_MIDDLE;
            case self::SIDE_MIDDLE;
                return trim($this->target_side);    
                
        }
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLeftGamer()
    {
        return $this->hasOne(User::className(), ['id' => 'left_gamer']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRightGamer()
    {
        return $this->hasOne(User::className(), ['id' => 'right_gamer']);
    }
    
     /**
     * @return \yii\db\ActiveQuery
     */
    public function getMiddleGamer()
    {
        return $this->hasOne(User::className(), ['id' => 'middle_gamer']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDecks()
    {
        return $this->hasMany(Figure::className(), ['game_id' => 'id'])->inverseOf('game');
    }

    public function getLeftDecks()
    {
        return $this->hasMany(Figure::className(), ['game_id' => 'id'])->onCondition(['side' => self::SIDE_LEFT]);
    }

    public function getRightDecks()
    {
        return $this->hasMany(Figure::className(), ['game_id' => 'id'])->onCondition(['side' => self::SIDE_RIGHT]);

    }
    
    public function getMiddleDecks()
    {
        return $this->hasMany(Figure::className(), ['game_id' => 'id'])->onCondition(['side' => self::SIDE_MIDDLE]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSteps()
    {
        return $this->hasMany(Step::className(), ['game_id' => 'id'])->inverseOf('game');
    }

    public function getLeftSteps()
    {
        return $this->hasMany(Step::className(), ['game_id' => 'id'])->onCondition(['side'=>self::SIDE_LEFT]);
    }

    public function getRightSteps()
    {
        return $this->hasMany(Step::className(), ['game_id' => 'id'])->onCondition(['side'=>self::SIDE_RIGHT]);
    }
    
    public function getMiddleSteps()
    {
        return $this->hasMany(Step::className(), ['game_id' => 'id'])->onCondition(['side'=>self::SIDE_MIDDLE]);
    }
    
    public function checkGameResult($side){
        
        if($side === self::SIDE_MIDDLE)
        {
            $hitMiddle = $this->hasMany(Step::className(), ['game_id' => 'id'])->onCondition(['side'=>self::SIDE_MIDDLE, 'result'=>\app\models\Cell::HIT_STATE])->count();
            if($hitMiddle === 6){
               return false;
            }else{
               return self::SIDE_MIDDLE;
            }
        }elseif($side === self::SIDE_LEFT)
        {
            $hitLeft = $this->hasMany(Step::className(), ['game_id' => 'id'])->onCondition(['side'=>self::SIDE_LEFT, 'result'=>\app\models\Cell::HIT_STATE])->count();
            $hitRight = $this->hasMany(Step::className(), ['game_id' => 'id'])->onCondition(['side'=>self::SIDE_RIGHT, 'result'=>\app\models\Cell::HIT_STATE])->count();
            if($hitLeft === 10 && $hitRight === 10){
                return false;
            }elseif($hitLeft === 10 && $hitRight < 10){
                return self::SIDE_RIGHT;
            }
            return self::SIDE_LEFT;
        }elseif($side === self::SIDE_RIGHT)
        {
            $hitRight = $this->hasMany(Step::className(), ['game_id' => 'id'])->onCondition(['side'=>self::SIDE_RIGHT, 'result'=>\app\models\Cell::HIT_STATE])->count();
            $hitLeft = $this->hasMany(Step::className(), ['game_id' => 'id'])->onCondition(['side'=>self::SIDE_LEFT, 'result'=>\app\models\Cell::HIT_STATE])->count();
            if($hitRight === 10 && $hitLeft === 10){
                return false;
            }elseif($hitRight === 10 && $hitLeft < 10){
                return self::SIDE_LEFT;
            }
            return self::SIDE_RIGHT;
        }
    }

    public function getSideSteps($side)
    {
        switch ($side){
            case self::SIDE_RIGHT;
                return $this->rightSteps;
            case self::SIDE_LEFT;
                return $this->leftSteps;
            case self::SIDE_MIDDLE;
                return $this->middleSteps;
        }
    }
    
    public function getSideDecks($side)
    {
        switch ($side){
            case self::SIDE_RIGHT;
                return $this->rightDecks;
            case self::SIDE_LEFT;
                return $this->leftDecks;
            case self::SIDE_MIDDLE;
                return $this->middleDecks;
        }
    }

    public function getField($side) : Field
    {
        if(($this->fields[$side] ?? null) instanceof Field) {
            return $this->fields[$side];
		}
        
        // если мы раньше не обращались к полю - его надо сформировать из палуб и прошлых выстрелов
        $this->fields[$side] = new Field(['decks' => $this->getSideDecks($side), 'steps' => $this->getSideSteps($side), 'side' => $side]);             
        return $this->fields[$side];
		
    }

    /**
     * Нуждается ли игра в заполении (с какой стороны)
     * @return string|false
     */
    public function isNeedToFillBySide()
    {
        if (!$this->leftDecks) {
            return self::SIDE_LEFT;
        }
        if (!$this->rightDecks){
            return self::SIDE_RIGHT;
        }
        
        if (!$this->middleDecks){
            return self::SIDE_MIDDLE;
        }
        return false;
    }

    public function getNext()
    {
        //return ($this->attack_side == self::SIDE_LEFT) ? self::SIDE_RIGHT : self::SIDE_LEFT;
        switch ($this->attack_side){
            case self::SIDE_LEFT;
                return self::SIDE_RIGHT;
            case self::SIDE_RIGHT;
                return self::SIDE_MIDDLE;
            case self::SIDE_MIDDLE;
                return self::SIDE_LEFT;              
        }
    }
    
    public function getNextAttackTarget()
    {
        if(trim($this->attack_side) === self::SIDE_LEFT){
           $attack_side = self::SIDE_MIDDLE;
           $target_side = self::SIDE_LEFT;           
        }
        
        if(trim($this->attack_side) === self::SIDE_MIDDLE && trim($this->target_side) === self::SIDE_LEFT){
           $attack_side = self::SIDE_RIGHT;
           $target_side = self::SIDE_MIDDLE;
        }
        
        if(trim($this->attack_side) === self::SIDE_RIGHT){
           $attack_side = self::SIDE_MIDDLE;
           $target_side = self::SIDE_RIGHT;
        }
        
        if(trim($this->attack_side) === self::SIDE_MIDDLE && trim($this->target_side) === self::SIDE_RIGHT){
           $attack_side = self::SIDE_LEFT;
           $target_side = self::SIDE_MIDDLE;
        }
        return ['attack_side' => $attack_side, 'target_side' => $target_side];
    }

    public function fillSide($data){
        $side = $this->isNeedToFillBySide();
        foreach ($data as $coordinates){
            $coordinates = array_values(array_flip($coordinates));
            $deck =  new Figure();
            $deck->coordinates = array_shift($coordinates);
            $deck->side = $side;
            $deck->game_id = $this->id;
            $deck->save();
        }
        $this->attack_side = $this->getNext();
        $this->save();
        $this->refresh();
    }

    /**
     *
     * @param $data  - входящие даные
     */
    public function step($data){      
        $arrKeys = array_keys($data);
        $coord = array_shift($arrKeys);       
        $field = $this->getAttackedField(); 
        $newState = $field->getCell($coord)->applyRule(
            [
                Cell::EMPTY_STATE => Cell::MISS_STATE,
                Cell::DECK_STATE => Cell::HIT_STATE
            ]
        );

        //создание нового хода
        $step = new Step();
        $step->coordinates = $coord;
        $step->side = $this->getAttackedSide();
        $step->game_id = $this->id;
        $step->result = $newState;
        $step->save();

        
        
        // если не попал - ход отдается другому игроку
        if (in_array($newState, [Cell::MISS_STATE])) {
            $turn = $this->getNextAttackTarget(); // логика смены очередности выстрелов
            $this->attack_side = $turn['attack_side'];
            $this->target_side = $turn['target_side'];
            $this->save();
        }
        
        // проверка победы и если left или right потопили то он выбывает из игры
        $target_side = $this->checkGameResult($this->target_side);
        if($target_side){
            $this->target_side = $target_side;
            $this->save();
        }else{
            if($this->target_side === self::SIDE_MIDDLE){
               return $this->getUser(self::SIDE_MIDDLE); 
            }else{
               return $this->getUser(self::SIDE_LEFT).' и '.$this->getUser(self::SIDE_RIGHT); 
            }
        }
        
        $attack_side = $this->checkGameResult($this->attack_side);
        if($attack_side){
            $this->attack_side = $attack_side;
            $this->save();
        }
        
        return null;
    }
}
