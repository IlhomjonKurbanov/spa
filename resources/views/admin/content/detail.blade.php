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
                <span class="caption-subject font-green sbold uppercase">Cập nhật nội dung</span>
            </div>
        </div>
        <form action="{{url('create_content_form')}}" class="form-horizontal form-bordered" method="POST" enctype="multipart/form-data">
            {{ csrf_field() }}
            <div class="form-body">
                <div class="form-group">
                    <label class="col-md-3 control-label">Tên</label>
                    <div class="col-md-4">
                        <input type="text" class="form-control" name="name" placeholder="Điền tên của menu con">
                    </div>
                </div>
                <input type="hidden" value="{{$menu}}" name="menu"/>
                <input type="hidden" value="{{$menuType}}" name="menu_type"/>

                <div class="form-group">
                    <label class="col-md-3 control-label">Nội dung</label>
                    <div class="col-md-4">
                        <textarea type="text" class="form-control" name="content"></textarea>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-3 control-label">Ảnh (có thể upload nhiều ảnh)</label>
                    <div class="col-md-4">
                        <input type="file" class="form-control" name="images[]" multiple>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-3">Icon</label>
                    <div class="col-md-9">
                        <div class="fileinput fileinput-new" data-provides="fileinput">
                            <div class="fileinput-new thumbnail" style="width: 200px; height: 150px;">
                                <img src="http://www.placehold.it/200x150/EFEFEF/AAAAAA&amp;text=no+image" alt="" /> </div>
                            <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 400px; max-height: 500px;"> </div>
                            <div>
                                                                <span class="btn default btn-file">
                                                                    <span class="fileinput-new"> Chọn ảnh </span>
                                                                    <span class="fileinput-exists"> Thay đổi </span>
                                                                    <input type="file" name="icon"> </span>
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
                                <img src="http://www.placehold.it/200x150/EFEFEF/AAAAAA&amp;text=no+image" alt="" /> </div>
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
                            <button type="submit" class="btn green">Đăng</button>
                            <button type="button" class="btn default">Hủy</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

@endsection