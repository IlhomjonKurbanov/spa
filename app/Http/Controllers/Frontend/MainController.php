<?php

namespace App\Http\Controllers\Frontend;

use App\Garena\Functions;
use App\Http\Controllers\Controller;
use App\Template;
use App\Topic;

class MainController extends Controller
{

    /**
     * FrontendController constructor.
     */
    public function __construct()
    {
        //hard login
        //Functions::hardLogin();
        $this->middleware('auth.frontend', ['except' => ['index']]);
    }

    public function index()
    {
        return view('welcome');
    }

    public function home()
    {
        echo "welcome, " . auth('frontend')->user()->username;
    }

    public function getAllTemplates()
    {
        try {
            $data = Template::all();
        } catch (\Exception $ex) {
            return response([
                'message' => 'error',
                'status' => 0,
                'data' => ''
            ]);
        }
        return response([
            'message' => 'success',
            'status' => 1,
            'data' => $data
        ]);
    }

    public function getTemplates($id)
    {
        try {
            $data = Template::find($id);
        } catch (\Exception $ex) {
            return response([
                'message' => 'error',
                'status' => 0,
                'data' => ''
            ]);
        }
        return response([
            'message' => 'success',
            'status' => 1,
            'data' => $data
        ]);
    }

    public function getScreen($id)
    {
        try {
            $data = Topic::find($id);
        } catch (\Exception $ex) {
            return response([
                'message' => 'error',
                'status' => 0,
                'data' => ''
            ]);
        }
        return response([
            'message' => 'success',
            'status' => 1,
            'data' => $data
        ]);
    }

    public function getScreenDescendant($id)
    {
        try {
            $data = Topic::where('parent_id', $id);
        } catch (\Exception $ex) {
            return response([
                'message' => 'error',
                'status' => 0,
                'data' => ''
            ]);
        }
        return response([
            'message' => 'success',
            'status' => 1,
            'data' => $data
        ]);
    }

    public function getAllScreens()
    {
        $data = Topic::all();
        return response([
            'message' => 'success',
            'status' => 1,
            'data' => $data
        ]);
    }
}
