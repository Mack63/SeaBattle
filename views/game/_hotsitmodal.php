<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user \app\models\User */

\yii\bootstrap\Modal::begin([

    'clientOptions' => ['show' => true],
    'size' => 'modal-lg',

]);

if($gameIsOver){
   echo "Игра закончилась"; 
}else{
    echo "Ход игрока " . $user->firstName;
}



\yii\bootstrap\Modal::end();

?>

