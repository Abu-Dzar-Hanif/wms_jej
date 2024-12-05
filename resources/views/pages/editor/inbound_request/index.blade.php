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
                    <button class="d-none d-sm-inline-block btn btn-sm btn-info shadow-sm" type="button" id="btn-cari">Cari</button>
                    @if (Auth::user()->hasPermissionByName('Inbound Request','create'))
                    <button type="button" id="add_new" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">Add</button>
                    <button type="button" id="upload_stock" class="d-none d-sm-inline-block btn btn-sm btn-success shadow-sm">Upload</button>
                    @endif
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
            <nav>
                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                    <button class="nav-link active" id="nav-open-tab" data-toggle="tab" data-target="#nav-open" type="button" role="tab" aria-controls="nav-open" aria-selected="true">open</button>
                    <button class="nav-link" id="nav-need-putaway-tab" data-toggle="tab" data-target="#nav-need-putaway" type="button" role="tab" aria-controls="nav-need-putaway" aria-selected="false">need-putaway</button>
                    <button class="nav-link" id="nav-done-tab" data-toggle="tab" data-target="#nav-done" type="button" role="tab" aria-controls="nav-done" aria-selected="false">done</button>
                </div>
            </nav>
            <div class="tab-content" id="nav-tabContent">
                <div class="tab-pane fade show active" id="nav-open" role="tabpanel" aria-labelledby="nav-open-tab">...</div>
                <div class="tab-pane fade" id="nav-need-putaway" role="tabpanel" aria-labelledby="nav-need-putaway-tab">...</div>
                <div class="tab-pane fade" id="nav-done" role="tabpanel" aria-labelledby="nav-done-tab">...</div>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered" id="TinboundR" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Vendor</th>
                            <th>Type</th>
                            <th>Remark</th>
                            <th>Date</th>
                            <th>Status</th>
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
    <div class="modal fade" id="addModal" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Inbound Request</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group row">
                        <div class="form-group col">
                            <label for="">Warehouse</label>
                            <select name="warehouse" id="warehouse" class="getWh form-control select2"></select>
                        </div>
                        <div class="form-group col">
                            <label for="">Vendor</label>
                            <select name="vendor" id="vendor" class="getVendor form-control select2"></select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="form-group col">
                            <label for="">Date</label>
                            <input type="date" name="date" id="date" class="form-control">
                        </div>
                        <div class="form-group col">
                            <label for="">Do/SJ Number</label>
                            <input type="text" name="do_number" id="do_number" class="form-control">
                        </div>
                        <div class="form-group col">
                            <label for="">PO Number</label>
                            <input type="text" name="po_number" id="po_number" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="form-group col">
                            <label for="">SKU</label>
                            <select id="sku" class="getSku form-control"></select>
                        </div>
                        <div class="form-group col">
                            <label for="">Qty</label>
                            <div class="input-group">
                                <input type="number" id="qty" class="form-control">
                                <div class="input-group-append">
                                    <button class="btn btn-info" type="button" id="addSku">Add</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <table class="table table-bordered" id="Tindtl">
                        <thead>
                            <tr>
                                <th>SKU</th>
                                <th>QTY</th>
                                <th>#</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
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
        $(".getVendor").select2({
            placeholder: 'Vendor',
            allowClear: true,
            width:'100%',
            ajax: { 
                url: "{{ URL::route('editor.vendor.data.select') }}",
                type: "get",
                dataType: 'json',
                data: function (params) {
                    return {
                        cari: params.term,
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
        $(".getSku").select2({
            placeholder: 'SKU',
            allowClear: true,
            width:'100%',
            ajax: { 
                url: "{{ URL::route('editor.sku.data.select') }}",
                type: "get",
                dataType: 'json',
                data: function (params) {
                    return {
                        cari: params.term,
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
                {"data": "inbound_request_type","orderable":false},
                {"data": "remarks","orderable":false},
                {"data": "date","orderable":false},
                {"data": "status","orderable":false,render:function(data){
                    let textSts ="";
                    switch (data) {
                        case 0:
                        textSts = `<span class="badge badge-primary">Open</span>`;
                            break;
                        case 1:
                        textSts = `<span class="badge badge-warning">Need Putaway</span>`;
                            break;
                        case 2:
                        textSts = `<span class="badge badge-success">Done</span>`;
                            break;
                        default:
                            break;
                    }
                    return textSts;
                    }
                },
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
                url:"{{ URL::route('editor.inbound-request.store') }}",
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
        $("#addSku").on('click',function(e){
            e.preventDefault();
            let sku_id = $("#sku").val();
            let sku_name = $("#sku option:selected").text();
            let qty = $("#qty").val();
            if(sku_id>0&&qty>0){
                if($("#Tindtl tbody #row"+sku_id).length){
                    toastr_error("Data sudah ada");
                }else{
                    $("#sku").val(null).trigger('change');
                    $("#qty").val(null);
                    $("#Tindtl tbody").append(`
                    <tr>
                    <td><input type="hidden" name="sku_id[]" value="${sku_id}">${sku_name}</td>
                    <td><input type="number" class="form-control" name="qty[]" value="${qty}"></td>
                    <td><button class="btn btn-danger remove" type="button"><i class="fas fa-minus-square"></i></button></td>
                    </tr>
                    `);
                }
            }else{
                toastr_error("masukan data");
            }
        });
        $("#Tindtl").on('click','.remove',function(){
            $(this).parent().parent().remove();
        });
    });
</script>
@endsection