<?php

use yii\db\Migration;

/**
 * Handles adding target_side to table `game`.
 */
class m181227_181239_add_target_side_column_to_game_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('game', 'target_side', $this->string()->notNull());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('game', 'target_side');
    }
}
