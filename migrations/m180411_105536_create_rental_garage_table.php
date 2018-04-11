<?php

use yii\db\Migration;

/**
 * Handles the creation of table `rental_garage`.
 */
class m180411_105536_create_rental_garage_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('rental_garage', [
            'id' => $this->primaryKey(),
            'rental_id' => $this->integer(),
            'bike_id' => $this->integer(),
            'condition_id' => $this->integer(),
            'number' => $this->string(),
            'status' => $this->integer(),
            'year' => $this->string(),
            'millage' => $this->string(),
            'radius' => $this->integer(),
            'region_id'=> $this->integer()
        ]);

        $this->createIndex(
            'idx-rental_garage-rental-id',
            'rental_garage',
            'rental_id'
        );
        $this->createIndex(
            'idx-rental_garage-bike-id',
            'rental_garage',
            'bike_id'
        );

        $this->createIndex(
            'idx-rental_garage-condition-id',
            'rental_garage',
            'condition_id'
        );
        $this->createIndex(
            'idx-rental_garage-region-id',
            'rental_garage',
            'region_id'
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
            'fk-condition_list-garage_condition',
            'rental_garage',
            'condition_id',
            'condition',
            'id',
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
        $this->dropTable('rental_garage');
    }
}
