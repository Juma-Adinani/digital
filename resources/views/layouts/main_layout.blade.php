<!DOCTYPE html>
<html lang="en">

@include('partials._head_tag')

<body>

    <!-- ======= Header ======= -->
    @include('partials._navbar')
    <!-- End Header -->

    <!-- ======= Sidebar ======= -->
    @include('partials._sidebar')
    <!-- End Sidebar-->

    <main id="main" class="main">
        <div class="pagetitle">
            <h1>{{$title}}</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                    <li class="breadcrumb-item">{{$role}}</li>
                    <li class="breadcrumb-item active">{{$page}}</li>
                </ol>
            </nav>
        </div>
        <!-- End Page Title -->
        <section class="section">
            <div class="row">
                @yield('content')
            </div>
        </section>

    </main>
    <!-- End #main -->
    @include('partials._script_tag')
</body>

</html>
