@extends('admin')

@section('content')

    <form action="" class="form-horizontal" method="post">
        <div class="form-body">

    <div class="form-group">
        <label class="col-md-3 control-label">Bạn có muốn cập nhật zip file ?</label>
        <label class="col-md-3 control-label" id="result"></label>
    </div>
    <div class="form-actions">
        <div class="row">
            <div class="col-md-offset-3 col-md-9">
                <button type="button" class="btn green" id="update-zip">Có</button>
                <button type="button" class="btn default">Không</button>
            </div>
        </div>
    </div>
            </div>
        </form>
@endsection

@push('scripts')
<script>

    $(document).ready(function() {
        $('#update-zip').click(function() {
            $.ajax({
                 url: '/make-zip',
                 type: 'get',
                 dataType: 'json',
                 success: function(response)
                 {
                     $('#result').html('');
                     if(response.status == 1) {

                         $('#result').html(response.message);
                     } else {
                         $('#result').html('Có lỗi xảy ra');
                     }
                 },
                 
                 error: function () {
                     $('#result').html('Có lỗi xảy ra');
                 }
            });
        });
    });


</script>
@endpush