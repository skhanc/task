<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\URL;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function allUsers(Request $request)
    {
        $allUsers = User::all();
        $userdata =[];
        $url= URL::to('/');
        foreach ($allUsers as $user)
        {
            $users['id']= $user->id;
            $users['first_name']= $user->first_name;
            $users['last_name']= $user->last_name;
            $users['email']= $user->email;
            $users['location']= $user->location;
            //$users['image']= $url= URL::to('/').$user->image;
            $users['image']="data:image/png;base64,".base64_encode(file_get_contents(public_path($user->image)));
            array_push($userdata,$users);
        }

       return response()->json(['success'=>true,'users'=>$userdata]);
    }

    public function search(Request $request)
    {
        $query = $request['search'];
        $searches = User::where('first_name','like', '%' . $query . '%')->orWhere('last_name','like', '%' . $query . '%')->orWhere('email','like', '%' . $query . '%')->orWhere('location','like', '%' . $query . '%')->get();
        if($searches->count()>0)
        {
            $searchdata =[];
            foreach ($searches as $search)
            {
                $users['id']= $search->id;
                $users['first_name']= $search->first_name;
                $users['last_name']= $search->last_name;
                $users['email']= $search->email;
                $users['location']= $search->location;
                array_push($searchdata,$users);
            }
            return response()->json(['success'=>true,'search'=>$searchdata]);

        }else
            {
                return response()->json(['success'=>false,'search'=>'sorry no record found']);
            }

    }
}
