<?php

use yii\db\Migration;

/**
 * Handles the creation of table `language`.
 */
class m181114_061431_create_language_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('source_message', [
            'id' => 'INT(11) AUTO_INCREMENT',
            'category' => 'VARCHAR(32)',
            'message' => 'TEXT',
            'PRIMARY KEY (id)',
        ]);
        $this->createTable('message', [
            'id' => 'INT(11)',
            'language' => 'VARCHAR(16)',
            'translation' => 'TEXT',
            'PRIMARY KEY (id,language)',
        ]);

        $this->addForeignKey('fk_message_source_message', 'message', 'id', 'source_message', 'id','CASCADE','RESTRICT');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('source_message');
        $this->dropTable('message');
    }
}
