<?php

namespace CodeShopping\Listeners;

use CodeShopping\User;
use CodeShopping\Events\UserCreatedEvent;

class SendMailToDefinePassword
{
    /**
     * Create the event listener.
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
     * @param  UserCreatedEvent  $event
     * @return void
     */
    public function handle(UserCreatedEvent $event)
    {
        /** @var User $user */
        $user = $event->getUser();
        $token =  \Password::broker()->createToken($user);

        $user->sendPasswordResetNotification($token);
        //$user->notify(new Notification($token));
    }
}
