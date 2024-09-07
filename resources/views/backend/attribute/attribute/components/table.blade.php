<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <th>
                <input type="checkbox" value="" id="checkAll" class="input-checkbox">
            </th>
            <th>{{__('message.tableName')}}</th>
            @include('backend.dashboard.components.languageTh')
            <th class="text-center">{{__('message.tableStatus')}}</th>
            <th class="text-center">{{__('message.tableAction')}}</th>
        </tr>
    </thead>
    <tbody>
        @if (isset($attributes) && is_object($attributes))
            @foreach ($attributes as $attribute)
                <tr id="{{ $attribute->id }}">
                    <td>
                        <input type="checkbox" value="{{ $attribute->id }}" class="input-checkbox checkBoxItem">
                    </td>
                    <td>
                        <div class="uk-flex uk-flex-middle">
                            <div class="main-info">
                                <div class="name">
                                    <span class="maintitle">{{ $attribute->name }}</span>
                                </div>
                                <div class="catalogue">
                                    <span class="text-danger">{{__('message.tableGroup')}}</span>
                                    @foreach ($attribute->attribute_catalogues as $value)
                                        @foreach ($value->attribute_catalogue_language as $catalogue)
                                            <a href="{{ route('attribute.index', ['attribute_catalogue_id' => $value->id]) }}"
                                                title="">{{ $catalogue->name }}</a>
                                        @endforeach
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </td>
                    @include('backend.dashboard.components.languageTd', ['model' => $attribute, 'modeling' => 'Attribute'])

                    <td class="text-center js-switch-{{ $attribute->id }}">
                        <input type="checkbox" value="{{ $attribute->publish }}" class="js-switch status "
                            data-field="publish" data-model="{{ $config['model'] }}"
                            {{ $attribute->publish == 2 ? 'checked' : '' }} data-modelId="{{ $attribute->id }}" />
                    </td>
                    <td class="text-center">
                        <a href="{{ route('attribute.edit', $attribute->id) }}" class="btn btn-success"><i
                                class="fa fa-edit"></i></a>
                        <a href="{{ route('attribute.delete', $attribute->id) }}" class="btn btn-danger"><i
                                class="fa fa-trash"></i></a>
                    </td>
                </tr>
            @endforeach
        @endif
    </tbody>
</table>
{{ $attributes->links('pagination::bootstrap-4') }}
