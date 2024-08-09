<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <th>
                <input type="checkbox" value="" id="checkAll" class="input-checkbox">
            </th>
            <th>Tên nhóm người dùng</th>
            <th class="text-center">Tình trạng</th>
            <th class="text-center">Thao tác</th>
        </tr>
    </thead>
    <tbody>
        @if (isset($userCatalogues) && is_object($userCatalogues))
            @foreach ($userCatalogues as $userCatalogue)
                <tr>
                    <td>
                        <input type="checkbox" value="{{ $userCatalogue->id }}" class="input-checkbox checkBoxItem">
                    </td>
                    <td>
                        {{ $userCatalogue->name }}
                    </td>
                    <td class="text-center js-switch-{{ $userCatalogue->id }}">
                        <input type="checkbox" value="{{ $userCatalogue->publish_at }}" class="js-switch status"
                            data-field="publish" data-model="User" data-modelId="{{ $userCatalogue->id }}"
                            {{ $userCatalogue->publish == 1 ? 'checked' : '' }} />
                    </td>
                    <td class="text-center" style="display: flex; justify-content: center; gap: 5px;">
                        <a href="{{ route('user.edit', $userCatalogue->id) }}" class="btn btn-success"><i
                                class="fa fa-edit"></i></a>
                        <form action="{{ route('user.delete', $userCatalogue->id) }}" method="get">
                            <button class="btn btn-danger"><i class="fa fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
            @endforeach
        @endif
    </tbody>
</table>

{{ $userCatalogues->links('pagination::bootstrap-4') }}
