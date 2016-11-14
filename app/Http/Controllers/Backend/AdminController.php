<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Intervention\Image\Facades\Image;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
       // $this->middleware('auth.backend');
    }

    /**
     * Save images
     * @param $file
     * @param null $old
     * @return string
     */
    public function saveImage($file, $old = null)
    {
        $filename = md5(time()) . '.' . $file->getClientOriginalExtension();
        Image::make($file->getRealPath())->save(public_path('files/'. $filename));

        if ($old) {
            @unlink(public_path('files/' .$old));
        }
        return $filename;
    }

    public function checkCurrentStatus()
    {
        $status = cache()->get('app-status');

        return response([
           'status' => 1,
            'data' => $status
        ]);
    }

    public function updateStatus(Request $request)
    {
        $status = $request->input('status');

        cache()->forget('app-status');

        cache()->forever('app-status', $status);
    }
}
