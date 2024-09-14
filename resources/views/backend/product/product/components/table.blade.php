<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <th>
                <input type="checkbox" value="" id="checkAll" class="input-checkbox">
            </th>
            <th>{{__('message.tableName')}}</th>
            @include('backend.dashboard.components.languageTh')
            <th style="width: 60px">Vị trí</th>
            <th class="text-center">{{__('message.tableStatus')}}</th>
            <th class="text-center">{{__('message.tableAction')}}</th>
        </tr>
    </thead>
    <tbody>
        @if (isset($products) && is_object($products))
            @foreach ($products as $product)
                <tr id="{{ $product->id }}">
                    <td>
                        <input type="checkbox" value="{{ $product->id }}" class="input-checkbox checkBoxItem">
                    </td>
                    <td>
                        <div class="uk-flex uk-flex-middle">
                            <div class="main-info">
                                <div class="name">
                                    <span class="maintitle">{{ $product->name }}</span>
                                </div>
                                <div class="catalogue">
                                    <span class="text-danger">{{__('message.tableGroup')}}</span>
                                    @foreach ($product->product_catalogues as $value)
                                        @foreach ($value->product_catalogue_language as $catalogue)
                                            <a href="{{ route('product.index', ['product_catalogue_id' => $value->id]) }}"
                                                title="">{{ $catalogue->name }}</a>
                                        @endforeach
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </td>
                    @include('backend.dashboard.components.languageTd', ['model' => $product, 'modeling' => 'Product'])

                    <td>
                        <input disabled type="text" value="{{ $product->order }}" name="order"
                            class="form-control sort-order" data-id="{{ $product->id }}"
                            data-model="{{ $config['model'] }}" />
                    </td>
                    <td class="text-center js-switch-{{ $product->id }}">
                        <input type="checkbox" value="{{ $product->publish }}" class="js-switch status "
                            data-field="publish" data-model="{{ $config['model'] }}"
                            {{ $product->publish == 2 ? 'checked' : '' }} data-modelId="{{ $product->id }}" />
                    </td>
                    <td class="text-center">
                        <a href="{{ route('product.edit', $product->id) }}" class="btn btn-success"><i
                                class="fa fa-edit"></i></a>
                        <a href="{{ route('product.delete', $product->id) }}" class="btn btn-danger"><i
                                class="fa fa-trash"></i></a>
                    </td>
                </tr>
            @endforeach
        @endif
    </tbody>
</table>
{{ $products->links('pagination::bootstrap-4') }}
