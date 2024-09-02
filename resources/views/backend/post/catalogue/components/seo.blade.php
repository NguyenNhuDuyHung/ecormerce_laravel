<div class="ibox">
    <div class="ibox-title">
        <h5>Cấu hình SEO</h5>
    </div>

    <div class="ibox-content">
        <div class="seo-container">
            <div class="meta-title">
                {{ old('meta_title', $postCatalogue->meta_title ?? '') ? old('meta_title', $postCatalogue->meta_title ?? '') : 'Bạn chưa có tiêu đề SEO' }}
            </div>
            <div class="canonical">
                {{ old('canonical', isset($postCatalogue) ? $postCatalogue->canonical : '') ? config('app.url') . old('canonical') . config('apps.general.suffix') : 'http://duong-dan-cua-ban.html' }}
            </div>
            <div class="meta-description">
                {{ old('meta_description', $postCatalogue->meta_description ?? '') ? old('meta_description', $postCatalogue->meta_description ?? '') : 'Bạn chưa có mô tả SEO' }}
            </div>
        </div>

        <div class="seo-wrapper">
            <div class="row mb15">
                <div class="col-lg-12">
                    <div class="form-row">
                        <label for="" class="control-label text-left">
                            <div class="uk-flex uk-flex-middel uk-flex-space-between">
                                <span>Tiêu đề SEO</span>
                                <span class="count_meta-title">0 ký tự</span>
                            </div>
                        </label>
                        <input type="text" name="meta_title"
                            value="{{ old('meta_title', isset($postCatalogue) ? $postCatalogue->meta_title : '') }}"
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
                <textarea type="text" name="meta_description" autocomplete="off" class="form-control">{{ old('meta_description', isset($postCatalogue) ? $postCatalogue->meta_description : '') }}</textarea>
            </div>

            <div class="row mb15">
                <div class="col-lg-12">
                    <div class="form-row">
                        <label for="" class="control-label text-left">
                            <div class="uk-flex uk-flex-middel uk-flex-space-between">
                                <span>Đường dẫn <span class="text-danger">(*)</span></span>
                            </div>
                        </label>
                        <div class="input-wrapper">
                            <input type="text" name="canonical"
                                value="{{ old('canonical', isset($postCatalogue) ? $postCatalogue->canonical : '') }}"
                                placeholder="" autocomplete="off" class="form-control seo-canonical" />
                            <span class="baseUrl">{{ config('app.url') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
