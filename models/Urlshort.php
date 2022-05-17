<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "urlshort".
 *
 * @property int $id
 * @property string $url
 * @property string $short_code
 * @property int $hits
 * @property string|null $added_date
 */
class Urlshort extends \yii\db\ActiveRecord
{
   /* public $url;
    public $short_code;*/
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'urlshort';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['url'], 'required'],
            [['short_code'], 'string'],
            [['hits'], 'integer'],
            [['added_date','url'], 'safe'],
            [['short_code'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'url' => 'Url',
            'short_code' => 'Short Code',
            'hits' => 'Hits',
            'added_date' => 'Added Date',
        ];
    }
}
