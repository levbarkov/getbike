<?php

use yii\db\Migration;

/**
 * Handles the creation of table `bikes_price`.
 */
class m180411_105512_create_bikes_price_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('bikes_price', [
            'id' => $this->primaryKey(),
            'bike_id' => $this->integer(),
            'condition_id' => $this->integer(),
            'photo' => $this->string(),
            'price' => $this->string(),
            'region_id' => $this->integer()
        ]);

        $this->createIndex(
            'idx-bikes_price-bike-id',
            'bikes_price',
            'bike_id'
        );
        $this->createIndex(
            'idx-bikes_price-condition-id',
            'bikes_price',
            'condition_id'
        );
        $this->createIndex(
            'idx-bikes_price-region-id',
            'bikes_price',
            'region_id'
        );
        $this->addForeignKey(
            'fk-region_list-condition_region',
            'bikes_price',
            'region_id',
            'region_list',
            'id',
            'CASCADE'
        );
        $this->addForeignKey(
            'fk-condition_list-bike_condition',
            'bikes_price',
            'condition_id',
            'condition',
            'id',
            'CASCADE'
        );
        $this->addForeignKey(
            'fk-bikes-bike_condition',
            'bikes_price',
            'bike_id',
            'bikes',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('bikes_price');
    }
}
