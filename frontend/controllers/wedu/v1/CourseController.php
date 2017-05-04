<?php
namespace frontend\controllers\wedu\v1;

use Yii;
use yii\web\Response;
use yii\data\ActiveDataProvider;
use frontend\models\wedu\resources\SignIn;

class CourseController extends \common\rest\Controller
{
     public $modelClass = 'frontend\models\wedu\resources\Course'; 
    /**
     * @var array
     */
    public $serializer = [
        'class' => 'common\rest\Serializer',    // 返回格式数据化字段
        'collectionEnvelope' => 'result',       // 制定数据字段名称
        'errno' => 0,                           // 错误处理数字
        'message' => 'OK',                      // 文本提示
    ];

    /**
     * @param  [action] yii\rest\IndexAction
     * @return [type] 
     */
    public function beforeAction($action)
    {
        $format = \Yii::$app->getRequest()->getQueryParam('format', 'json');

        if($format == 'xml'){
            \Yii::$app->response->format = \yii\web\Response::FORMAT_XML;
        }else{
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        }

        // 移除access行为，参数为空全部移除
        // Yii::$app->controller->detachBehavior('access');
        return $action;
    }

    /**
     * @param  [type]
     * @param  [type]
     * @return [type]
     */
    public function afterAction($action, $result){
        $result = parent::afterAction($action, $result);

        return $result;
    }

    public function actions()
    {
        $actions = parent::actions();
        unset($actions['index'],$actions['crete']);
        return $actions;
    }

   


    /**
     * @SWG\Get(path="/course/index",
     *     tags={"700-Course-课程课表"},
     *     summary="message",
     *     description="课程列表",
     *     produces={"application/json"},
     *
     *     @SWG\Response(
     *         response = 200,
     *         description = "已上过的课程"
     *     ),
     * )
     *
    **/

    /**
     * 
     * @param  [type] $type [description]
     * @return [type]       [description]
     */
    public function actionIndex()
    {	
    	$signin = SignIn::find()->where(['student_id'=>Yii::$app->user->identity->id])->all();
    	if(!$signin){
    		return [];
    	}
    	foreach ($signin as $key => $value) {
    		if($value->course){
    			$data[$key] = $value->course->toArray(['course_id','title','intro','courseware_id']);
    			$data[$key]['image_url'] = Yii::$app->params['user_avatar'];
    		}
    	}
    	return $data;
    }  
}