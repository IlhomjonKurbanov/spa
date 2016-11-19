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

    public function getContentDetail($id)
    {
        $content = Content::find($id);

        return view('admin.content.detail', compact('content'));
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

        DB::beginTransaction();

        try {
            Menu::create([
                'name' => $name,
                'icon_sidebar' => $icon_sidebar,
                'icon_sidebar_hover' => $icon_sidebar_hover,
                'main' => $main,
                'thumbnail' => $thumb,
                'order' => $maxCurrentOrder + 1,
                'path' => $maxCurrentOrder + 1,
                'rank' => 1
            ]);

            $content = [
                'type' => 'Menu',
                'name' => $name,
                'childNumber' => 0,
                'childNames' => ''

            ];

            $menuContent = [
                'type' => 'Menu',
                'name' => 'Menu',
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
                File::copy(public_path('files/'.$thumb), public_path('Menu/'.$newOrder.'/thumbnail.png'));
            }

        } catch(\Exception $ex)
        {
            DB::rollBack();

            return redirect()->back()->with('error', 'Thêm menu thất bại: '.$ex->getMessage());
        }

        DB::commit();

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

            $path = $menu;
            $rank = 1;

        } else {

            $subMenu = SubMenu::find($menu);

            $path = $subMenu->path;

            $rank = $subMenu->rank;

            if (!File::exists('Menu/' . $path . '/' . $newOrder)) {
                File::makeDirectory('Menu/' . $path . '/' . $newOrder);
            }
        }

        DB::beginTransaction();

        try {
            SubMenu::create([
                'name' => $name,
                'icon' => $icon,
                'icon_hover' => $icon_hover,
                'main' => $main,
                'order' => $newOrder,
                'parent' => $menu,
                'parent_type' => $menuType,
                'path' => $path .'/'.$newOrder,
                'rank' => $rank + 1
            ]);

            $content = [
                'type' => 'sub-menu',
                'name' => $name,
                'childNumber' => 0,
                'childNames' => ''

            ];

            $subMenuNames = SubMenu::where('parent', $menu)->where('parent_type', $menuType)->pluck('name', 'order')->toArray();

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
                File::put(public_path('Menu/' . $path . '/Description.txt'), json_encode($menuContent), true);

                File::put(public_path('Menu/' . $path . '/' . $newOrder . '/Description.txt'), json_encode($content), true);

                if (!empty($icon)) {
                    File::copy(public_path('files/' . $icon), public_path('Menu/' . $path . '/' . $newOrder . '/icon.png'));
                }

                if (!empty($icon_hover)) {
                    File::copy(public_path('files/' . $icon_hover), public_path('Menu/' . $path . '/' . $newOrder . '/icon-hover.png'));
                }

                if (!empty($main)) {
                    File::copy(public_path('files/' . $main), public_path('Menu/' . $path . '/' . $newOrder . '/main.png'));
                }
            }

        } catch(\Exception $ex)
        {
            DB::rollBack();

            return redirect()->back()->with('error', $ex->getMessage(). $ex->getLine());
        }

        DB::commit();

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
            if(empty($content))
            {
                $detailType = 3;
            } else {
                $detailType = 4;
            }

            foreach ($images as $image)
            {
                $imageLink[] = $this->saveImage($image);
            }
        }

        if($menuType == 1)
        {
            $path = Menu::find($menu)->path;
            $rank = 1;

        } else {
            $subMenu = SubMenu::find($menu);

            $path = $subMenu->path;

            $rank = $subMenu->rank;
        }


        try {
            $subMenuCount = DB::table('sub_menus')->where('parent', $menu)->where('parent_type', $menuType)->count();

            $contentCount = DB::table('contents')->where('menu', $menu)->where('menu_type', $menuType)->count();

            $order = $subMenuCount + $contentCount + 1;

            DB::beginTransaction();


            if (!File::exists('Menu/' . $path . '/' . $order)) {
                File::makeDirectory('Menu/' . $path . '/' . $order);
            }


            $localImageLink = [];

            if(!empty($imageLink)) {
                $i = 0;
                foreach($imageLink as $itemImage) {
                    $i++;
                    File::copy(public_path('files/' . $itemImage), public_path('Menu/' . $path  . '/'.$i.'.png'));

                    $localImageLink[] = $i.'.png';
                }
            }


            Content::create([
                'menu' => $menu,
                'menu_type' => $menuType,
                'icon' => $icon,
                'image' => json_encode($imageLink),
                'main' => $main,
                'content' => $content,
                'path' => $path .'/'. $order,
                'rank' => $rank + 1
            ]);

            $content = [
                'images' =>json_encode($localImageLink),
                'title' => $title,
                'content' => $content,
            ];

            $description = ['type'=>'detail', 'layoutType'=>$detailType];

            File::put(public_path('Menu/' . $path . '/'.$order.'/Description.txt'), json_encode($description), true);

            File::put(public_path('Menu/' . $path . '/'.$order.'/Content.txt'), json_encode($content), true);

            if (!empty($icon)) {
                File::copy(public_path('files/' . $icon), public_path('Menu/' . $path . '/' . $order . '/icon.png'));
            }

            if (!empty($main)) {
                File::copy(public_path('files/' . $main), public_path('Menu/' . $path . '/' . $order . '/main.png'));
            }



        } catch(\Exception $ex)
        {
            DB::rollBack();
            return redirect()->back()->with('error', $ex->getMessage().$ex->getLine());
        }

        DB::commit();

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
        if($request->file('thumbnail') && $request->file('thumbnail')->isValid()) {
            $data['thumbnail'] =  $this->saveImage($request->file('thumbnail'));
        };


        $order = $menu->order;

        if (!File::exists(public_path('Menu/'.$order)))
        {
            File::makeDirectory(public_path('Menu/'.$order));
        }

        $childMenus = SubMenu::where('parent', $id)->get();

        $childNumber = $childMenus->count();

        $childNames = $childMenus->pluck('name')->all();

        DB::beginTransaction();

        try {
            $menu->update($data);

            $content = [
                'type' => 'menu',
                'name' => $data['name'],
                'childNumber' => $childNumber,
                'childNames' => $childNames

            ];

            $menuContent = [
                'type' => 'menu',
                'name' => '',
                'childNumber' => Menu::all()->count(),
                'childNames' => Menu::all()->pluck('name', 'order')->toArray()
            ];

            $updatedMenu = Menu::find($id);

            $newOrder = $updatedMenu->order;

            $icon_sidebar = $updatedMenu->icon_sidebar;
            $icon_sidebar_hover = $updatedMenu->icon_sidebar_hover;
            $main = $updatedMenu->main;
            $thumb = $updatedMenu->thumbnail;

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
                File::copy(public_path('files/'.$thumb), public_path('Menu/'.$newOrder.'/thumbnail.png'));
            }

        } catch(\Exception $ex)
        {
            DB::rollBack();

            return redirect()->back()->with('error', $ex->getMessage().$ex->getLine());
        }

        DB::commit();

        return redirect()->back()->with('success', 'Cập nhật menu thành công');

    }

    public function updateSubMenu( Request $request)
    {
        $menu = $request->input('id');

        $data = $request->all();

        if(empty($menu))
        {
            return view('admin.sub_menu.index')->with('error', 'Thêm menu con thất bại');
        }

        $name = $request->input('name');

        if($request->file('icon') && $request->file('icon')->isValid()) {
            $data['icon'] =  $this->saveImage($request->file('icon'));
        };

        if($request->file('icon_hover') && $request->file('icon_hover')->isValid()) {
            $data['icon_hover'] =  $this->saveImage($request->file('icon_hover'));
        };

        if($request->file('main') && $request->file('main')->isValid()) {
            $data['main'] =  $this->saveImage($request->file('main'));
        };


        $subMenu = SubMenu::find($menu);

        $newOrder = $subMenu->order;


        DB::beginTransaction();

        try {
            $subMenu->update($data);

            $content = [
                'type' => 'sub-menu',
                'name' => $name,
                'childNumber' => $newOrder,
                'childNames' => ''

            ];


            $parentMenu = SubMenu::where('id', $menu)->first()->parent;

            $subMenuNames = SubMenu::where('parent', $parentMenu)->pluck('name')->all();

            $menuContent = [
                'type' => 'menu',
                'name' => Menu::find($parentMenu)->name,
                'childNumber' => $newOrder,
                'childNames' => $subMenuNames
            ];

            if (!File::exists('Menu/'.$parentMenu.'/'.$newOrder))
            {
                File::makeDirectory('Menu/'.$parentMenu.'/'.$newOrder);
            }

            File::put(public_path('Menu/'.$parentMenu.'/Description.txt'), json_encode($menuContent), true);

            File::put(public_path('Menu/'.$parentMenu.'/'.$newOrder.'/Description.txt'), json_encode($content), true);

            if(!empty($icon))
            {
            File::copy(public_path('files/'.$icon), public_path('Menu/'.$parentMenu.'/'.$newOrder.'/icon.png'));
            }

            if(!empty($icon_hover))
            {
                File::copy(public_path('files/'.$icon_hover), public_path('Menu/'.$parentMenu.'/'.$newOrder.'/icon-hover.png'));
            }

            if(!empty($main))
            {
                File::copy(public_path('files/'.$main), public_path('Menu/'.$parentMenu.'/'.$newOrder.'/main.png'));
            }

        } catch(\Exception $ex)
        {

            DB::rollBack();

            return redirect()->back()->with('error', $ex->getMessage().$ex->getLine());
        }

        DB::commit();

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

    public function updateContent(Request $request)
    {
        $data = $request->all();

        $id = $request->input('id');


        if(empty($id))
        {
            return redirect()->back()->with('error', 'Cập nhật nội dung thất bại');
        }

        if($request->file('icon') && $request->file('icon')->isValid()) {
            $data['icon'] =  $this->saveImage($request->file('icon'));
        };


        if($request->file('main') && $request->file('main')->isValid()) {
            $data['main'] =  $this->saveImage($request->file('main'));
        };

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

            $data['images'] = json_encode($imageLink);


        } else if($countImages > 1)
        {
            $dataContent = $data['content'];

            if(empty($dataContent)) {

                $detailType = 3;

            } else {

                $detailType = 4;
            }

            foreach ($images as $image) {

                $imageLink[] = $this->saveImage($image);
            }

            $data['images'] = json_encode($imageLink);

        }

        $content = Content::find($id);

        $path = $content->path;

        DB::beginTransaction();

        try {



            $content->update($data);

            $content = [
                'images' =>json_encode($localImageLink),
                'title' => $data['title'],
                'content' => $data['content'],
            ];

            $description = ['type'=>'detail', 'layoutType'=>$detailType];

            File::put(public_path('Menu/' . $path .'/Description.txt'), json_encode($description), true);

            File::put(public_path('Menu/' . $path . '/Content.txt'), json_encode($content), true);

            if (!empty($icon)) {
                File::copy(public_path('files/' . $icon), public_path('Menu/' . $path . '/icon.png'));
            }

            if (!empty($main)) {
                File::copy(public_path('files/' . $main), public_path('Menu/' . $path . '/main.png'));
            }


        } catch(\Exception $ex)
        {
            DB::rollBack();

            return redirect()->back()->with('error', $ex->getMessage().$ex->getLine());
        }

        DB::commit();

        return redirect()->back()->with('success', 'Cập nhật nội dung thành công');
    }

    public function deleteMenu($id)
    {
        $menu = Menu::find($id);

        $path = $menu->order;

        if(File::exists('Menu/'. $path)) {
            File::deleteDirectory('Menu/' . $path);
        }

        $menusBiggers = Menu::where('order' , '>', $path)->where('rank', $menu->rank)->get();

        foreach ($menusBiggers as $menusBigger)
        {
            $orderBigger = $menusBigger->order;

            File::copyDirectory('Menu/'.$orderBigger, 'Menu/'.$path);

            File::deleteDirectory('Menu/' . $orderBigger);

            $menusBigger->update(['order' => $orderBigger - 1]);

            $this->updateByRank($menusBigger->id, $orderBigger - 1, 1);

        }

        $menu->delete();

        SubMenu::where('parent', $id)->where('parent_type', 1)->delete();

        Content::where('menu', $id)->where('menu_type', 1)->delete();

        return redirect()->back()->with('success', 'Xóa menu thành công');

    }

    public function deleteSubMenu($id)
    {

        $menu = SubMenu::find($id);

        $path = $menu->path;

        if(File::exists('Menu/'. $path)) {
            File::deleteDirectory('Menu/' . $path);
        }

        $menusBiggers = SubMenu::where('order' , '>', $menu->order)->where('rank', $menu->rank)->get();



        foreach ($menusBiggers as $menusBigger)
        {
            $orderBigger = $menusBigger->order;

            File::copyDirectory('Menu/'.$menusBigger->path, 'Menu/'.$path);

            File::deleteDirectory('Menu/' . $menusBigger->path);

            $currentPath = explode('/', $menusBigger->path);

            $currentPath[$menusBigger->rank - 1] = $orderBigger - 1;

            $newPath = implode('/', $currentPath);

            $menusBigger->update(['path' => $newPath, 'order' => $orderBigger - 1]);


            $this->updateByRank($menusBigger->id, $orderBigger - 1, $menusBigger->rank);

        }

        $menu->delete();

        SubMenu::where('parent', $id)->where('parent_type', 2)->delete();

        Content::where('menu', $id)->where('menu_type', 2)->delete();

        return redirect()->back()->with('success', 'Xóa menu thành công');
    }

    public function deleteContent($id)
    {

        $content = Content::find($id);

        $path = $content->path;

        $menu = $content->menu;

        $rank = $content->rank;

        $menusBiggers = SubMenu::where('parent', $menu)->where('order', '>', $menu->order)->where('rank', $rank)->get();

        foreach ($menusBiggers as $menusBigger)
        {
            $orderBigger = $menusBigger->order;

            File::copyDirectory('Menu/'.$menusBigger->path, 'Menu/'.$path);

            File::deleteDirectory('Menu/' . $menusBigger->path);

            $currentPath = explode('/', $menusBigger->path);

            $currentPath[$menusBigger->rank - 1] = $orderBigger - 1;

            $newPath = implode('/', $currentPath);

            $menusBigger->update(['path' => $newPath, 'order' => $orderBigger - 1]);


            $this->updateByRank($menusBigger->id, $orderBigger - 1, $menusBigger->rank);

        }

        $content->delete();

        if(File::exists('Menu/'. $path)) {
            File::deleteDirectory('Menu/' . $path);
        }

        return redirect()->back()->with('success', 'Xóa nội dung thành công');
    }

    public function updateByRank($menu, $order, $rank)
    {
        if($rank == 1) {
            $subMenus = SubMenu::where('parent', $menu)->where('parent_type', 1)->get();
        } else {
            $subMenus = SubMenu::where('parent', $menu)->where('parent_type', 2)->get();
        }

        if($subMenus->count() == 0)
        {
            return;
        }

        //dd($subMenus->count());

        foreach ($subMenus as $subMenu)
        {
            $currentPath = explode('/', $subMenu->path);

            $currentPath[$rank - 1] = $order;

           // File::append(public_path('Menu/Rank.txt'), $rank.', ', true);

            $newPath = implode('/', $currentPath);

            $subMenu->update(['path' => $newPath]);


            $this->updateByRank($subMenu->id, $order, $rank);
        }
    }


}
