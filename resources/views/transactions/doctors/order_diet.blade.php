<div class="card">
    <div class="card-header">
        <div class="row align-items-center">
            <div class="col-8">
                <h4 class="mb-0">Diet List Order</h4>
            </div>
            <div class="col-4 text-right">
                <button type="button" class="btn btn-sm btn-primary" id="btnAddDiet">New Diet Order</button>
            </div>
        </div>
    <div class="card-body ">
        <hr/>
        @if (count($errors) > 0)
            @include('alerts.error')
        @endif
        <div class="table-responsive">
            <table id="dietTable" class="display" cellspacing="0" style="width:100%" >
                <thead class=" text-primary">
                    <th>Date Requested</th>
                    <th>Start Date</th>
                    <th>Breakfast</th>
                    <th>Lunch</th>
                    <th>Dinner</th>
                    <th>Remarks</th>
                    <th scope="col">Ordered By</th>
                    <th scope="col">Actions</th>
                </thead>
            </table>
        </div>
    </div>
 </div>
</div>

<!-- start addmodal-->
<div class="modal fade" tabindex="-1" role="dialog" id="modalAddDiet">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">New Diet Order</h4>
            </div>
            <div class="modal-body">
            <form role="form" id="frmDietAdd">
                <input type="text" id="enccode" name="enccode" value="{{$enccode}}">
                <div class="form-group">
                    <label for="edit_dodate" class="control-label">
                    Date and Time of Order:<span class="required">*</span>
                    </label>
                    <input type="datetime-local" id="edit_dodate" name="dodate" class="form-control floating-label" step="any">
                    <p class="edit_errordodate text-danger hidden"></p>
                </div>
                <div class="form-group">
                    <label for="edit_doctor" class="control-label">
                    Ordered By<span class="required">*</span>
                    </label>
                    <select class="form-control" id="edit_licno" name="licno">
                    @foreach($doctors as $doctor)
                   <option value="{{ $doctor->licno }}"><strong>{{getdoctorinfo($doctor->licno)}}</strong></option>
                    @endforeach
                    </select>
                    <p class="edit_errorDoctor text-danger hidden"></p>
                </div>
                <div class="form-group">

                    <label><strong>Breakfast:</strong></label>
                    {{-- <select name="provider_id" id="input-provider" class="form-select2 form-control-alternative{{ $errors->has('provider_id') ? ' is-invalid' : '' }}" required> --}}
                    <select class="form-control" id="edit_dietcode" name="dietcode" required>
                    @foreach($diettypes as $diettype)
                    <option value="{{$diettype->dietcode}}"><strong>{{getdietdesc($diettype->dietcode)}}</strong></option>
                    @endforeach
                    </select>
                 </div>
                <div class="form-group">
                    <label><strong>Lunch:</strong></label>
                    {{-- <input type="text" class="form-control" id="edit_lunch">  --}}
                    <select class="form-control" id="edit_dietlunch" name="dietlunch" required>
                    @foreach($diettypes as $diettype)
                    <option value="{{$diettype->dietcode}}"><strong>{{getdietdesc($diettype->dietcode)}}</strong></option>
                    @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label><strong>Supper:</strong></label>
                    <select class="form-control" id="edit_dietdinner" name="dietdinner" required>
                    @foreach($diettypes as $diettype)
                    <option value="{{$diettype->dietcode}}"><strong>{{getdietdesc($diettype->dietcode)}}</strong></option>
                    @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="edit_remarks" class="control-label">
                    Diet Remarks<span class=""></span>
                    </label>
                    <input type="text" class="form-control" id="edit_remarks" name="remarks">
                    <p class="edit_errorRemarks text-danger hidden"></p>
                </div>
                <div class="form-group">
                    <label for="edit_doctornotes" class="control-label">
                    Doctor Notes<span class="">*</span>
                    </label>
                    <textarea class="form-control" id="edit_doctornotes" name="donotes"></textarea>
                    <p class="edit_errordoctornotes text-danger hidden"></p>
                </div>
                <div class="form-group">
                    <label for="edit_doctornotes" class="control-label">
                        <label>Date and Time Posted<span class="">*</span>
                        </label>
                        <input type="datetime-local" id="edit_dodtepost" name="dodtepost" class="form-control floating-label" step="any" required>
                    </div>
                </div>
            </form>
                <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" id="btnSave"><i class="glyphicon glyphicon-save"></i>&nbsp;Save</button>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- end editmodal-->


