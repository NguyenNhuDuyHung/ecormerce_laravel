<div class="row">
    <div class="col-lg-5">
        <div class="panel-head">
            <div class="panel-title">Vị trí Menu</div>
            <div class="panel-description">
                <p>Website có các vị trí hiển thị cho từng menu</p>
                <p>Lựa chọn vị trí mà bạn muốn hiển thị</p>
            </div>
        </div>
    </div>

    <div class="col-lg-7">
        <div class="ibox">
            <div class="ibox-content">
                <div class="row">
                    <div class="col-lg-12 mb15">
                        <div class="uk-flex uk-flex-middle uk-flex-space-between">
                            <label for="">Chọn vị trí hiển thị</label>
                            <button data-toggle="modal" data-target="#createMenuCatalogue" type="button" name=""
                                class="createMenuCatalogue btn btn-danger">Tạo vị trí
                                hiển thị</button>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <select class="setupSelect2" name="menu_catalogue_id" id="" style="width: 100%;">
                            <option value="0">[Chọn vị trí hiển thị]</option>
                            @if(count($menuCatalogues))
                                @foreach($menuCatalogues as $key => $menuCatalogue)
                                    <option value="{{ $menuCatalogue->id }}">{{ $menuCatalogue->name }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>

                    <div class="col-lg-6">
                        <select class="setupSelect2" name="menu_catalogue_id" id="" style="width: 100%;">
                            <option value="none">[Chọn kiểu menu]</option>
                            @foreach(__('module.type') as $key => $value)
                                <option value="{{ $key }}">{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>