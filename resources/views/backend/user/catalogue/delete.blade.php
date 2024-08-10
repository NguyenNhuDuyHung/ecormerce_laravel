@include('backend.dashboard.components.breadcrum', ['title' => $config['seo']['delete']['title']])
<form action="{{route('user.catalogue.destroy', $userCatalogue->id)}}" method="post" class="box">
    @csrf
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-5">
                <div class="panel-head">
                    <div class="panel-title">Thông tin chung</div>
                    <div class="panel-description">
                        <p>Lưu ý: Không thể khôi phục khi xóa. Hãy chắc chắn bạn muốn thực hiện chức năng
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
                                    <label for="" class="control-label">Tên nhóm <span
                                            class="text-danger">(*)</span></label>
                                    <input type="text" name="name"
                                        value="{{ old('name', isset($userCatalogue) ? $userCatalogue->name : '') }}" placeholder=""
                                        autocomplete="off" class="form-control" readonly>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-row">
                                    <label for="" class="control-label">Ghi chú <span
                                            class="text-danger">(*)</span></label>
                                    <input type="text" name="description"
                                        value="{{ old('description', isset($userCatalogue) ? $userCatalogue->description : '') }}" placeholder=""
                                        autocomplete="off" class="form-control" readonly>
                                </div>
                            </div>
                        </div>

                        @php
                            $userCatalogueCatalogue = ['[Chọn nhóm thành viên]', 'Quản trị viên', 'Cộng tác viên'];
                            $userCatalogueCatalogueSelected = old(
                                'user_catalogue_id',
                                isset($userCatalogue) ? $userCatalogue->user_catalogue_id : '',
                            );
                        @endphp
                    </div>
                </div>
            </div>
        </div>



        <div class="text-right mb15">
            <button class="btn btn-danger" name="send" value="send" type="submit">Xóa dữ liệu</button>
        </div>
    </div>
</form>
