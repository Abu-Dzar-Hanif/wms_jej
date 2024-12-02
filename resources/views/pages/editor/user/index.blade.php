@extends('layouts.editor')
@section('title')
   User
@endsection

@section('content')
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">User</h1>
        <form action="" id="form_cari" method="post">
            @csrf
            <div class="input-group">
                <input type="text" class="form-control" placeholder="Cari nama" name="cari" id="cari">
                <div class="input-group-append">
                    @if (Auth::user()->hasPermissionByName('User','create'))
                    <button type="button" id="add_new" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">Add</button>  
                    @endif
                  <button class="d-none d-sm-inline-block btn btn-sm btn-info shadow-sm" type="button" id="btn-cari">Cari</button>
                </div>
              </div>
        </form>
    </div>
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Data User</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="Tuser" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<form id="addForm" method="post" enctype="multipart/form-data">
    @csrf
    <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">User</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-grop row">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="">Name</label>
                                <input type="text" id="name" name="name" class="form-control" placeholder="Name">
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="">Username</label>
                                <input type="text" id="username" name="username" class="form-control" placeholder="Username">
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="">Email</label>
                                <input type="email" id="email" name="email" class="form-control" placeholder="Email">
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="">Password</label>
                                <div class="input-group">
                                    <input type="password" id="password" name="password" class="form-control" placeholder="Password">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <span id="togglePassword" class="fas fa-eye"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="">Password Confirm</label>
                                <div class="input-group">
                                    <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" placeholder="Password Confirm">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <span id="togglePasswordC" class="fas fa-eye"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <button type="button" id="proses_add" class="btn btn-primary">Save</button>
                </div>
            </div>
        </div>
    </div>
</form>
<form id="updateForm" method="post" enctype="multipart/form-data">
    @csrf
    <div class="modal fade" id="updateModal" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Update User</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group row">
                        <input type="hidden" id="id_update" name="id" class="form-control">
                        <div class="col-sm-4">
                            <label for="">Nama User</label>
                            <input type="text" id="name_update" name="name" class="form-control">
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="">Username</label>
                                <input type="text" id="username_update" name="username" class="form-control">
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="">Email</label>
                                <input type="email" id="email_update" name="email" class="form-control">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <button type="button" id="proses_update" class="btn btn-primary">Update</button>
                </div>
            </div>
        </div>
    </div>
</form>
<form id="accessForm" method="post" enctype="multipart/form-data">
    @csrf
    <div class="modal fade" id="accessModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Access User</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <input type="hidden" name="id" id="user_id_access">
                        <table id="Tau" class="table table-bordered">
                            <thead>
                                <th>Menu</th>
                                <th>Create</th>
                                <th>Read</th>
                                <th>Update</th>
                                <th>Delete</th>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    @if (Auth::user()->hasPermissionByName('User Access','create'))
                    <button type="button" id="proses_access" class="btn btn-primary">Save</button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</form>
