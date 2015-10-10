<?php

namespace backend\modules\tasks\controllers;

use common\models\Siteuser;
use common\models\Task;
use common\models\TaskUser;
use sammaye\audittrail\AuditTrail;
use yii\data\ActiveDataProvider;
use backend\controllers\SiteController as Controller;

class DefaultController extends Controller
{
    public function actionIndex($p1 = '', $p2 = '')
    {
        if($p1 == "" && $p2 == ""){
            return $this->runAction('actionindex');
        }else{
            if($p2 != ""){
                return $this->runAction($p1, [
                    'param' =>  $p2
                ]);
            }else{
                return $this->runAction($p1);
            }
        }
    }

    public function actionActionindex(){
        return $this->redirect('/admin/tasks/calendar');
        //return $this->renderContent('asd');
    }

    public function actionCalendar(){
        if(\Yii::$app->request->post()){
            $p = \Yii::$app->request->post();

            if(isset($p['Task']) && !empty($p['Task'])){
                if(!empty($p['Task']['id'])){
                    $m = Task::findOne($p['Task']['id']);
                }else{
                    $m = new Task;
                }

                $m->load($p);
                $m->priority = $p['Task']['priority'];
                $m->save(false);
            }

            if(isset($p['TaskUser']) && !empty($p['TaskUser'])){
                $m = new TaskUser;
                $m->load($p);
                $m->save();
            }
        }

        $users = Siteuser::find()->where(['tasksUser' => 1])->orderBy('workStatus asc')->all();
        $events = $events2 = Task::find();

        if(\Yii::$app->request->get("smartfilter")){
            switch(\Yii::$app->request->get("smartfilter")){
                case 'stitched':
                    $events->where('dateTo > desiredDateTo');
                    break;
                case 'inWork':
                    $events->where(['status' => 1]);
                    break;
            }
        }

        return $this->render('index', [
            'users'         =>  $users,
            'events'        =>  $events->all(),
            'dataProvider'  =>  new ActiveDataProvider([
                'query' =>  Task::find()->where('status != 2')->orderBy('desiredDateTo asc')
            ]),
            'doneEvents'  =>  new ActiveDataProvider([
                'query' =>  Task::find()->where('status = 2')->orderBy('desiredDateTo desc')
            ])
        ]);
    }

    public function actionGetuserinfo($p1 = null){
        $userid = $p1;

        if($userid == null){
            $userid = \Yii::$app->request->post("userID");
        }

        $user = Siteuser::findOne($userid);

        if(!$user){
            return false;
        }
        $userTasks = new ActiveDataProvider([
            'query' =>  Task::find()->where(['in', 'id', TaskUser::find()->select('task_id')->where(['user_id' => $user->id])])->andWhere('status != 2')
        ]);

        $userActions = new ActiveDataProvider([
            'query' =>  AuditTrail::find()->where(['user_id' => $user->id, 'model'  =>  Task::className()])->andWhere(['or', 'action = \'SET\'', 'action = \'CHANGE\''])->orderBy('stamp desc')
        ]);

        if(\Yii::$app->request->isAjax){
            \Yii::$app->response->format = 'json';

            return [
                'content'   =>  $this->renderAjax('_ajax_user', [
                    'user'          =>  $user,
                    'userTasks'     =>  $userTasks,
                    'userActions'   =>  $userActions
                ]),
                'title'     =>  'Пользователь '.$user->name
            ];
        }
    }

    public function actionGettaskroles($p1){
        if(\Yii::$app->request->isAjax){
            \Yii::$app->response->format = 'json';

            return [
                '1' =>  '1',
                '2' =>  '2',
                '3' =>  $p1
            ];
        }
    }

    public function actionViewtask($p1 = null){
        $taskID = $p1;

        if($taskID == null){
            $taskID = \Yii::$app->request->post("taskID");
        }

        $task = Task::findOne($taskID);

        if(empty($task)){
            return false;
        }

        $taskChanges = new ActiveDataProvider([
            'query' =>  AuditTrail::find()->where([
                'model'     =>  $task->className(),
                'model_id'  =>  $task->id
            ])->orderBy('stamp DESC')
        ]);

        $workedUsers = TaskUser::find()->select('user_id')->where(['task_id' => $task->id]); //Пользователи, трудящиеся над задачей

        $taskUsers = new ActiveDataProvider([
            'query' =>  Siteuser::find()
                ->select([Siteuser::tableName().'.*', TaskUser::tableName().'.user_role as user_role'])
                ->leftJoin(TaskUser::tableName(), TaskUser::tableName().'.user_id = '.Siteuser::tableName().'.id')
                ->where([TaskUser::tableName().'.task_id' => $task->id])
        ]);

        $activeUsers = [];

        foreach(Siteuser::find()->where(['not in', 'id', $workedUsers])->andWhere(['tasksUser' => 1])->each() as $user){
            $activeUsers[$user->id] = $user->name.' (@'.$user->username.')';
        }

        if(\Yii::$app->request->isAjax){
            \Yii::$app->response->format = 'json';
            return [
                'content'   =>  $this->renderAjax('_ajax_task', [
                    'task'          =>  $task,
                    'taskChanges'   =>  $taskChanges,
                    'taskUsers'     =>  $taskUsers,
                    'activeUsers'   =>  $activeUsers
                ]),
                'title' =>  'Задача #'.$task->id.': '.$task->title,
            ];
        }
    }

    public function actionChangetaskstatus(){
        if(\Yii::$app->request->isAjax){
            $m = Task::findOne(\Yii::$app->request->post("taskID"));

            if(!$m){
                return false;
            }

            $m->status = \Yii::$app->request->post("status");
            $m->dateTo = date('Y-m-d');
            $m->save(false);

            return $m->status;
        }
    }


}
