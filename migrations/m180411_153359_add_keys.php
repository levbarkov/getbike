<?php

use yii\db\Migration;

/**
 * Class m180411_153359_add_keys
 */
class m180411_153359_add_keys extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addForeignKey(
            'fk-pay-zakaz',
            'zakaz',
            'pay_id',
            'pay',
            'id',
            'CASCADE'
        );

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m180411_153359_add_keys cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180411_153359_add_keys cannot be reverted.\n";

        return false;
    }
    */
}
