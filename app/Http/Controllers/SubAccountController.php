<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Auth;

class SubAccountController extends Controller
{

    public function __construct()
    {
        $this->middleware('manager');
    }

    /**
     * Get the sub accounts
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('account.sub_accounts', [
            'accounts' => User::whereCompanyId(Auth::user()->company_id)->paginate(15)
        ]);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'username' => 'required',
            'password' => 'required|confirmed',
            'email' => 'required|email'
        ]);

        if ($validator->passes()) {
            $user = new User;

            $user->username     = $request->input('username');
            $user->company_id   = Auth::user()->company_id;
            $user->email        = $request->input('email');
            $user->active       = true;
            $user->isAdmin      = false;
            $user->manager      = $request->input('manager');
            $user->password     = bcrypt($request->input('password'));

            if ($user->save()) {
                \Log::info('Created a sub account for user ' . Auth::user()->username);

                return redirect('account/accounts')
                    ->with('message', 'Het sub account is aangemaakt.');
            } else {
                \Log::error('An error occurred while creating a sub account');

                return redirect()
                    ->back()
                    ->withErrors('Er is een fout opgetreden tijdens het opslaan van het sub account');
            }
        } else {
            \Log::warning('Error occurred while validating input for sub account creation');

            return redirect()
                ->back()
                ->withErrors($validator->errors());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'id' => 'required',
            'username' => 'required',
            'password' => 'required|confirmed',
            'email' => 'required|email'
        ]);

        if ($validator->passes()) {
            \Log::warning('Failed to update sub account. Errors: ' . json_encode($validator->errors()));

            return redirect()
                ->back()
                ->withInput($request->except('password'))
                ->withErrors($validator->errors());
        } else {
            \Log::warning('Failed to update sub account. Errors: ' . json_encode($validator->errors()));

            return redirect()
                ->back()
                ->withInput($request->except('password'))
                ->withErrors($validator->errors());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
