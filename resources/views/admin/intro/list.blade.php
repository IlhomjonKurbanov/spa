@extends('admin')

@section('content')
    @if (session()->has('error'))
        <div class="alert alert-error">{{ session()->get('error') }}</div>
    @endif
    @if (session()->has('success'))
        <div class="alert alert-success">{{ session()->get('success') }}</div>
    @endif

    @if(count($intros) > 0)
        <table class="table table-striped table-bordered table-hover">
            <thead>
            <tr>
                <th>Id</th>
                <th>Tên</th>
                <th>Nội dung</th>
                <th>Ảnh (có thể gồm nhiều ảnh)</th>
                <th>Icon</th>
                <th>Main</th>
                <th>Hành động</th>
            </tr>
            </thead>
            <tbody>
            @foreach($intros as $intro)
                <tr>
                    <td>{{$intro->id}}</td>
                    <td>{{$intro->name}}</td>
                    <td>{{$intro->content}}</td>
                    <td>
                        @php $images = $intro->image @endphp

                        @foreach($images as $image)
                            <img src="{{'/files/'.$image }}" style="max-width: 150px" />
                        @endforeach


                    </td>


                    <td><img src="{{'/files/'.$intro->icon}}" style="max-width: 150px" /></td>
                    <td><img src="{{'/files/'.$intro->main}}" style="max-width: 150px" /></td>

                    <td>


                        <div class="col-md-12">
                            <div class="btn-group">
                                <a class="btn btn-xs green dropdown-toggle" href="{{url('view-intro', ['id' => $intro->id])}}"> Sửa
                                </a>
                            </div>
                            <div class="btn-group">
                                <button class="btn btn-xs red dropdown-toggle" onclick="deleteIntro({{$intro->id}})"> Xóa
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

    @push('scripts')
    <script>

        var baseUrl = '{{url('/')}}';

        function deleteIntro(id)
        {
            if (confirm("Bạn có chắc chắn muốn xóa nội dung ?")) {
                window.location.href = baseUrl+'/delete-intro/'+id;
            }
            return false;

        }
    </script>
    @endpush

@endsection
