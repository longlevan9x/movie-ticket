<?php

namespace backend\modules\api\controllers;

use backend\controllers\ApiController;

/**
 * Default controller for the `api` module
 */
class TransportController extends ApiController
{
    /**
     * Renders the index view for the module
     * @return string
     */

    public function actions()
    {
        return [
            'online' => ['class' => 'backend\modules\transport\actions\OnlineAction', 'checkAccess' => [$this, 'checkAccess']],
            'driver-list' => ['class' => 'backend\modules\transport\actions\DriverListAction', 'checkAccess' => [$this, 'checkAccess']],
            'trip' => ['class' => 'backend\modules\transport\actions\TripAction', 'checkAccess' => [$this, 'checkAccess']],
            'trip-list' => ['class' => 'backend\modules\transport\actions\TripListAction', 'checkAccess' => [$this, 'checkAccess']],
            'trip-bulk-delete' => ['class' => 'backend\modules\transport\actions\TripBulkDeleteAction', 'checkAccess' => [$this, 'checkAccess']],
            'track-driver' => ['class' => 'backend\modules\transport\actions\TrackDriverAction', 'checkAccess' => [$this, 'checkAccess']],
        ];
    }

}
