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
    $url = $config['method'] == 'create' ? route('permission.store') : route('permission.update', $permission->id);
@endphp

<form action="{{ $url }}" method="post" class="box">
    @csrf
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-5">
                <div class="panel-head">
                    <div class="panel-title">Thông tin chung</div>
                    <div class="panel-description">
                        <p>Nhập thông tin chung</p>
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
                                    <label for="" class="control-label">Tiêu đề <span
                                            class="text-danger">(*)</span></label>
                                    <input type="text" name="name"
                                        value="{{ old('name', isset($permission) ? $permission->name : '') }}"
                                        placeholder="" autocomplete="off" class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-row">
                                    <label for="" class="control-label">Canonical </label>
                                    <input type="text" name="canonical"
                                        value="{{ old('canonical', isset($permission) ? $permission->canonical : '') }}"
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
                <button class="btn btn-primary" name="send" value="send" type="submit">Thêm</button>
            @else
                <button class="btn btn-primary" name="send" value="send" type="submit">Lưu thông tin
                </button>
            @endif
        </div>
    </div>
</form>
