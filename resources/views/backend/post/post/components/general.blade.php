<div class="row">
    <div class="col-lg-12">
        <div class="form-row">
            <label for="" class="control-label text-left">{{ __('message.title') }} <span
                    class="text-danger">(*)</span></label>
            <input type="text" name="name"
                value="{{ old('name', isset($post) ? $post->name : '') }}" placeholder=""
                autocomplete="off" class="form-control" />
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="form-row">
            <label for="" class="control-label text-left">{{ __('message.description') }} </label>
            <textarea type="text" name="description" placeholder="" autocomplete="off" class="form-control ck-editor"
                id="description" data-height="150">{{ old('description', isset($post) ? $post->description : '') }}</textarea>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="form-row">
            <div class="uk-flex uk-flex-middle uk-flex-space-between">
                <label for="" class="control-label text-left">{{ __('message.content') }}</label>
                <a href="" class="multipleUploadImageCkeditor" data-target='content'>{{ __('message.upload') }}</a>
            </div>

            <textarea type="text" name="content" placeholder="" autocomplete="off" class="form-control ck-editor" id="content"
                data-height="500">{{ old('content', isset($post) ? $post->content : '') }}</textarea>
        </div>
    </div>
</div>
