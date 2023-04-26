<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Profile; // for use of model class Profile
use App\Models\Prof_history;
use Carbon\Carbon;

class ProfileController extends Controller
{
    //
        public function add()
    {
        return view('admin.profile.create');
    }

    public function create(Request $request)
    {
        // dd($request);
        // Validation
        $this->validate($request, Profile::$rules);
        
        $profile = new Profile;
        $form = $request->all();

        // delete unnecessary items
        unset($form['_token']);

        // store all data in news table
        $profile->fill($form);
        $profile->save();
        //dd($form);
        //\Debugbar::info($form);
        return redirect('admin/profile/create');
    }

    public function edit(Request $request)
    {
        $profile = Profile::find($request->id);
        //var_dump($profile);
        if (empty($profile)) {
            abort('404');
        }
        return view('admin.profile.edit', ['profile_form' => $profile]);
    }

    public function update(Request $request)
    {
        $this->validate($request, Profile::$rules);
        //var_dump($request->all());
        
        $profile = Profile::find($request->id);

        $profile_form = $request->all();

        unset($profile_form['_token']);
        
        $profile->fill($profile_form)->save();
        
        $prof_history = new Prof_history();
        $prof_history->profile_id = $profile->id;
        $prof_history->edited_at = Carbon::now();
        $prof_history->save();

        return view('admin.profile.edit', ['profile_form' => $profile]);
    }
}
