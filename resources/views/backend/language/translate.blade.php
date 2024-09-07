@include('backend.dashboard.components.breadcrum', ['title' => $config['seo']['index']['title']])
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('language.storeTranslate') }}" method="post" class="box">
    @csrf
    <input type="hidden" name="option[id]" value="{{ $option['id'] }}">
    <input type="hidden" name="option[languageId]" value="{{ $option['languageId'] }}">
    <input type="hidden" name="option[model]" value="{{ $option['model'] }}">
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-6">
                <div class="ibox">
                    <div class="ibox-title">
                        <h5>Thông tin chung</h5>
                    </div>

                    <div class="ibox-content" style="display: flex; flex-direction: column; gap: 40px">
                        @include('backend.dashboard.components.content', [
                            'model' => $object ?? null,
                            'disabled' => 1,
                        ])
                    </div>
                </div>

                @include('backend.dashboard.components.seo', ['model' => $object ?? null, 'disabled' => 1])
            </div>

            <div class="col-lg-6">
                <div class="ibox">
                    <div class="ibox-title">
                        <h5>Thông tin chung</h5>
                    </div>

                    <div class="ibox-content" style="display: flex; flex-direction: column; gap: 40px">
                        @include('backend.dashboard.components.translate', [
                            'model' => $objectTranslate ?? null,
                            'disabled' => 1,
                        ])
                    </div>
                </div>

                @include('backend.dashboard.components.seoTranslate', [
                    'model' => $objectTranslate ?? null,
                    'disabled' => 1,
                ])
            </div>
        </div>

        <hr>

        <div class="text-right mb15">
            <button class="btn btn-primary" name="send" value="send" type="submit">Lưu thông tin
            </button>
        </div>
    </div>
</form>
