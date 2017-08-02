<?php
// This class was automatically generated by a giiant build task
// You should not change it manually as it will be overwritten on next build

namespace backend\modules\campus\controllers\base;

use Yii;
use backend\modules\campus\models\CourseSchedule;
use backend\modules\campus\models\search\CourseScheduleSearch;
use common\components\Controller;
use yii\web\HttpException;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\filters\AccessControl;
use dmstr\bootstrap\Tabs;

/**
* CourseScheduleController implements the CRUD actions for CourseSchedule model.
*/
class CourseScheduleController extends Controller
{


/**
* @var boolean whether to enable CSRF validation for the actions in this controller.
* CSRF validation is enabled only when both this property and [[Request::enableCsrfValidation]] are true.
*/
public $enableCsrfValidation = false;

    /**
    * @inheritdoc
    */
    public function behaviors()
    {
    return [
    'access' => [
    'class' => AccessControl::className(),
    'rules' => [
    [
    'allow' => true,
                        'actions' => ['index', 'view', 'create', 'update', 'delete'],
                        'roles' => ['CampusCourseScheduleFull'],
                    ],
    [
    'allow' => true,
                        'actions' => ['index', 'view'],
                        'roles' => ['CampusCourseScheduleView'],
                    ],
    [
    'allow' => true,
                        'actions' => ['update', 'create', 'delete'],
                        'roles' => ['CampusCourseScheduleEdit'],
                    ],
    
                ],
            ],
    ];
    }

/**
* Lists all CourseSchedule models.
* @return mixed
*/
public function actionIndex()
{
    $searchModel  = new CourseScheduleSearch;
    $dataProvider = $searchModel->search($_GET);
    $schools = Yii::$app->user->identity->schoolsInfo;
        $grades =  Yii::$app->user->identity->gradesInfo;
        $schools = ArrayHelper::map($schools,'school_id','school_title');
        $grades  = ArrayHelper::map($grades,'grade_id','grade_name');
        $dataProvider->query->andWhere([
                'c.grade_id'  => $this->gradeIdCurrent,
        ]);
    Tabs::clearLocalStorage();

    Url::remember();
    \Yii::$app->session['__crudReturnUrl'] = null;

    return $this->render('index', [
    'dataProvider' => $dataProvider,
        'searchModel' => $searchModel,
    ]);
}

/**
* Displays a single CourseSchedule model.
* @param integer $course_schedule_id
*
* @return mixed
*/
public function actionView($course_schedule_id)
{
\Yii::$app->session['__crudReturnUrl'] = Url::previous();
Url::remember();
Tabs::rememberActiveState();

return $this->render('view', [
'model' => $this->findModel($course_schedule_id),
]);
}

/**
* Creates a new CourseSchedule model.
* If creation is successful, the browser will be redirected to the 'view' page.
* @return mixed
*/
public function actionCreate()
{
$model = new CourseSchedule;

try {
if ($model->load($_POST) && $model->save()) {
return $this->redirect(['view', 'course_schedule_id' => $model->course_schedule_id]);
} elseif (!\Yii::$app->request->isPost) {
$model->load($_GET);
}
} catch (\Exception $e) {
$msg = (isset($e->errorInfo[2]))?$e->errorInfo[2]:$e->getMessage();
$model->addError('_exception', $msg);
}
return $this->render('create', ['model' => $model]);
}

/**
* Updates an existing CourseSchedule model.
* If update is successful, the browser will be redirected to the 'view' page.
* @param integer $course_schedule_id
* @return mixed
*/
public function actionUpdate($course_schedule_id)
{
$model = $this->findModel($course_schedule_id);

if ($model->load($_POST) && $model->save()) {
return $this->redirect(Url::previous());
} else {
return $this->render('update', [
'model' => $model,
]);
}
}

/**
* Deletes an existing CourseSchedule model.
* If deletion is successful, the browser will be redirected to the 'index' page.
* @param integer $course_schedule_id
* @return mixed
*/
public function actionDelete($course_schedule_id)
{
try {
$this->findModel($course_schedule_id)->delete();
} catch (\Exception $e) {
$msg = (isset($e->errorInfo[2]))?$e->errorInfo[2]:$e->getMessage();
\Yii::$app->getSession()->addFlash('error', $msg);
return $this->redirect(Url::previous());
}

// TODO: improve detection
$isPivot = strstr('$course_schedule_id',',');
if ($isPivot == true) {
return $this->redirect(Url::previous());
} elseif (isset(\Yii::$app->session['__crudReturnUrl']) && \Yii::$app->session['__crudReturnUrl'] != '/') {
Url::remember(null);
$url = \Yii::$app->session['__crudReturnUrl'];
\Yii::$app->session['__crudReturnUrl'] = null;

return $this->redirect($url);
} else {
return $this->redirect(['index']);
}
}

/**
* Finds the CourseSchedule model based on its primary key value.
* If the model is not found, a 404 HTTP exception will be thrown.
* @param integer $course_schedule_id
* @return CourseSchedule the loaded model
* @throws HttpException if the model cannot be found
*/
protected function findModel($course_schedule_id)
{
if (($model = CourseSchedule::findOne($course_schedule_id)) !== null) {
return $model;
} else {
throw new HttpException(404, 'The requested page does not exist.');
}
}
}
