<?php

namespace App\Handlers\Events;

use App\Events\AdminUserActions;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendAdminUserNotificaitonAction
{
    /**
     * Create the event handler.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  AdminUserActions  $event
     * @return void
     */
    public function handle(AdminUserActions $event)
    {
        //
    }
}
