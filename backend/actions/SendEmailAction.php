<?php
namespace backend\actions;

use common\components\FHtml;
use Yii;

class SendEmailAction extends BaseAction
{
    public function run()
    {
        /*Yii::$app->mailer->compose()
            ->setFrom('fruity.tester@gmail.com')
            ->setTo('fruity.cuonghq@gmail.com')
            ->setSubject('Test email')
            ->setTextBody('Plain text content')
            ->setHtmlBody('<b>HTML content</b>')
            ->send()*/;
        $params = 'Hello';
        /*\Yii::$app->mailer->compose(['html' => 'demo-template-html', 'text' => 'demo-template-text'], ['params' => $params])
            ->setFrom('fruity.tester@gmail.com')
            ->setTo('fruity.nam@gmail.com')
            ->setSubject('Hello')
            ->send();*/

        //mail('fruity.nam@gmail.com', 'My Subject', $params);
    }

}
