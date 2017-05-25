<?php

namespace App\Http\ViewComposers;

use Illuminate\View\View;
use Auth;

class AllViewComposer
{
    /**
     * The user repository implementation.
     *
     * @var UserRepository
     */
    protected $currentUser;

    /**
     * Create a new profile composer.
     *
     * @param  UserRepository  $users
     * @return void
     */
    public function __construct()
    {
        $this->currentUser = Auth::user();
    }

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $view->with('currentUser', $this->currentUser);
    }
}