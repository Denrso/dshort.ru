<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\Urlshort;
use yii\db\Expression;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        //echo Yii::$app->request->url;
        return $this->render('index');
    }

    public function actionGenkey($url)
    {

            $key = substr(md5(uniqid(rand(), true)),0,7);
            $field = Urlshort::find()->where(['short_code' => $key])->one();
            return $key;


    }

    public function actionGetkey(){


        if(\Yii::$app->request->isAjax){
            $data = Yii::$app->request->post();
            $field = Urlshort::find()->where(['url' => $data['url']])->one();
            if ($field > 0) {
                return Url::base('http').'/'.$field->short_code;
            }
            $key = SiteController::actionGenkey($data['url']);
            $model = new Urlshort();
            $model->url = $data['url'];
            $model->hits = 0;
            $model->short_code = $key;
            $model->added_date = date('Y-m-d H:i:s');
            $model->save();
            if($model->save()){
                return Url::base('http').'/'.$model->short_code;

            }else{
                echo "NOT SAVED";
                print_r($model->getAttributes());
                print_r($model->getErrors());
                exit;
            }

        }

    }



    public function actionShortlink()
    {
        $model = new Urlshort();
        $url = Yii::$app->request->url;
        if($url == '/'){

            return $this->render('link', ['model' => $model]);
        }else{
            $res = explode('/', parse_url($url, PHP_URL_PATH));
            $field = Urlshort::find()->where(['short_code' => $res[1]])->one();
            if ($field > 0) {
                $hit = Urlshort::findOne($field->id);
                $hit->hits = $field->hits+1;
                $hit->save();
                return  $this->redirect($field->url);
            }else{
                \Yii::$app->session->setFlash('msg','ссылка не действительна');

                return $this->render('link', ['model' => $model]);
            }

        }

    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }


}
