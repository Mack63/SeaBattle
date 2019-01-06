<?php

use yii\db\Migration;

/**
 * Handles adding middle_gamer to table `game`.
 */
class m181227_184659_add_middle_gamer_column_to_game_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('game', 'middle_gamer', $this->integer()->notNull());
        $this->addForeignKey('fk_game_middle_user', 'game', 'middle_gamer', 'user', 'id', 'RESTRICT');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('game', 'middle_gamer');
    }
}
