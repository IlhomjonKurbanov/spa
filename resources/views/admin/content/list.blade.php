@extends('admin')

@section('content')

    @if(count($contents) > 0)
    <table class="table table-striped table-bordered table-hover">
        <thead>
        <tr>
            <th>Id</th>
            <th>Tên</th>
            <th>Nội dung</th>
            <th>Ảnh (có thể gồm nhiều ảnh)</th>
            <th>Icon</th>
            <th>Main</th>
            <th>Thuộc menu</th>
            <th>Hành động</th>
        </tr>
        </thead>
        <tbody>
        @foreach($contents as $content)
            <tr>
                <td>{{$content->id}}</td>
                <td>{{$content->name}}</td>
                <td>{{$content->content}}</td>
                <td>
                    @php $images = $content->image @endphp

                    @foreach($images as $image)
                    <img src="{{'/files/'.$image }}" style="max-width: 150px" />
                    @endforeach


                </td>


                <td><img src="{{'/files/'.$content->icon}}" style="max-width: 150px" /></td>
                <td><img src="{{'/files/'.$content->main}}" style="max-width: 150px" /></td>
                <td>{{'Menu: '.$content->parentMenu['menu']->name .', loại: '.$content->parentMenu['menu_type']}}<br>
                    @if($content->menu_type == 1)
                    <a class="btn btn-xs red dropdown-toggle" href="{{ url('view-menu', ['id'=>$content->menu]) }}"> Xem menu
                    </a>
                        @else
                        <a class="btn btn-xs red dropdown-toggle" href="{{ url('view-sub-menu', ['id'=>$content->menu]) }}"> Xem menu
                        </a>
                    @endif
                </td>
                <td>


                        <div class="col-md-12">
                            <div class="btn-group">
                                <button class="btn btn-xs green dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="true"> Sửa
                                </button>
                            </div>
                            <div class="btn-group">
                                <button class="btn btn-xs red dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="true"> Xóa
                                </button>

                            </div>
                        </div>

                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    @else
    <h3>Không có nội dung nào</h3>
    @endif

@endsection
