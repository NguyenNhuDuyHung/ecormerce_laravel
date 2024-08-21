@include('backend.dashboard.components.breadcrum', ['title' => $config['seo'][$config['method']]['title']])

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
            ? route('post.store')
            : route('post.update', $post->id);
@endphp

<form action="{{ $url }}" method="post" class="box">
    @csrf
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-9">
                <div class="ibox">
                    <div class="ibox-title">
                        <h5>{{__('message_tableHeading')}}</h5>
                    </div>

                    <div class="ibox-content" style="display: flex; flex-direction: column; gap: 40px">
                        @include('backend.post.post.components.general')
                    </div>
                </div>

                @include('backend.dashboard.components.album')
                @include('backend.post.post.components.seo')
            </div>

            <div class="col-lg-3">
                @include('backend.post.post.components.aside')
            </div>
        </div>

        <hr>

        <div class="text-right mb15">
            @if ($config['method'] == 'create')
                <button class="btn btn-primary" name="send" value="send" type="submit">Thêm</button>
            @else
                <button class="btn btn-primary" name="send" value="send" type="submit">{{__('message_save')}}
                </button>
            @endif
        </div>
    </div>
</form>
