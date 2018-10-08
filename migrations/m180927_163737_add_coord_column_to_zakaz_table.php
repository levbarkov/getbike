<?php

use yii\db\Migration;

/**
 * Handles adding coord to table `zakaz`.
 */
class m180927_163737_add_coord_column_to_zakaz_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('zakaz', 'coord', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('zakaz', 'coord');
    }
}
