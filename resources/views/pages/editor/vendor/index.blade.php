@extends('layouts.editor')
@section('title')
   Vendor
@endsection

@section('content')
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Vendor</h1>
        <form action="" id="form_cari" method="post">
            @csrf
            <div class="input-group">
                <input type="text" class="form-control" placeholder="Cari nama" name="cari" id="cari">
                <div class="input-group-append">
                    @if (Auth::user()->hasPermissionByName('Vendor','create'))
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
            <h6 class="m-0 font-weight-bold text-primary">Data Vendor</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="Tvendor" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Vendor</th>
                            <th>PIC</th>
                            <th>Phone</th>
                            <th>Address</th>
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
                    <h5 class="modal-title">Vendor</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="">Nama Vendor</label>
                        <input type="text" id="name" name="name" class="form-control" placeholder="Nama Vendor">
                    </div>
                    <div class="form-group">
                        <label for="">PIC Vendor</label>
                        <input type="text" id="pic" name="pic" class="form-control" placeholder="PIC Vendor">
                    </div>
                    <div class="form-group">
                        <label for="">Phone Vendor</label>
                        <input type="text" id="phone" name="phone" class="form-control" placeholder="Phone Vendor">
                    </div>
                    <div class="form-group">
                        <label for="">Address Vendor</label>
                        <textarea name="address" id="address" class="form-control" placeholder="Address Vendor" cols="20" rows="5"></textarea>
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
                    <h5 class="modal-title">Update Vendor</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <input type="hidden" id="id_update" name="id" class="form-control">
                        <label for="">Nama Vendor</label>
                        <input type="text" id="name_update" name="name" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="">PIC Vendor</label>
                        <input type="text" id="pic_update" name="pic" class="form-control" placeholder="PIC Vendor">
                    </div>
                    <div class="form-group">
                        <label for="">Phone Vendor</label>
                        <input type="text" id="phone_update" name="phone" class="form-control" placeholder="Phone Vendor">
                    </div>
                    <div class="form-group">
                        <label for="">Address Vendor</label>
                        <textarea name="address" id="address_update" class="form-control" placeholder="Address Vendor" cols="20" rows="5"></textarea>
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
@endsection
@section('script')
<script>
    $('document').ready(function(e){
        var Tvendor = $('#Tvendor').DataTable({
            "responsive": true,
            'searching': false,
            "processing": true,
            "serverSide": true,
            "pagingType": "full_numbers",
            "paging":true,
            "ajax":{
                "url":"{{ route('editor.vendor.data') }}",
                "data":function(parm){
                    parm.search = function(){
                        return $('#cari').val();
                    }
                },
                   
            },
            "columns":[
                {"data": "name","orderable":false},
                {"data": "pic","orderable":false},
                {"data": "phone","orderable":false},
                {"data": "address","orderable":false},
                {
                    "data": "id","orderable":false,render: function ( data, type, row ){
                        var idData = row.id;
                        let btn ='<div class="btn-group" role="group" aria-label="Basic example">';
                            if("{{ Auth::user()->hasPermissionByName('Vendor','update') }}"){
                                btn += '<button type="button" class="btn btn-warning btnUpdate">Update</button>';
                            }
                            if ("{{ Auth::user()->hasPermissionByName('Vendor','delete') }}") {
                                btn += '<button type="button" class="btn btn-danger btnDelete">Delete</button>';
                            }
                        btn += '</div>';
                        return btn;
                    }
                },
            ]
        });
        function redraw(){
            Tvendor.draw();
        }
        $("#add_new").click(function(){
            $("#addModal").modal("show");
        });
        $("#proses_add").click(function(){
            var postData = new FormData($("#addForm")[0]);
            $.ajax({
                url:"{{ URL::route('editor.vendor.manage') }}",
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
        $("#Tvendor tbody").on('click','.btnUpdate',function(){
            let data = Tvendor.row( $(this).parents('tr') ).data();
            let idData = data.id;
            $.ajax({
                url:"{{ URL::route('editor.vendor.detail') }}",
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
                        let pic = data.data.pic;
                        let phone = data.data.phone;
                        let address = data.data.address;
                        $("#id_update").val(id);
                        $("#name_update").val(name);
                        $("#pic_update").val(pic);
                        $("#phone_update").val(phone);
                        $("#address_update").val(address);
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
                url:"{{ URL::route('editor.vendor.manage') }}",
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
        $("#Tvendor tbody").on('click','.btnDelete',function(){
            let data = Tvendor.row( $(this).parents('tr') ).data();
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
                        url:"{{ URL::route('editor.vendor.delete') }}",
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
        $("#btn-cari").click(function(){
            let search = $("#cari").val();
            Tvendor.draw();
        });
    });
</script>
@endsection