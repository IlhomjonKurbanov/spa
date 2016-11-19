<?php

namespace App\Http\Controllers\Backend;

use App\Intro;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class IntroController extends AdminController
{
    //

    public function getIntroDetail($id)
    {
        $intro = Intro::find($id);

        return view('admin.intro.detail', compact('intro'));
    }

    public function getIntros()
    {
        $intros = Intro::all();

        return view('admin.intro.list', compact('intros'));
    }


    public function createIntro(Request $request)
    {

        $content = $request->input('content');
        $title = $request->input('title');
        $name = $request->input('name');
        $detailType = $request->input('detail_type');


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

        \DB::beginTransaction();
        try {
            Intro::create([
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

            $order = Intro::all()->count();

            $description = ['title'=>$title, 'content'=>$content];

            if(!\File::exists(public_path('Menu/0/')))
            {
                \File::makeDirectory(public_path('Menu/0/'), 0777);
            }

            if(!\File::exists(public_path('Menu/0/'.$order)))
            {
                \File::makeDirectory(public_path('Menu/0/'.$order), 0777);
            }

            \File::put(public_path('Menu/0/'.$order.'/Description.txt'), json_encode($description), true);
            \File::put(public_path('Menu/0/'.$order.'/Content.txt'), json_encode($content), true);



        } catch(\Exception $ex)
        {
            \DB::rollBack();

            return redirect()->back()->with('error', $ex->getMessage().$ex->getLine());
        }

        \DB::commit();


        return redirect()->back()->with('success', 'Thêm nội dung thành công');
    }


    public function updateIntro(Request $request)
    {
        $data = $request->all();

        $id = $request->input('id');


        if(empty($id))
        {
            return redirect()->back()->with('error', 'Thêm nội dung thất bại');
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
            $detailType = 3;

            foreach ($images as $image)
            {
                $imageLink[] = $this->saveImage($image);
            }
            $data['images'] = json_encode($imageLink);
        }

        $content = Intro::find($id);

        try {
            $content->update($data);

//            $content = [
//                'layoutType' => $detailType,
//                'name' => $data['name'],
//                'image' =>json_encode($imageLink),
//                'main' => $data['main'],
//                'content' => $content,
//                'icon' => $data['icon'],
//                'type' => 'detail'
//            ];

//            $description = ['title'=>$data['title'], 'content'=>$data['content']];
//
//            $subMenuCount = DB::table('sub_menus')->where('parent', $menu)->where('parent_type', $menuType)->count();
//
//            $contentCount = DB::table('contents')->where('menu', $menu)->where('menu_type', $menuType)->count();
//
//            $order = $subMenuCount + $contentCount + 1;
//
//
//            if($menuType == 1) {
//                File::put(public_path('Menu/' . $menu . '/'.$order.'/Description.txt'), json_encode($description), true);
//                File::put(public_path('Menu/' . $menu . '/'.$order.'/Content.txt'), json_encode($content), true);
//            } else if ($menuType == 2)
//            {
//                File::put(public_path('Menu/' . $this->getMenuRecursive($menu) . '/Description.txt'), json_encode($content), true);
//            }
//            //  File::put(public_path('Menu/'.$menu.'/'.$newOrder.'/Description.txt'), json_encode($content), true);


        } catch(\Exception $ex)
        {
            return redirect()->back()->with('error', $ex->getMessage().$ex->getLine());
        }



        return redirect()->back()->with('success', 'Cập nhật nội dung thành công');
    }

    public function deleteIntro($id)
    {

        $intro = Intro::find($id);

        $intro->delete();

        return redirect()->back()->with('success', 'Xóa nội dung thành công');
    }
}
