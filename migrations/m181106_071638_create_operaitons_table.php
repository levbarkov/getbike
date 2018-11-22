<?php

use yii\db\Migration;

/**
 * Handles the creation of table `operaitons`.
 */
class m181106_071638_create_operaitons_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('operations', [
            'id' => $this->primaryKey(),
            'rental_id' => $this->integer(),
            'order_id' => $this->integer()->null(),
            'sum' =>$this->integer(),
            'operations' => $this->integer(),
            'date' => $this->timestamp()
        ]);

        $this->createIndex(
            'idx-operations-rental_id',
            'operations',
            'rental_id'
        );
        $this->createIndex(
            'idx-operations-order_id',
            'operations',
            'order_id'
        );

        $this->addForeignKey(
            'fk-rental-operations',
            'operations',
            'rental_id',
            'rental',
            'id',
            'CASCADE'
        );
        $this->addForeignKey(
            'fk-order-operations',
            'operations',
            'order_id',
            'zakaz',
            'id',
            'CASCADE'
        );
    }


    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-rental-operations', 'operations');
        $this->dropIndex('idx-operations-rental_id', 'operations');
        $this->dropTable('operaitons');
    }
}
