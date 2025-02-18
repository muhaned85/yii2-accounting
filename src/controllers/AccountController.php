<?php

namespace muh\accounting\src\controllers;

use Yii;
use muh\accounting\Models\Account;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * AccountController يقوم بإدارة الحسابات ضمن شجرة الحسابات.
 */
class AccountController extends Controller
{
    /**
     * يعرض شجرة الحسابات مع الأرصدة.
     */
    public function actionIndex()
    {
        $accounts = Account::find()->orderBy('code')->all();
        $tree = $this->buildTree($accounts);
        return $this->render('index', [
            'tree' => $tree,
        ]);
    }

    /**
     * يبني شجرة الحسابات بشكل تكراري.
     *
     * @param Account[] $accounts قائمة بجميع الحسابات
     * @param int|null $parentId معرف الحساب الأب (null للجذور)
     * @return array
     */
    protected function buildTree($accounts, $parentId = null)
    {
        $branch = [];
        foreach ($accounts as $account) {
            if ($account->parent_id == $parentId) {
                $children = $this->buildTree($accounts, $account->id);
                $branch[] = [
                    'model' => $account,
                    'children' => $children,
                ];
            }
        }
        return $branch;
    }

    /**
     * يقوم بإنشاء حساب جديد.
     */
    public function actionCreate()
    {
        $model = new Account();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }
        // استرجاع جميع الحسابات لاختيار الحساب الأب (اختياري)
        $accounts = Account::find()->orderBy('code')->all();
        return $this->render('create', [
            'model' => $model,
            'accounts' => $accounts,
        ]);
    }
}
