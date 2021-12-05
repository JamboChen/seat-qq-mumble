@extends('web::layouts.grids.12')

@section('title', 'bind QQ')

@section('full')

    <form class="form-inline" method="POST">
        @csrf
        <div class="form-group mx-sm-3 mb-2">
            <label for="inputPassword2" class="sr-only">Password</label>
            <input type="number" class="form-control" id="qq" name='qq' placeholder="请输入 QQ" value="{{ $qqinfo }}"
                min="0" max="999999999999999">
        </div>
        <button type="submit" class="btn btn-primary mb-2">确认</button>
    </form>


@stop

@push('javascript')
    <script>
        console.log('Include any JavaScript you may need here!');
    </script>
@endpush