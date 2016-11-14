@extends('admin')

@section('content')
    @if (session()->has('error'))
        <div class="alert alert-error">{{ session()->get('error') }}</div>
    @endif
    @if (session()->has('success'))
        <div class="alert alert-success">{{ session()->get('success') }}</div>
    @endif
    <div class="portlet-body form">
        <div class="portlet-title">
            <div class="caption">
                <i class="icon-settings font-green"></i>
                <span class="caption-subject font-green sbold uppercase">Cập nhật menu</span>
            </div>
        </div>
        <form action="{{url('update_menu_form')}}" class="form-horizontal form-bordered" method="POST" enctype="multipart/form-data">
            {{ csrf_field() }}

            <input type="hidden" name="id" value="{{$menu->id}}">

            <div class="form-body">
                <div class="form-group">
                    <label class="col-md-3 control-label">Tên</label>
                    <div class="col-md-4">
                        <input type="text" class="form-control" name="name" placeholder="Điền tên của menu" value="{{$menu->name}}">
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-3">Icon SideBar</label>
                    <div class="col-md-9">
                        <div class="fileinput fileinput-new" data-provides="fileinput">
                            <div class="fileinput-new thumbnail" style="width: 200px; height: 150px;">
                                <img src="{{ '/files/'.$menu->icon_sidebar }}" alt="" /> </div>
                            <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 400px; max-height: 500px;"> </div>
                            <div>
                                                                <span class="btn default btn-file">
                                                                    <span class="fileinput-new"> Chọn ảnh </span>
                                                                    <span class="fileinput-exists"> Thay đổi </span>
                                                                    <input type="file" name="icon_sidebar"> </span>
                                <a href="javascript:;" class="btn red fileinput-exists" data-dismiss="fileinput"> Xóa </a>
                            </div>
                        </div>
                        <div class="clearfix margin-top-10">

                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-3">Icon SideBar Hover</label>
                    <div class="col-md-9">
                        <div class="fileinput fileinput-new" data-provides="fileinput">
                            <div class="fileinput-new thumbnail" style="width: 200px; height: 150px;">
                                <img src="{{ '/files/'.$menu->icon_sidebar_hover }}" alt="" /> </div>
                            <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 400px; max-height: 500px;"> </div>
                            <div>
                                                                <span class="btn default btn-file">
                                                                    <span class="fileinput-new"> Chọn ảnh </span>
                                                                    <span class="fileinput-exists"> Thay đổi </span>
                                                                    <input type="file" name="icon_sidebar_hover"> </span>
                                <a href="javascript:;" class="btn red fileinput-exists" data-dismiss="fileinput"> Xóa </a>
                            </div>
                        </div>
                        <div class="clearfix margin-top-10">

                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-3">Thumbnail</label>
                    <div class="col-md-9">
                        <div class="fileinput fileinput-new" data-provides="fileinput">
                            <div class="fileinput-new thumbnail" style="width: 200px; height: 150px;">
                                <img src="{{ '/files/'.$menu->thumbnail }}" alt="" /> </div>
                            <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 400px; max-height: 500px;"> </div>
                            <div>
                                                                <span class="btn default btn-file">
                                                                    <span class="fileinput-new"> Chọn ảnh </span>
                                                                    <span class="fileinput-exists"> Thay đổi </span>
                                                                    <input type="file" name="thumbnail"> </span>
                                <a href="javascript:;" class="btn red fileinput-exists" data-dismiss="fileinput"> Xóa </a>
                            </div>
                        </div>
                        <div class="clearfix margin-top-10">

                        </div>
                    </div>

                </div>
                <div class="form-group last">
                    <label class="control-label col-md-3">Main</label>
                    <div class="col-md-9">
                        <div class="fileinput fileinput-new" data-provides="fileinput">
                            <div class="fileinput-new thumbnail" style="width: 200px; height: 150px;">
                                <img src="{{ '/files/'.$menu->main }}" alt="" /> </div>
                            <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 400px; max-height: 500px;"> </div>
                            <div>
                                                                <span class="btn default btn-file">
                                                                    <span class="fileinput-new"> Chọn ảnh </span>
                                                                    <span class="fileinput-exists"> Thay đổi </span>
                                                                    <input type="file" name="main"> </span>
                                <a href="javascript:;" class="btn red fileinput-exists" data-dismiss="fileinput"> Xóa </a>
                            </div>
                        </div>
                        <div class="clearfix margin-top-10">

                        </div>
                    </div>
                </div>
                <div class="form-actions">
                    <div class="row">
                        <div class="col-md-offset-3 col-md-9">
                            <button type="submit" class="btn green">Cập nhật</button>
                            <button type="button" class="btn default">Hủy</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

@endsection