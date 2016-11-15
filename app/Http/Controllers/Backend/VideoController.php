<?php

namespace App\Http\Controllers\Backend;

use App\Video;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use File;

class VideoController extends AdminController
{
    //
    public function createVideo(Request $request)
    {
        $data = $request->all();
        $data['image'] =  ($request->file('image') && $request->file('image')->isValid()) ? $this->saveImage($request->file('image')) : '';

        Video::create($data);

        if (!File::exists('Video'))
        {
            File::makeDirectory('Video');
        }

        $videos = Video::all();
        $videoNumber = Video::all()->count();

        $videosUrls = $videos->pluck('link')->toArray();
        $videosImages = $videos->pluck('image')->toArray();

        $i = 1;

        foreach($videos as $video)
        {
            File::copy(public_path('files/'.$video->image), public_path('Video/'.$i.'.png'));
            $i++;
        }
        try {

            $content = [
                'type' => 'videosPage',
                'videoNumber' => $videoNumber,
                "videoUrls" => $videosUrls,
                "videoImages" => $videosImages,
            ];

            File::put(public_path('Video/description.json'), json_encode($content), true);
        } catch (\Exception $ex)
        {
            return redirect()->back()->with('error', 'Thêm video thất bại');
        }

        return redirect()->back()->with('success', 'Thêm video thành công');
    }

    public function getVideos()
    {
        $videos  = Video::all();

        return view('admin.video.index', compact('videos'));
    }
}
