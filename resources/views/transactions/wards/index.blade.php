<script src="{{ asset('assets') }}/js/core/jquery.min.js"></script>
@extends('layouts.app', ['page' => 'Transactions', 'pageSlug' => 'Nursing Ward', 'section' => 'Ward'])
@section('content')
@include('alerts.success')

<style>
    .ellipsis{
       display: inline-block;
       width: 200px;
       white-space: nowrap;
       overflow: hidden;
       text-overflow: ellipsis;
   }
 </style>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                            <h6 class="title d-inline">
                            Inpatient List by Ward {{$id}}</h6>
                            <p class="card-category d-inline">Shows the list of admitted patients</p>
                        <div class="pull-right">
                            <div class="col-lg-12">
                                <div class="row">
                                    <div class="col-sm-5">
                                        <label><strong>Select Option</strong></label>
                                      </div>

                                    <div class="col-4 text-right pull-right">
                                        <div class="dropdown">
                                            {{-- <h6 class="title d-inline">Option</h6> --}}
                                             <button type="button" class="btn btn-link dropdown-toggle btn-icon text-center" data-toggle="dropdown">
                                                 <i class="tim-icons icon-settings-gear-63"></i>
                                             </button>
                                                <div class="dropdown-menu dropdown-menu-right dropdown-white" aria-labelledby="dropdownMenuLink">
                                                     <h6 class="dropdown-header">Select Ward</h6>
                                                    @foreach ($wards as $key => $row)
                                                         <a class="dropdown-item" href="{{ route('wards.index', ['id'=>$row->wardname]) }}">{{$row->wardname}}</a>
                                                            {{-- <a class="dropdown-item" href="#" onclick="getPatientList('{{$row->wardname}}');return false;">{{$row->wardname}}</a> --}}
                                                            {{-- <a class="dropdown-item" href="#" title="Click to do add Patient Charges" onclick="getPatientList('{{$row->wardname}}');return false;">{{$row->wardname}}</a> --}}
                                                    @endforeach
                                                         <a  class="dropdown-item" href="{{ route('wards.index',['']) }}">View All</a>
                                                        <a  class="dropdown-item" href="#">Print</a>
                                                </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                </div>
                <div class="card-body "><hr/>
                    <div class="">
                      {{--   <table class="table tablesorter " id="inpatient"> --}}
                        <div class="table-responsive">
                        <table id="inpatientsTable" class="display" cellspacing="0" style="width:100%" >
                            <thead class=" text-primary">
                                <th>Patient Details</th>
                                <th>Admission Details</th>
                                <th style="width:50px;" class="colspan">Admitting Diagnosis</th>
                                <th>Doctor</th>
                                <th>Remarks</th>
                                <th>Actions</th>
                            </thead>
                            {{-- <tbody>
                                @foreach ($inpatients as $key => $inpatient)
                                @if(!$inpatient->fordischarge)
                                <tr>
                                @else
                                 <tr style="background-color:yellow;">
                                 @endif

                                   <td>{{$key+1}}</td>

                                    <td><strong>{{ getpatientinfo($inpatient->hpercode) }}</strong> <br/>{{ $inpatient->patsex }}, {{ number_format($inpatient->patage) }} year(s) old
                                    <br/><small>{{$inpatient->hpercode}}</small></td>
                                    <td>{{getFormattedDate($inpatient->admdate)}} at {{ asDateTime($inpatient->admdate)}}
                                        <br/>
                                        <strong>{{$inpatient->wardname}}  - {{$inpatient->rmname }} - {{$inpatient->bdname}}</strong>
                                        <br/>
                                        <small>Length of Stay: {{ \Carbon\Carbon::parse($inpatient->admdate)->diffInDays(\Carbon\Carbon::now())}} day(s)</small>
                                    <td>DR. {{getdoctorinfo($inpatient->licno)}}<br/><small><strong>{{ $inpatient->tsdesc}}</strong></small></td>

                                    <td>

                                        @if(!$inpatient->diet)
                                        <span class="badge badge-danger">No Diet</span>
                                        @endif
                                        @if($inpatient->fordischarge)
                                        <span class="badge badge-warning">For Discharge</span>
                                        @endif
                                    </td>
                                    <td>
                                       <div class="dropdown">
                                           <button type="button" class="btn btn-link dropdown-toggle btn-icon" data-toggle="dropdown" aria-expanded="false">
                                            <i class="tim-icons icon-settings-gear-63"></i>
                                           </button>

                                           <div class="dropdown-menu dropdown-menu-center" aria-labelledby="dropdownMenuLink" x-placement="top-end" style="position: absolute; transform: translate3d(-122px, -341px, 0px); top: 0px; left: 0px; will-change: transform;" x-out-of-boundaries="">
                                            <h6 class="dropdown-header">Select Action</h6>
                                               <a class="dropdown-item"
                                                    data-toggle="tooltip" title="Click to do view Patient Charges" onclick="patientcharges('{{$inpatient->enccode}}');return false;" href="#">Patient Charges</a>
                                               <a data-toggle="modal" data-id="@book.Id" title="Add this item" class="open-AddBookDialog"></a>
                                               <a class="dropdown-item" href="#" title="Click to do add Patient Charges" onclick="doctorsorder('{{$inpatient->enccode}}');return false;">Doctors Order</a>
                                               <a class="dropdown-item" href="#pablo">Room assignements</a>
                                               <a class="dropdown-item" href="#" title="Click to do view Doctors" onclick="patientdoctors('{{$inpatient->enccode}}');return false;">View Doctors</a>
                                               <a class="dropdown-item discharge"
                                                    data-toggle="modal" data-toggle="tooltip" title="Click to discharge patient" data-placement="right" data-target="#discharge" data-keyboard="false" data-backdrop="static"
                                                    data-id="{{ $inpatient->enccode}}"
                                                    data-hpercode="{{ $inpatient->hpercode}}"
                                                    data-licno="{{ $inpatient->licno}}"
                                                    data-patient="{{getpatientinfo($inpatient->hpercode)}}"
                                                    href="#">Discharge</i>
                                                </a>


                                           </div>
                                       </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody> --}}
                        </table>
                    </div>
                    </div>
                </div>
            </div>
        </div>
     {{-- Discharge Patient Modal --}}
   <div class="modal fade" tabindex="-1" data-backdrop="static" role="dialog" id="modalDischargeAdmission" aria-labelledby="dischargeModal" aria-hidden="true">
    @include('modals.admission_discharge')
 </div>
    {{-- Discharge Patient Modal
    <div class="modal fade" id="discharge" tabindex="-1" role="dialog" aria-labelledby="dischargeModal" aria-hidden="true">
       @include('modals.patient-discharge')
    </div> --}}
    <script>
        $(document).ready(function(){
            $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
         });
       </script>

   <script>

    function doctorsorder(id){
        if(id){
            var res = id.split('/').join('-');
            var url = '{{ route("patient.doctorsorder", ":id") }}';
            url = url.replace(':id', res);
            document.location.href=url;



        }//if
    }
    </script>
    <script>
        function patientcharges(id){
           // alert("me");
            if(id){
                var res = id.split('/').join('-');
                var url ='{{ route("patient.charges", ":id")}}';
                url = url.replace(':id', res);
                document.location.href=url;
            }//if
        }

        function patientdoctors(id){
         //convert to index
            alert(me);
            if(id){
                var res = id.split('/').join('-');
                var url ='{{ route("patient.doctors", ":id")}}';
                url = url.replace(':id', res);
                // document.location.href=url;
                $.ajax({
                    url : url,
                    type : 'GET',
                    datatype : 'json',
                    success:function(data){
                        $('#id').val(data.enccode);
                    }
                });
            }//if
        }
    </script>

    <script>
        $(document).on("click", ".discharge", function () {
        var id = $(this).data('id');
        $(".modal-body #enccode").val( id );
        var hpercode = $(this).data('hpercode');
        $(".modal-body #hpercode").val( hpercode );
        var licno = $(this).data('licno');
        $(".modal-body #licno").val( licno );
        var patname = $(this).data('patient');
        $(".modal-body #patname").val( patname );
       });
    </script>

  <script>
