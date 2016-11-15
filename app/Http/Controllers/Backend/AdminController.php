<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Intervention\Image\Facades\Image;
use Illuminate\Http\Request;
use Chumper\Zipper\Zipper;
use File;

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
        $filename = md5(time()) . str_slug($file->getClientOriginalName()).'.'.$file->getClientOriginalExtension();
        Image::make($file->getRealPath())->save(public_path('files/'. $filename));

        if ($old) {
            @unlink(public_path('files/' .$old));
        }
        return $filename;
    }

    public function checkCurrentVersion()
    {
        $status = cache()->get('app-version');

        return response([
           'status' => 1,
            'data' => $status
        ]);
    }

    public function makeZip()
    {
        if(!cache()->has('version'))
        {
            cache()->forever('app-version', '1');
        } else {
            $oldVersion = cache()->get('app-version');

            cache()->forever('app-version', $oldVersion + 1);
        }

        $files = glob(public_path('Menu/*'));
        $zipper = new Zipper();
        $zipper->make(public_path('Menu.zip'))->add($files);

        return response([
            'status' => 1,
            'message' => 'Zip thành công'
        ]);
    }

    public function zipAndReturnFile()
    {
        if(File::exists(public_path('Menu.zip')))
        {
            return response()->download(public_path('Menu.zip'));
        }

    }
}
