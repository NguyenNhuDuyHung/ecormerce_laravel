<div class="row mb15">
    <div class="col-lg-12">
        <div class="form-row">
            <label for="" class="control-label text-left">Tiêu đề nhóm bài viết <span
                    class="text-danger">(*)</span></label>
            <input type="text" name="name"
                value="{{ old('name', isset($postCatalogue) ? $postCatalogue->name : '') }}" placeholder=""
                autocomplete="off" class="form-control" />
        </div>
    </div>
</div>

<div class="row mb15">
    <div class="col-lg-12">
        <div class="form-row">
            <label for="" class="control-label text-left">Mô tả ngắn </label>
            <textarea type="text" name="description" placeholder="" autocomplete="off" class="form-control ck-editor"
                id="description" data-height="150">{{ old('description', isset($postCatalogue) ? $postCatalogue->description : '') }}</textarea>
        </div>
    </div>
</div>

<div class="row mb15">
    <div class="col-lg-12">
        <div class="form-row">
            <label for="" class="control-label text-left">Nội dung</label>
            <textarea type="text" name="content" placeholder="" autocomplete="off" class="form-control ck-editor" id="content"
                data-height="500">{{ old('content', isset($postCatalogue) ? $postCatalogue->content : '') }}</textarea>
        </div>
    </div>
</div>
