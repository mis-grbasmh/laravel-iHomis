<div class="card-body ">
    <div class="table-responsive">
            <table id="itemsTable" class="display" cellspacing="0" style="width:100%" >
                <thead class=" text-primary">
                    <th>Date & Time</th>
                    <th>Charge Type</th>
                    <th>Account No. </th>
                    <th>Charge Slip No.</th>
                    <th>Item</th>
                    <th>QTY</th>
                    <th>Price</th>
                    <th>Total Amount</th>
                    <th>Entry By</th>
                    <th scope="col">Actions</th>
                </thead>
                <tfoot align="right">
                    <tr><th></th><th></th><th></th><th></th><th></th><th></th></tr>
                </tfoot>
                <tr><strong>Grand Total:Php <span id="test"></span></strong>
                    <hr/>
                </tr>
            </table>
        </div>
        <input type="text" id="test1">
    </div>
<script>
    function get_itemscharges(query){

        if(query){
    table = $('#itemsTable').DataTable({
    stateSave: true,
    search: false,
    fixedColumns: true,
    responsive: true,
    processing: true,
    serverSide : true,
    order : [0,'desc'],
    destroy: true,
    scrollX:true,
    scrollY:true,
    columnDefs: [
    {
      targets: [0],
      className: 'nw'
    }
    ],
    processing: true,
    serverSide: true,
    "ajax": {
                url:"{{ route('getPatient.itemscharges') }}",
                method:'GET',
        data:{query:query},
        dataType:'json',
            error: function (errmsg) {
            alert('Unexpected Error');
            console.log(errmsg['responseText']);
        },

    },
    columns: [
            { "data": "pcchrgdte" },
            { "data": "chargetype" },
            { "data": "acctno" },
            { "data": "pcchrgcod" },
            { "data": "itemcode"},
            { "data": "qty" },
            { "data": "pchrgup" },
            { "data": "total" },
            { "data": "entryby" },

            {data: 'action' , name : 'action', orderable : false ,searchable: false},


       ],
       success:function(data)
                    {
                        $('#test').text(data.total);
                    },


    });
  }


}




</script>
<script>


    // $(function() {
    //     var table = $('#itemsTable').DataTable();
    //     $('#itemsTable').on( 'draw.dt', function () {
    //         var tablesum = table.column(4).data().sum();
    //         $(".dataTables_info").append('. Sum of records per page ' + tablesum);
    //     } );
    // });
</script>

@section('styles')
    {{-- <style>
        #itemsTable tbody td.details-control {
            background-image: url('{{ asset('assets/admin/images/details_open.png') }}');
            cursor: pointer;
            background-repeat:  no-repeat;
            background-position: center;
            background-origin: content-box;
            background-size: cover;
            padding: 7px;
        }
        #itemsTable tbody tr.shown td.details-control {
            background-image: url('{{ asset('assets/admin/images/details_close.png') }}');
            cursor: pointer;
            background-repeat:  no-repeat;
            background-position: center;
            background-origin: content-box;
            background-size: cover;
            padding: 7px;
        }

        #itemsTable tbody tr .rowDetails p {
            font-size: 14px;
            font-weight: 800;
            float: left;
            margin-right: 10px;
            padding: 1px;
            margin-bottom: 0;
        }
        #itemsTable tbody tr .rowDetails a{

        }
        #itemsTable tbody tr .rowDetails td{
            padding: 5px;
        }
        .m-r-10{
            margin-right: 10px !important;
        }
    </style> --}}




@endsection
