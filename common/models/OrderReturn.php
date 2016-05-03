<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "vozvrat".
 *
 * @property integer $id
 * @property integer $history_id
 * @property string $data_otpravki
 * @property string $ttn
 * @property string $telefon
 * @property string $prichina_vozvrata
 * @property integer $brak
 * @property integer $sootvetstvie
 * @property integer $nepodoshel
 * @property string $vozvrat_deneg
 * @property integer $suma_vozvrata
 * @property string $comment
 * @property integer $status
 * @property string $bank_cart
 * @property string $bank_pib
 */
class OrderReturn extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'vozvrat';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['history_id', 'data_otpravki', 'ttn', 'telefon', 'prichina_vozvrata', 'brak', 'sootvetstvie', 'nepodoshel', 'vozvrat_deneg', 'suma_vozvrata', 'comment', 'status', 'bank_cart', 'bank_pib'], 'required'],
            [['history_id', 'brak', 'sootvetstvie', 'nepodoshel', 'suma_vozvrata', 'status'], 'integer'],
            [['data_otpravki'], 'string', 'max' => 15],
            [['ttn', 'prichina_vozvrata', 'vozvrat_deneg', 'bank_cart'], 'string', 'max' => 50],
            [['telefon'], 'string', 'max' => 30],
            [['comment'], 'string', 'max' => 2000],
            [['bank_pib'], 'string', 'max' => 100],
        ];
    }

    public function getOrderNumber(){
        return $this->history_id;
    }

    public function setOrderNumber($val){
        $this->history_id = $val;
    }

    public function getSendDate(){
        return $this->data_otpravki;
    }

    public function setSendDate($val){
        $this->data_otpravki = $val;
    }

    public function getRefundMethods(){
        return [
            'на карту'          =>  'на карту ПриватБанк',
            'на личный счёт'    =>  'добавить на ваш личный счет Krasota-Style.ua'
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'history_id' => 'History ID',
            'data_otpravki' => 'Data Otpravki',
            'ttn' => 'Ttn',
            'telefon' => 'Telefon',
            'prichina_vozvrata' => 'Prichina Vozvrata',
            'brak' => 'Brak',
            'sootvetstvie' => 'Sootvetstvie',
            'nepodoshel' => 'Nepodoshel',
            'vozvrat_deneg' => 'Vozvrat Deneg',
            'suma_vozvrata' => 'Suma Vozvrata',
            'comment' => 'Comment',
            'status' => 'Status',
            'bank_cart' => 'Bank Cart',
            'bank_pib' => 'Bank Pib',
        ];
    }
}
