<!-- Custom fonts for this template-->
<link href="{{ asset('template/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
<link
    href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
    rel="stylesheet">

<!-- Custom styles for this template-->
<link href="{{ asset('template/css/sb-admin-2.min.css') }}" rel="stylesheet">
<link href="{{ asset('template/css/loader.css') }}" rel="stylesheet">
<!-- Custom styles for this page -->
<link href="{{ asset('template/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
{{-- toastr --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    /* Terapkan tampilan form-control pada select2 */
    .select2-container--default .select2-selection--single {
        height: calc(2.25rem + 2px); /* Sesuaikan tinggi dengan form-control */
        padding: .375rem .75rem; /* Padding form-control */
        font-size: 1rem; /* Ukuran font yang sama dengan form-control */
        line-height: 1.5;
        color: #495057;
        background-color: #fff;
        background-clip: padding-box;
        border: 1px solid #ced4da; /* Border form-control */
        border-radius: .25rem; /* Radius form-control */
        transition: border-color .15s ease-in-out, box-shadow .15s ease-in-out;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
        color: #495057;
        padding-left: 0; /* Hilangkan padding kiri ekstra */
        padding-right: 0;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: calc(2.25rem + 2px);
        top: 0px; /* Sesuaikan posisi panah */
    }
</style>
