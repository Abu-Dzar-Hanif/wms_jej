@extends('layouts.editor')
@section('title')
   Inbound Request
@endsection

@section('content')
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Inbound Request</h1>
        <form action="" id="form_cari" method="post">
            @csrf
            <div class="input-group">
                <input type="text" class="form-control" placeholder="Cari nama" name="cari" id="cari">
                <div class="input-group-append">
                    @if (Auth::user()->hasPermissionByName('Inbound Request','create'))
                    <button type="button" id="add_new" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">Add</button>
                    <button type="button" id="upload_stock" class="d-none d-sm-inline-block btn btn-sm btn-success shadow-sm">Upload</button>
                    @endif
                  <button class="d-none d-sm-inline-block btn btn-sm btn-info shadow-sm" type="button" id="btn-cari">Cari</button>
                </div>
              </div>
        </form>
    </div>
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Data Inbound Request</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="TinboundR" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Inbound Request</th>
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
                    <h5 class="modal-title">Inbound Request</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="">Nama Inbound Request</label>
                        <input type="text" id="name" name="name" class="form-control" placeholder="Nama Inbound Request">
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
                    <h5 class="modal-title">Update Inbound Request</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <input type="hidden" id="id_update" name="id" class="form-control">
                        <label for="">Nama Inbound Request</label>
                        <input type="text" id="name_update" name="name" class="form-control">
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
<form id="uploadStockForm" method="post" enctype="multipart/form-data">
    @csrf
    <div class="modal fade" id="uploadStockModal" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Upload Stock</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <div class="btn-group" role="group" aria-label="Basic example">
                            <button class="btn btn-primary" id="download" type="button"> example </button>
                        </div>
                    </div>
                    <div class='form-group'>
                        <label>Warehouse</label>
                        <select name="warehouse_id" class="getWh form-control" id="wh_id_upload"></select>
                    </div>
                    <div class='form-group'>
                        <label>Keterangan / Note</label>
                        <textarea name="ket" id="ket" class="form-control"></textarea>
                    </div>
                    <div class="form-group">
                        <label>Data excel stock</label>
                        <input required="" type="file" class="form-control" name="file_stock" id="file_stock">
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <button class="btn btn-success" type="submit" >Upload</button>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection
@section('script')
<script>
    $('document').ready(function(e){
        $(".getWh").select2({
            placeholder: 'Warehouse',
            allowClear: true,
            width:'100%',
            ajax: { 
                url: "{{ URL::route('editor.access-wh.data.select') }}",
                type: "get",
                dataType: 'json',
                data: function (params) {
                    return {
                        cari: params.term,
                        user : "{{ Auth::user()->id }}"
                    }
                },
                processResults: function (response) {
                    return {
                    results: response
                    };
                },
                cache: false,
            },
        });
        var TinboundR = $('#TinboundR').DataTable({
            "responsive": true,
            'searching': false,
            "processing": true,
            "serverSide": true,
            "pagingType": "full_numbers",
            "paging":true,
            "ajax":{
                "url":"{{ route('editor.inbound-request.data') }}",
                "data":function(parm){
                    parm.search = function(){
                        return $('#cari').val();
                    }
                },
                   
            },
            "columns":[
                {"data": "vendor_name","orderable":false},
                {
                    "data": "id","orderable":false,render: function ( data, type, row ){
                        var idData = row.id;
                        let btn ='<div class="btn-group" role="group" aria-label="Basic example">';
                            if("{{ Auth::user()->hasPermissionByName('Inbound Request','update') }}"){
                                btn += '<button type="button" class="btn btn-warning btnUpdate">Update</button>';
                            }
                            if ("{{ Auth::user()->hasPermissionByName('Inbound Request','delete') }}") {
                                btn += '<button type="button" class="btn btn-danger btnDelete">Delete</button>';
                            }
                        btn += '</div>';
                        return btn;
                    }
                },
            ]
        });
        function redraw(){
            TinboundR.draw();
        }
        $("#add_new").click(function(){
            $("#addModal").modal("show");
        });
        $("#proses_add").click(function(){
            var postData = new FormData($("#addForm")[0]);
            $.ajax({
                
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
        $("#TinboundR tbody").on('click','.btnUpdate',function(){
            let data = TinboundR.row( $(this).parents('tr') ).data();
            let idData = data.id;
            $.ajax({
                
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
                        $("#id_update").val(id);
                        $("#name_update").val(name);
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
        $("#TinboundR tbody").on('click','.btnDelete',function(){
            let data = TinboundR.row( $(this).parents('tr') ).data();
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
            TinboundR.draw();
        });
        $("#upload_stock").click(function(){
            $("#uploadStockModal").modal("show");
        });
        $("#uploadStockForm").on("submit", function(e) {
            e.preventDefault();
            extension = $('#file_stock').val().split('.').pop().toLowerCase();
            if ($.inArray(extension, ['xls', 'xlsx']) == -1) {
                toastr.error("error");
            } else {
                let gudang_id = $("#wh_id_upload").val();
                let ket = $("#ket").val();
                if(gudang_id != null){
                    var file_data = $('#file_stock').prop('files')[0];
                    var form_data = new FormData();
                    form_data.append('file', file_data);
                    form_data.append("_token", "{{ csrf_token() }}");
                    form_data.append("warehouse",gudang_id);
                    form_data.append("ket",ket);
                    $.ajax({
                        url: "{{ URL::route('editor.inbound-request.upload.stock') }}",
                        data: form_data,
                        type: "post",
                        dataType: "json",
                        contentType: false,
                        cache: false,
                        processData: false,
                        beforeSend: function(){
                            $('.loading-clock').css('display','flex');
                        },
                        success: function(data) {
                            if(data.success == 1){
                                toastr_success(data.messages);
                                $("#uploadStockModal").modal("hide");
                            }else{
                                toastr_error(data.messages);
                            }
                            redraw();
                        },
                        complete: function(){
                            $('.loading-clock').css('display','none');
                        },
                    });
                }else{
                    toastr_error("pilih gudang dulu");
                }
            }
        });
    });
</script>
@endsection