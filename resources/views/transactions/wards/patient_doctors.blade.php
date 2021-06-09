<script src="{{ asset('assets') }}/js/core/jquery.min.js"></script>
{{-- <script src="{{ asset('assets') }}/js/jquery.dataTables.min.js"></script> --}}
{{-- <script src="{{ asset('assets') }}/css/jquery.dataTables.min.css"></script> --}}

@extends('layouts.app', ['page' => 'Patient Doctors', 'pageSlug' => 'transactions', 'section' => 'transactions'])
@section('content')

    <div class="row">
       <div class="col-lg-12 col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-8">
                            <h4 class="mb-0">Patient's Doctors List</h4>
                            <input type="hidden" class="form-control" id="add_ID" value="{{$enccode}}" name="enccode" disabled>

                        </div>
                        <div class="col-4 text-right">
                            <a data-toggle="modal" href="#"  data-target="#modalAddDoctor"  data-backdrop="static" class="btn btn-primary btn-sm">Add Doctor</i></a>
                        </div>
                    </div>

                </div>
                <div class="card-body "><hr/>
                    @include('alerts.success')
                    <div class="table-responsive">

                      {{--   <table class="table tablesorter " id="inpatient"> --}}
                        <table id="doctorsTable" class="display" style="width:100%" >
                            <thead class=" text-primary">
                                <th>#</th>
                                <th>Physicians Details</th>
                                <th>Type</th>
                                <th>Classification</th>
                                <th>Entry By</th>
                                <th>Actions</th>
                            </thead>
                            <tbody>
                                @foreach ($doctors as $key => $row)
                                <tr>
                                   <td>{{$key+1}}</td>

                                    <td><strong>{{ getdoctorinfo($row->licno) }}</strong><br/><small>{{$row->licno}}</small></td>
                                    <td>{{ $row->doctype}}</td>
                                    <td>{{  convertDoctorClassification($row->clscode)}}</td>
                                    <td>{{  $row->user_id  }}</td>
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


<!-- start addmodal-->
<div class="modal fade" tabindex="-1" role="dialog" id="modalAddDoctor">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">New Doctor</h4>
            </div>
            <div class="modal-body">

            <form role="form" id="frmDoctorAdd">
                <input type="hidden" class="form-control" id="add_hpercode" value="{{$hpercode}}" name="hpercode" disabled>
                <div class="form-group">
                    <label class="form-control-label">Select Doctor Type:</label>
                    <select class="form-select" id="add-doctype" name="doctype" onchange="getDoctorbytype()">
                        <option value="ATTEN"><strong>Attending Doctor</strong></option>
                        <option value="CONSU"><strong>Consultant Doctor</strong></option>
                        <option value="RESID"><strong>Resident Doctor</strong></option>
                        <option value="VISIP"><strong>Visiting Doctor</strong></option>
                        <option value="FELLO"><strong>Fellow Doctor</strong></option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-control-label">Select Doctor Classification:</label>
                    <select class="form-select" id="add-classification" name="docclass" onchange="getDoctorbytype()">
                       @foreach(DoctorClassification() as $key => $classification)
                        <option value="{{$key}}">{{($classification)}} </option>
                       @endforeach
                    </select>
                </div>

                <div class="form-group{{ $errors->has('licno') ? ' has-danger' : '' }}">
                    <label class="form-control-label" for="add_licno">Select Physician/Doctor</label>
                    <select name="licno" id="add-licno" class="form-select form-control-alternative{{ $errors->has('licno') ? ' is-invalid' : '' }}">
                        <option value="">Not Specified</option>
                        {{-- @foreach ($activedoctors as $activedoctor)
                            @if($activedoctor->licno == old('$activedoctor->licno'))
                                <option value="{{$activedoctor->lico}}" selected>{{getdoctorinfo($activedoctor->licno)}} </option>
                            @else
                                <option value="{{$activedoctor->licno}}">{{getdoctorinfo($activedoctor->licno)}} </option>
                            @endif
                        @endforeach --}}
                    </select>
                    @include('alerts.feedback', ['field' => 'licno'])
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


<!-- start editmodal-->
<div class="modal fade" tabindex="-1" role="dialog" id="modalAddDoctor">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">New Doctor</h4>
            </div>
            <div class="modal-body">
            <form role="form" id="frmDoctorEdit">
                <div class="form-group">
                    <label class="form-control-label">Select Doctor Type:</label>
                    <select class="form-control" id="add_type" name="doctype">
                    <option value="ATTEN"><strong>Attending Doctor</strong></option>
                    <option value="CONSU"><strong>Consultant Doctor</strong></option>
                    <option value="RESID"><strong>Resident Doctor</strong></option>
                    <option value="VISIP"><strong>Visiting Doctor</strong></option>
                    <option value="FELLO"><strong>Fellow Doctor</strong></option>
                    </select>
                </div>
                <div class="form-row">
                <div class="form-group{{ $errors->has('licno') ? ' has-danger' : '' }} col-md-12">
                    <label class="form-control-label" for="input-licno">Select Physician/Doctor</label>
                    <select name="licno" id="add-licno" class="form-select form-control-alternative{{ $errors->has('licno') ? ' is-invalid' : '' }}">
                        <option value="">Not Specified</option>
                        {{-- @foreach ($activedoctors as $activedoctor)
                            @if($activedoctor->licno == old('$activedoctor->licno'))
                                <option value="{{$activedoctor->lico}}" selected>{{getdoctorinfo($activedoctor->licno)}} </option>
                            @else
                                <option value="{{$activedoctor->licno}}">{{getdoctorinfo($activedoctor->licno)}} </option>
                            @endif
                        @endforeach --}}
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
    getDoctorbytype()
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
      query =document.getElementById("add-doctype").value;
        query2 =document.getElementById("add-classification").value;
        var url = '{{ route("doctor.get_doctors") }}';
     //   alert(query2)
        $.ajax({
            type :'GET',
            url : url,
            dataType : 'json',
            data:{'query':query,'query2':query2},

            success:function(data){
                console.log(data);
                    var len = data.length;
                    $("#add-licno").empty();
                    for( var i = 0; i<len; i++){
                        var id = data[i]['licno'];
                        var name = data[i]['name'];
                        $("#add-licno").append("<option value='"+id+"'>"+name+"</option>");
                     }
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
        select: '#add-licno'
    })
        new SlimSelect({
            select: '#add-classification'
        })
</script>

@endpush
