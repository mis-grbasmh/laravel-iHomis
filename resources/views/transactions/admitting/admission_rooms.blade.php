<script src="{{ asset('assets') }}/js/core/jquery.min.js"></script>
{{-- <script src="{{ asset('assets') }}/js/jquery.dataTables.min.js"></script> --}}
{{-- <script src="{{ asset('assets') }}/css/jquery.dataTables.min.css"></script> --}}

@extends('layouts.app', ['page' => 'Patient Rooms', 'pageSlug' => 'transactions', 'section' => 'admitting'])
@section('content')

    <div class="row">
       <div class="col-lg-12 col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-8">
                            <h4 class="mb-0">Patient's Admission Room List</h4>
                            <input type="text" class="form-control" id="add_enccode" value="{{$enccode}}" name="enccode" disabled>
                            <input type="text" class="form-control" id="add_hpercode"  name="hpercode" disabled>

                        </div>
                        <div class="col-4 text-right">
                            <a data-toggle="modal" href="#"  data-target="#modalAddRoom"  data-backdrop="static" class="btn btn-primary btn-sm">Assign Room</i></a>
                        </div>
                    </div>

                </div>
                <div class="card-body "><hr/>
                    @include('alerts.success')
                    <div class="">

                      {{--   <table class="table tablesorter " id="inpatient"> --}}
                        <table id="example" class="display" style="width:100%" >
                            <thead class=" text-primary">
                                <th>#</th>
                                <th>Date</th>
                                <th>Ward</th>
                                <th>Room</th>
                                <th>Bed</th>
                                <th>Rooms Rate</th>
                                <th>Entry By</th>
                                <th>Actions</th>
                            </thead>
                            <tbody>
                                @foreach ($admissionrooms as $key => $row)
                                <tr>
                                   <td>{{$key+1}}</td>
                                   <td>{{ $row->transdate}}</td>
                                    <td><strong>{{ $row->wardname }}</strong></td>
                                    <td>{{ $row->rmname}}</td>
                                    <td>{{  $row->bdname}}</td>
                                    <td>{{ $row->rmrate}}</td>
                                    <td>{{ getemployeeinfo($row->entryby)}}</td>
                                    <td class="text-right">
                                        <a href="javascript:void(0)" class="btn btn-link btn-warning btn-icon btn-sm edit"><i class="tim-icons icon-pencil"></i></a>
                                        <a href="javascript:void(0)" class="btn btn-link btn-danger btn-icon btn-sm remove"><i class="tim-icons icon-simple-remove"></i></a>
                                      </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>


<!-- start editmodal-->
<div class="modal fade" tabindex="-1" role="dialog" id="modalAddRoom">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Assign Room</h4>
            </div>
            <div class="modal-body">
            <form role="form" id="frmRoomAdd">
                <div class="form-group">
                    <label class="form-control-label">Select Ward:</label>
                    <select class="form-control" id="add_wardcode" name="wardcode">
                        @foreach ($wards as $row)
                            <option value="{{$row->wardcode}}" selected>{{$row->wardname}} </option>
                       @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-control-label">Select Room:</label>
                    <select class="form-control" id="add_room" name="rmintkey">

                    </select>
                </div>
                <div class="form-group">
                    <label class="form-control-label">Select Bed:</label>
                    <select class="form-control" id="add_bed" name="bdintkey">

                    </select>
                </div>
                <div class="form-row">
                <div class="form-group{{ $errors->has('licno') ? ' has-danger' : '' }} col-md-12">
                    <label class="form-control-label" for="input-licno">Select Physician/Doctor</label>
                    <select name="licno" id="input-licno" class="form-select form-control-alternative{{ $errors->has('licno') ? ' is-invalid' : '' }}">
                        <option value="">Not Specified</option>

                    </select>
                    @include('alerts.feedback', ['field' => 'licno'])
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
<!-- end addmodal-->

<script type="text/javascript">
   $(document).ready(function() {
    $('#example').DataTable( {
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ]
    } );
} );


 // updating data infomation
 $('#btnSave').on('click',function(e){
       e.preventDefault();
       query = $('#add_ID').val();
       var res = query.split('/').join('-');
       var url = '{{ route("admission.add_doctor", ":id") }}';

                url = url.replace(':id', res);

        var frm = $('#frmDoctorAdd');
        swal({
              title: "Are you sure want to add Doctor?",
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
                var dataResult = JSON.parse(data);
				    if(dataResult.statusCode==200){
                        $('#modalAddDoctor').modal('hide');
                        swal("Deleted!", "Diet has been deleted", "success");
                        table.ajax.reload(null,false);
				    }else{
                        swal('Success!','Failed','success');
                    }

                // console.log(data);
                // if (data.success == true) {
                //     frm.trigger('reset');

                //     swal('Success!','Doctor added Successfully','success');
                //     table.ajax.reload(null,false);
                // }
            },
            error:function(err){
                console.log(err);
            }

            });
            }
        });
     });//insert doctor
function getDoctorbytype(){
    //get doctors by type
    query =document.getElementById("add_type").value;
    var url = '{{ route("doctor.get_doctors", ":id") }}';
    url = url.replace(':id', query);
    $.ajax({
            type :'GET',
            url : url,
            dataType : 'json',
            data:{'query':query},
            success:function(data){
                var dataResult = JSON.parse(data);
				    if(dataResult.statusCode==200){
                        swal('Success!','Failed','success');

                    }else{
                        swal('Success!','Failed','success');
                    }
            },
            error:function(err){
                console.log(err);
            }

            });
}
</script>

@endsection
@push('js')
<script>

new SlimSelect({
            select: '.form-select'
        })

        new SlimSelect({
        select: '#input_licno'
    })
</script>

@endpush
