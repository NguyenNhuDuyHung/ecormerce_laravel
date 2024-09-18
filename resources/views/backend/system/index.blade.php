@include('backend.dashboard.components.breadcrum', ['title' => $config['seo']['index']['title']])

@if (isset($errors) && $errors->any())
<div class="alert alert-danger">
    <ul>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<form action="{{ route('system.store') }}" method="post" class="box">
    @csrf
    <div class="wrapper wrapper-content animated fadeInRight">
        @foreach($systemConfig as $key => $value)
        <div class="row">
            <div class="col-lg-5">
                <div class="panel-head">
                    <div class="panel-title">{{ $value['label'] }}</div>
                    <div class="panel-description">
                        <p>{{ $value['description'] }}</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-7">
                <div class="ibox">
                    @if(count($value['value']))
                    <div class="ibox-content">
                        @foreach($value['value'] as $keyVal => $item)
                        @php
                        $name = $key . '_' .$keyVal;
                        @endphp
                        <div class="row mb15">
                            <div class="col-lg-12">
                                <div class="form-row">
                                    <label for="" class="uk-flex uk-flex-space-between">
                                        <span>{{ $item['label'] }}</span>
                                        <span> {!! renderSystemLink($item) !!}</span>
                                    </label>
                                    @switch($item['type'])
                                    @case('text')
                                    {!! renderSystemInput($name, $systems) !!}
                                    @break
                                    @case('images')
                                    {!! renderSystemImages($name, $systems) !!}
                                    @break
                                    @case('textarea')
                                    {!! renderSystemTextArea($name, $systems) !!}
                                    @break
                                    @case('select')
                                    {!! renderSystemSelect($item, $name, $systems) !!}
                                    @break
                                    @case('editor')
                                    {!! renderSystemEditor($name, $systems) !!}
                                    @break

                                    @endswitch
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>
        </div>

        @endforeach

        <div class="text-right mb15">
            <button class="btn btn-primary" name="send" value="send" type="submit">Lưu thông tin
            </button>
        </div>
    </div>
</form>