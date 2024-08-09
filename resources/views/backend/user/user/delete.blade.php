@include('backend.dashboard.components.breadcrum', ['title' => $config['seo']['delete']['title']])
<form action="{{route('user.destroy', $user->id)}}" method="post" class="box">
    @csrf
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-5">
                <div class="panel-head">
                    <div class="panel-title">Thông tin chung</div>
                    <div class="panel-description">
                        <p>Bạn đang muốn xóa người dùng có email là : {{ $user->email }}</p>
                        <p>Lưu ý: Không thể khôi phục người dùng khi xóa. Hãy chắc chắn bạn muốn thực hiện chức năng
                            này.</p>
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
                                        autocomplete="off" class="form-control" readonly>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-row">
                                    <label for="" class="control-label">Họ Tên <span
                                            class="text-danger">(*)</span></label>
                                    <input type="text" name="name"
                                        value="{{ old('name', isset($user) ? $user->name : '') }}" placeholder=""
                                        autocomplete="off" class="form-control" readonly>
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
                                    <select name="user_catalogue_id" class="form-control" disabled>
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
                                        autocomplete="off" class="form-control" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>



        <div class="text-right mb15">
            <button class="btn btn-danger" name="send" value="send" type="submit">Xóa người dùng</button>
        </div>
    </div>
</form>
