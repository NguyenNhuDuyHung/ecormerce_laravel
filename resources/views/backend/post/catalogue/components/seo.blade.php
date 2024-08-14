<div class="ibox">
    <div class="ibox-title">
        <h5>Cấu hình SEO</h5>
    </div>

    <div class="ibox-content">
        <div class="seo-container">
            <div class="meta-title">
                Tự học laravel
            </div>
            <div class="canonical">
                http://laravel.com/tu-hoc-laravel.html
            </div>
            <div class="meta-description">
                Nội dung đơn giản dễ học, nếu kiên trì có thể bạn sẽ hoàn thành trong 4 ngày học, học
                những phần cơ bản nhất để có được kiến thức ban đầu về Laravel.
            </div>
        </div>

        <div class="seo-wrapper">
            <div class="row mb15">
                <div class="col-lg-12">
                    <div class="form-row">
                        <label for="" class="control-label text-left">
                            <div class="uk-flex uk-flex-middel uk-flex-space-between">
                                <span>Mô tả SEO</span>
                                <span class="count_meta-title">0 ký tự</span>
                            </div>
                        </label>
                        <input type="text" name="meta_description"
                            value="{{ old('meta_description', isset($postCatalogue) ? $postCatalogue->meta_description : '') }}"
                            placeholder="" autocomplete="off" class="form-control" />
                    </div>
                </div>
            </div>
            <div class="row mb15">
                <div class="col-lg-12">
                    <div class="form-row">
                        <label for="" class="control-label text-left">
                            <div class="uk-flex uk-flex-middel uk-flex-space-between">
                                <span>Từ khóa SEO</span>
                            </div>
                        </label>
                        <input type="text" name="meta_keyword"
                            value="{{ old('meta_keyword', isset($postCatalogue) ? $postCatalogue->meta_keyword : '') }}"
                            placeholder="" autocomplete="off" class="form-control" />
                    </div>
                </div>
            </div>
            <div class="form-row">
                <label for="" class="control-label text-left">
                    <div class="uk-flex uk-flex-middel uk-flex-space-between">
                        <span>Mô tả SEO</span>
                        <span class="count_meta-title">0 ký tự</span>
                    </div>
                </label>
                <textarea type="text" name="meta_description"
                    value="{{ old('meta_description', isset($postCatalogue) ? $postCatalogue->meta_description : '') }}" placeholder=""
                    autocomplete="off" class="form-control"></textarea>
            </div>

            <div class="row mb15">
                <div class="col-lg-12">
                    <div class="form-row">
                        <label for="" class="control-label text-left">
                            <div class="uk-flex uk-flex-middel uk-flex-space-between">
                                <span>Đường dẫn</span>
                            </div>
                        </label>
                        <input type="text" name="canonical"
                            value="{{ old('canonical', isset($postCatalogue) ? $postCatalogue->canonical : '') }}"
                            placeholder="" autocomplete="off" class="form-control" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>