<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <th>
                <input type="checkbox" value="" id="checkAll" class="input-checkbox">
            </th>
            <th>T{{__('message.tableName')}}</th>
            <th style="width: 60px">Vị trí</th>
            <th class="text-center">{{__('message.tableStatus')}}</th>
            <th class="text-center">{{__('message.tableAction')}}</th>
        </tr>
    </thead>
    <tbody>
        @if (isset($posts) && is_object($posts))
            @foreach ($posts as $post)
                <tr id="{{ $post->id }}">
                    <td>
                        <input type="checkbox" value="{{ $post->id }}" class="input-checkbox checkBoxItem">
                    </td>
                    <td>
                        <div class="uk-flex uk-flex-middle">
                            <div class="image mr5">
                                <div class="img-cover">
                                    <img src="{{ $post->image }}" alt="">
                                </div>
                            </div>
                            <div class="main-info">
                                <div class="name">
                                    <span class="maintitle">{{ $post->name }}</span>
                                </div>
                                <div class="catalogue">
                                    <span class="text-danger">{{__('message.tableGroup')}}</span>
                                    @foreach ($post->post_catalogues as $value)
                                        @foreach ($value->post_catalogue_language as $catalogue)
                                            <a href="{{ route('post.index', ['post_catalogue_id' => $value->id]) }}"
                                                title="">{{ $catalogue->name }}</a>
                                        @endforeach
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <input disabled type="text" value="{{ $post->order }}" name="order"
                            class="form-control sort-order" data-id="{{ $post->id }}"
                            data-model="{{ $config['model'] }}" />
                    </td>
                    <td class="text-center js-switch-{{ $post->id }}">
                        <input type="checkbox" value="{{ $post->publish }}" class="js-switch status "
                            data-field="publish" data-model="{{ $config['model'] }}"
                            {{ $post->publish == 2 ? 'checked' : '' }} data-modelId="{{ $post->id }}" />
                    </td>
                    <td class="text-center">
                        <a href="{{ route('post.edit', $post->id) }}" class="btn btn-success"><i
                                class="fa fa-edit"></i></a>
                        <a href="{{ route('post.delete', $post->id) }}" class="btn btn-danger"><i
                                class="fa fa-trash"></i></a>
                    </td>
                </tr>
            @endforeach
        @endif
    </tbody>
</table>
{{ $posts->links('pagination::bootstrap-4') }}