$(document).ready(function(){

    document.getElementById('dvtransferred').style.display='block'

$('#disdate').val(new Date().toLocaleString("sv-SE", {
    year: "numeric",
    month: "2-digit",
    day: "2-digit",
    hour: "2-digit",
    minute: "2-digit",
    second: "2-digit"
}).replace(" ", "T"));
});
    </script>

    <!-- for Disposition -->
<script type="text/javascript">
    function ShowHideDivDisposition() {
    var transferred = document.getElementById("ddldisposition");
        // alert(transferred.value);
        dvtransferred.style.display = transferred.value == "TRANS" ? "block" : "none";

     //   dvadmission.style.display = transferred.value == "ADMIT" ? "block" : "none";
    }//
</script>


{{-- <script type="text/javascript">
   $(document).ready(function() {
    $('#example').DataTable( {
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ]
    } );
} );
</script> --}}
<script type="text/javascript">
  $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(document).ready(function(){
        $("select").change(function(){
            $(this).find("option:selected").each(function(){
                var optionValue = $(this).attr("value");
                if(optionValue){
                    $(".box").not("." + optionValue).hide();
                    $("." + optionValue).show();
                } else{
                    $(".box").hide();
                }
            });
        }).change();
    });
    </script>


   <script>
    $(document).ready(function(){
    table = $('#inpatientsTable').DataTable({
      stateSave: true,
      responsive: true,
      processing: true,
      serverSide : true,
      order : [1,'DESC'],
      destroy: true,
      scrollX:true,
      scrollY:true,
      columnDefs: [{
        targets: [0],
        className: 'nw'
      }],
      processing: true,
      serverSide: true,
      "ajax": {
          url: "{{route('wards.index','')}}",
          method:'GET',
          data:{query:''},
          dataType:'json',
          error: function (errmsg) {
          alert('Unexpected Error');
          console.log(errmsg['responseText']);
          },
      },
        columns: [
              { "data": "patient" },
              { "data": "admission" },
              { "data": "diagnosis" },
              { "data": "doctor" },
              { "data": "types" },
              { "data": "actions" }

         ]
      });
    //  { "data": "diagnosis"},

});

