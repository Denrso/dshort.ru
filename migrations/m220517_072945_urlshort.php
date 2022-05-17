<?php

use yii\db\Migration;

/**
 * Class m220517_072945_urlshort
 */
class m220517_072945_urlshort extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('urlshort',[
            'id'=>$this->primaryKey(),
            'url'=>$this->string(255),
            'short_code'=>$this->string(20)->unique(),
            'hits'=>$this->integer(),
            'added_date'=>$this->dateTime(),
        ]);


    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('urlshort');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220517_072945_urlshort cannot be reverted.\n";

        return false;
    }
    */
}
