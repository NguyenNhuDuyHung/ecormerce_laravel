
<div class="ibox w">
    <div class="ibox-title">
        <h5>{{ __('message.image') }}</h5>
    </div>
    <div class="ibox-content">
        <div class="row">
            <div class="col-lg-12">
                <div class="form-row">
                    <span class="image img-cover image-target"><img src="{{ (old('image', ($model->image) ?? '' ) ? old('image', ($model->image) ?? '')   :  'backend/img/not_found.jpg') }}" alt=""></span>
                    <input type="hidden" name="image" value="{{ old('image', ($model->image) ?? '' ) }}">
                </div>
            </div>
        </div>
    </div>
</div>
<div class="ibox w">
    <div class="ibox-title">
        <h5>{{ __('message.advange') }}</h5>
    </div>
    <div class="ibox-content">
        <div class="row">
            <div class="col-lg-12">
                <div class="form-row">
                    <div class="mb15">
                        <select name="publish" class="form-control setupSelect2" id="">
                            @foreach(__('message.publish') as $key => $val)
                            <option {{ 
                                $key == old('publish', (isset($model->publish)) ? $model->publish : '') ? 'selected' : '' 
                                }} value="{{ $key }}">{{ $val }}</option>
                            @endforeach
                        </select>
                    </div>
                    <select name="follow" class="form-control setupSelect2" id="">
                        @foreach(__('message.follow') as $key => $val)
                        <option {{ 
                            $key == old('follow', (isset($model->follow)) ? $model->follow : '') ? 'selected' : '' 
                            }} value="{{ $key }}">{{ $val }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>
</div>