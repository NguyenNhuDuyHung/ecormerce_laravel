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
    $url = $config['method'] == 'create' ? route('user.store') : route('user.update', $user->id);
@endphp

<form action="{{ $url }}" method="post" class="box">
    @csrf
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-5">
                <div class="panel-head">
                    <div class="panel-title">Thông tin chung</div>
                    <div class="panel-description">
                        <p>Nhập thông tin chung của người sử dụng</p>
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
                                    <label for="" class="control-label">Email <span
                                            class="text-danger">(*)</span></label>
                                    <input type="text" name="email"
                                        value="{{ old('email', isset($user) ? $user->email : '') }}" placeholder=""
                                        autocomplete="off" class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-row">
                                    <label for="" class="control-label">Họ Tên <span
                                            class="text-danger">(*)</span></label>
                                    <input type="text" name="name"
                                        value="{{ old('name', isset($user) ? $user->name : '') }}" placeholder=""
                                        autocomplete="off" class="form-control">
                                </div>
                            </div>
                        </div>

                        @php
                            $userCatalogue = ['[Chọn nhóm thành viên]', 'Quản trị viên', 'Cộng tác viên'];
                            $userCatalogueSelected = old(
                                'user_catalogue_id',
                                isset($user) ? $user->user_catalogue_id : '',
                            );
                        @endphp

                        <div class="row mb15">
                            <div class="col-lg-6">
                                <div class="form-row">
                                    <label for="" class="control-label">Nhóm Thành Viên <span
                                            class="text-danger">(*)</span></label>
                                    <select name="user_catalogue_id" class="form-control">
                                        @foreach ($userCatalogue as $key => $item)
                                            <option @if ($userCatalogueSelected) selected @endif
                                                value="{{ $key }}">{{ $item }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-row">
                                    <label for="" class="control-label">Số điện thoại <span
                                            class="text-danger">(*)</span></label>
                                    <input type="text" name="phone"
                                        value="{{ old('phone', isset($user) ? $user->phone : '') }}" placeholder=""
                                        autocomplete="off" class="form-control">
                                </div>
                            </div>
                        </div>

                        @if ($config['method'] == 'create')
                            <div class="row mb15">
                                <div class="col-lg-6">
                                    <div class="form-row">
                                        <label for="" class="control-label">Mật khẩu <span
                                                class="text-danger">(*)</span></label>
                                        <input type="password" name="password" value="" placeholder=""
                                            autocomplete="off" class="form-control">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-row">
                                        <label for="" class="control-label">Nhập lại mật khẩu <span
                                                class="text-danger">(*)</span></label>
                                        <input type="password" name="re_password" value="" placeholder=""
                                            autocomplete="off" class="form-control">
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="row mb15">
                            <div class="col-lg-6">
                                <div class="form-row">
                                    <label for="" class="control-label">Ngày sinh </label>
                                    <input type="date" name="birthday"
                                        value="{{ old('birthday', isset($user) && $user->birthday ? date('Y-m-d', strtotime($user->birthday)) : '') }}"
                                        placeholder="" autocomplete="off" class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-row">
                                    <label for="" class="control-label">Ảnh đại diện</label>
                                    <input type="text" name="image" placeholder=""
                                        value="{{ old('image', isset($user) ? $user->image : '') }}" autocomplete="off"
                                        class="form-control upload-image" data-type="Images">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <hr>

        <div class="row">
            <div class="col-lg-5">
                <div class="panel-head">
                    <div class="panel-title">Thông tin liên hệ</div>
                    <div class="panel-description">
                        <p>Nhập thông tin liên hệ của người sử dụng</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-7">
                <div class="ibox">
                    <div class="ibox-content">
                        <div class="row mb15">
                            <div class="col-lg-6">
                                <div class="form-row">
                                    <label for="" class="control-label">Thành Phố </label>
                                    <select name="province_id" class="form-control setupSelect2 province location"
                                        data-target="districts">
                                        <option value="0">[Chọn Thành Phố]</option>
                                        @if (isset($provinces))
                                            @foreach ($provinces as $province)
                                                <option @if (old('province_id') == $province->code) selected @endif
                                                    value="{{ $province->code }}">{{ $province->name }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-row">
                                    <label for="" class="control-label">Quận/Huyện </label>
                                    <select name="district_id" class="form-control setupSelect2 districts location"
                                        data-target="wards">
                                        <option value="0">[Chọn Quận/Huyện]</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row mb15">
                            <div class="col-lg-6">
                                <div class="form-row">
                                    <label for="" class="control-label">Phường/Xã </label>
                                    <select name="ward_id" class="form-control setupSelect2 wards">
                                        <option value="0">[Chọn Phường/Xã]</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-row">
                                    <label for="" class="control-label">Địa chỉ</label>
                                    <input type="text" name="address"
                                        value="{{ old('address', isset($user) ? $user->address : '') }}"
                                        placeholder="" autocomplete="off" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="row mb15">
                            <div class="col-lg-12">
                                <div class="form-row">
                                    <label for="" class="control-label">Ghi chú </label>
                                    <input type="text" name="description"
                                        value="{{ old('description', isset($user) ? $user->description : '') }}"
                                        placeholder="" autocomplete="off" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

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

<script>
    var province_id = '{{ isset($user) && $user->province_id ? $user->province_id : old('province_id') }}';
    var district_id = '{{ isset($user) && $user->district_id ? $user->district_id : old('district_id') }}';
    var ward_id = '{{ isset($user) && $user->ward_id ? $user->ward_id : old('ward_id') }}';
</script>
