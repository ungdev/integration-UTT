<?php

namespace App\Http\Controllers;

use Request;
use Redirect;
use DB;
use View;
/**
 * Handle dashboard pages and administrators actions unrealted to any other controller.
 *
 * @author  Thomas Chauchefoin <thomas@chauchefoin.fr>
 * @license MIT
 */
class DashboardController extends BaseController {

    /**
     * Display dashboard index, with changelog, etc.
     *
     * @return Response
     */
    public function getIndex()
    {
        return View::make('dashboard.home');
    }

    /**
     * Display administators manager.
     *
     * @return Response
     */
    public function getAdministrators()
    {
        $administrators = DB::table('administrators')->get();
        return View::make('dashboard.administrators', [
            'administrators' => $administrators
        ]);
    }

    /**
     * Handle any action taken in the administrators manager page.
     *
     * @return RedirectResponse
     */
    public function postAdministrators()
    {
        $action = Request::input('action');
        if ($action == 'add')
        {
            if (DB::table('administrators')->where('student_id', Request::input('student-id'))->first() === null)
            {
                DB::table('administrators')->insert(['student_id' => Request::input('student-id')]);
                return Redirect::back()->withSuccess('L\'utilisateur '. Request::input('student-id') .' a été ajouté !');
            }
            return Redirect::back()->withError('L\'utilisateur existe déjà !');
        }
        else if ($action == 'delete')
        {
            DB::table('administrators')->where('student_id', Request::input('student-id'))->delete();
            return Redirect::back()->withSuccess('Action effectuée !');
        }
        return Redirect::back()->withError('Action invalide !');
    }

}