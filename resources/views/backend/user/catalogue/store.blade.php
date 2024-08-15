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
            ? route('user.catalogue.store')
            : route('user.catalogue.update', $userCatalogue->id);
@endphp

<form action="{{ $url }}" method="post" class="box">
    @csrf
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-5">
                <div class="panel-head">
                    <div class="panel-title">Thông tin chung của nhóm người dùng</div>
                    <div class="panel-description">
                        <p>Nhập thông tin chung của của nhóm người dùng</p>
                        <p>Lưu ý: Những trường đánh dấu <span class="text-danger">(*)</span> là bắt buộc</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-7">
                <div class="ibox">
                    <div class="ibox-content">
                        <div class="row mb15">
                            <div class="col-lg-6">
                                <div class="form-row">
                                    <label for="" class="control-label">Tên nhóm <span
                                            class="text-danger">(*)</span></label>
                                    <input type="text" name="name"
                                        value="{{ old('name', isset($userCatalogue) ? $userCatalogue->name : '') }}"
                                        placeholder="" autocomplete="off" class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-row">
                                    <label for="" class="control-label">Ghi chú </label>
                                    <input type="text" name="description"
                                        value="{{ old('description', isset($userCatalogue) ? $userCatalogue->description : '') }}"
                                        placeholder="" autocomplete="off" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <hr>

        <div class="text-right mb15">
            @if ($config['method'] == 'create')
                <button class="btn btn-primary" name="send" value="send" type="submit">Thêm người
                    dùng</button>
            @else
                <button class="btn btn-primary" name="send" value="send" type="submit">Lưu thông tin
                </button>
            @endif
        </div>
    </div>
</form>
