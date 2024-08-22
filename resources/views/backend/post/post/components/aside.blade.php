<div class="ibox">
    <div class="ibox-content">
        <div class="row mb15">
            <div class="col-lg-12">
                <div class="form-row">
                    <label for="" class="control-label">{{ __('message.parent') }} <span
                            class="text-danger">(*)</span></label>
                    <span class="text-danger notice">{{ __('message.parentNotice') }}</span>
                    <select name="post_catalogue_id" class="form-control setupSelect2" id="">
                        @foreach ($dropdown as $key => $value)
                            <option
                                {{ $key == old('post_catalogue_id', isset($post) ? $post->post_catalogue_id : '') ? 'selected' : '' }}
                                value="{{ $key }}">{{ $value }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        @php
            $catalogue = [];
            if (isset($post)) {
                foreach ($post->post_catalogues as $key => $value) {
                    $catalogue[] = $value->id;
                }
            }
        @endphp

        <div class="row mb15">
            <div class="col-lg-12">
                <div class="form-row">
                    <label class="control-label">Danh mục phụ</label>
                    <select multiple name="catalogue[]" class="form-control setupSelect2" id="">
                        @foreach ($dropdown as $key => $value)
                            <option @if (is_array(old('catalogue', isset($catalogue) && count($catalogue) ? $catalogue : [])) &&
                                    isset($post) &&
                                    $key !== $post->post_catalogue_id &&
                                    in_array($key, old('catalogue', isset($catalogue) ? $catalogue : []))) selected @endif value="{{ $key }}">
                                {{ $value }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="ibox">
    <div class="ibox-title">
        <h5>{{ __('message.image') }} </h5>
    </div>
    <div class="ibox-content">
        <div class="row mb15">
            <div class="col-lg-12">
                <div class="form-row">
                    <span class="image img-cover img-target">
                        <img src="{{ old('image', isset($post) ? $post->image : 'backend/img/not_found.jpg') }}"
                            alt="">
                    </span>
                    <input type="hidden" name="image"
                        value="{{ old('image', isset($post) ? $post->image : 'backend/img/not_found.jpg') }}"
                        placeholder="" autocomplete="off" class="form-control upload-image">
                </div>
            </div>
        </div>
    </div>
</div>
<div class="ibox">
    <div class="ibox-title">
        <h5>{{ __('message.advance') }}</h5>
    </div>
    <div class="ibox-content">
        <div class="row mb15">
            <div class="col-lg-12">
                <div class="form-row">
                    <div class="mb15">
                        <select name="publish" class="form-control setupSelect2" id="">
                            @foreach (__('message.publish') as $key => $value)
                                <option
                                    {{ old('pubish', isset($post) ? $post->publish : '') == $key ? 'selected' : '' }}
                                    value="{{ $key }}">
                                    {{ $value }}</option>
                            @endforeach
                        </select>
                    </div>

                    <select name="follow" class="form-control setupSelect2" id="">
                        @foreach (__('message.follow') as $key => $value)
                            <option {{ $key == old('follow', isset($post) ? $post->follow : '') ? 'selected' : '' }}
                                value="{{ $key }}">
                                {{ $value }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>
</div>
