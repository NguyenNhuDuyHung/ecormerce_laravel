@include('backend.dashboard.components.breadcrumb', ['title' => $config['seo'][$config['method']]['title']])

@if (isset($errors) && $errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@php
    $url =
        $config['method'] == 'create'
            ? route('{view}.store')
            : route('{view}.update', $postCatalogue->id);
@endphp

<form action="{{ $url }}" method="post" class="box">
    @csrf
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-9">
                <div class="ibox">
                    <div class="ibox-title">
                        <h5>Thông tin chung</h5>
                    </div>

                    <div class="ibox-content" style="display: flex; flex-direction: column; gap: 40px">
                        @include('backend.dashboard.components.content', ['model' => ${module} ?? null])
                    </div>
                </div>

                @include('backend.dashboard.components.album', ['model' => ${module} ?? null])
                @include('backend.dasboard.components.seo', ['model' => ${module} ?? null])
            </div>

            <div class="col-lg-3">
                @include('backend.{view}.components.aside')
            </div>
        </div>

        <hr>

        <div class="text-right mb15">
            @if ($config['method'] == 'create')
                <button class="btn btn-primary" name="send" value="send" type="submit">Thêm</button>
            @else
                <button class="btn btn-primary" name="send" value="send" type="submit">Lưu thông tin
                </button>
            @endif
        </div>
    </div>
</form>
