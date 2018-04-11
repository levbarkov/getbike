<?php

use yii\db\Migration;

/**
 * Handles the creation of table `region_list`.
 */
class m180411_105706_create_region_list_table extends Migration
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

        $this->addForeignKey(
            'fk-region_list-zakaz_region',
            'zakaz',
            'region_id',
            'region_list',
            'id',
            'CASCADE'
        );
        $this->addForeignKey(
            'fk-region_list-garage_region',
            'rental_garage',
            'region_id',
            'region_list',
            'id',
            'CASCADE'
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
            'fk-region_list-rental_region',
            'rental',
            'region_id',
            'region_list',
            'id',
            'CASCADE'
        );



        $this->addForeignKey(
            'fk-condition_list-garage_condition',
            'rental_garage',
            'condition_id',
            'condition',
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
            'bikes',
            'id',
            'bikes_price',
            'bike_id',
            'CASCADE'
        );
        $this->addForeignKey(
            'fk-bikes-garage_bikes',
            'rental_garage',
            'bike_id',
            'bikes',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-rental-garage_rental',
            'rental',
            'id',
            'rental_garage',
            'rental_id',
            'CASCADE'
        );



    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('region_list');
    }
}
