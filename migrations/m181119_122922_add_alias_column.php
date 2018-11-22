<?php

use yii\db\Migration;

/**
 * Class m181119_122922_add_alias_column
 */
class m181119_122922_add_alias_column extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('region_list', 'alias',$this->string());
        $this->addColumn('country_list', 'alias',$this->string()->unique());

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        //echo "m181119_122922_add_alias_column cannot be reverted.\n";
        $this->dropColumn('region_list', 'alias');
        $this->dropColumn('country_list', 'alias');

    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m181119_122922_add_alias_column cannot be reverted.\n";

        return false;
    }
    */
}
