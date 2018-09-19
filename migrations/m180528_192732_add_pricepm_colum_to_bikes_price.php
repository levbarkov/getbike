<?php

use yii\db\Migration;

/**
 * Class m180528_192732_add_pricepm_colum_to_bikes_price
 */
class m180528_192732_add_pricepm_colum_to_bikes_price extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('bikes_price', 'pricepm', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m180528_192732_add_pricepm_colum_to_bikes_price cannot be reverted.\n";
        $this->dropColumn('bikes_price', 'pricepm');
        //return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180528_192732_add_pricepm_colum_to_bikes_price cannot be reverted.\n";

        return false;
    }
    */
}
