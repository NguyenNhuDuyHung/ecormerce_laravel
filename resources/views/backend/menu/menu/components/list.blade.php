<div class="row">
    <div class="col-lg-5">
        <div class="ibox">
            <div class="ibox-content">
                <div class="panel-group" id="accordion">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h5 class="panel-title">
                                <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne"
                                    aria-expanded="true" class="">Liên kết tự tạo</a>
                            </h5>
                        </div>
                        <div id="collapseOne" class="panel-collapse collapse in" aria-expanded="true" style="">
                            <div class="panel-body">
                                <div class="panel-title">Tạo menu</div>
                                <div class="panel-description">
                                    <p>+Cài đặt Menu mà bạn muốn hiển thị</p>
                                    <p><small class="text-danger">* Khi khởi tạo menu bạn phải chắc chắn rằng đường dẫn
                                            của menu có hoạt động. Đường dẫn trên website được khởi tạo tại các module:
                                            Bài viết, Sản phẩm, Dự án, ...</small></p>
                                    <p><small class="text-danger">* Tiêu đề và đường dẫn của menu không được bỏ
                                            trống.</small></p>
                                    <p><small class="text-danger">* Hệ thống chỉ hỗ trợ tối đa 5 cấp menu.</small></p>
                                    <a href="" style="color: #000; border-color:#c4cdd5; display:inline-block;" title=""
                                        class="btn btn-default add-menu m-b m-r right">
                                        Thêm đường dẫn
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    @foreach(__('module.model') as $key => $value)
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" data-model="{{ $key }}" data-parent="#accordion"
                                        href="#{{$key}}" class="collapsed menu-module"
                                        aria-expanded="false">{{ $value }}</a>
                                </h4>
                            </div>
                            <div id="{{$key}}" class="panel-collapse collapse {{ $key == 'PostCatalogue' ? 'in' : '' }}"
                                aria-expanded="false" style="">
                                <div class="panel-body">
                                    <input type="text" name="keyword" class="form-control search-menu"
                                        placeholder="Tìm kiếm...">

                                    <div class="menu-list mt-20">

                                        <div id="paginationMenu"></div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    @endforeach

                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-7">
        <div class="ibox">
            <div class="ibox-content">
                <div class="row mb15">
                    <div class="col-lg-4">
                        <label for="">Tên Menu</label>
                    </div>
                    <div class="col-lg-4">
                        <label for="">Đường dẫn</label>
                    </div>
                    <div class="col-lg-2">
                        <label for="">Vị trí</label>
                    </div>
                    <div class="col-lg-2 text-center">
                        <label for="">Xóa</label>
                    </div>
                </div>
                <div class="hr-line-dashed" style="margin: 10px 0;"></div>
                <div class="menu-wrapper">
                    <div
                        class="notification text-center {{is_array(old('menu')) && count(old('menu')) ? 'none' : '' }}">
                        <h4 style="font-weight: 500; font-size: 14px; color: #000;">Danh sách liên kết này chưa có bất
                            kỳ đường dẫn nào!</h4>
                        <p style="color:#555; margin-top:10px;">Hãy nhấn vào <span style="color: blue;">"Thêm đường
                                dẫn"</span> để bắt đầu thêm!</p>
                    </div>

                    @if(is_array(old('menu')) && count(old('menu')))
                        @foreach(old('menu')['name'] as $key => $value)
                            <div class="row mb-20 menu-item {{old('menu')['canonical'][$key]}}">
                                <div class="col-lg-4">
                                    <input type="text" value="{{ $value }}" class="form-control" name="menu[name][]">
                                </div>
                                <div class="col-lg-4">
                                    <input type="text" value="{{ old('menu')['canonical'][$key] }}" class="form-control"
                                        name="menu[canonical][]">
                                </div>
                                <div class="col-lg-2">
                                    <input type="text" value="{{ old('menu')['order'][$key] }}" class="form-control"
                                        name="menu[order][]">
                                </div>
                                <div class="col-lg-2">
                                    <div class="form-row text-center"><a class="delete-menu"><i class="fa fa-trash"></i></a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>