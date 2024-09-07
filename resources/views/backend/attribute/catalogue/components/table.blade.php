<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <th>
                <input type="checkbox" value="" id="checkAll" class="input-checkbox">
            </th>
            <th>Tên Nhóm</th>
            @include('backend.dashboard.components.languageTh')
            <th class="text-center">{{ __('message.tableStatus') }}</th>
            <th class="text-center">{{ __('message.tableAction') }}</th>
        </tr>
    </thead>
    <tbody>
        @if (isset($attributeCatalogues) && is_object($attributeCatalogues))
            @foreach ($attributeCatalogues as $attributeCatalogue)
                <tr>
                    <td>
                        <input type="checkbox" value="{{ $attributeCatalogue->id }}" class="input-checkbox checkBoxItem">
                    </td>
                    <td>
                        {{ str_repeat('|----', $attributeCatalogue->level > 0 ? $attributeCatalogue->level - 1 : 0) . $attributeCatalogue->name }}
                    </td>

                    @include('backend.dashboard.components.languageTd', ['model' => $attributeCatalogue, 'modeling' => 'AttributeCatalogue'])

                    <td class="text-center js-switch-{{ $attributeCatalogue->id }}">
                        <input type="checkbox" value="{{ $attributeCatalogue->publish }}" class="js-switch status "
                            data-field="publish" data-model="attributeCatalogue"
                            {{ $attributeCatalogue->publish == 2 ? 'checked' : '' }}
                            data-modelId="{{ $attributeCatalogue->id }}" />
                    </td>
                    <td class="text-center">
                        <a href="{{ route('attribute.catalogue.edit', $attributeCatalogue->id) }}" class="btn btn-success"><i
                                class="fa fa-edit"></i></a>
                        <a href="{{ route('attribute.catalogue.delete', $attributeCatalogue->id) }}" class="btn btn-danger"><i
                                class="fa fa-trash"></i></a>
                    </td>
                </tr>
            @endforeach
        @endif
    </tbody>
</table>
{{ $attributeCatalogues->links('pagination::bootstrap-4') }}
