@extends('admin')

@section('content')

    <table class="table table-striped table-bordered table-hover">
        <thead>
        <tr>
            <th>Số thứ tự</th>
            <th>Tên</th>
            <th>Link Youtube</th>
            <th>Ảnh</th>
            <th>Hành động</th>
        </tr>
        </thead>
        <tbody>
        @foreach($videos as $video)
            <tr>
                <td>{{$video->order}}</td>
                <td>{{$video->name}}</td>
                <td>{{$video->link}}</td>
                <td><img src="{{'/files/'.$video->image}}" style="max-width: 150px" /></td>
                <td>
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
