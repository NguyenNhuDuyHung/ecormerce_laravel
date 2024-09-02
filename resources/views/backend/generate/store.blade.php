@include('backend.dashboard.components.breadcrum', ['title' => $config['seo'][$config['method']]['title']])

@if (isset($errors) && $errors->any())
<<<<<<< HEAD
<div class="alert alert-danger">
    <ul>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
=======
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
>>>>>>> language
@endif

@php
$url = $config['method'] == 'create' ? route('generate.store') : route('generate.update', $generate->id);
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
                            <div class="col-lg-12 mb-20">
                                <div class="form-row">
                                    <label for="" class="control-label">Tên Module <span
                                            class="text-danger">(*)</span></label>
                                    <input type="text" name="name"
                                        value="{{ old('name', isset($generate) ? $generate->name : '') }}"
                                        placeholder="" autocomplete="off" class="form-control">
                                </div>
                            </div>

                            <div class="col-lg-12 mb-20">
                                <div class="form-row">
                                    <label for="" class="control-label">Loại Module<span
                                            class="text-danger">(*)</span></label>
                                    <select name="module_type" id="" class="form-control setupSelect2">
                                        <option value="0">Chọn loại module</option>
                                        <option value="1">Module danh mục</option>
                                        <option value="2">Module chi tiết</option>
                                        <option value="3">Module khác</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-12 mb-20">
                                <div class="form-row">
                                    <label for="" class="control-label">Schema <span
                                            class="text-danger">(*)</span></label>
                                    <textarea type="text" name="schema"
                                        value="{{ old('schema', isset($generate) ? $generate->schema : '') }}"
                                        placeholder="" autocomplete="off" class="form-control" rows="20"></textarea>
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
<<<<<<< HEAD
            <button class="btn btn-primary" name="send" value="send" type="submit">Thêm</button>
            @else
            <button class="btn btn-primary" name="send" value="send" type="submit">Lưu thông tin
            </button>
=======
                <button class="btn btn-primary" name="send" value="send" type="submit">Thêm</button>
            @else
                <button class="btn btn-primary" name="send" value="send" type="submit">Lưu thông tin
                </button>
>>>>>>> language
            @endif
        </div>
    </div>
</form>