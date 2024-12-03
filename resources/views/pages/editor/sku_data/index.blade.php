@extends('layouts.editor')
@section('title')
   Sku
@endsection

@section('content')
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Sku</h1>
        <form action="" id="form_cari" method="post">
            @csrf
            <div class="input-group">
                <input type="text" class="form-control" placeholder="Cari nama" name="cari" id="cari">
                <div class="input-group-append">
                    @if (Auth::user()->hasPermissionByName('Sku','create'))
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
            <h6 class="m-0 font-weight-bold text-primary">Data Sku</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="Tsku" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>SKU Code</th>
                            <th>SKU Name</th>
                            <th>UOM</th>
                            <th>Type</th>
                            <th>Category</th>
                            <th>Weight</th>
                            <th>Height</th>
                            <th>Length</th>
                            <th>Width</th>
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
                    <h5 class="modal-title">Sku</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group row">
                        <div class="form-group col">
                            <label for="">Kode Sku</label>
                            <div class="input-group">
                                <input type="text" id="sku_code" name="sku_code" class="form-control" placeholder="kode Sku">
                                <div class="input-group-append">
                                  <button class="btn btn-outline-success auto_code" type="button">Generate</button>
                                </div>
                            </div>
                        </div>
                        <div class="form-group col">
                            <label for="">Nama Sku</label>
                            <input type="text" id="sku_name" name="sku_name" class="form-control" placeholder="Nama Sku">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="form-group col">
                            <label for="">Uom</label>
                            <select name="uom" id="uom" class="getUom form-control select2"></select>
                        </div>
                        <div class="form-group col">
                            <label for="">Tipe</label>
                            <select name="type" id="type" class="getType form-control select2"></select>
                        </div>
                        <div class="form-group col">
                            <label for="">Kategori</label>
                            <select name="category" id="category" class="getCategory form-control select2"></select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="form-group col">
                            <label for="">Ketrangan</label>
                            <textarea name="ket" id="ket" class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="form-group col">
                            <label for="">Berat</label>
                            <input type="number" id="weight" name="weight" class="form-control" placeholder="Berat">
                        </div>
                        <div class="form-group col">
                            <label for="">Tinggi</label>
                            <input type="number" id="height" name="height" class="form-control" placeholder="Tinggi">
                        </div>
                        <div class="form-group col">
                            <label for="">Panjang</label>
                            <input type="number" id="length" name="length" class="form-control" placeholder="Panjang">
                        </div>
                        <div class="form-group col">
                            <label for="">Lebar</label>
                            <input type="number" id="width" name="width" class="form-control" placeholder="Lebar">
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
                    <h5 class="modal-title">Update Sku</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group row">
                        <div class="form-group col">
                            <input type="hidden" id="id_update" name="id" class="form-control">
                            <label for="">Kode Sku</label>
                            <input type="text" id="sku_code_update" name="sku_code" class="form-control" placeholder="kode Sku">
                        </div>
                        <div class="form-group col">
                            <label for="">Nama Sku</label>
                            <input type="text" id="sku_name_update" name="sku_name" class="form-control" placeholder="Nama Sku">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="form-group col">
                            <label for="">Uom</label>
                            <select name="uom" id="uom_update" class="getUom form-control select2"></select>
                        </div>
                        <div class="form-group col">
                            <label for="">Tipe</label>
                            <select name="type" id="type_update" class="getType form-control select2"></select>
                        </div>
                        <div class="form-group col">
                            <label for="">Kategori</label>
                            <select name="category" id="category_update" class="getCategory form-control select2"></select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="form-group col">
                            <label for="">Ketrangan</label>
                            <textarea name="ket" id="ket_update" class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="form-group col">
                            <label for="">Berat</label>
                            <input type="number" id="weight_update" name="weight" class="form-control" placeholder="Berat">
                        </div>
                        <div class="form-group col">
                            <label for="">Tinggi</label>
                            <input type="number" id="height_update" name="height" class="form-control" placeholder="Tinggi">
                        </div>
                        <div class="form-group col">
                            <label for="">Panjang</label>
                            <input type="number" id="length_update" name="length" class="form-control" placeholder="Panjang">
                        </div>
                        <div class="form-group col">
                            <label for="">Lebar</label>
                            <input type="number" id="width_update" name="width" class="form-control" placeholder="Lebar">
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
@endsection
@section('script')
<script>
    $('document').ready(function(e){
        $(".auto_code").on('click',function(){
            $.ajax({
                url:"{{ URL::route('editor.sku.generate.code') }}",
                type: "GET",
                data: {
                    "_token": "{{ csrf_token() }}",
                },
                dataType: "JSON",
                cache: false,
                success: function(data) {
                    if(data.success == 1){
                        let code = data.code;
                        $("#sku_code").val(code);
                    } else{
                        toastr_error(data.messages);
                    }
                },
            });
        });
        $(".getUom").select2({
            placeholder: 'Uom',
            allowClear: true,
            width:'100%',
            ajax: { 
                url: "{{ URL::route('editor.uom.data.select') }}",
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
        $(".getType").select2({
            placeholder: 'Type',
            allowClear: true,
            width:'100%',
            ajax: { 
                url: "{{ URL::route('editor.sku-type.data.select') }}",
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
        $(".getCategory").select2({
            placeholder: 'Category',
            allowClear: true,
            width:'100%',
            ajax: { 
                url: "{{ URL::route('editor.category.data.select') }}",
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
        var Tsku = $('#Tsku').DataTable({
            "responsive": true,
            'searching': false,
            "processing": true,
            "serverSide": true,
            "pagingType": "full_numbers",
            "paging":true,
            "ajax":{
                "url":"{{ route('editor.sku.data') }}",
                "data":function(parm){
                    parm.search = function(){
                        return $('#cari').val();
                    }
                },
                   
            },
            "columns":[
                {"data": "sku_code","orderable":false},
                {"data": "sku_name","orderable":false},
                {"data": "uom_name","orderable":false},
                {"data": "type_sku","orderable":false},
                {"data": "category_sku","orderable":false},
                {"data": "weight","orderable":false},
                {"data": "height","orderable":false},
                {"data": "length","orderable":false},
                {"data": "width","orderable":false},
                {
                    "data": "id","orderable":false,render: function ( data, type, row ){
                        var idData = row.id;
                        let btn ='<div class="btn-group" role="group" aria-label="Basic example">';
                            if("{{ Auth::user()->hasPermissionByName('Sku','update') }}"){
                                btn += '<button type="button" class="btn btn-warning btnUpdate">Update</button>';
                            }
                            if ("{{ Auth::user()->hasPermissionByName('Sku','delete') }}") {
                                btn += '<button type="button" class="btn btn-danger btnDelete">Delete</button>';
                            }
                        btn += '</div>';
                        return btn;
                    }
                },
            ]
        });
        function redraw(){
            Tsku.draw();
        }
        $("#add_new").click(function(){
            $("#addModal").modal("show");
        });
        $("#proses_add").click(function(){
            var postData = new FormData($("#addForm")[0]);
            $.ajax({
                url:"{{ URL::route('editor.sku.manage') }}",
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
                        $('#uom').val(null).trigger('change');
                        $('#type').val(null).trigger('change');
                        $('#category').val(null).trigger('change');
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
        $("#Tsku tbody").on('click','.btnUpdate',function(){
            let data = Tsku.row( $(this).parents('tr') ).data();
            let idData = data.id;
            $.ajax({
                url:"{{ URL::route('editor.sku.detail') }}",
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
                        let code = data.data.sku_code;
                        let name = data.data.sku_name;
                        let uom = data.data.uom_id;
                        let uom_name = data.data.uom_name;
                        let type = data.data.sku_type_id;
                        let type_sku = data.data.type_sku;
                        let category = data.data.category_id;
                        let category_name = data.data.category_sku;
                        let ket = data.data.ket;
                        let b = data.data.weight;
                        let t = data.data.height;
                        let p = data.data.length;
                        let l = data.data.width;
                        let uomOption = new Option(uom_name, uom, true, true);
                        let typeOption = new Option(type_sku, type, true, true);
                        let categoryOption = new Option(category_name, category, true, true);
                        $("#id_update").val(id);
                        $("#sku_code_update").val(code);
                        $("#sku_name_update").val(name);
                        $("#updateForm #uom_update").append(uomOption).trigger('change');
                        $("#type_update").append(typeOption).trigger('change');
                        $("#category_update").append(categoryOption).trigger('change');
                        $("#ket_update").val(ket);
                        $("#weight_update").val(b);
                        $("#height_update").val(t);
                        $("#length_update").val(p);
                        $("#width_update").val(l);
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
                url:"{{ URL::route('editor.sku.manage') }}",
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
        $("#Tsku tbody").on('click','.btnDelete',function(){
            let data = Tsku.row( $(this).parents('tr') ).data();
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
                        url:"{{ URL::route('editor.sku.delete') }}",
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
            Tsku.draw();
        });
    });
</script>
@endsection