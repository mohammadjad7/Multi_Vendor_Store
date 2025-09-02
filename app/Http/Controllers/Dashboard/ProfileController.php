<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileRequest;
use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\Intl\Countries;
use Symfony\Component\Intl\Languages;

class ProfileController extends Controller
{
    //
    public function edit()
    {
        $user = Auth::user();
        return view("dashboard.profile.edit", [
            "user" => $user,
            "countries" => Countries::getNames(),
            "locales" => Languages::getNames(),
        ]);
    }
    public function update(ProfileRequest $request)
    {
        $request->validated();

        $user = $request->user();

        $user->profile->fill($request->all())->save();

        return redirect()->route("profile.edit")->with("success","profile update");

        // $profile = $user->profile;
        // if ($profile->user_id) {
        //     $profile->update($request->all());
        // } else {
        //     // $request->merge([
        //     //     "user_id" => $user->id
        //     // ]);
        //     // Profile::create($request->all());

        //     $user->profile()->create($request->all());
        // }
    }
}
