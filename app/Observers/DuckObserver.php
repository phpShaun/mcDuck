<?php

namespace App\Observers;

use App\Duck;

class DuckObserver
{
    /**
     * Handle the duck "created" event.
     *
     * @param  \App\Duck  $duck
     * @return void
     */
    public function created(Duck $duck)
    {
        //
    }

    /**
     * Handle the duck "updated" event.
     *
     * @param  \App\Duck  $duck
     * @return void
     */
    public function updated(Duck $duck)
    {
        //
    }

    /**
     * Handle the duck "deleted" event.
     *
     * @param  \App\Duck  $duck
     * @return void
     */
    public function deleted(Duck $duck)
    {
        //
    }

    /**
     * Handle the duck "restored" event.
     *
     * @param  \App\Duck  $duck
     * @return void
     */
    public function restored(Duck $duck)
    {
        //
    }

    /**
     * Handle the duck "force deleted" event.
     *
     * @param  \App\Duck  $duck
     * @return void
     */
    public function forceDeleted(Duck $duck)
    {
        //
    }
}
