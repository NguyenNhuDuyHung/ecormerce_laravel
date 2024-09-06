@include('backend.dashboard.components.breadcrum', ['title' => $config['seo']['delete']['title']])
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
<form action="{{ route('product.catalogue.destroy', $productCatalogue->id) }}" method="post" class="box">
    @csrf
    @include('backend.dashboard.components.destroy', ['model' => ($productCatalogue) ?? null])
</form>
