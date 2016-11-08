<?php

namespace App\Http\Controllers\Backend;

use App\Menu;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use File;
use DB;

class MenuController extends AdminController
{
    //
    public function index()
    {
        File::makeDirectory('Menu');
    }

    public function createMenu(Request $request)
    {
        $name = $request->input('name');

        $icon_sidebar =  ($request->file('icon_sidebar') && $request->file('icon_sidebar')->isValid()) ? $this->saveImage($request->file('icon_sidebar')) : '';
        $icon_sidebar_hover =  ($request->file('icon_sidebar_hover') && $request->file('icon_sidebar_hover')->isValid()) ? $this->saveImage($request->file('icon_sidebar_hover')) : '';
        $main =  ($request->file('main') && $request->file('main')->isValid()) ? $this->saveImage($request->file('main')) : '';
        $thumb =  ($request->file('thumb') && $request->file('thumb')->isValid()) ? $this->saveImage($request->file('thumb')) : '';

        if (!File::exists('Menu'))
        {
            File::makeDirectory('Menu');
        }

        $maxCurrentOrder = DB::table('menus')->max('order');

        Menu::create([
            'name' => $name,
            'icon_sidebar' => $icon_sidebar,
            'icon_sidebar_hover' => $icon_sidebar_hover,
            'main' => $main,
            'thumb' => $thumb
        ]);


    }

    public function createSubMenu(Request $request)
    {

    }

    public function createContent(Request $request)
    {

    }

}
