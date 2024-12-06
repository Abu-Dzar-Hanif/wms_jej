<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <title>Download Inbound</title>
    <style>
        body{
            font-size:11px;
        }
        .table,.table-detail{
            border-collapse: collapse;
        width: 100%;
        }
      .table thead tr td, .table tbody tr td {
         border: 1px solid #000000!important;
    	 padding: 3px;
      }
      .table thead tr th, .table tbody tr th {
         border: 1px solid #000000!important;
    	 padding: 3;
      }
      .table-detail thead tr td, .table-detail tbody tr td {
         border-top: 1px solid #000000!important;
         border-bottom: 1px solid #000000!important;
         padding: 0px;
      }
      .thead-light {
         border: 1px solid #000000!important;
      }
      .page-break {
        break-after: always;
        /* for firefox */
        /*page-break-after: always;*/
        page-break-inside: avoid;
        /* for webkit */
        -webkit-column-break-after: always;
      }
      .table-info{
        border-collapse: collapse;
    	 padding: 0px;
        width: 100%;
      }
      .table-info thead tr td, .table-info tbody tr td {
         /*border: 1px solid#000000!important;*/
         border: none!important;
    	 padding: 0px;
         font-size:14px;
      }
    </style>
  </head>
  <body>
    <table class='table table-bordered page-break' style="width:100%;" border="1">
        <thead>
            <tr>
                <td colspan="3">
                    <table class="table-info">
                        <tr>
                            <td colspan="3" rowspan="6">
                                Barcode Inbound : {{ $InboundRequest->id }}
                                <br>
                                <img src="data:image/png;base64,{{ $InboundRequest['qrid'] }}" alt="QR Code">
                            </td>
                        </tr>
                        <tr>
                            <td>Date</td>
                            <td>:</td>
                            <td>{{$InboundRequest->date}}</td>
                        </tr>
                        <tr>
                            <td>Type</td>
                            <td>:</td>
                            <td>{{$InboundRequest->inbound_request_type}}</td>
                        </tr>
                        <tr>
                            <td>Vendor</td>
                            <td>:</td>
                            <td>{{ ucwords($InboundRequest->vendor_name) }}</td>
                        </tr>
                        <tr>
                            <td>DO Number</td>
                            <td>:</td>
                            <td>{{$InboundRequest->no_sj}}</td>
                        </tr>
                        <tr>
                            <td>PO Number</td>
                            <td>:</td>
                            <td>{{$InboundRequest->po_number}}</td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <th>Name</th>
                <th>Sku Barcode</th>
                <th>Qty</th>
            </tr>
        </thead>
    	<tbody>
    		@foreach($ird as $item) 
        	<tr class="ttr">
        	    <td>{{ $item['sku_name'] }}</td>
        	    <th>{{ $item['sku_code'] }}</th>
        	    <th>{{ $item['qty'] }}</th>
        	</tr>
    		@endforeach
    	</tbody>
    </table>
  </body>
</html>
