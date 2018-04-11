<?php

use yii\db\Migration;

/**
 * Handles the creation of table `condition`.
 */
class m180411_105401_create_condition_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('condition', [
            'id' => $this->primaryKey(),
            'text' => $this->string()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('condition');
    }
}
