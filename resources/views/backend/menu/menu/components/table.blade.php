<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <th>
                <input type="checkbox" value="" id="checkAll" class="input-checkbox">
            </th>
            <th>Tên Menu</th>
            <th>Từ khóa</th>
            <th>Người tạo</th>
            <th>Ngày tạo</th>
            <th class="text-center">Tình trạng</th>
            <th class="text-center">Thao tác</th>
        </tr>
    </thead>
    <tbody>
        @if (isset($menus) && is_object($menus))
            @foreach ($menus as $menu)
                <tr>
                    <td>
                        <input type="checkbox" value="{{ $menu->id }}" class="input-checkbox checkBoxItem">
                    </td>
                    <td>
                        {{ $menu->name }}
                    </td>
                    <td>
                        {{ $menu->email }}
                    </td>
                    <td>
                        {{ $menu->phone }}
                    </td>
                    <td>
                        {{ $menu->address }}
                    </td>
                    <td>
                        {{ $menu->user_catalogues->name }}
                    </td>
                    <td class="text-center js-switch-{{ $menu->id }}">
                        <input type="checkbox" value="{{ $menu->publish }}" class="js-switch status"
                            data-field="publish" data-model="User" data-modelId="{{ $menu->id }}"
                            {{ $menu->publish == 2 ? 'checked' : '' }} />
                    </td>
                    <td class="text-center" style="display: flex; justify-content: center; gap: 5px;">
                        <a href="{{ route('user.edit', $menu->id) }}" class="btn btn-success"><i
                                class="fa fa-edit"></i></a>
                        <form action="{{ route('user.delete', $menu->id) }}" method="get">
                            <button class="btn btn-danger"><i class="fa fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
            @endforeach
        @endif
    </tbody>
</table>

