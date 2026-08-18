<?php

namespace App\Listeners;

/**
 * Deprecated legacy listener.
 * Email dispatching has been split into SendApplicationSubmittedListener and SendApplicationStatusUpdatedListener
 * to prevent duplicate email triggers.
 */
class SendApplicationStatusNotification
{
    // Class intentionally left empty to avoid duplicate event handling.
}
