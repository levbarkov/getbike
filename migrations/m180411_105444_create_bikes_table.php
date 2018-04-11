<?php

use yii\db\Migration;

/**
 * Handles the creation of table `bikes`.
 */
class m180411_105444_create_bikes_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('bikes', [
            'id' => $this->primaryKey(),
            'model' => $this->string()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('bikes');
    }
}
