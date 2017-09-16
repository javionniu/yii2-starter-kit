<?php
// This class was automatically generated by a giiant build task
// You should not change it manually as it will be overwritten on next build

namespace backend\modules\campus\models\base;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the base-model class for table "couese_order_item".
 *
 * @property integer $couese_order_item_id
 * @property integer $parent_id
 * @property integer $school_id
 * @property integer $grade_id
 * @property integer $user_id
 * @property integer $introducer_id
 * @property integer $payment
 * @property integer $presented_course
 * @property integer $status
 * @property integer $payment_status
 * @property string $total_price
 * @property string $real_price
 * @property string $coupon_price
 * @property integer $total_course
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $aliasModel
 */
abstract class CourseOrderItem extends \yii\db\ActiveRecord
{

    const PAYMENT_STATUS_REFUNDED   = 400;     // 400退款
    const PAYMENT_STATUS_PAID       = 300;     // 300已成功支付
    const PAYMENT_STATUS_CONFIRMING = 200;     // 200确认中
    const PAYMENT_STATUS_NON_PAID   = 100;     // 100未支付

    const PAYMENT_ONLINE         = 100;        // 在线支付
    const PAYMENT_ALIPAY         = 110;        // 支付宝
    const PAYMENT_WECHAT         = 111;        // 微信支付
    const PAYMENT_APPLEPAY_INAPP = 115;        // 苹果内购支付
    const PAYMENT_APPLEPAY       = 116;        // 苹果支付
    const PAYMENT_OFFLINE        = 200;        // 线下支付

    const STATUS_VALID   = 10;        // 有效
    const STATUS_INVALID = 20;        // 无效

    public static function optStatus()
    {
        return [
            self::STATUS_VALID   => '有效',
            self::STATUS_INVALID => '无效',
        ];
    }

    public static function getStatusValueLabel($value)
    {
        $lable = self::optStatus();
        if(isset($lable[$value])){
            return $lable[$value];
        }
        return $value;
    }

    public static function optPayment()
    {
        return [
            self::PAYMENT_ONLINE         => '在线支付',
            self::PAYMENT_ALIPAY         => '支付宝',
            self::PAYMENT_WECHAT         => '微信支付',
            self::PAYMENT_APPLEPAY_INAPP => 'Apple Pay In-APP',
            self::PAYMENT_APPLEPAY       => 'Apple Pay',
            self::PAYMENT_OFFLINE        => '线下支付',
        ];
    }

    public static function getPaymentValueLabel($value)
    {
        $lable = self::optPayment();
        if(isset($lable[$value])){
            return $lable[$value];
        }
        return $value;
    }

    public static function optPaymentStatus()
    {
        return [
            self::PAYMENT_STATUS_REFUNDED   => '退款',
            self::PAYMENT_STATUS_PAID       => '成功支付',
            self::PAYMENT_STATUS_CONFIRMING => '确认中',
            self::PAYMENT_STATUS_NON_PAID   => '未支付',
        ];
    }

