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
<form action="{{ route('attribute.catalogue.destroy', $attributeCatalogue->id) }}" method="post" class="box">
    @include('backend.dashboard.components.destroy', ['model' => ($attributeCatalogue) ?? null])
</form>
