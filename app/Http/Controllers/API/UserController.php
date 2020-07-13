<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\ImageManagerStatic as Image;

class UserController extends Controller
{


    /**
     * UserController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth:api');
        //$this->authorize('isAdmin');
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return User::latest()->paginate(20);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' =>'required|string|max:191',
            'email'=> 'required|string|email|unique:users',
            'type'=> 'required',
            'password'=> 'required|min:5',
        ]);
       return User::create([
           'name' =>$request->name,
           'email'=> $request->email,
           'bio'=> $request->bio,
          // 'photo'=> $request->photo,
           'type'=> $request->type,
           'password'=> Hash::make($request->password),

       ]);
    }



/**
 * profile show data
 */
    public function profile()
    {
        return auth('api')->user();
    }



/**
 * Request update data to database
 */
    public function updateProfile(Request $request)
    {
        $user = auth('api')->user();


        $request->validate([
            'name' => 'required|string|max:191',
            'email' => 'required|string|email|max:191|unique:users,email,'.$user->id,
            'password' => 'sometimes|required|min:6'
        ]);


        $currentPhoto = $user->photo;


        if($request->photo != $currentPhoto){
            $name = time().'.' . explode('/', explode(':',
            substr($request->photo, 0, strpos($request->photo, ';')))[1])[1];

            \Image::make($request->photo)->save(public_path('img/profile/').$name);
            $request->merge(['photo' => $name]);

            $userPhoto = public_path('img/profile/').$currentPhoto;
            if(file_exists($userPhoto)){
                @unlink($userPhoto);
            }
        }
        if(!empty($request->password)){
            $request->merge(['password' => Hash::make($request['password'])]);
        }
        $user->update($request->all());
        return ['message' => "Success"];
    }



    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $request->validate([
            'name' =>'required|string|max:191',
            'email'=> 'required|string|email|max:191|unique:users,email,'.$user->id,
            'type'=> 'required',
            'password'=> 'sometimes|min:5',
        ]);
        $user->update($request->all());

        return ['message' => 'User Updated'];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->authorize('isAdmin');
        $user = User::findOrFail($id);
        // delete the user

        $user->delete();

        return ['message' => 'User Deleted'];
    }
}
