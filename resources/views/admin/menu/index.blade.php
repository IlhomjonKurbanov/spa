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
                <td>
                    @if(!empty($menu->icon_sidebar))
                    <img src="{{'files/'.$menu->icon_sidebar}}" style="max-width: 150px" />
                        @endif
                </td>
                <td> @if(!empty($menu->icon_sidebar_hover))
                    <img src="{{'files/'.$menu->icon_sidebar_hover}}" style="max-width: 150px" />
                @endif</td>
                <td>
                    @if(!empty($menu->main))<img src="{{'files/'.$menu->main}}" style="max-width: 150px" />@endif</td>
                <td> @if(!empty($menu->thumbnail))<img src="{{'files/'.$menu->thumbnail}}" style="max-width: 150px" />@endif</td>
                <td>
                    <div class="row">
                        <div class="col-md-12">
                    <div class="btn-group">
                        <a href="{{url('create-sub-menu', ['menu' => $menu->id, 'menuType' => 1])}}" class="btn btn-xs blue dropdown-toggle"> Tạo menu con
                        </a>

                    </div>
                    <div class="btn-group">
                        <a href="{{url('list-sub-menu', ['menu' => $menu->id, 'menuType' => 1])}}" class="btn btn-xs orange dropdown-toggle"> Xem danh sách menu con
                        </a>

                    </div>
                            </div>
                    </div>

                    <div class="row" style="margin-top: 20px">
                        <div class="col-md-12">
                    {{--<div class="btn-group">--}}
                        {{--<button class="btn btn-xs gray dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="true"> Tạo nội dung--}}
                        {{--</button>--}}
                    {{--</div>--}}
                            <div class="btn-group">
                                <a class="btn btn-xs gray dropdown-toggle" href="{{url('list-content', ['menu'=>$menu->id, 'menuType'=>1])}}"> Xem nội dung
                                </a>

                            </div>
                    <div class="btn-group">
                        <a class="btn btn-xs green dropdown-toggle" href="{{url('view-menu', ['id'=>$menu->id])}}"> Sửa
                        </a>
                    </div>
                    <div class="btn-group">
                        <button class="btn btn-xs red dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="true"> Xóa
                        </button>

                    </div>
                        </div>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

@endsection
