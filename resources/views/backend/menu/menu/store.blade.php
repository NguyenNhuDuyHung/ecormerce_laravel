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
    $url = $config['method'] == 'create' ? route('menu.store') : route('menu.update', $menu->id);
@endphp

<form action="{{ $url }}" method="post" class="box menuContainer">
    @csrf
    <div class="wrapper wrapper-content animated fadeInRight">
        @include('backend.menu.menu.components.catalogue')
        <hr>
        @include('backend.menu.menu.components.list')

        <div class="text-right mb15">
            <button class="btn btn-primary" name="send" value="send" type="submit">Lưu thông tin
            </button>
        </div>
    </div>
</form>
@include('backend.menu.menu.components.popup')