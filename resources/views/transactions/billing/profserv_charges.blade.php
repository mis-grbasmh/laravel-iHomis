

<div class="card-body ">
     <div class="table-responsive">
            <table id="profservTable" class="display" cellspacing="0" style="width:100%" >
                <thead class=" text-primary">
                    <th>Name of Doctor</th>
                    <th>No. of Visits</th>
                    <th>Rate</th>
                    <th>Procedure/Operation</th>
                    <th>Type of Anesthesia</th>
                    <th>Service From</th>
                    <th>Service To</th>
                    <th>Type of Professional Service</th>
                    <th>Professional Fee</th>
                    <th>Include PF in Bill</th>
                    <th>Remarks</th>
                    <th>Claim Type</th>
                    <th scope="col">Actions</th>
                </thead>
            </table>
        </div>
    </div>
<script>
    function get_profservcharges(query){
    if(query){
    table = $('#profservTable').DataTable({
    stateSave: true,
    search: false,
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
                url:"{{ route('getPatient.profservcharges') }}",
                method:'GET',
        data:{query:query},
        dataType:'json',
          error: function (errmsg) {
        alert('Unexpected Error');
        console.log(errmsg['responseText']);
        },
    }, columns: [
            { "data": "doctor" },
            { "data": "numvisit" },
            { "data": "profrate" },
            { "data": "pfdisc" },
            { "data": "prikey" },
            { "data": "pfdtefrom" },
            { "data": "pfdteto" },
            { "data": "tpikey" },

            { "data": "pfamt" },
            { "data": "profincl" },
            { "data": "pfnotes" },
            { "data": "ClaimType" },
            {data: 'action' , name : 'action', orderable : false ,searchable: false},
       ]

    });
  }
}

</script>
