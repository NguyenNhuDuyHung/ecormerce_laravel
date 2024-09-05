@include('backend.dashboard.components.breadcrumb', ['title' => $config['seo']['index']['title']])
<div class="row mt-20">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>{{ $config['seo']['index']['tableHeading'] }}</h5>
                @include('backend.dashboard.components.toolbox', ['model' => ${Module} ?? null])
            </div>
            <div class="ibox-content">
                @include('backend.{view}.components.filter')
                @include('backend.{view}.components.table')
            </div>
        </div>
    </div>
</div>
