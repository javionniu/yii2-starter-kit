<?php
// This class was automatically generated by a giiant build task
// You should not change it manually as it will be overwritten on next build

namespace backend\modules\campus\models\base;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the base-model class for table "activation_code".
 *
 * @property integer $activation_code_id
 * @property string $activation_code
 * @property integer $course_order_item_id
 * @property integer $school_id
 * @property integer $grade_id
 * @property integer $user_id
 * @property integer $introducer_id
 * @property integer $payment
 * @property integer $status
 * @property string $total_price
 * @property string $real_price
 * @property string $coupon_price
 * @property integer $coupon_type
 * @property integer $expired_at
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $aliasModel
 */
abstract class ActivationCode extends \yii\db\ActiveRecord
{
    const PAYMENT_ONLINE  = 100;        // 在线支付
    const PAYMENT_ALIPAY  = 110;        // 支付宝
    const PAYMENT_WECHAT  = 111;        // 微信支付
    const PAYMENT_OFFLINE = 200;        // 线下支付

    const STATUS_INACTIVATED = 10;   // 未激活
    const STATUS_ACTIVATED = 20;     // 已激活

    const COUPON_TYPE_FIRST  = 2;    // 首单减免
    const COUPON_TYPE_RANDOM = 3;    // 随机减免

    const SCENARIO_BATCH_CREATE = 'batch_create';    // 批量创建

    const MAX_QUANTITY = 100;    // 批量最大数量
    const MIN_QUANTITY = 1;    // 批量最小数量

    public $quantity;   // 数量

    public static function optsStatus()
    {
        return [
            self::STATUS_ACTIVATED => '已激活',
            self::STATUS_INACTIVATED => '未激活',
        ];
    }

    public static function optsPayment()
    {
        return [
            self::PAYMENT_ONLINE => '在线支付',
            self::PAYMENT_ALIPAY => '支付宝',
            self::PAYMENT_WECHAT => '微信支付',
            self::PAYMENT_OFFLINE => '线下支付',
        ];
    }

    public static function optsCoupon()
    {
        return [
            self::COUPON_TYPE_FIRST => '首单减免',
            self::COUPON_TYPE_RANDOM => '随机减免',
        ];
    }

    public static function getStatusValueLabel($value)
    {
        $label = self::optsStatus();
        if (isset($label[$value])) {
            return $label[$value];
        }
        return $value;
    }

    public static function getPaymentValueLabel($value)
    {
        $label = self::optsPayment();
        if (isset($label[$value])) {
            return $label[$value];
        }
        return $value;
    }

    public static function getCouponValueLabel($value)
    {
        $label = self::optsCoupon();
        if (isset($label[$value])) {
            return $label[$value];
        }
        return $value;
    }


    public static function getDb(){
        //return \Yii::$app->modules['campus']->get('campus');
        return Yii::$app->get('campus');
    }
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'activation_code';
    }


    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['course_order_item_id', 'school_id', 'grade_id', 'user_id', 'introducer_id', 'payment', 'status', 'coupon_type'], 'integer'],
            [['school_id','status', 'payment', 'total_price', 'expired_at'], 'required'],
            [['total_price', 'real_price', 'coupon_price'], 'number'],
            [['activation_code'], 'string', 'max' => 32],
            ['introducer_id','default','value' => Yii::$app->user->isGuest ? 0 : Yii::$app->user->identity->id],
            ['expired_at', 'filter', 'filter' => 'strtotime', 'skipOnEmpty' => true],
            ['user_id', 'default', 'value' => 0],
            ['real_price','default','value'=>function(){
                $this->real_price = ($this->total_price-$this->coupon_price);
                return $this->real_price;
            }],
            ['total_price','required','when'=>function($model,$attribute){
                if($model->total_price <= $model->coupon_price){
                    return $this->addError($attribute,'总金额必须大于优惠价格');
                }
            }],
            ['quantity','integer','min' => self::MIN_QUANTITY,'max' => self::MAX_QUANTITY, 'on' => self::SCENARIO_BATCH_CREATE],
            ['quantity','required','on' => self::SCENARIO_BATCH_CREATE],
        ];
    }

    // public function scenarios()
    // {
    //     $scenarios = parent::scenarios();
    //     $scenarios[self::SCENARIO_BATCH_CREATE] = ['quantity','school_id','status','payment','total_price','expired_at'];
    //     return $scenarios;
    // }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'activation_code_id' => Yii::t('models', '激活码ID'),
            'activation_code' => Yii::t('models', '激活码'),
            'course_order_item_id' => Yii::t('models', '订单ID'),
            'school_id' => Yii::t('models', '学校'),
            'grade_id' => Yii::t('models', 'Grade ID'),
            'user_id' => Yii::t('models', '用户'),
            'introducer_id' => Yii::t('models', '创建者'),
            'payment' => Yii::t('models', '支付方式'),
            'status' => Yii::t('models', '激活状态'),
            'total_price' => Yii::t('models', '总价'),
            'real_price' => Yii::t('models', '实际付款'),
            'coupon_price' => Yii::t('models', '优惠金额'),
            'coupon_type' => Yii::t('models', '优惠类型'),
            'expired_at' => Yii::t('models', '过期时间'),
            'created_at' => Yii::t('models', 'Created At'),
            'updated_at' => Yii::t('models', 'Updated At'),
            'quantity' => Yii::t('models', '数量'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeHints()
    {
        return array_merge(parent::attributeHints(), [
            'activation_code' => Yii::t('models', '创建时自动生成唯一6位字符组合'),
            // 'course_order_item_id' => Yii::t('models', '订单ID'),
            // 'introducer_id' => Yii::t('models', '创建者'),
            // 'payment' => Yii::t('models', '100 在线支付； 110 支付宝 支付 ； 111 微信支付；  200 货到付款'),
            // 'status' => Yii::t('models', '10未激活，20已经激活'),
            'total_price' => Yii::t('models', '每个激活码的价格'),
            'real_price' => Yii::t('models', '每个激活码减免优惠后的价格'),
            'coupon_price' => Yii::t('models', '不能超过实际付款'),
            // 'coupon_type' => Yii::t('models', '2首单减免,3随机减免'),
            'expired_at' => Yii::t('models', '过期后无法使用'),
            'quantity' => Yii::t('models', '创建数量，每次最多100个'),
        ]);
    }

    public function getSchool(){
        return $this->hasOne(\backend\modules\campus\models\School::className(),['school_id'=>'school_id']);
    }


    
    /**
     * @inheritdoc
     * @return \backend\modules\campus\models\query\ActivationCodeQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \backend\modules\campus\models\query\ActivationCodeQuery(get_called_class());
    }


}