<!-- start editmodal-->
<div class="modal fade" tabindex="-1" role="dialog" id="modalEditDiet">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Edit Diet Order</h4>
            </div>
            <div class="modal-body">
            <form role="form" id="frmDietEdit">
                <input type="text" class="form-control" id="edit_ID" name="edit_ID" disabled>
                <div class="form-group">
                    <label for="edit_dodate" class="control-label">
                    Date and Time of Order:<span class="required">*</span>
                    </label>
                    <input type="datetime-local" id="edit_dodate" name="dodate" class="form-control floating-label" step="any">

                    <p class="edit_errordodate text-danger hidden"></p>
                </div>
                <div class="form-group">
                    <label for="edit_doctor" class="control-label">
                    Ordered By<span class="required">*</span>
                    </label>
                    <select class="form-control" id="edit_licno" name="licno">
                    @foreach($doctors as $doctor)
                   <option value="{{ $doctor->licno }}"><strong>{{getdoctorinfo($doctor->licno)}}</strong></option>
                    @endforeach
                    </select>
                    <p class="edit_errorDoctor text-danger hidden"></p>
                </div>
                <div class="form-group">
                    <label><strong>Breakfast:</strong></label>
                    {{-- <select name="provider_id" id="input-provider" class="form-select2 form-control-alternative{{ $errors->has('provider_id') ? ' is-invalid' : '' }}" required> --}}
                    <select class="form-control" id="edit_dietcode" name="dietcode" required>
                    @foreach($diettypes as $diettype)
                    <option value="{{$diettype->dietcode}}"><strong>{{getdietdesc($diettype->dietcode)}}</strong></option>
                    @endforeach
                    </select>
                 </div>
                <div class="form-group">
                    <label><strong>Lunch:</strong></label>
                    {{-- <input type="text" class="form-control" id="edit_lunch">  --}}
                    <select class="form-control" id="edit_dietlunch" name="dietlunch" required>
                    @foreach($diettypes as $diettype)
                    <option value="{{$diettype->dietcode}}"><strong>{{getdietdesc($diettype->dietcode)}}</strong></option>
                    @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label><strong>Supper:</strong></label>
                    <select class="form-control" id="edit_dietdinner" name="dietdinner" required>
                    @foreach($diettypes as $diettype)
                    <option value="{{$diettype->dietcode}}"><strong>{{getdietdesc($diettype->dietcode)}}</strong></option>
                    @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="edit_remarks" class="control-label">
                    Diet Remarks<span class=""></span>
                    </label>
                    <input type="text" class="form-control" id="edit_remarks" name="remarks">
                    <p class="edit_errorRemarks text-danger hidden"></p>
                </div>
                <div class="form-group">
                    <label for="edit_doctornotes" class="control-label">
                    Doctor Notes<span class="">*</span>
                    </label>
                    <textarea class="form-control" id="edit_doctornotes" name="donotes"></textarea>
                    <p class="edit_errordoctornotes text-danger hidden"></p>
                </div>
                <div class="form-group">
                    <label for="edit_doctornotes" class="control-label">
                        <label>Date and Time Posted<span class="">*</span>
                        </label>
                        <input type="datetime-local" id="edit_dodtepost" name="dodtepost" class="form-control floating-label" step="any" required>
                    </div>
                </div>
            </form>
                <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" id="btnUpdate"><i class="glyphicon glyphicon-save"></i>&nbsp;Update</button>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- end editmodal-->

{{-- New Diet Order Modal --}}
<div class="modal fade" id="newdietorder" tabindex="-1" role="dialog" aria-labelledby="dietorderModal" aria-hidden="true">
    {{-- @include('modals.docord-dietorder') --}}
 </div>

 {{-- Edit Diet Order Modal --}}
    <div class="modal fade" id="editdietorder" tabindex="-1" role="dialog" aria-labelledby="editdietorderModal" aria-hidden="true">
    {{-- @include('modals.docord-dietorder-edit') --}}
 </div>


