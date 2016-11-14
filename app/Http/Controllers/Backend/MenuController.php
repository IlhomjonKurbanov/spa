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

    public $resultMenuRecursive = '';

    public function getMenus()
    {
        $menus  = Menu::all();

        return view('admin.menu.index', compact('menus'));
    }

    public function getSubMenus($menu, $menuType)
    {
        $subMenus = SubMenu::where('parent', $menu)->where('parent_type', $menuType)->get();

        if($menuType == 1) {

            $parentMenu = Menu::find($menu);
        } else {
            $parentMenu = SubMenu::find($menu);
        }

        return view('admin.sub_menu.index', compact('subMenus', 'parentMenu'));
    }

    public function getContents($menu, $menuType)
    {
        $contents = Content::where('menu', $menu)->where('menu_type', $menuType)->get();

        if ($menuType == 1)
        {
            $parentMenu = Menu::find($menu);
        } else if ($menuType == 2)
        {
            $parentMenu = SubMenu::find($menu);
        }

        return view('admin.content.list', compact('contents', 'parentMenu', 'menuType'));
    }

    public function getMenuDetail($id)
    {
        $menu = Menu::find($id);

        return view('admin.menu.detail', compact('menu'));
    }

    public function getSubMenuDetail($id)
    {
        $menu = SubMenu::find($id);

        return view('admin.sub_menu.detail', compact('menu'));
    }

    public function createMenuView()
    {
        return view('admin.menu.create');
    }

    public function createSubMenuView($menu, $menuType)
    {
        return view('admin.sub_menu.create', ['menu' => $menu, 'menuType'=> $menuType]);
    }

    public function createContentView($menu, $menuType)
    {
        return view('admin.content.create', ['menu' => $menu, 'menuType' => $menuType]);
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
                'childNumber' => 0,
                'childNames' => ''

            ];

            $menuContent = [
                'type' => 'menu',
                'name' => '',
                'childNumber' => Menu::all()->count(),
                'childNames' => Menu::all()->pluck('name', 'order')->toArray()
            ];

            File::put(public_path('Menu/Description.txt'), json_encode($menuContent), true);

            File::put(public_path('Menu/'.$newOrder.'/Description.txt'), json_encode($content), true);

            if(!empty($icon_sidebar))
            {
                File::copy(public_path('files/'.$icon_sidebar), public_path('Menu/'.$newOrder.'/icon-sidebar.png'));
            }

            if(!empty($icon_sidebar_hover))
            {
                File::copy(public_path('files/'.$icon_sidebar_hover), public_path('Menu/'.$newOrder.'/icon-sidebar-hover.png'));
            }

            if(!empty($main))
            {
                File::copy(public_path('files/'.$main), public_path('Menu/'.$newOrder.'/main.png'));
            }

            if(!empty($thumb))
            {
                File::copy(public_path('files/'.$thumb), public_path('Menu/'.$newOrder.'/thumb.png'));
            }

        } catch(\Exception $ex)
        {
            return redirect()->back()->with('error', 'Thêm menu thất bại: '.$ex->getMessage());
        }

        return redirect()->back()->with('success', 'Thêm menu thành công');


    }

    public function createSubMenu(Request $request)
    {
        $menu = $request->input('menu');
        $menuType = $request->input('menuType');


        if(empty($menu))
        {
            return view('admin.sub_menu.index')->with('error', 'Thêm menu con thất bại');
        }

        $name = $request->input('name');

        $icon =  ($request->file('icon') && $request->file('icon')->isValid()) ? $this->saveImage($request->file('icon')) : '';
        $icon_hover =  ($request->file('icon_hover') && $request->file('icon_hover')->isValid()) ? $this->saveImage($request->file('icon_hover')) : '';
        $main =  ($request->file('main') && $request->file('main')->isValid()) ? $this->saveImage($request->file('main')) : '';


        $subMenuCount = DB::table('sub_menus')->where('parent', $menu)->where('parent_type', $menuType)->count();

        $contentCount = DB::table('contents')->where('menu', $menu)->where('menu_type', $menuType)->count();

        $newOrder = $subMenuCount + $contentCount + 1;


        if($menuType == 1) {
            if (!File::exists('Menu/' . $menu . '/' . $newOrder)) {
                File::makeDirectory('Menu/' . $menu . '/' . $newOrder);
            }
        } else {
            if (!File::exists('Menu/' . $this->getMenuRecursive($menu) . '/' . $newOrder)) {
                File::makeDirectory('Menu/' . $this->getMenuRecursive($menu) . '/' . $newOrder);
            }
        }

        try {
            SubMenu::create([
                'name' => $name,
                'icon' => $icon,
                'icon_hover' => $icon_hover,
                'main' => $main,
                'order' => $newOrder,
                'parent' => $menu,
                'parent_type' => $menuType
            ]);

            $content = [
                'type' => 'sub-menu',
                'name' => $name,
                'childNumber' => $subMenuCount + $contentCount,
                'childNames' => ''

            ];

            $subMenuNames = SubMenu::where('parent', $menu)->where('parent_type', $menuType)->pluck('order', 'name')->all();

            if($menuType == 1) {
            $menuContent = [
                'type' => 'menu',
                'name' => Menu::find($menu)->name,
                'childNumber' => $newOrder,
                'childNames' => $subMenuNames
            ]; } else {
                $menuContent = [
                    'type' => 'menu',
                    'name' => SubMenu::find($menu)->name,
                    'childNumber' => $newOrder,
                    'childNames' => $subMenuNames
                ];
            }



            if($menuType == 1) {

                File::put(public_path('Menu/' . $menu . '/Description.txt'), json_encode($menuContent), true);

                File::put(public_path('Menu/' . $menu . '/' . $newOrder . '/Description.txt'), json_encode($content), true);

                if (!empty($icon)) {
                    File::copy(public_path('files/' . $icon), public_path('Menu/' . $menu . '/' . $newOrder . '/icon.png'));
                }

                if (!empty($icon_hover)) {
                    File::copy(public_path('files/' . $icon_hover), public_path('Menu/' . $menu . '/' . $newOrder . '/icon-hover.png'));
                }

                if (!empty($main)) {
                    File::copy(public_path('files/' . $main), public_path('Menu/' . $menu . '/' . $newOrder . '/main.png'));
                }
            } else {
                File::put(public_path('Menu/' . $this->getMenuRecursive($menu) . '/Description.txt'), json_encode($menuContent), true);

                File::put(public_path('Menu/' . $this->getMenuRecursive($menu) . '/' . $newOrder . '/Description.txt'), json_encode($content), true);

                if (!empty($icon)) {
                    File::copy(public_path('files/' . $icon), public_path('Menu/' . $this->getMenuRecursive($menu) . '/' . $newOrder . '/icon.png'));
                }

                if (!empty($icon_hover)) {
                    File::copy(public_path('files/' . $icon_hover), public_path('Menu/' . $this->getMenuRecursive($menu) . '/' . $newOrder . '/icon-hover.png'));
                }

                if (!empty($main)) {
                    File::copy(public_path('files/' . $main), public_path('Menu/' . $this->getMenuRecursive($menu) . '/' . $newOrder . '/main.png'));
                }
            }

        } catch(\Exception $ex)
        {
            return redirect()->back()->with('error', $ex->getMessage(). $ex->getLine());
        }

        return redirect()->back()->with('success', 'Thêm menu con thành công');
    }

    public function createContent(Request $request)
    {
        $menu = $request->input('menu');
        $menuType = $request->input('menu_type');

        if(empty($menu))
        {
            return redirect()->back()->with('error', 'Thêm nội dung thất bại');
        }

        if(empty($menuType))
        {
            return redirect()->back()->with('error', 'Thêm nội dung thất bại');
        }

        $content = $request->input('content');
        $title = $request->input('title');
        $name = $request->input('name');


        $icon =  ($request->file('icon') && $request->file('icon')->isValid()) ? $this->saveImage($request->file('icon')) : '';
        $main =  ($request->file('main') && $request->file('main')->isValid()) ? $this->saveImage($request->file('main')) : '';

        $images = $request->file('images');

        $countImages = count($images);

        $imageLink = [];

        $detailType = 0;

        if($countImages == 0)
        {
            $detailType = 1;

        } else if($countImages == 1)
        {
            $detailType = 2;

            foreach ($images as $image)
            {
                $imageLink[] = $this->saveImage($image);
            }


        } else if($countImages > 1)
        {
            $detailType = 3;

            foreach ($images as $image)
            {
                $imageLink[] = $this->saveImage($image);
            }
        }

        try {
            Content::create([
                'menu' => $menu,
                'menu_type' => $menuType,
                'icon' => $icon,
                'image' => json_encode($imageLink),
                'main' => $main,
                'content' => $content
            ]);

            $content = [
                'layoutType' => $detailType,
                'name' => $name,
                'image' =>json_encode($imageLink),
                'main' => $main,
                'content' => $content,
                'icon' => $icon,
                'type' => 'detail'
            ];

            $description = ['title'=>$title, 'content'=>$content];

            $subMenuCount = DB::table('sub_menus')->where('parent', $menu)->where('parent_type', $menuType)->count();

            $contentCount = DB::table('contents')->where('menu', $menu)->where('menu_type', $menuType)->count();

            $order = $subMenuCount + $contentCount + 1;


            if($menuType == 1) {
                File::put(public_path('Menu/' . $menu . '/'.$order.'/Description.txt'), json_encode($description), true);
                File::put(public_path('Menu/' . $menu . '/'.$order.'/Content.txt'), json_encode($content), true);
            } else if ($menuType == 2)
            {
                File::put(public_path('Menu/' . $this->getMenuRecursive($menu) . '/Description.txt'), json_encode($content), true);
            }
          //  File::put(public_path('Menu/'.$menu.'/'.$newOrder.'/Description.txt'), json_encode($content), true);


        } catch(\Exception $ex)
        {
            return redirect()->back()->with('error', $ex->getMessage().$ex->getLine());
        }



        return redirect()->back()->with('success', 'Thêm nội dung thành công');
    }

    public function updateMenu(Request $request)
    {
        $id = $request->input('id');
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

        $childMenus = SubMenu::where('parent', $id)->get();

        $childNumber = $childMenus->count();

        $childNames = $childMenus->pluck('name')->all();

        try {
            $menu->update($data);

            $content = [
                'type' => 'menu',
                'name' => $data['name'],
                'childNumber' => $childNumber,
                'childNames' => $childNames

            ];

            $updatedMenu = Menu::find($id);

            $icon_sidebar = $updatedMenu->icon_sidebar;
            $icon_sidebar_hover = $updatedMenu->icon_sidebar_hover;
            $main = $updatedMenu->main;
            $thumb = $updatedMenu->thumbnail;

            File::put(public_path('Menu/'.$order.'/Description.txt'), json_encode($content), true);


            File::copy(public_path('files/'.$icon_sidebar), public_path('Menu/'.$order.'/icon_sidebar.png'));
            File::copy(public_path('files/'.$icon_sidebar_hover), public_path('Menu/'.$order.'/icon_sidebar_hover.png'));
            File::copy(public_path('files/'.$main), public_path('Menu/'.$order.'/main.png'));
            File::copy(public_path('files/'.$thumb), public_path('Menu/'.$order.'/thumb.png'));

        } catch(\Exception $ex)
        {
            return redirect()->back()->with('error', 'Cập nhật menu thất bại');
        }

        return redirect()->back()->with('success', 'Cập nhật menu thành công');

    }

    public function updateSubMenu(Request $request)
    {
        $menu = $request->input('id');



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

        $subMenu = SubMenu::find($menu);

        if (!File::exists('Menu/'.$menu.'/'.$newOrder))
        {
            File::makeDirectory('Menu/'.$menu.'/'.$newOrder);
        }

        try {
            $subMenu->update([
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

            File::put(public_path('Menu/'.$menu.'/Description.txt'), json_encode($menuContent), true);

            File::put(public_path('Menu/'.$menu.'/'.$newOrder.'/Description.txt'), json_encode($content), true);

            if(!empty($icon))
            {
            File::copy(public_path('files/'.$icon), public_path('Menu/'.$menu.'/'.$newOrder.'/icon.png'));
            }

            if(!empty($icon_hover))
            {
                File::copy(public_path('files/'.$icon_hover), public_path('Menu/'.$menu.'/'.$newOrder.'/icon-hover.png'));
            }

            if(!empty($main))
            {
                File::copy(public_path('files/'.$main), public_path('Menu/'.$menu.'/'.$newOrder.'/main.png'));
            }

        } catch(\Exception $ex)
        {
            return redirect()->back()->with('error', 'Cập nhật menu thất bại');
        }

        return redirect()->back()->with('success', 'Cập nhật menu thành công');

    }

    public function getMenuRecursive($menu)
    {
        $subMenu = SubMenu::where('id', $menu)->first();

        $this->resultMenuRecursive =  $subMenu->order;

        if($subMenu->parent_type == 2) {

            $parentOrder = SubMenu::where('id', $subMenu->parent)->first();
            $this->resultMenuRecursive = $parentOrder->order . '/' . $this->resultMenuRecursive;
            $this->getMenuRecursive($parentOrder->id);
        } else {
            $result = $this->resultMenuRecursive = $subMenu->parent . '/' . $this->resultMenuRecursive;
            $this->resultMenuRecursive = '';
            return $result;
        }
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

    public function createVideo()
    {

    }

}
