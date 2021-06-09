<div class="card">
    <div class="card-header">
        <div class="row align-items-center">
            <div class="col-8">
                <h4 class="mb-0">Laboratory Orders</h4>
            </div>
            <div class="col-4 text-right">
                <a data-toggle="modal" href="#"  data-target="#newdietorder"  data-backdrop="static" class="btn btn-primary btn-sm">New Examination Order</i></a>
            </div>
        </div>
    <div class="card-body ">
        <hr/>
        @if (count($errors) > 0)
            @include('alerts.error')
        @endif
        <div class="table-responsive">
            <table id="laboratoryTable" class="display" cellspacing="0" style="width:100%" >
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

<script>
$('#dietTable').on('click','.btnEdit[data-edit]',function(e){
    e.preventDefault();
    var url = $(this).data('edit');
    //  alert(url);
    swal({
          title: "Are you sure want to Edit this item?",
          type: "info",
          showCancelButton: true,
          confirmButtonClass: "btn-info",
          confirmButtonText: "Confirm",
          cancelButtonText: "Cancel",
          closeOnConfirm: true,
          closeOnCancel: true
        },
            function(isConfirm) {
            if (isConfirm) {
                $.ajax({
                    url : url,
                    type : 'GET',
                    datatype : 'json',
                    success:function(data){
                        $('#edit_ID').val(data.id);
                        $('#edit_dodate').val(data.dodate);
                        $('#edit_dodtepost').val(data.dodtepost);
                        $('#edit_licno').val(data.licno);
                        $('#edit_dietcode').val(data.dietcode);
                        $('#edit_dietlunch').val(data.dietlunch);
                        $('#edit_dietdinner').val(data.dietdinner);
                        $('#edit_remarks').val(data.remarks);
                        $('#edit_doctornotes').val(data.donotes);
                        $('#edit_dodtepost').val(data.dodtepost);
                        // $('.edit_errorName').addClass('hidden');
                        // $('.edit_errorContact').addClass('hidden');
                        // $('.edit_errorAddress').addClass('hidden');
                        // $('#mdlEditData').modal('show');
                        $('#mdlEditData').modal('show');
                    }

                });
            }
    });
});

</script>


<script>
//deleting data
    $('#dietTable').on('click','.btnDelete[data-remove]',function(e){
        e.preventDefault();
        var url = $(this).data('remove');
        swal({
           title: "Are you sure want to remove this item?",
           text: "Data will be Temporary Deleted!",
           type: "warning",
           showCancelButton: true,
           confirmButtonClass: "btn-danger",
           confirmButtonText: "Confirm",
           cancelButtonText: "Cancel",
           closeOnConfirm: false,
           closeOnCancel: false,
        },
        function(isConfirm) {
            if (isConfirm) {
            $.ajax({
                url : url,
                type: 'DELETE',
                dataType : 'json',
                data : { method : '_DELETE' , submit : true},
                success:function(data){
                    if (data == 'Success') {
                        swal("Deleted!", "Diet has been deleted", "success");
                        table.ajax.reload(null,false);
                    }
                }
            });

        }else{

        swal("Cancelled", "You Cancelled", "error");

        }

        });
    });


     // updating data infomation
     $('#btnUpdate').on('click',function(e){
       e.preventDefault();
       query = $('#edit_ID').val();
        alert(query);
    //    var res = query.split('/').join('-');
        var url = '{{ route("dietorder.update", ":id") }}';
                url = url.replace(':id', res);

        var frm = $('#frmDietEdit');
        swal({
              title: "Are you sure want to update Admission?",
              type: "info",
              showCancelButton: true,
              confirmButtonClass: "btn-info",
              confirmButtonText: "Confirm",
              cancelButtonText: "Cancel",
              closeOnConfirm: true,
              closeOnCancel: true
            },
            function(isConfirm) {
            if (isConfirm) {
                $.ajax({
            type :'GET',
            url : url,
            dataType : 'json',
          //  data:{'query':query},
             data : frm.serialize(),
            success:function(data){
                console.log(data);
                if (data.success == true) {
                    frm.trigger('reset');
                    $('#modalEditAdmission').modal('hide');
                    swal('Success!','Data Updated Successfully','success');
                    table.ajax.reload(null,false);
                }
            },
            error:function(err){
                console.log(err);
            }

            });
            }
        });
     });//Update Admission
</script>




