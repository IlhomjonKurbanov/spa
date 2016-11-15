@extends('admin')

@section('content')
    {{--<h3>Danh sách menu con menu {{$parentMenu->name}}</h3>--}}
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

        @php $subMenus = \App\SubMenu::all() @endphp
        @foreach($subMenus as $menu)
            <tr>
                <td>{{$menu->order}}</td>
                <td>{{$menu->name}}</td>
                <td>
                    @if(!empty($menu->icon))
                        <img src="{{'/files/'.$menu->icon}}" style="max-width: 150px" />
                    @endif
                </td>
                <td>@if(!empty($menu->icon_hover))<img src="{{'/files/'.$menu->icon_hover}}" style="max-width: 150px" />@endif</td>
                <td>@if(!empty($menu->main))<img src="{{'/files/'.$menu->main}}" style="max-width: 150px" />@endif</td>
                <td>
                    {{--<div class="btn-group">--}}
                        {{--<a class="btn btn-xs blue dropdown-toggle" href="{{url('create-content', ['menu'=>$menu->id, 'menuType'=>2])}}"> Tạo nội dung--}}
                        {{--</a>--}}

                    {{--</div>--}}
                    {{--<div class="btn-group">--}}
                        {{--<a class="btn btn-xs gray dropdown-toggle" href="{{url('list-content', ['menu'=>$menu->id, 'menuType'=>2])}}"> Xem nội dung--}}
                        {{--</a>--}}

                    {{--</div>--}}

                    {{--<div class="btn-group">--}}
                        {{--<a class="btn btn-xs pink dropdown-toggle" href="{{url('list-sub-menu', ['menu' => $menu->id, 'menuType' => 2])}}"> Xem menu con--}}
                        {{--</a>--}}

                    {{--</div>--}}

                    <div class="btn-group">
                        <a class="btn btn-xs green dropdown-toggle" href="{{url('view-sub-menu', ['id'=>$menu->id])}}" > Sửa
                        </a>
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
