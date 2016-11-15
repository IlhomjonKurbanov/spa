@extends('admin')

@section('content')
    @if (session()->has('error'))
        <div class="alert alert-error">{{ session()->get('error') }}</div>
    @endif
    @if (session()->has('success'))
        <div class="alert alert-success">{{ session()->get('success') }}</div>
    @endif
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
                        <a class="btn btn-xs green dropdown-toggle" href="{{ url('view-video', ['id'=>$video->id]) }}" > Sửa
                        </a>
                    </div>
                    <div class="btn-group">
                        <button class="btn btn-xs red dropdown-toggle" onclick="deleteVideo({{$video->id}})"> Xóa
                        </button>
                    </div>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    @push('scripts')
    <script>

        var baseUrl = '{{url('/')}}';

        function deleteVideo(id)
        {
            if (confirm("Bạn có chắc chắn muốn xóa video ?")) {
                window.location.href = baseUrl+'/delete-video/'+id;
            }
            return false;

        }
    </script>
    @endpush


@endsection
