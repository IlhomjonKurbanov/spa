<?php

namespace App\Http\Controllers\Backend;

use App\Intro;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use File;

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

    public function getIntroOutside($id)
    {

        $intro = Intro::find($id);

        return view('admin.intro.outside_detail', compact('intro'));

    }

    public function createIntroOutside(Request $request)
    {
        $name = $request->input('name');

        $icon_sidebar =  ($request->file('icon_sidebar') && $request->file('icon_sidebar')->isValid()) ? $this->saveImage($request->file('icon_sidebar')) : '';
        $icon_sidebar_hover =  ($request->file('icon_sidebar_hover') && $request->file('icon_sidebar_hover')->isValid()) ? $this->saveImage($request->file('icon_sidebar_hover')) : '';
        $main =  ($request->file('main') && $request->file('main')->isValid()) ? $this->saveImage($request->file('main')) : '';
        $thumb =  ($request->file('thumbnail') && $request->file('thumbnail')->isValid()) ? $this->saveImage($request->file('thumbnail')) : '';

    }

    public function updateIntroOutside(Request $request)
    {

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
            $dataContent = $content;

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

        \DB::beginTransaction();
        try {


            $order = Intro::all()->count();

            $newOrder = $order + 1;

            Intro::create([
                'icon' => $icon,
                'image' => json_encode($imageLink),
                'main' => $main,
                'content' => $content,
                'order' => $order +1,
                'name' => $name,
                'title' => $title
            ]);

            if(!\File::exists(public_path('Intro/')))
            {
                \File::makeDirectory(public_path('Intro/'), 0777);
            }

            if(!\File::exists(public_path('Intro/'.$newOrder)))
            {
                \File::makeDirectory(public_path('Intro/'.$newOrder), 0777);
            }

            $localImageLink = [];

            if(!empty($imageLink)) {
                $i = 0;
                foreach($imageLink as $itemImage) {
                    $i++;
                    \File::copy(public_path('files/' . $itemImage), public_path('Intro/' . $newOrder . '/'.$i.'.png'));

                    $localImageLink[] = $i.'.png';
                }
            }

            $description = ['type'=>'detail', 'layoutType'=>$detailType];

            $content = [
                'images' =>json_encode($localImageLink),
                'title' => $title,
                'content' => $content,
            ];

            $menuContent = [
                'type' => 'Intro',
                'name' => $name,
                'childNumber' => Intro::all()->count(),
                'childNames' => Intro::all()->pluck('name', 'order')->toArray()
            ];

            \File::put(public_path('Intro/Description.txt'), json_encode($menuContent), true);

            \File::put(public_path('Intro/'.$newOrder.'/Description.txt'), json_encode($description), true);

            \File::put(public_path('Intro/'.$newOrder.'/Content.txt'), json_encode($content), true);

            if (!empty($icon)) {
                File::copy(public_path('files/' . $icon), public_path('Intro/' . $newOrder . '/icon.png'));
            }

            if (!empty($main)) {
                File::copy(public_path('files/' . $main), public_path('Intro/' . $newOrder . '/main.png'));
            }


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

        $content = Intro::find($id);

        $order = $content->order;

        try {
            $content->update($data);

            $localImageLink = [];

            if(!empty($imageLink)) {
                $i = 0;
                foreach($imageLink as $itemImage) {
                    $i++;
                    \File::copy(public_path('files/' . $itemImage), public_path('Intro/' . $order . '/'.$i.'.png'));

                    $localImageLink[] = $i.'.png';
                }
            }

            $description = ['type'=>'detail', 'layoutType'=>$detailType];

            $content = [
                'images' =>json_encode($localImageLink),
                'title' => $data['title'],
                'content' => $data['content'],
            ];

            $menuContent = [
                'type' => 'Intro',
                'name' => $data['name'],
                'childNumber' => Intro::all()->count(),
                'childNames' => Intro::all()->pluck('name', 'order')->toArray()
            ];

            \File::put(public_path('Intro/Description.txt'), json_encode($menuContent), true);

            \File::put(public_path('Intro/' . $order .'/Description.txt'), json_encode($description), true);

            \File::put(public_path('Intro/' . $order . '/Content.txt'), json_encode($content), true);

            if (!empty($icon)) {
                \File::copy(public_path('files/' . $icon), public_path('Intro/' . $order . '/icon.png'));
            }

            if (!empty($main)) {
                \File::copy(public_path('files/' . $main), public_path('Intro/' . $order . '/main.png'));
            }


        } catch(\Exception $ex)
        {
            return redirect()->back()->with('error', $ex->getMessage().$ex->getLine());
        }

        return redirect()->back()->with('success', 'Cập nhật nội dung thành công');
    }

    public function deleteIntro($id)
    {

        $intro = Intro::find($id);

        $order = $intro->order;

        $biggerIntros = Intro::where('order', '>', $order)->get();

        foreach($biggerIntros as $biggerIntro)
        {
            $biggerIntro->update(['order' => $biggerIntro->order - 1]);
        }

        $intro->delete();

        return redirect()->back()->with('success', 'Xóa nội dung thành công');
    }
}
