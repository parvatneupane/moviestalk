@if(session('error'))
<div class="container-xxl container-p-y" style="padding-bottom:0px !important;">
    <div class="alert alert-danger alert-dismissible" role="alert">
        {{session('error')}}
        {{-- <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button> --}}
    </div>
</div>
@endif
@if(session('success'))
<div class="container-xxl container-p-y" style="padding-bottom:0px !important;">
    <div class="alert alert-info alert-dismissible" role="alert">
        {{session('success')}}
        {{-- <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button> --}}
    </div>
</div>
@endif
@if(session('warning'))
<div class="container-xxl container-p-y" style="padding-bottom:0px !important;">
    <div class="alert alert-warning alert-dismissible" role="alert">
        {{session('warning')}}
        {{-- <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button> --}}
    </div>
</div>
@endif
