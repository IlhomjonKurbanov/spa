<?php

namespace App\Http\Controllers\Backend;

use App\Topic;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Requests\ScreenRequest;
use App\Http\Controllers\Backend\AdminController;

class ScreenController extends AdminController
{
    //
    public function index(Request $request)
    {
        $screens = Topic::latest()->whereNull('parent_id')->paginate(config('constants.ADMIN_ITEM_PER_PAGE'));
        return view('admin.screen.index', compact('screens'));
    }

    public function create()
    {
        return view('admin.screen.form');
    }

    public function store(ScreenRequest $request)
    {
        $name = $request->input('name');
        $image = ($request->file('image') && $request->file('image')->isValid()) ? $this->saveImage($request->file('image')) : '';
        $description = $request->input('description');


        try {
            Topic::create([
                'name' => $name,
                'data' => json_encode([
                    'image' => $image,
                    'name' => $name,
                    'description' => $description
                ]),
                'status' => ($request->input('status') == 'on') ? true : false,
            ]);
        }
        catch (\Exception $ex) {
            flash($ex->getMessage(), 'error');
        }

        flash('Tạo một item thành công', 'success');
        return redirect('admin/topics');

    }

    public function edit($id)
    {
        $screen = Topic::find($id);
        return view('admin.topic.form', compact('screen'));
    }

    public function update($id, ScreenRequest $request)
    {
        $screen = Topic::find($id);


        $name = $request->input('name');
        $image = ($request->file('image') && $request->file('image')->isValid()) ? $this->saveImage($request->file('image')) : '';
        $description = $request->input('description');

        if ($request->file('image') && $request->file('image')->isValid()) {
            $image = ($request->file('image') && $request->file('image')->isValid()) ? $this->saveImage($request->file('image')) : '';
        }


        try {
            $screen->update([
                'name' => $name,
                'data' => json_encode([
                    'image' => $image,
                    'name' => $name,
                    'description' => $description
                ]),
                'status' => ($request->input('status') == 'on') ? true : false,
            ]);
        } catch (\Exception $ex) {
            flash($ex->getMessage(), 'error');
        }
        flash('Cập nhật item thành công', 'success');
        return redirect('admin/topics');
    }

    public function destroy($id)
    {
        $screen = Topic::find($id);

        $data = json_decode($screen->data);


        if (file_exists(public_path('files/' . $data->image))) {
            @unlink(public_path('files/' . $data->image));
        }
        try {
            $screen->delete();
        } catch (\Exception $ex) {
            flash($ex->getMessage(), 'error');
        }
        flash('Xóa item thành công');
        return redirect('admin/topics');
    }
}
