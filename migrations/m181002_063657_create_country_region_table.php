<?php

use yii\db\Migration;

/**
 * Handles the creation of table `country_region`.
 */
class m181002_063657_create_country_region_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('country', [
            'id' => $this->primaryKey(),
            'text' => $this->string()
        ]);
        $this->createTable('region', [
            'id' => $this->primaryKey(),
            'country_id' => $this->integer(),
            'text' => $this->string()
        ]);
        $this->createIndex(
            'idx-article-region-id',
            'region',
            'country_id'
        );
        $this->addForeignKey(
            'fk-country-region',
            'region',
            'country_id',
            'country',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('country');
        $this->dropTable('region');
    }
}
