<?php
// This class was automatically generated by a giiant build task
// You should not change it manually as it will be overwritten on next build

namespace backend\modules\campus\models\base;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the base-model class for table "student_record_value".
 *
 * @property integer $student_record_value_id
 * @property integer $student_record_key_id
 * @property integer $student_record_id
 * @property string $body
 * @property integer $status
 * @property integer $sort
 * @property integer $updated_at
 * @property integer $created_at
 * @property string $aliasModel
 */
abstract class StudentRecordValue extends \yii\db\ActiveRecord
{

    const STUDENT_VALUE_STATUS_CLOSE = 0;//关闭
    const STUDENT_VALUE_STATUS_OPEN  = 1;//正常
    const STUDENT_VALUE_STATUS_AUDIT  = 2;//待审核

    const EXAM_TYPE_MIDTERM = 10;       //期中考试
    const EXAM_TYPE_FINALEXAM = 11;     //期末考试
    const EXAM_TYPE_OTHER = 30;         //其他

    public static function optsStatus(){
        return [
            self::STUDENT_VALUE_STATUS_OPEN  => '正常',
            self::STUDENT_VALUE_STATUS_CLOSE => '关闭',
            self::STUDENT_VALUE_STATUS_AUDIT => '待审核',
        ];
    }

    public static function  getStatusValueLabel($value){
        $label = self::optsStatus();
        if(isset($label[$value])){
            return $label[$value];
        }
        return $value;
    }
    public static function optsExamType()
    {
        return [
            self::EXAM_TYPE_MIDTERM => '期中',
            self::EXAM_TYPE_FINALEXAM => '期末',
            self::EXAM_TYPE_OTHER => '其他',
        ];
    }
    public static function getExamTypeLabel($value){
        $label = self::optsExamType();
        if(isset($label[$value])){
            return $label[$value];
        }
        return $value;
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'student_record_value';
    }

    public static function getDb(){
        return Yii::$app->get('campus');
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
            [['student_record_key_id'], 'required'],
            [['student_record_key_id', 'student_record_id', 'status', 'sort'], 'integer'],
            [['body'], 'string', 'max' => 1024],
            [['user_id','school_id','grade_id','score','total_score','exam_type','student_record_key_id'],'required','on'=>'score'],
            [['total_score','score'],'number','on'=>'score'],
        ];
    }

    public function scenarios(){
        $scenario = parent::scenarios();
        $scenario['score'] = ['student_record_key_id','user_id','school_id','grade_id','student_record_id','score','total_score','status','sort','exam_type'];
        return $scenario;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'student_record_value_id' => Yii::t('backend', '自增ID'),
            'student_record_key_id' => Yii::t('backend', '科目标题'),
            'user_id' => Yii::t('backend', '用户'),
            'school_id' => Yii::t('backend', '学校'),
            'grade_id' => Yii::t('backend', '班级'),
            'student_record_id' => Yii::t('backend', '学员档案ID'),
            'total_score' => Yii::t('backend', '总分'),
            'score' => Yii::t('backend', '得分'),
            'body' => Yii::t('backend', '学员档案条目描述'),
            'status' => Yii::t('backend', '状态'),
            'sort' => Yii::t('backend', '默认与排序'),
            'updated_at' => Yii::t('backend', '更新时间'),
            'created_at' => Yii::t('backend', '创建时间'),
            'exam_type' => Yii::t('backend', '成绩类型'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeHints()
    {
        return array_merge(parent::attributeHints(), [
            'student_record_value_id' => Yii::t('backend', '自增ID'),
            // 'student_record_key_id' => Yii::t('backend', '标题ID'),
            'student_record_id' => Yii::t('backend', '学员档案ID'),
            'grade_id' => Yii::t('backend', '请先选择学校'),
            'user_id' => Yii::t('backend', '请先选择班级'),
            'body' => Yii::t('backend', '学员档案条目描述'),
            'total_score' => Yii::t('backend', '科目满分'),
            'score' => Yii::t('backend', '科目得分'),
            'status' => Yii::t('backend', '状态'),
            'sort' => Yii::t('backend', '默认与排序'),
        ]);
    }

    public function getStudentRecordValueToFile(){
        return $this->hasMany(\backend\modules\campus\models\StudentRecordValueToFile::className(),['student_record_value_id'=>'student_record_value_id']);
    }
    public function getStudentRecordKey(){
        return $this->hasOne(\backend\modules\campus\models\StudentRecordKey::className(),['student_record_key_id'=>'student_record_key_id']);
    }
    public function getSchool(){
        return $this->hasOne(\backend\modules\campus\models\School::className(),['school_id'=>'school_id']);
    }
    public function getGrade(){
        return $this->hasOne(\backend\modules\campus\models\Grade::className(),['grade_id'=>'grade_id']);
    }
    /**
     * @inheritdoc
     * @return \backend\modules\campus\models\query\StudentRecordValueQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \backend\modules\campus\models\query\StudentRecordValueQuery(get_called_class());
    }


}