$('#inpatientsTable').on('click','.btnDischarge[data-discharge]',function(e){
        e.preventDefault();
        var id =$(this).data('id');
        var res = id.split('/').join('-');
        var url ='{{ route("admission.dischargeinfo", ":id")}}';
              url = url.replace(':id', res);
        swal({
              title: "Are you sure you want to Discharge Patient?",
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
                             $('#disc_id').val(data.enccode);
                             $('#disc_hpercode').val(data.hpercode);
                             $('#disc_licno').val(data.licno);
                            // formated_id = data.enccode;
                            // formated_id = formated_id.split('/').join('-');
                           //  $('#frmDataEdit').attr('action',"{{url('')}}/admission/update/"+formated_id);
                             $('#disc_name').val(data.patientname);
                            $("#modalDischargeAdmission").modal({
                            backdrop: 'static',
                            keyboard: false,
                            show: true,
                            });
                        }

                    });
                }
        });
    });//btnDischarge



       // patien discharge
       $('#btnDischarge').on('click',function(e){
       e.preventDefault();
       query = $('#disc_id').val();
       var res = query.split('/').join('-');
        var url = '{{ route("admission.discharge", ":id") }}';
       //  var url = '{{ route("admission.update") }}';
                url = url.replace(':id', res);

        var frm = $('#frmDataDischarge');
        swal({
              title: "Confirm Discharge?",
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
                    $('#modalDischargeAdmission').modal('hide');
                    swal('Success!','Patient Discharge Successfully','success');
                    table.ajax.reload(null,false);
                }
            },
            error:function(err){
                console.log(err);
            }

            });
            }
        });
     });//Discharge Admission
  </script>


@endsection
