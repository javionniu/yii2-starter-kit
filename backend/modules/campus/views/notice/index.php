<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;

use backend\modules\campus\models\Notice;

/**
* @var yii\web\View $this
* @var yii\data\ActiveDataProvider $dataProvider
    * @var backend\modules\campus\models\NoticeSearch $searchModel
*/

$this->title = Yii::t('backend', 'Notices');
$this->params['breadcrumbs'][] = $this->title;


/**
* create action column template depending acces rights
*/
$actionColumnTemplates = [];

if (\Yii::$app->user->can('campus_notice_view', ['route' => true])) {
    $actionColumnTemplates[] = '{view}';
}

if (\Yii::$app->user->can('campus_notice_update', ['route' => true])) {
    $actionColumnTemplates[] = '{update}';
}

if (\Yii::$app->user->can('campus_notice_delete', ['route' => true])) {
    $actionColumnTemplates[] = '{delete}';
}
if (isset($actionColumnTemplates)) {
$actionColumnTemplate = implode(' ', $actionColumnTemplates);
    $actionColumnTemplateString = $actionColumnTemplate;
} else {
Yii::$app->view->params['pageButtons'] = Html::a('<span class="glyphicon glyphicon-plus"></span> ' . Yii::t('backend', 'New'), ['create'], ['class' => 'btn btn-success']);
    $actionColumnTemplateString = "{view} {update} {delete}";
}
$actionColumnTemplateString = '<div class="action-buttons">'.$actionColumnTemplateString.'</div>';
?>
<div class="giiant-crud notice-index">

    <?php
//             echo $this->render('_search', ['model' =>$searchModel]);
        ?>

    
    <?php \yii\widgets\Pjax::begin(['id'=>'pjax-main', 'enableReplaceState'=> false, 'linkSelector'=>'#pjax-main ul.pagination a, th a', 'clientOptions' => ['pjax:success'=>'function(){alert("yo")}']]) ?>

    <h1>
        <?= Yii::t('backend', '消息管理') ?>
        <small>
            List
        </small>
    </h1>
    <div class="clearfix crud-navigation">
        <div class="pull-left">
            <?php if(\Yii::$app->user->can('campus_notice_create', ['route' => true])){ ?>

            <?= Html::a('<span class="glyphicon glyphicon-plus"></span> ' . Yii::t('backend', 'New'), ['create'], ['class' => 'btn btn-success']) ?>

            <?php } ?>

            <?php echo Html::a('<span class="glyphicon glyphicon-plus"></span> ' . Yii::t('backend', '创建消息通知'), ['create','category' => Notice::CATEGORY_ONE], ['class' => 'btn btn-success']) ?>

            <?php echo Html::a('<span class="glyphicon glyphicon-plus"></span> ' . Yii::t('backend', '创建学生消息通知'), ['create','category' => Notice::CATEGORY_TWO], ['class' => 'btn btn-success']) ?>


        </div>
        <div class="pull-right">

                        
            <?= \yii\bootstrap\ButtonDropdown::widget([
                'id'          => 'giiant-relations',
                'encodeLabel' => false,
                'label'       => '<span class="glyphicon glyphicon-paperclip"></span> ' . Yii::t('backend', 'Relations'),
                'dropdown'    => [
                    'options' => [
                        'class' => 'dropdown-menu-right'
                    ],
                    'encodeLabels' => false,
                    'items'        => []
                ],
                'options' => [
                    'class' => 'btn-default'
                ]
            ]); ?>
        </div>
    </div>

    <hr />

    <div class="table-responsive">
        <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'pager' => [
        'class' => yii\widgets\LinkPager::className(),
        'firstPageLabel' => Yii::t('backend', 'First'),
        'lastPageLabel' => Yii::t('backend', 'Last'),
        ],
                    'filterModel' => $searchModel,
                'tableOptions' => ['class' => 'table table-striped table-bordered table-hover'],
        'headerRowOptions' => ['class'=>'x'],
        'columns' => [
                [
            'class' => 'yii\grid\ActionColumn',
            'template' => $actionColumnTemplateString,
            'buttons' => [
                'view' => function ($url, $model, $key) {
                    $options = [
                        'title' => Yii::t('yii', 'View'),
                        'aria-label' => Yii::t('yii', 'View'),
                        'data-pjax' => '0',
                    ];
                    return Html::a('<span class="glyphicon glyphicon-file"></span>', $url, $options);
                }
            ],
            'urlCreator' => function($action, $model, $key, $index) {
                // using the column name as key, not mapping to 'id' like the standard generator
                $params = is_array($key) ? $key : [$model->primaryKey()[0] => (string) $key];
                $params[0] = \Yii::$app->controller->id ? \Yii::$app->controller->id . '/' . $action : $action;
                return Url::toRoute($params);
            },
            'contentOptions' => ['nowrap'=>'nowrap']
        ],
			'message:ntext',
			'sender_id',
			'receiver_id',
			'is_sms',
			'is_wechat_message',
			'times',
			'status_send',
			/*'status_check',*/
			/*'title',*/
			/*'message_hash',*/
			/*'receiver_name',*/
			/*'wechat_message_id',*/
			/*'receiver_phone_numeber',*/
        ],
        ]); ?>
    </div>

</div>


<?php \yii\widgets\Pjax::end() ?>

