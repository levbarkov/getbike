<?php

use yii\db\Migration;

/**
 * Handles the creation of table `article`.
 */
class m180921_125341_create_article_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('article', [
            'id' => $this->primaryKey(),
            'title' => $this->text(),
            'page_title' => $this->text(),
            'page_decs' => $this->text(),
            'text' => $this->text(),
            'country_id' => $this->integer(),
            'region_id' => $this->integer()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('article');
    }
}
