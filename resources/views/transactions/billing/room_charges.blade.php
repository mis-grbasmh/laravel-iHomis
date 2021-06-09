<div class="card-body ">
     <div class="table-responsive">
            <table id="roomTable" class="display" cellspacing="0" style="width:100%" >
                <thead class=" text-primary">
                    <th>Date</th>
                    <th>Ward</th>
                    <th>Room</th>
                    <th>Bed</th>
                    <th>Rate</th>
                    <th>Entry By</th>
                    <th scope="col">Actions</th>
                </thead>
            </table>
        </div>
    </div>
<script>
    function get_roomcharges(query){
    if(query){
    table = $('#roomTable').DataTable({
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
                url:"{{ route('getPatient.roomcharges') }}",
                method:'GET',
        data:{query:query},
        dataType:'json',
          error: function (errmsg) {
        alert('Unexpected Error');
        console.log(errmsg['responseText']);
        },
    }, columns: [
            { "data": "hprdate" },
            { "data": "wardname" },
            { "data": "rmname"},
            { "data": "bdname" },
            { "data": "rmrate" },
            { "data": "entryby" },
            {data: 'action' , name : 'action', orderable : false ,searchable: false},

       ]
    });
  }
}
</script>
