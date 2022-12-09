<!DOCTYPE html>
    <html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('layout.head')
    </head>
    <body >
        <div class="wrapper">
            <section class="content">
            <div class="container-fluid">

                @yield('content')

            </div>
            </section>
        </div>

        @include('layout.scripts')
    </body>
</html>
