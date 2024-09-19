<!-- Modal -->
<div class="modal fade" id="createMenuCatalogue" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <form action="" class="form create-menu-catalogue">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Thêm mới vị trí hiển thị Menu</h1>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12 mb15">
                            <label for="">Tên vị trí hiển thị</label>
                            <input type="text" class="form-control" value="" name="name" />
                            <div class="error name text-danger"></div>
                        </div>

                        <div class="col-lg-12 mb15">
                            <label for="">Từ khóa</label>
                            <input type="text" class="form-control" value="" name="keyword" />
                            <div class="error keyword text-danger"></div>
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <div class="form-error" style="margin-bottom: 20px;"></div>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                    <button type="submit" name="create" value="create" class="btn btn-primary">Lưu lại</button>
                </div>
            </div>
        </div>
    </form>
</div>