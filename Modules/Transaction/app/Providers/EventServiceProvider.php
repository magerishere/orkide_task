<?php

namespace Modules\Transaction\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\Transaction\app\Events\CardToCardTransaction;
use Modules\Transaction\app\Listeners\SendDepositMail;
use Modules\Transaction\app\Listeners\SendWithdrawMail;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event handler mappings for the application.
     *
     * @var array<string, array<int, string>>
     */
    protected $listen = [
        CardToCardTransaction::class => [
            SendWithdrawMail::class,
            SendDepositMail::class
        ],
    ];

    /**
     * Indicates if events should be discovered.
     *
     * @var bool
     */
    protected static $shouldDiscoverEvents = true;

    /**
     * Configure the proper event listeners for email verification.
     */
    protected function configureEmailVerification(): void
    {
        //
    }
}
