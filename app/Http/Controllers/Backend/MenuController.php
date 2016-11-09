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

    public function getMenus()
    {
        $menus  = Menu::all();

        return view('admin.menu.index', compact('menus'));
    }

    public function getSubMenus($menu)
    {
        $subMenus = SubMenu::where('parent', $menu)->get();

        return view('admin.sub_menu.index', compact('subMenus'));
    }

    public function getMenuDetail($id)
    {
        $menu = Menu::find($id);

        return view('admin.menu.detail', compact('menu'));
    }

    public function getSubMenuDetail($id)
    {
        $subMenu = SubMenu::find($id);

        return view('admin.sub_menu.detail', compact('subMenu'));
    }

    public function createMenuView()
    {
        return view('admin.menu.create');
    }

    public function createSubMenuView($menu)
    {
        return view('admin.sub_menu.create', ['menu' => $menu]);
    }

    public function createMenu(Request $request)
    {
        $name = $request->input('name');

        $icon_sidebar =  ($request->file('icon_sidebar') && $request->file('icon_sidebar')->isValid()) ? $this->saveImage($request->file('icon_sidebar')) : '';
        $icon_sidebar_hover =  ($request->file('icon_sidebar_hover') && $request->file('icon_sidebar_hover')->isValid()) ? $this->saveImage($request->file('icon_sidebar_hover')) : '';
        $main =  ($request->file('main') && $request->file('main')->isValid()) ? $this->saveImage($request->file('main')) : '';
        $thumb =  ($request->file('thumbnail') && $request->file('thumbnail')->isValid()) ? $this->saveImage($request->file('thumbnail')) : '';

        $maxCurrentOrder = DB::table('menus')->count();

        $newOrder = $maxCurrentOrder + 1;

        if (!File::exists('Menu/'.$newOrder))
        {
            File::makeDirectory(public_path('Menu/'.$newOrder), 0777);
        }

        try {
            Menu::create([
                'name' => $name,
                'icon_sidebar' => $icon_sidebar,
                'icon_sidebar_hover' => $icon_sidebar_hover,
                'main' => $main,
                'thumbnail' => $thumb,
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
            return redirect()->back()->with('error', 'Thêm menu thất bại: '.$ex->getMessage());
        }

        return redirect()->back()->with('success', 'Thêm menu thành công');


    }

    public function createSubMenu(Request $request)
    {
        $menu = $request->input('menu');

        if(empty($menu))
        {
            return view('admin.sub_menu.index')->with('error', 'Thêm menu con thất bại');
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
                'order' => $maxCurrentOrder + 1,
                'parent' => $menu
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
            return redirect()->back()->with('error', 'Thêm menu con thất bại');
        }

        return redirect()->back()->with('success', 'Thêm menu con thành công');
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
            return view('admin.content.index')->with('error', 'Thêm nội dung thất bại');
        }

        if(empty($menuType))
        {
            return view('admin.content.index')->with('error', 'Thêm nội dung thất bại');
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
            return view('admin.content.index')->with('error', 'Thêm nội dung thất bại');
        }

        return view('admin.content.index')->with('success', 'Thêm nội dung thành công');
    }

    public function updateMenu($id, Request $request)
    {
        $menu = Menu::find($id);

        $data['name'] = $request->input('name');

        if($request->file('icon_sidebar') && $request->file('icon_sidebar')->isValid()) {
            $data['icon_sidebar'] =  $this->saveImage($request->file('icon_sidebar'));
        };
        if($request->file('icon_sidebar_hover') && $request->file('icon_sidebar_hover')->isValid()) {
            $data['icon_sidebar_hover'] =  $this->saveImage($request->file('icon_sidebar_hover'));
        };
        if($request->file('main') && $request->file('main')->isValid()) {
            $data['main'] =  $this->saveImage($request->file('main'));
        };
        if($request->file('thumb') && $request->file('thumb')->isValid()) {
            $data['thumb'] =  $this->saveImage($request->file('thumb'));
        };


        $order = $menu->order;

        if (!File::exists(public_path('Menu/'.$order)))
        {
            File::makeDirectory(public_path('Menu/'.$order));
        }

        $childMenus = Menu::where('parent', $id)->where('menu_type', 'menu')->get();

        $childNumber = $childMenus->count();

        $childNames = $childMenus->pluck('name')->all();

        try {
            $updatedMenu = $menu->update($data);

            $content = [
                'type' => 'menu',
                'name' => $data['name'],
                'childNumber' => $childNumber,
                'childNames' => $childNames

            ];

            $icon_sidebar = $updatedMenu->icon_sidebar;
            $icon_sidebar_hover = $updatedMenu->icon_sidebar_hover;
            $main = $updatedMenu->main;
            $thumb = $updatedMenu->thumb;

            File::put(public_path('Menu/'.$order.'/description.json'), json_encode($content), true);


            File::copy(public_path('files/'.$icon_sidebar), public_path('Menu/'.$order.'/icon_sidebar.png'));
            File::copy(public_path('files/'.$icon_sidebar_hover), public_path('Menu/'.$order.'/icon_sidebar_hover.png'));
            File::copy(public_path('files/'.$main), public_path('Menu/'.$order.'/main.png'));
            File::copy(public_path('files/'.$thumb), public_path('Menu/'.$order.'/thumb.png'));

        } catch(\Exception $ex)
        {
            return view('admin.menu.index')->with('error', 'Cập nhật menu thất bại');
        }

        return view('admin.menu.index')->with('success', 'Cập nhật menu thành công');

    }

    public function updateSubMenu($id, Request $request)
    {
        $menu = $request->input('menu');

        if(empty($menu))
        {
            return view('admin.sub_menu.index')->with('error', 'Thêm menu con thất bại');
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
            return view('admin.menu.index')->with('error', 'Thêm menu con thất bại');
        }

        return view('admin.sub_menu.index')->with('success', 'Thêm menu con thành công');

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