    public static function getPaymentStatusValueLabel($value)
    {
        $lable = self::optPaymentStatus();
        if(isset($lable[$value])){
            return $lable[$value];
        }
        return $value;
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'course_order_item';
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

    public static function getDb(){
        return Yii::$app->get('campus');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['parent_id', 'school_id', 'grade_id', 'user_id', 'introducer_id', 'payment', 'presented_course', 'status', 'payment_status', 'total_course','course_id','coupon_type','expired_at'], 'integer'],
            [['user_id', 'total_price','total_course'], 'required'],
            ['payment_status','default','value'=>300],
            ['payment','default','value'=>200],
            ['real_price','default','value'=>function(){
                $this->real_price = ($this->total_price-$this->coupon_price);
                return $this->real_price;
            }],
             ['presented_course','default','value'=>0],
            [
                'total_price','required','when'=>function($model,$attribute){
                        if($model->total_price < $model->coupon_price){
                            return $this->addError($attribute,'总金额不能小于优惠价格');
                        }
                }
            ],
            [['total_price',  'coupon_price'], 'number'],
            ['real_price','safe'],
            [['payment_id','order_sn'],'string'],
            [['order_sn'], 'string', 'max' => 32],
            [['order_sn'], 'unique'],
            ['data','safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'course_order_item_id' => Yii::t('backend', '课程订单ID'),
            'payment_id'           => Yii::t('backend', '交易流水号'),
            'order_sn'             => Yii::t('backend', '订单号'),
            'course_id'            => Yii::t('backend', '课程ID'),
            'parent_id'            => Yii::t('backend', '父订单ID'),
            'school_id'            => Yii::t('backend', '学校'),
            'grade_id'             => Yii::t('backend', '班级'),
            'user_id'              => Yii::t('backend', '用户'),
            'introducer_id'        => Yii::t('backend', '介绍人'),
            'payment'              => Yii::t('backend', '支付方式'),
            'presented_course'     => Yii::t('backend', '赠送课程'),
            'status'               => Yii::t('backend', '订单状态'),
            'payment_status'       => Yii::t('backend', '支付状态'),
            'total_price'          => Yii::t('backend', '总价'),
            'real_price'           => Yii::t('backend', '实际付款'),
            'coupon_price'         => Yii::t('backend', '优惠金额'),
            'coupon_type'          => Yii::t('backend', '优惠类型'),
            'total_course'         => Yii::t('backend', '课程总数'),
            'expired_at'         => Yii::t('backend', '过期时间'),
            'created_at'           => Yii::t('backend', '创建时间'),
            'updated_at'           => Yii::t('backend', '更新时间'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeHints()
    {
        return array_merge(parent::attributeHints(), [
            'payment_id'       => Yii::t('backend', '创建后不可修改'),
            'order_sn'         => Yii::t('backend', '创建后不可修改'),
            'parent_id'        => Yii::t('backend', '父订单id'),
            'introducer_id'    => Yii::t('backend', '介绍人'),
            'payment'          => Yii::t('backend', '支付方式'),
            'presented_course' => Yii::t('backend', '赠送的课程'),
            'status'           => Yii::t('backend', '订单的状态'),
            'payment_status'   => Yii::t('backend', '支付状态'),
            'total_price'      => Yii::t('backend', '总价'),
            'real_price'       => Yii::t('backend', '实际付款'),
            'coupon_price'     => Yii::t('backend', '优惠金额'),
            'total_course'     => Yii::t('backend', '课程总数，不含赠送'),
        ]);
    }

    public function getUser(){
        return $this->hasOne(\common\models\User::className(),['id'=>'user_id']);
    }

    public function getIntroducer(){
        return $this->hasOne(\common\models\User::className(),['id'=>'introducer_id']);
    }

    public function getSchool(){
        return $this->hasOne(\backend\modules\campus\models\School::className(),['school_id'=>'school_id']);
    }

    public function getGrade(){
        return $this->hasOne(\backend\modules\campus\models\Grade::className(),['grade_id'=>'grade_id']);
    }

    /**
     * 生成订单号，小于32位
     * @author Bruce_bnu@126.com
     * @copyright   [copyright]
     * @license     [license]
     * @version     [version]
     * @date        2016-06-28
     * @anotherdate 2016-06-28T14:35:01+0800
     * @param       [type]                   $buyer_id [description]
     * @param       integer                  $level    [description]
     * @return      [string]                             [小于32位]
     */
    public function builderNumber($params = [])
    {
        $year_code = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N');
        $order_sn  = $year_code[intval(date('Y'))-2010];
        $order_sn .= strtoupper(dechex(date('m')));
        $order_sn .= date('d').substr(time(),-5);
        $order_sn .= substr(microtime(),2,5);
        $order_sn .= sprintf('%02d',rand(0,99));
        return $order_sn;
    }
    
    /**
     * @inheritdoc
     * @return \backend\modules\campus\models\query\CourseOrderItemQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \backend\modules\campus\models\query\CourseOrderItemQuery(get_called_class());
    }


}
