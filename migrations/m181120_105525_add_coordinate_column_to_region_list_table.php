<?php

use yii\db\Migration;

/**
 * Handles adding coordinate to table `region_list`.
 */
class m181120_105525_add_coordinate_column_to_region_list_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('region_list', 'coord', $this->text()->null());
        $this->addColumn('region_list', 'tag_line', $this->text());
        $this->addColumn('region_list', 'adress', $this->text());
        $this->addColumn('region_list', 'description', $this->text());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('region_list', 'coord');
        $this->dropColumn('region_list', 'tag_line');
        $this->dropColumn('region_list', 'adress');
        $this->dropColumn('region_list', 'description');
    }
}
