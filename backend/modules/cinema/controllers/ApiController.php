<?php

namespace backend\modules\cinema\controllers;

// use ApiController;

/**
 * Default controller for the `api` module
 */
class ApiController extends \backend\controllers\ApiController
{
    /**
     * Renders the index view for the module
     * @return string
     */

    public function actions()
    {
        return [
            'index' => ['class' => 'backend\modules\music\actions\HomeAction', 'checkAccess' => [$this, 'checkAccess']],
            'actor' => ['class' => 'backend\modules\cinema\actions\CinemaActorAction', 'checkAccess' => [$this, 'checkAccess']],
            'hall' => ['class' => 'backend\modules\cinema\actions\CinemaHallAction', 'checkAccess' => [$this, 'checkAccess']],
            'search' => ['class' => 'backend\modules\music\actions\SearchAction', 'checkAccess' => [$this, 'checkAccess']],
            'feedback' => ['class' => 'backend\modules\music\actions\FeedbackAction', 'checkAccess' => [$this, 'checkAccess']],
            'browse' => ['class' => 'backend\modules\music\actions\BrowseMusicAction', 'checkAccess' => [$this, 'checkAccess']],
        ];
    }

}
