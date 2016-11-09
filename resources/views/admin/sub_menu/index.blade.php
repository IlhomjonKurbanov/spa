@extends('admin')

@section('content')

    <table class="table table-striped table-bordered table-hover">
        <thead>
        <tr>
            <th>Số thứ tự</th>
            <th>Tên</th>
            <th>Icon</th>
            <th>Icon Hover</th>
            <th>Main</th>
            <th>Hành động</th>
        </tr>
        </thead>
        <tbody>
            @foreach($subMenus as $menu)
            <tr>
                <td>{{$menu->order}}</td>
                <td>{{$menu->name}}</td>
                <td><img src="{{'/files/'.$menu->icon}}" style="max-width: 250px" /></td>
                <td><img src="{{'/files/'.$menu->icon_hover}}" style="max-width: 250px" /></td>
                <td><img src="{{'/files/'.$menu->main}}" style="max-width: 250px" /></td>
                <td>
                    <div class="btn-group">
                        <button class="btn btn-xs blue dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="true"> Tạo nội dung
                        </button>

                    </div>
                    <div class="btn-group">
                        <button class="btn btn-xs green dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="true"> Sửa
                        </button>
                    </div>
                    <div class="btn-group">
                        <button class="btn btn-xs red dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="true"> Xóa
                        </button>

                    </div>

                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

@endsection
