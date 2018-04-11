<?php

use yii\db\Migration;

/**
 * Handles the creation of table `rental`.
 */
class m180411_105409_create_rental_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('rental', [
            'id' => $this->primaryKey(),
            'phone' => $this->string(),
            'mail' => $this->string(),
            'adress' => $this->text(),
            'radius' => $this->integer(),
            'name' => $this->string(),
            'hash' => $this->string(),
            'region_id' => $this->integer()
        ]);
        $this->createIndex(
            'idx-rental-region-id',
            'rental',
            'region_id'
        );
        $this->addForeignKey(
            'fk-region_list-rental_region',
            'rental',
            'region_id',
            'region_list',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('rental');
    }
}
