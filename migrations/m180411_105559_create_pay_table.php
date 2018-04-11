<?php

use yii\db\Migration;

/**
 * Handles the creation of table `pay`.
 */
class m180411_105559_create_pay_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('pay', [
            'id' => $this->primaryKey(),
            'status' => $this->string(),
            'info' => $this->text()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('pay');
    }
}
