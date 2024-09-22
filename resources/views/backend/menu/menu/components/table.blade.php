<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <th>
                <input type="checkbox" value="" id="checkAll" class="input-checkbox">
            </th>
            <th>Tên Menu</th>
            <th>Từ khóa</th>
            <th class="text-center">Tình trạng</th>
            <th class="text-center">Thao tác</th>
        </tr>
    </thead>
    <tbody>
        @if (isset($menuCatalogues) && is_object($menuCatalogues))
            @foreach ($menuCatalogues as $menuCatalogue)
                <tr>
                    <td>
                        <input type="checkbox" value="{{ $menuCatalogue->id }}" class="input-checkbox checkBoxItem">
                    </td>
                    <td>
                        {{ $menuCatalogue->name }}
                    </td>
                    <td>
                        {{ $menuCatalogue->keyword }}
                    </td>
                    <td class="text-center js-switch-{{ $menuCatalogue->id }}">
                        <input type="checkbox" value="{{ $menuCatalogue->publish }}" class="js-switch status"
                            data-field="publish" data-model="{{ $config['model'] }}" data-modelId="{{ $menuCatalogue->id }}"
                            {{ $menuCatalogue->publish == 2 ? 'checked' : '' }} />
                    </td>
                    <td class="text-center" style="display: flex; justify-content: center; gap: 5px;">
                        <a href="{{ route('menu.edit', $menuCatalogue->id) }}" class="btn btn-success"><i
                                class="fa fa-edit"></i></a>
                        <form action="{{ route('menu.delete', $menuCatalogue->id) }}" method="get">
                            <button class="btn btn-danger"><i class="fa fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
            @endforeach
        @endif
    </tbody>
</table>

