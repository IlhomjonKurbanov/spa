<?php

namespace App\Http\Controllers\Backend;

use App\Content;
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

        $maxCurrentOrder = DB::table('menus')->count();

        $newOrder = $maxCurrentOrder + 1;

        if (!File::exists(public_path('Menu/'.$newOrder)))
        {
            File::makeDirectory(public_path('Menu/'.$newOrder));
        }

        try {
            Menu::create([
                'name' => $name,
                'icon_sidebar' => $icon_sidebar,
                'icon_sidebar_hover' => $icon_sidebar_hover,
                'main' => $main,
                'thumb' => $thumb,
                'order' => $maxCurrentOrder + 1
            ]);

            $content = [
                'type' => 'menu',
                'name' => $name,
                'childNumber' => 1,
                'childNames' => ''

            ];

            File::put(public_path('Menu/'.$newOrder.'/description.json'), json_encode($content), true);


            File::copy(public_path('files/'.$icon_sidebar), public_path('Menu/'.$newOrder.'/icon_sidebar.png'));
            File::copy(public_path('files/'.$icon_sidebar_hover), public_path('Menu/'.$newOrder.'/icon_sidebar_hover.png'));
            File::copy(public_path('files/'.$main), public_path('Menu/'.$newOrder.'/main.png'));
            File::copy(public_path('files/'.$thumb), public_path('Menu/'.$newOrder.'/thumb.png'));

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


        $maxCurrentOrder = DB::table('sub_menus')->count();

        $newOrder = $maxCurrentOrder + 1;

        if (!File::exists('Menu/'.$menu.'/'.$newOrder))
        {
            File::makeDirectory('Menu/'.$menu.'/'.$newOrder);
        }

        try {
            SubMenu::create([
                'name' => $name,
                'icon' => $icon,
                'icon_hover' => $icon_hover,
                'main' => $main,
                'order' => $maxCurrentOrder + 1
            ]);

            $content = [
                'type' => 'sub-menu',
                'name' => $name,
                'childNumber' => $newOrder,
                'childNames' => ''

            ];

            $subMenuNames = SubMenu::where('parent', $menu)->pluck('name')->all();

            $menuContent = [
                'type' => 'menu',
                'name' => Menu::find($menu)->name,
                'childNumber' => $newOrder,
                'childNames' => $subMenuNames
            ];

            File::put(public_path('Menu/'.$menu.'/description.json'), json_encode($menuContent), true);

            File::put(public_path('Menu/'.$menu.'/'.$newOrder.'/description.json'), json_encode($content), true);
            File::copy(public_path('files/'.$icon), public_path('Menu/'.$menu.'/'.$newOrder.'/icon.png'));
            File::copy(public_path('files/'.$icon_hover), public_path('Menu/'.$menu.'/'.$newOrder.'/icon_hover.png'));
            File::copy(public_path('files/'.$main), public_path('Menu/'.$menu.'/'.$newOrder.'/main.png'));

        } catch(\Exception $ex)
        {
            return view('backend.menu.index')->with('error', 'Thêm menu con thất bại');
        }

        return view('backend.sub_menu.index')->with('success', 'Thêm menu con thành công');
    }

    public function createContent(Request $request)
    {
        $menu = $request->input('menu');
        $menuType = $request->input('menu_type');

        $content = $request->input('content');


        $icon =  ($request->file('icon') && $request->file('icon')->isValid()) ? $this->saveImage($request->file('icon')) : '';
        $image =  ($request->file('icon_hover') && $request->file('image')->isValid()) ? $this->saveImage($request->file('image')) : '';
        $main =  ($request->file('main') && $request->file('main')->isValid()) ? $this->saveImage($request->file('main')) : '';

        if(empty($menu))
        {
            return view('backend.content.index')->with('error', 'Thêm nội dung thất bại');
        }

        if(empty($menuType))
        {
            return view('backend.content.index')->with('error', 'Thêm nội dung thất bại');
        }

        try {
            Content::create([
                'menu' => $menu,
                'menu_type' => $menuType,
                'icon' => $icon,
                'image' => $image,
                'main' => $main,
                'content' => $content
            ]);
        } catch(\Exception $ex)
        {
            return view('backend.content.index')->with('error', 'Thêm nội dung thất bại');
        }

        return view('backend.content.index')->with('success', 'Thêm nội dung thành công');
    }

    public function updateMenu($id, Request $request)
    {
        $menu = Menu::find($id);

        $name = $request->input('name');

        $icon_sidebar =  ($request->file('icon_sidebar') && $request->file('icon_sidebar')->isValid()) ? $this->saveImage($request->file('icon_sidebar')) : '';
        $icon_sidebar_hover =  ($request->file('icon_sidebar_hover') && $request->file('icon_sidebar_hover')->isValid()) ? $this->saveImage($request->file('icon_sidebar_hover')) : '';
        $main =  ($request->file('main') && $request->file('main')->isValid()) ? $this->saveImage($request->file('main')) : '';
        $thumb =  ($request->file('thumb') && $request->file('thumb')->isValid()) ? $this->saveImage($request->file('thumb')) : '';
        $status = $request->input('status');

        if (!File::exists('Menu'))
        {
            File::makeDirectory('Menu');
        }

        try {
            $menu->update([
                'name' => $name,
                'icon_sidebar' => $icon_sidebar,
                'icon_sidebar_hover' => $icon_sidebar_hover,
                'main' => $main,
                'thumb' => $thumb,
                'status' => $status
            ]);
        } catch(\Exception $ex)
        {
            return view('backend.menu.index')->with('error', 'Thêm menu thất bại');
        }

        return view('backend.menu.index')->with('success', 'Thêm menu thành công');


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
