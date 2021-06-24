<div class="card-body ">
    <Strong>Total Issued Amount (Php):&nbsp; <span id="total_sum"></span></strong>
     <div class="table-responsive">
            <table id="drugmedsTable" class="display" cellspacing="0" style="width:100%" >
                <thead class=" text-primary">
                    <th>Date Issued</th>
                    <th>Charge Code</th>
                    <th>Item</th>
                    <th>QTY</th>
                    <th>Issued</th>
                    <th>Price</th>
                    <th>Total</th>
                    <th>Entry By</th>
                    <th scope="col">Actions</th>
                </thead>
            </table>
        </div>
<hr/>
<Strong>Total Returned Amount (Php):&nbsp; <span id="total_returned"></span></strong>
<div class="table-responsive">
    <table id="drugmedsreturnTable" class="display" cellspacing="0" style="width:100%" >
        <thead class=" text-primary">
            <th>Date Returned</th>
            <th>Item Details</th>
            <th>QTY</th>
            <th>Price</th>
            <th>Total</th>
            <th>Returned By</th>
            <th>Remarks</th>
            <th scope="col">Actions</th>
        </thead>
        <tbody></tbody>
        <tfoot>
            <tr>
                    <th colspan="3">Total</th>
                    <th colspan="4" id="total_amtreturned"></th>
            </tr>
        </tfoot>
    </table>
</div>

    </div>
<script>
    function get_drugmedscharges(query){
    if(query){
    table = $('#drugmedsTable').DataTable({
    stateSave: true,
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
                url:"{{ route('getPatient.drugmedscharges') }}",
                method:'GET',
        data:{query:query},
        dataType:'json',


          error: function (errmsg) {
        alert('Unexpected Error');
        console.log(errmsg['responseText']);
        },
    }, columns: [
            { "data": "dodtepost" },
            { "data": "pcchrgcod" },
            { "data": "dmdcomb"},
            { "data": "pchrgqty"},
            { "data": "qtyissued" },
            { "data": "dmduprice" },
            { "data": "amount" },
            { "data": "entryby" },
            {data: 'action' , name : 'action', orderable : false ,searchable: false},
       ],
       drawCallback: function (response) {
                   $('#total_sum').html(response.json.totalamt);
                //   console.log(response.json.totalamt);
                 //   alert(response.json.total);
                }
    });

    //
    if(query){
    table = $('#drugmedsreturnTable').DataTable({
    stateSave: true,
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
            url:"{{ route('getPatient.drugmedsreturn') }}",
            method:'GET',
            data:{query:query},
            dataType:'json',
                error: function (errmsg) {
                    alert('Unexpected Error');
                    console.log(errmsg['responseText']);
                },
            },
            columns: [
            { "data": "returndate" },
            { "data": "dmdcomb"},
            { "data": "qty" },
            { "data": "dmduprice"},
            { "data": "amount"},
            { "data": "returnby" },
            { "data": "remarks" },
            {  data: 'action' , name : 'action', orderable : false ,searchable: false},
            ],
            drawCallback: function (response) {
                   $('#total_returned').html(response.json.totalamt);
                   console.log(response.json.totalamt);
                 //   alert(response.json.total);
                }
        });

    }//ajax

  }//endif


}//function


</script>


