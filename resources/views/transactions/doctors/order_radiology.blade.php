<div class="card">
    <div class="card-header">
        <div class="row align-items-center">
            <div class="col-8">
                <h6 class="title d-inline">
                Laboratory Orders</h6>
            </div>
            <div class="col-4 text-right">
                <a data-toggle="modal" href="#"  data-target="#newdietorder"  data-backdrop="static" class="btn btn-primary btn-sm">New Radiology Order</i></a>
            </div>
        </div>
    <div class="card-body ">
        <hr/>
        @if (count($errors) > 0)
            @include('alerts.error')
        @endif
        <div class="table-responsive">
            <table id="RadiologyTable" class="display" cellspacing="0" style="width:100%" >
                <thead class=" text-primary">
                    <th>Date of Order</th>
                    <th>Chargeslip No.</th>
                    <th>Examination</th>
                    <th>Remarks</th>
                    <th scope="col">Ordering<br/>Doctor</th>
                    <th scope="col">Entry<br/>By</th>
                    <th scope="col">Actions</th>
                </thead>
            </table>
        </div>
    </div>
 </div>
</div>

<script>
    function getradiologyorder(query){
    if(query){
    table = $('#RadiologyTable').DataTable({
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
                url:"{{ route('getPatient.radiologyorder') }}",
                method:'GET',
        data:{query:query},
        dataType:'json',
          error: function (errmsg) {
        alert('Unexpected Error');
        console.log(errmsg['responseText']);
        },
    }, columns: [
            { "data": "dodate" },
            { "data": "pcchrgcod" },
            { "data": "procdesc" },
            { "data": "remarks" },
            { "data": "licno" },
            { "data": "entby" },
            {data: 'action' , name : 'action', orderable : false ,searchable: false},
       ]
    });
  }
}
</script>




