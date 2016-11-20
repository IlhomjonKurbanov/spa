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
        <form action="{{url('update_intro_form')}}" class="form-horizontal form-bordered" method="POST" enctype="multipart/form-data">
            {{ csrf_field() }}
            <div class="form-body">
                <div class="form-group">
                    <label class="col-md-3 control-label">Tên</label>
                    <div class="col-md-4">
                        <input type="text" class="form-control" name="name" placeholder="Điền tên" value="{{$intro->name}}">
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-3 control-label">Title</label>
                    <div class="col-md-4">
                        <input type="text" class="form-control" name="title" placeholder="Điền title" value="{{$intro->title}}">
                    </div>
                </div>
                <input type="hidden" name="id" value="{{$intro->id}}" >


                <div class="form-group">
                    <label class="col-md-3 control-label">Nội dung</label>
                    <div class="col-md-4">
                        <textarea type="text" class="form-control" name="content">{{$intro->content}}</textarea>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-3 control-label">Chọn loại nội dung</label>
                    <div class="col-md-4">
                        <select class="form-control" value="detail_type">
                            <option value="1">Chỉ có nội dung</option>
                            <option value="2">Có 1 ảnh</option>
                            <option value="3">Có nhiều ảnh</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    @php $images = $intro->image @endphp
                    <div class="row">
                        <div class="col-md-3 col-md-offset-3">
                            @foreach($images as $image)
                                <img src="{{'/files/'.$image }}" style="max-width: 150px" />
                            @endforeach
                        </div>
                    </div>
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
                                <img src="{{ '/files/' . $intro->icon }}" alt="" /> </div>
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
                                <img src="{{ '/files/' . $intro->main }}" alt="" /> </div>
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