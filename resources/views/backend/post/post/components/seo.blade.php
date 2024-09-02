<div class="ibox">
    <div class="ibox-title">
        <h5>{{ __('message.seo') }}</h5>
    </div>

    <div class="ibox-content">
        <div class="seo-container">
            <div class="meta-title">
                {{ old('meta_title', $post->meta_title ?? '') ?: __('message.seoTitle') }}
            </div>
            <div class="canonical">
                {{ old('canonical', isset($post) ? $post->canonical : '')
                    ? config('app.url') . old('canonical') . config('apps.general.suffix')
                    : __('message.seoCanonical') }}
            </div>
            <div class="meta-description">
                {{ old('meta_description', $post->meta_description ?? '') ?: __('message.seoMetaDescription') }}
            </div>
        </div>

        <div class="seo-wrapper">
            <div class="row mb15">
                <div class="col-lg-12">
                    <div class="form-row">
                        <label for="" class="control-label text-left">
                            <div class="uk-flex uk-flex-middel uk-flex-space-between">
                                <span>{{ __('message.seoMetaTitle') }}</span>
                                <span class="count_meta-title">0 {{ __('message.character') }}</span>
                            </div>
                        </label>
                        <input type="text" name="meta_title"
                            value="{{ old('meta_title', isset($post) ? $post->meta_title : '') }}" placeholder=""
                            autocomplete="off" class="form-control" />
                    </div>
                </div>
            </div>
            <div class="row mb15">
                <div class="col-lg-12">
                    <div class="form-row">
                        <label for="" class="control-label text-left">
                            <div class="uk-flex uk-flex-middel uk-flex-space-between">
                                <span>{{ __('message.seoMetaKeyword') }}</span>
                            </div>
                        </label>
                        <input type="text" name="meta_keyword"
                            value="{{ old('meta_keyword', isset($post) ? $post->meta_keyword : '') }}" placeholder=""
                            autocomplete="off" class="form-control" />
                    </div>
                </div>
            </div>
            <div class="form-row">
                <label for="" class="control-label text-left">
                    <div class="uk-flex uk-flex-middel uk-flex-space-between">
                        <span>{{ __('message.seoMetaDescription') }}</span>
                        <span class="count_meta-title">0 {{ __('message.character') }}</span>
                    </div>
                </label>
                <textarea type="text" name="meta_description" autocomplete="off" class="form-control">{{ old('meta_description', isset($post) ? $post->meta_description : '') }}</textarea>
            </div>

            <div class="row mb15">
                <div class="col-lg-12">
                    <div class="form-row">
                        <label for="" class="control-label text-left">
                            <div class="uk-flex uk-flex-middel uk-flex-space-between">
                                <span>{{ __('message.canonical') }} <span class="text-danger">(*)</span></span>
                            </div>
                        </label>
                        <div class="input-wrapper">
                            <input type="text" name="canonical"
                                value="{{ old('canonical', isset($post) ? $post->canonical : '') }}" placeholder=""
                                autocomplete="off" class="form-control seo-canonical" />
                            <span class="baseUrl">{{ config('app.url') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
