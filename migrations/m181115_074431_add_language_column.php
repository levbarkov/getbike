<?php

use yii\db\Migration;

/**
 * Class m181115_074431_add_language_column
 */
class m181115_074431_add_language_column extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('article', 'language', $this->string()->null());
        $this->addColumn('pages', 'language', $this->string()->null());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('article', 'language');
        $this->dropColumn('pages', 'language');
        //echo "m181115_074431_add_language_column cannot be reverted.\n";
        //return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m181115_074431_add_language_column cannot be reverted.\n";

        return false;
    }
    */
}
