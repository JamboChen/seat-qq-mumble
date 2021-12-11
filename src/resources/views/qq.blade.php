@extends('web::layouts.grids.12')

@section('title', 'bind QQ')

@section('full')

    <div class="card card-success">
        <div class="card-header">
            <h3 class="card-title">个人设置</h3>
        </div>
        <form method="POST" action="">
            <div class="card-body">

                <div class="form-group">
                    <label for="qq">QQ</label>
                    <input type="number" class="form-control" name="qq" id="qq" value="{{ $qqinfo }}"
                        placeholder="请输入 QQ号" min="0" max="999999999999999">
                </div>
                <div class="form-group">
                    <label for="title">中文昵称（Title）</label>
                    <input type="text" class="form-control" name="title" id="title" value="{{ $title }}">
                </div>
                <div class="d-grid gap-2 d-md-flex">
                    <input type="submit" class="btn btn-primary " value="设置" />
                </div>
                {{ csrf_field() }}
            </div>
        </form>
    </div>

@php
    use Illuminate\Support\Facades\DB;
    $test  = DB::table('titles')->where('user_id', 4)->value('title');
    
    print_r($test!=null)

@endphp


@stop

@push('javascript')
    <script>
        console.log('Include any JavaScript you may need here!');
    </script>
@endpush
