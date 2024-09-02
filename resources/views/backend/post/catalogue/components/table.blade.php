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
        @if (isset($postCatalogues) && is_object($postCatalogues))
            @foreach ($postCatalogues as $postCatalogue)
                <tr>
                    <td>
                        <input type="checkbox" value="{{ $postCatalogue->id }}" class="input-checkbox checkBoxItem">
                    </td>
                    <td>
                        {{ str_repeat('|----', $postCatalogue->level > 0 ? $postCatalogue->level - 1 : 0) . $postCatalogue->name }}
                    </td>

                    @include('backend.dashboard.components.languageTd', ['model' => $postCatalogue, 'modeling' => 'PostCatalogue'])

                    <td class="text-center js-switch-{{ $postCatalogue->id }}">
                        <input type="checkbox" value="{{ $postCatalogue->publish }}" class="js-switch status "
                            data-field="publish" data-model="PostCatalogue"
                            {{ $postCatalogue->publish == 2 ? 'checked' : '' }}
                            data-modelId="{{ $postCatalogue->id }}" />
                    </td>
                    <td class="text-center">
                        <a href="{{ route('post.catalogue.edit', $postCatalogue->id) }}" class="btn btn-success"><i
                                class="fa fa-edit"></i></a>
                        <a href="{{ route('post.catalogue.delete', $postCatalogue->id) }}" class="btn btn-danger"><i
                                class="fa fa-trash"></i></a>
                    </td>
                </tr>
            @endforeach
        @endif
    </tbody>
</table>
{{ $postCatalogues->links('pagination::bootstrap-4') }}
