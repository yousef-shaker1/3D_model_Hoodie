<!DOCTYPE html>
<html lang="en">

@include('layouts.head')
<body>
    
    @include('layouts.header')

    @include('layouts.sidebar')
    
    @yield('content')
    
    
    @include('layouts.footer')
    
    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>
    
    @include('layouts.sccript')

</body>

</html>