<div class="pagetitle">
    <h1>@yield('title')</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
            @hasSection('breadcrumbs')
                @yield('breadcrumbs')
            @else
                <li class="breadcrumb-item active">@yield('title')</li>
            @endif
        </ol>
    </nav>
</div><!-- End Page Title -->