@endsection
@section('script')
<script>
    $('document').ready(function(e){
        var Tuser = $('#Tuser').DataTable({
            "responsive": true,
            'searching': false,
            "processing": true,
            "serverSide": true,
            "pagingType": "full_numbers",
            "paging":true,
            "ajax":{
                "url":"{{ route('editor.user.data') }}",
                "data":function(parm){
                    parm.search = function(){
                        return $('#cari').val();
                    }
                },
                   
            },
            "columns":[
                {"data": "name","orderable":false},
                {"data": "username","orderable":false},
                {"data": "email","orderable":false},
                {
                    "data": "id","orderable":false,render: function ( data, type, row ){
                        var idData = row.id;
                        let btn ='<div class="btn-group" role="group" aria-label="Basic example">';
                            if("{{ Auth::user()->hasPermissionByName('User','update') }}"){
                                btn += '<button type="button" class="btn btn-warning btnUpdate">Update</button>';
                            }
                            if("{{ Auth::user()->hasPermissionByName('User Access','read') }}"){
                                btn += '<button type="button" class="btn btn-success btnAccess">Access</button>';
                            }
                            if("{{ Auth::user()->hasPermissionByName('User','delete') }}"){
                                btn += '<button type="button" class="btn btn-danger btnDelete">Delete</button>';
                            }
                        btn += '</div>';
                        return btn;
                    }
                },
            ]
        });
        function redraw(){
            Tuser.draw();
        }
        $("#add_new").click(function(){
            $("#addModal").modal("show");
        });
        $("#proses_add").click(function(){
            var postData = new FormData($("#addForm")[0]);
            $.ajax({
                url:"{{ URL::route('editor.user.store') }}",
                data:postData,
                type:"POST",
                dataType:"JSON",
                cache:false,
                contentType: false,
                processData: false,
                beforeSend: function(){
                    $('.loading-clock').css('display','flex');
                },
                success:function(data){
                    if(data.success == 1){
                        // Reset form
                        $('#addForm')[0].reset();
                        // Remove image preview
                        $("#addModal").modal("hide");
                        toastr_success(data.messages);
                        redraw();
                    }else{
                        toastr_error(data.messages);
                    }
                },
                complete: function(){
                    $('.loading-clock').css('display','none');
                },
            });
        });
        $("#Tuser tbody").on('click','.btnUpdate',function(){
            let data = Tuser.row( $(this).parents('tr') ).data();
            let idData = data.id;
            $.ajax({
                url:"{{ URL::route('editor.user.detail') }}",
                type: "GET",
                data: {
                    "_token": "{{ csrf_token() }}",
                    'id': idData
                },
                dataType: "JSON",
                cache: false,
                beforeSend: function(){
                    $('.loading-clock').css('display','flex');
                },
                success: function(data) {
                    if(data.success == 1){
                        let id = data.data.id;
                        let name = data.data.name;
                        let username = data.data.username;
                        let email = data.data.email;
                        $("#id_update").val(id);
                        $("#name_update").val(name);
                        $("#username_update").val(username);
                        $("#email_update").val(email);
                    } else{
                        toastr_error(data.messages);
                    }
                },
                complete: function(){
                    $('.loading-clock').css('display','none');
                },
            })
            $("#updateModal").modal("show");
        });
        $("#proses_update").click(function(){
            var postData = new FormData($("#updateForm")[0]);
            $.ajax({
                url:"{{ URL::route('editor.user.update') }}",
                data:postData,
                type:"POST",
                dataType:"JSON",
                cache:false,
                contentType: false,
                processData: false,
                beforeSend: function(){
                    $('.loading-clock').css('display','flex');
                },
                success:function(data){
                    if(data.success == 1){
                        $("#updateModal").modal("hide");
                        toastr_success(data.messages);
                        redraw();
                    }else{
                        toastr_error(data.messages);
                    }
                },
                complete: function(){
                    $('.loading-clock').css('display','none');
                },
            });
        });
        $("#Tuser tbody").on('click','.btnDelete',function(){
            let data = Tuser.row( $(this).parents('tr') ).data();
            let idData = data.id;
            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!"
                }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url:"{{ URL::route('editor.user.delete') }}",
                        type: "DELETE",
                        data: {
                            "_token": "{{ csrf_token() }}",
                            'id': idData
                        },
                        dataType: "JSON",
                        cache: false,
                        beforeSend: function(){
                            $('.loading-clock').css('display','flex');
                        },
                        success: function(data) {
                            if(data.success == 1){
                                toastr_success(data.messages);
                                redraw();
                            } else{
                                toastr_error(data.messages);
                            }
                        },
                        complete: function(){
                            $('.loading-clock').css('display','none');
                        },
                    }); 
                }
            });
        });
        $("#Tuser tbody").on('click','.btnAccess',function(){
            let data = Tuser.row( $(this).parents('tr') ).data();
            let idData = data.id;
            $.ajax({
                url:"{{ URL::route('editor.access.data') }}",
                type: "GET",
                data: {
                    "_token": "{{ csrf_token() }}",
                    'id': idData
                },
                dataType: "JSON",
                cache: false,
                beforeSend: function(){
                    $('.loading-clock').css('display','flex');
                },
                success: function(data) {
                    if(data.success == 1){
                        let res = data.data;
                        let access = res.access;
                        $("#user_id_access").val(res.user);
                        let tbody = $("#Tau tbody");
                        tbody.empty(); // Kosongkan isi tabel sebelum mengisi ulang

                        // Looping data untuk mengisi tabel
                        access.forEach(item => {
                            let row = `
                                <tr>
                                    <td>${item.name_menu}</td>
                                    <td>
                                        <input type="checkbox" name="access[${item.id_menu}][create]" value="1" ${item.create ? 'checked' : ''}>
                                    </td>
                                    <td>
                                        <input type="checkbox" name="access[${item.id_menu}][read]" value="1" ${item.read ? 'checked' : ''}>
                                    </td>
                                    <td>
                                        <input type="checkbox" name="access[${item.id_menu}][update]" value="1" ${item.update ? 'checked' : ''}>
                                    </td>
                                    <td>
                                        <input type="checkbox" name="access[${item.id_menu}][delete]" value="1" ${item.delete ? 'checked' : ''}>
                                    </td>
                                </tr>
                            `;
                            tbody.append(row); // Tambahkan baris ke tabel
                        });
                    } else{
                        toastr_error(data.messages);
                    }
                },
                complete: function(){
                    $('.loading-clock').css('display','none');
                },
            })
            $("#accessModal").modal("show");
        });
        $("#proses_access").click(function(){
            var postData = new FormData($("#accessForm")[0]);
            // Loop melalui semua checkbox untuk memastikan checkbox yang tidak dicentang juga mengirimkan nilai 0
            $("input[type='checkbox']").each(function() {
                // Jika checkbox tidak dicentang, tambahkan nilai 0
                if (!this.checked) {
                    postData.append($(this).attr('name'), 0);
                }
            });
            $.ajax({
                url:"{{ URL::route('editor.access.manage') }}",
                data:postData,
                type:"POST",
                dataType:"JSON",
                cache:false,
                contentType: false,
                processData: false,
                beforeSend: function(){
                    $('.loading-clock').css('display','flex');
                },
                success:function(data){
                    if(data.success == 1){
                        $("#accessModal").modal("hide");
                        toastr_success(data.messages);
                        redraw();
                    }else{
                        toastr_error(data.messages);
                    }
                },
                complete: function(){
                    $('.loading-clock').css('display','none');
                },
            });
        });
        
        $("#btn-cari").click(function(){
            let search = $("#cari").val();
            Tuser.draw();
        });
        $('#togglePassword').on('click', function() {
            // Toggle tipe input
            const passwordField = $('#password');
            const type = passwordField.attr('type') === 'password' ? 'text' : 'password';
            passwordField.attr('type', type);
            $(this).toggleClass('fa-eye fa-eye-slash');
        });
        $('#togglePasswordC').on('click', function() {
            // Toggle tipe input
            const passwordField = $('#password_confirmation');
            const type = passwordField.attr('type') === 'password' ? 'text' : 'password';
            passwordField.attr('type', type);
            $(this).toggleClass('fa-eye fa-eye-slash');
        });
    });
</script>
@endsection