<?php

use yii\db\Migration;

/**
 * Handles the creation of table `pages`.
 */
class m181004_115315_create_pages_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('pages', [
            'id' => $this->primaryKey(),
            'alias' => $this->string(255)->notNull(),
            'title' => $this->string(255)->null(),
            'desc' => $this->string(255)->null(),
            'code' => $this->text()->null(),
            'css' => $this->text()->null(),
            'js' => $this->text()->null(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('pages');
    }
}
