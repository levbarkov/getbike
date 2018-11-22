<?php

use yii\db\Migration;

/**
 * Handles the creation of table `country_list`.
 */
class m181119_113037_create_country_list_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('country_list', [
            'id' => $this->primaryKey(),
            'iso' => $this->string()->notNull(),
            'text' => $this->string()->notNull(),
            'currency'=>$this->string()->notNull(),
            'base_cord'=>$this->text()
        ]);
        $this->addColumn('region_list','country_id', $this->integer());
        $this->createIndex('index_region_list','region_list', 'country_id');
        $this->addForeignKey(
            'fk_country_list_region_list',
            'region_list',
            'country_id',
            'country_list',
            'id',
            'CASCADE',
            'RESTRICT');

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('country_list');
    }
}
