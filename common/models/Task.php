<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "tasks".
 *
 * @property integer $id
 * @property string $title
 * @property string $description
 * @property integer $author
 * @property string $dateAdded
 * @property string $dateFrom
 * @property string $desiredDateTo
 * @property string $dateTo
 * @property integer $status
 * @property integer $priority
 */
class Task extends \yii\db\ActiveRecord
{

    public $dateRange;

    public static $priorities = [
        '0'     =>  'Отсутствует',
        '1'     =>  'Может подождать',
        '2'     =>  'Не очень важно',
        '3'     =>  'Важно',
        '4'     =>  'Срочно',
        '5'     =>  'Срочнее срочного',
    ];

    public static $statuses = [
        '0'     =>  'Новая',
        '1'     =>  'В работе',
        '2'     =>  'Сделана'
    ];

    public static $priorityColors = [
        '0'     =>  '#bdc3c7',
        '1'     =>  '#3498db',
        '2'     =>  '#f1c40f',
        '3'     =>  '#e67e22',
        '4'     =>  '#e74c3c',
        '5'     =>  '#c0392b',
    ];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tasks';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'description', 'author', 'dateRange'], 'required'],
            [['description'], 'string'],
            [['author', 'status'], 'integer'],
            [['dateRange'], 'date'],
            [['dateAdded', 'dateFrom', 'desiredDateTo', 'dateTo', 'dateRange'], 'safe'],
            [['title'], 'string', 'max' => 255]
        ];
    }

    public function behaviors()
    {
        return [
            'LoggableBehavior' => [
                'class' => 'sammaye\audittrail\LoggableBehavior',
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'            => 'ID',
            'title'         => 'Название',
            'description'   => 'Описание',
            'author'        => 'Автор',
            'dateAdded'     => 'Добавлено',
            'dateFrom'      => 'Начало выполнения',
            'desiredDateTo' => 'Дедлайн',
            'dateTo'        => 'Конец выполнения',
            'status'        => 'Статус',
            'dateRange'     => 'Сроки выполнения',
            'priority'      => 'Приоритет',
        ];
    }

    public function afterFind(){
        $this->dateRange = $this->dateFrom.' до '.$this->desiredDateTo;

        return parent::afterFind();
    }

    public function beforeSave($i){
        if(!empty($this->dateRange)){
            $dates = explode(' до ', $this->dateRange);
            $this->dateFrom = $dates['0'];
            $this->desiredDateTo = $dates['1'];
        }

        if(empty($this->author) && empty($this->oldAttributes['author'])){
            $this->author = \Yii::$app->user->identity['id'];
        }

        if(empty($this->dateAdded) && empty($this->oldAttributes['dateAdded'])){
            $this->dateAdded = date('Y-m-d H:i:s');
        }

        return parent::beforeSave($i);
    }
}
