<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <th>
                <input type="checkbox" value="" id="checkAll" class="input-checkbox">
            </th>
            <th>Tên Module </th>
            <th class="text-center">Thao tác</th>
        </tr>
    </thead>
    <tbody>
        @if (isset($generates) && is_object($generates))
        @foreach ($generates as $generate)
                <tr>
                    <td>
                        <input type="checkbox" value="{{ $generate->id }}" class="input-checkbox checkBoxItem">
                    </td>
                    <td>
                    {{ $generate->name }}
                    </td>
                    <td class="text-center js-switch-{{ $generate->id }}">
                        <input type="checkbox" value="{{ $generate->publish }}" class="js-switch status "
                            data-field="publish" data-model="generate" {{ $generate->publish == 2 ? 'checked' : '' }}
                            data-modelId="{{ $generate->id }}" />
                    </td>
                    <td class="text-center">
                        <a href="{{ route('generate.edit', $generate->id) }}" class="btn btn-success"><i
                                class="fa fa-edit"></i></a>
                        <a href="{{ route('generate.delete', $generate->id) }}" class="btn btn-danger"><i
                                class="fa fa-trash"></i></a>
                    </td>
                </tr>
            @endforeach
        @endif
    </tbody>
</table>
{{ $languages->links('pagination::bootstrap-4') }}
