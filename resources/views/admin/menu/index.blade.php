@extends('admin')

@section('content')

    <table class="table table-striped table-bordered table-hover">
        <thead>
        <tr>
            <th>Số thứ tự</th>
            <th>Tên</th>
            <th>Icon Sidebar</th>
            <th>Icon Sidebar Hover</th>
            <th>Main</th>
            <th>Thumbnail</th>
            <th>Hành động</th>
        </tr>
        </thead>
        <tbody>
            @foreach($menus as $menu)
            <tr>
                <td>{{$menu->order}}</td>
                <td>{{$menu->name}}</td>
                <td><img src="{{'files/'.$menu->icon_sidebar}}" style="max-width: 250px" /></td>
                <td><img src="{{'files/'.$menu->icon_sidebar_hover}}" style="max-width: 250px" /></td>
                <td><img src="{{'files/'.$menu->main}}" style="max-width: 250px" /></td>
                <td><img src="{{'files/'.$menu->thumbnail}}" style="max-width: 250px" /></td>
                <td>
                    <div class="btn-group">
                        <a href="{{url('create-sub-menu', ['menu' => $menu->id])}}" class="btn btn-xs blue dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="true"> Tạo menu con
                        </a>

                    </div>
                    <div class="btn-group">
                        <button class="btn btn-xs gray dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="true"> Tạo nội dung
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
