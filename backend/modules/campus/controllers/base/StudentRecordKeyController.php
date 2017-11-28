<?php
// This class was automatically generated by a giiant build task
// You should not change it manually as it will be overwritten on next build

namespace backend\modules\campus\controllers\base;

use backend\modules\campus\models\StudentRecordKey;
use backend\modules\campus\models\search\StudentRecordKeySearch;
use common\components\Controller;
use yii\web\HttpException;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\filters\AccessControl;
use dmstr\bootstrap\Tabs;
use Yii;

/**
* StudentRecordKeyController implements the CRUD actions for StudentRecordKey model.
*/
class StudentRecordKeyController extends Controller
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
                        'roles' => ['CampusStudentRecordKeyFull'],
                    ],
    [
    'allow' => true,
                        'actions' => ['index', 'view'],
                        'roles' => ['CampusStudentRecordKeyView'],
                    ],
    [
    'allow' => true,
                        'actions' => ['update', 'create', 'delete'],
                        'roles' => ['CampusStudentRecordKeyEdit'],
                    ],
    
                ],
            ],
    ];
    }

/**
* Lists all StudentRecordKey models.
* @return mixed
*/
public function actionIndex()
{
    $searchModel  = new StudentRecordKeySearch;
    $dataProvider = $searchModel->search($_GET);
        $schools = Yii::$app->user->identity->schoolsInfo;
        $grades =  Yii::$app->user->identity->gradesInfo;
        $schools = ArrayHelper::map($schools,'school_id','school_title');
        $grades  = ArrayHelper::map($grades,'grade_id','grade_name');
Tabs::clearLocalStorage();

Url::remember();
\Yii::$app->session['__crudReturnUrl'] = null;

return $this->render('index', [
'dataProvider' => $dataProvider,
    'searchModel' => $searchModel,
    'grades'       => $grades,
        'schools'      => $schools,
]);
}

/**
* Displays a single StudentRecordKey model.
* @param integer $student_record_key_id
*
* @return mixed
*/
public function actionView($student_record_key_id)
{
\Yii::$app->session['__crudReturnUrl'] = Url::previous();
Url::remember();
Tabs::rememberActiveState();

return $this->render('view', [
'model' => $this->findModel($student_record_key_id),
]);
}

/**
* Creates a new StudentRecordKey model.
* If creation is successful, the browser will be redirected to the 'view' page.
* @return mixed
*/
public function actionCreate()
{
$model = new StudentRecordKey;
if (env('THEME') == 'gedu') {
   $model->setScenario('score');
}
try {
if ($model->load($_POST) && $model->save()) {

return $this->redirect(Url::previous());
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
* Updates an existing StudentRecordKey model.
* If update is successful, the browser will be redirected to the 'view' page.
* @param integer $student_record_key_id
* @return mixed
*/
public function actionUpdate($student_record_key_id)
{
$model = $this->findModel($student_record_key_id);
if (env('THEME') == 'gedu') {
   $model->setScenario('score');
}
if ($model->load($_POST) && $model->save()) {
return $this->redirect(Url::previous());
} else {
return $this->render('update', [
'model' => $model,
]);
}
}

/**
* Deletes an existing StudentRecordKey model.
* If deletion is successful, the browser will be redirected to the 'index' page.
* @param integer $student_record_key_id
* @return mixed
*/
public function actionDelete($student_record_key_id)
{
try {
$this->findModel($student_record_key_id)->delete();
} catch (\Exception $e) {
$msg = (isset($e->errorInfo[2]))?$e->errorInfo[2]:$e->getMessage();
\Yii::$app->getSession()->addFlash('error', $msg);
return $this->redirect(Url::previous());
}

// TODO: improve detection
$isPivot = strstr('$student_record_key_id',',');
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
* Finds the StudentRecordKey model based on its primary key value.
* If the model is not found, a 404 HTTP exception will be thrown.
* @param integer $student_record_key_id
* @return StudentRecordKey the loaded model
* @throws HttpException if the model cannot be found
*/
protected function findModel($student_record_key_id)
{
if (($model = StudentRecordKey::findOne($student_record_key_id)) !== null) {
return $model;
} else {
throw new HttpException(404, 'The requested page does not exist.');
}
}
}