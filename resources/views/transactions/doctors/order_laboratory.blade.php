<div class="card">
    <div class="card-header">
        <div class="row align-items-center">
            <div class="col-8">
                <h4 class="mb-0">Laboratory Examination Order</h4>
            </div>
            <div class="col-4 text-right">
                <a data-toggle="modal" href="#"  data-target="#newlaborder"  data-backdrop="static" class="btn btn-primary btn-sm">New Lab Order</i></a>
            </div>
        </div>
        <div class="card-body "><hr/>
            @if (count($errors) > 0)
                 @include('alerts.error') 
            @endif
            <div class="table-responsive">
                <table id="laboratoryTable" class="display" cellspacing="0" style="width:100%" >
                    <thead class=" text-primary">
                        <th scope="col">Date Requested</th>
                        <th scope="col">Start Date</th>
                        <th scope="col">Breakfast</th>
                        <th scope="col">Lunch</th>
                        <th scope="col">Dinner</th>
                        <th scope="col">Actions</th>
                    </thead>
                </table>
            </div>
    </div>
</div>
</div>
 
<script>
    function getlaboratory(query){
    if(query){
    table = $('#laboratoryTable').DataTable({ 
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
            url:"{{ route('getPatient.laboratories') }}",
                method:'GET',
        data:{query:query},
        dataType:'json',
          error: function (errmsg) {
        alert('Unexpected Error');
        console.log(errmsg['responseText']);
        },
    }, columns: [
            { "data": "dodate" }, 
            { "data": "procdesc" },
            { "data": "licno"},
            { "data": "pcchrgcod" },
            { "data": "acctno" },
            {data: 'action' , name : 'action', orderable : false ,searchable: false}
       ]
    });
  }
}
</script>