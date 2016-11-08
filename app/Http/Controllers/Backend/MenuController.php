<?php

namespace App\Http\Controllers\Backend;

use App\Menu;
use App\SubMenu;
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

        try {
            Menu::create([
                'name' => $name,
                'icon_sidebar' => $icon_sidebar,
                'icon_sidebar_hover' => $icon_sidebar_hover,
                'main' => $main,
                'thumb' => $thumb,
                'order' => $maxCurrentOrder + 1
            ]);
        } catch(\Exception $ex)
        {
            return view('backend.menu.index')->with('error', 'Thêm menu thất bại');
        }

        return view('backend.menu.index')->with('success', 'Thêm menu thành công');


    }

    public function createSubMenu(Request $request)
    {
        $menu = $request->input('menu');

        if(empty($menu))
        {
            return view('backend.sub_menu.index')->with('error', 'Thêm menu con thất bại');
        }

        $name = $request->input('name');

        $icon =  ($request->file('icon') && $request->file('icon')->isValid()) ? $this->saveImage($request->file('icon')) : '';
        $icon_hover =  ($request->file('icon_hover') && $request->file('icon_hover')->isValid()) ? $this->saveImage($request->file('icon_hover')) : '';
        $main =  ($request->file('main') && $request->file('main')->isValid()) ? $this->saveImage($request->file('main')) : '';


        if (!File::exists('Menu/'.$menu))
        {
            File::makeDirectory('Menu/'.$menu);
        }

        $maxCurrentOrder = DB::table('sub_menus')->max('order');

        try {
            SubMenu::create([
                'name' => $name,
                'icon' => $icon,
                'icon_hover' => $icon_hover,
                'main' => $main,
                'order' => $maxCurrentOrder + 1
            ]);
        } catch(\Exception $ex)
        {
            return view('backend.menu.index')->with('error', 'Thêm menu con thất bại');
        }

        return view('backend.sub_menu.index')->with('success', 'Thêm menu con thành công');
    }

    public function createContent(Request $request)
    {

    }

    public function updateMenu($id)
    {

    }

    public function updateSubMenu($id)
    {

    }

    public function updateContent($id)
    {

    }

    public function deleteMenu($id)
    {

    }

    public function deleteSubMenu($id)
    {

    }

    public function deleteContent($id)
    {

    }

}
