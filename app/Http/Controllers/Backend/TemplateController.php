<?php

namespace App\Http\Controllers\Backend;


use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Topic;
use App\Template;
use App\Http\Requests\TemplateRequest;

class TemplateController extends AdminController
{
    //
    public function index(Request $request)
    {
        $templates = Template::latest()->paginate(config('constants.ADMIN_ITEM_PER_PAGE'));
        return view('admin.template.index', compact('templates'));
    }

    public function create()
    {
        return view('admin.template.form');
    }

    public function store(TemplateRequest $request)
    {
        $data['image_preview'] = ($request->file('image') && $request->file('image')->isValid()) ? $this->saveImage($request->file('image')) : '';
        $data['status'] = ($request->input('status') == 'on') ? true : false;
        $data['name'] = $request->input('name');
        $data['data'] = json_encode($request->input('data'));

        try {
            Template::create($data);
        } catch (\Exception $ex) {
            flash($ex->getMessage(), 'error');
        }
        flash('Tạo template thành công', 'success');
        return redirect('admin/templates');

    }

    public function edit($id)
    {
        $template = Template::find($id);
        return view('admin.template.form', compact('template'));
    }

    public function update($id, TemplateRequest $request)
    {
        $template = Template::find($id);
        $data = $request->all();

        $data['status'] = ($request->input('status') == 'on') ? true : false;
        $data['data'] = json_encode($request->input('data'));

        if ($request->file('image') && $request->file('image')->isValid()) {
            $data['image_preview'] = ($request->file('image') && $request->file('image')->isValid()) ? $this->saveImage($request->file('image')) : '';
        } else {
            unset($data['image_preview']);
        }



        try {
            $template->update($data);
        } catch (\Exception $ex) {
            flash($ex->getMessage(), 'error');
        }
        flash('Cập nhật template thành công', 'success');
        return redirect('admin/templates');
    }

    public function destroy($id)
    {
        $template = Template::find($id);

        if (file_exists(public_path('files/' . $template->image_preview))) {
            @unlink(public_path('files/' . $template->image_preview));
        }
        try {
        $template->delete();
        } catch (\Exception $ex) {
            flash($ex->getMessage(), 'error');
        }
        flash('Xóa template thành công');
        return redirect('admin/templates');
    }
}
