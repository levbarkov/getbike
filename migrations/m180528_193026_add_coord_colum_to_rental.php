<?php

use yii\db\Migration;

/**
 * Class m180528_193026_add_coord_colum_to_rental
 */
class m180528_193026_add_coord_colum_to_rental extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('rental', 'coord', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m180528_193026_add_coord_colum_to_rental cannot be reverted.\n";
        $this->dropColumn('rental', 'coord');
        //return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180528_193026_add_coord_colum_to_rental cannot be reverted.\n";

        return false;
    }
    */
}
