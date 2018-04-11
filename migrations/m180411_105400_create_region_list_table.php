<?php

use yii\db\Migration;

/**
 * Handles the creation of table `region_list`.
 */
class m180411_105400_create_region_list_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('region_list', [
            'id' => $this->primaryKey(),
            'text' => $this->string()
        ]);

        /*$this->addForeignKey(
            'fk-bikes-bike_condition',
            'bikes',
            'id',
            'bikes_price',
            'bike_id',
            'CASCADE'
        );*/



    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('region_list');
    }
}
