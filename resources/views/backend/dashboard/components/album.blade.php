<div class="ibox">
    <div class="ibox-title">
        <div class="uk-flex uk-flex-middle uk-flex-space-between">
            <h5>Album ảnh</h5>

            <div class="upload-album">
                <a href="" class="upload-picture">Chọn hình ảnh</a>
            </div>
        </div>
    </div>

    <div class="ibox-content">
        <div class="row">
            <div class="col-lg-12">
                <div class="click-to-upload {{ isset($album) ? ($album ? 'hidden' : '') : '' }}">
                    <div class="icon">
                        <a href="" class="upload-picture">
                            <svg xmlns="http://www.w3.org/2000/svg" height="100px" viewBox="0 0 24 24" width="100px"
                                fill="#000000">
                                <path d="M0 0h24v24H0z" fill="none" />
                                <path d="M21 6h-3.17L16 4h-6v2h5.12l1.83
                                    2H21v12H5v-9H3v9c0 1.1.9 2 2 2h16c1.1
                                    0 2-.9 2-2V8c0-1.1-.9-2-2-2zM8 14c0
                                    2.76 2.24 5 5 5s5-2.24 5-5-2.24-5-5-5-5
                                    2.24-5 5zm5-3c1.65 0 3 1.35 3 3s-1.35
                                    3-3 3-3-1.35-3-3 1.35-3 3-3zM5
                                    6h3V4H5V1H3v3H0v2h3v3h2z" />
                            </svg>
                        </a>
                    </div>
                    <div class="small-text">Sử dụng nút chọn hình ảnh hoặc ấn vào đây để thêm hình ảnh</div>
                </div>

                <div class="upload-list">
                    <div class="row">
                        <ul id="sorttable" class="clearfix data-album sort ui-sorttable" style="padding: 0 14px;">
                            @if (isset($album))
                                @foreach ($album as $key => $value)
                                    <li class="ui-state-default">
                                        <div class="thumb">
                                            <span class="image img-scaledown">
                                                <img src="{{ $value }}" alt="{{ $value }}">
                                                <input type="hidden" name="album[]" value="{{ $value }}">
                                            </span>
                                            <button class="delete-image">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </div>
                                    </li>
                                @endforeach
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