<script>
    function getdiet(query){
    if(query){
    table = $('#dietTable').DataTable({
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
                url:"{{ route('getPatient.diet') }}",
                method:'GET',
        data:{query:query},
        dataType:'json',
          error: function (errmsg) {
        alert('Unexpected Error');
        console.log(errmsg['responseText']);
        },
    }, columns: [
            { "data": "dodate" },
            { "data": "statdate" },
            { "data": "dietcode"},
            { "data": "dietlunch" },
            { "data": "dietdinner" },
            { "data": "remarks" },
            { "data": "licno" },
            {data: 'action' , name : 'action', orderable : false ,searchable: false},

       ]
    });
  }
}
</script>

<script>
//calling add modal
$('#btnAddDiet').click(function(e){
        $('#modalAddDiet').modal('show');
    });

//Adding new data
    $('#btnSave').click(function(e){
        e.preventDefault();
        query = $('#edit_ID').val();
        var url = '{{ route("dietorder.diet_add", ":id") }}';
                url = url.replace(':id', query);
        var frm = $('#frmDietAdd');
        $.ajax({
            url : url,
            type : 'POST',
            dataType: 'json',
            data:{'query':query},
             data : frm.serialize(),
            // data : {
            //     'csrf-token': $('input[name=_token]').val(),
            //      name : $('#name').val(),
            //      contact : $('#contact').val(),
            //      address : $('#address').val(),
            // },
            success:function(data){
                $('.errorName').addClass('hidden');
                $('.errorContact').addClass('hidden');
                $('.errorAddress').addClass('hidden');
                if (data.errors) {
                    if (data.errors.name) {
                        $('.errorName').removeClass('hidden');
                        $('.errorName').text(data.errors.name);
                    }
                    if (data.errors.contact) {
                        $('.errorContact').removeClass('hidden');
                        $('.errorContact').text(data.errors.contact);
                    }
                    if (data.errors.address) {
                        $('.errorAddress').removeClass('hidden');
                        $('.errorAddress').text(data.errors.address);
                    }
                }
                if (data.success == true) {
                    $('#modalAddDiet').modal('hide');
                    frm.trigger('reset');
                    table.ajax.reload(null,false);
                    swal('success!','Successfully Added','success');

                }
            }
        });
    });
//End Add



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
                        $('#edit_dietcode2').val(data.dietcode);
                        $('#edit_dietlunch').val(data.dietlunch);
                        $('#edit_dietdinner').val(data.dietdinner);
                        $('#edit_remarks').val(data.remarks);
                        $('#edit_doctornotes').val(data.donotes);
                        $('#edit_dodtepost').val(data.dodtepost);
                        // $('.edit_errorName').addClass('hidden');
                        // $('.edit_errorContact').addClass('hidden');
                        // $('.edit_errorAddress').addClass('hidden');
                        // $('#mdlEditData').modal('show');
                        $('#modalEditDiet').modal('show');
                    }

                });
            }
    });
});




     // updating data infomation
     $('#btnUpdate').on('click',function(e){
       e.preventDefault();
       query = $('#edit_ID').val();
        var url = '{{ route("dietorder.update", ":id") }}';
                url = url.replace(':id', query);
            var frm = $('#frmDietEdit');
        swal({
              title: "Are you sure want to update Diet Order?",
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
            data:{'query':query},
             data : frm.serialize(),
            success:function(data){
                console.log(data);
                if (data.success == true) {
                    frm.trigger('reset');
                    $('#modalEditDiet').modal('hide');
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


     //deleting diet order
    $('#dietTable').on('click','.btnDelete[data-remove]',function(e){
        e.preventDefault();
        query = $('#edit_ID').val();
       // var url = "{{URL('userData')}}";
       // var dltUrl = url+"/"+query;
        var url = '{{ route("dietorder.destroy", ":id") }}';
               url = url.replace(':id', query);
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
                headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url : url,
                type: 'GET',
                cache: false,
                data:{'query':query},
                success:function(data){
                    var dataResult = JSON.parse(data);
				    if(dataResult.statusCode==200){
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
</script>

