<script src="{{ asset('assets') }}/js/core/jquery.min.js"></script>
{{-- <script src="{{ asset('assets') }}/js/jquery.dataTables.min.js"></script>  --}}
{{-- <script src="{{ asset('assets') }}/css/jquery.dataTables.min.css"></script> --}}
@extends('layouts.app', ['page' => 'Transactions', 'pageSlug' => 'transactions', 'section' => 'transactions'])
@section('content')
<style type="text/css">
    a:hover {
      cursor:pointer;
    }
  </style>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-8">
                            <h4 class="card-title">{{ __('Current Admitted Patients') }} {{$countall}}</h4>

                        </div>
                        <div class="col-4 text-right pull-right">
                           <!-- <a data-toggle="modal" href="#"  data-target="#newdietorder"  data-backdrop="static" class="btn btn-info btn-sm">Print</i></a>
                            <a data-toggle="modal" href="#"  data-target="#newdietorder"  data-backdrop="static" class="btn btn-info btn-sm animation-on-hover">Import</i></a>-->
                            <a class="btn btn-sm btn-primary" href="#" onclick="printInpatientsList()" data-placement="bottom" data-print="/admission/inpatient_pdf">Print</a>
                            <a data-target="#modalPatientSearch" data-toggle="modal" class="btn btn-sm btn-primary" id="MainNavHelp" href="#modalPatientSearch">{{ __('Admit Patient') }}</a>
                            {{-- <a href="{{ route('admitting.foradmission') }}" class="btn btn-sm btn-primary">{{ __('For Admission') }}</a> --}}
                            <div class="dropdown">
                                Select Ward to View
                               {{-- <h6 class="title d-inline">Option</h6> --}}
                                <button type="button" class="btn btn-link dropdown-toggle btn-icon text-center" data-toggle="dropdown">
                                    <i class="tim-icons icon-settings-gear-63"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-right dropdown-white" aria-labelledby="dropdownMenuLink">
                                    <h6 class="dropdown-header">Select Ward</h6>
                                    {{-- href="{{ route('inpatients.index', ['id'=>$row->wardname]) }}" --}}
                                    @foreach ($wards as $key => $row)

                                        {{-- <a class="dropdown-item" onchange="handler(event);" >{{$row->wardname}}</a> --}}
                                        <a class="dropdown-item" href="#" onclick="getPatientList('{{$row->wardname}}');return false;">{{$row->wardname}}</a>
                                        {{-- <a class="dropdown-item" href="#" title="Click to do add Patient Charges" onclick="getPatientList('{{$row->wardname}}');return false;">{{$row->wardname}}</a> --}}
                                    @endforeach
                                        <a  class="dropdown-item" href="{{ route('inpatients.index',['']) }}">View All</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <h6 class="title d-inline">Total Male Admitted: {{$males}} Total Female Admitted: {{$females}} <br/> Pediatrics: {{$pedia}} OBSTETRICS: {{$ob}} MEDICAL: {{$meds}}SURGEY: {{$sur}}</h6>
                    {{-- <p>Legend: <span style="background-color:yellow;" class="badge badge-danger">&nbsp;</span> <strong>  For Discharge</strong></p> --}}
                </div>
                @include('alerts.success')
                <div class="card-body "><hr/>
                      {{--   <table class="table tablesorter " id="inpatient"> --}}
                        <div class="table-responsive">
                            <table id="inpatientsTable" class="display dataTable" cellspacing="0" width="100%" role="grid" aria-describedby="example_info" style="width: 100%;">
                            <thead class=" text-primary">
                                <th class="colspan">Patient Details</th>
                                <th class="colspan">Admission Details</th>
                                <th class="colspan">MSS Classification</th>
                                <th class="colspan">Doctor</th>
                                <th class="colspan">Clerk</th>
                                <th>Actions</th>
                            </thead>

                        </table>
                    </div>
                </div>
            </div>

<!-- start editmodal-->
<div class="modal fade" tabindex="-1" data-backdrop="static" role="dialog" id="modalAddAdmission" aria-labelledby="addModal" aria-hidden="true">
    @include('modals.admission_add')
 </div>

            <!-- start editmodal-->
<div class="modal fade" tabindex="-1" data-backdrop="static" role="dialog" id="modalEditAdmission" aria-labelledby="editModal" aria-hidden="true">
   @include('modals.admission_edit')
</div>


    <div class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" role="dialog" id="modalPatientSearch" aria-labelledby="patientsearchModal" aria-hidden="true">
    @include('modals.patient_search')
  </div>

   {{-- Discharge Patient Modal --}}
   <div class="modal fade" tabindex="-1" data-backdrop="static" role="dialog" id="modalDischargeAdmission" aria-labelledby="dischargeModal" aria-hidden="true">
       @include('modals.admission_discharge')
    </div>
    <div class="modal fade" tabindex="-1" data-backdrop="static" role="dialog" id="modalCoversheet" aria-labelledby="coversheetModal" aria-hidden="true">
        @include('modals.admission_coversheet')
     </div>

    <script>
    $(document).ready(function(){
        $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }

    });
        getPatientList('');
     });
   </script>
  <script>

    function getPatientList(e){
        var query = e;
        if(query){
                var url = '{{ route("inpatients.index", ":id") }}';
                url = url.replace(':id', query);
        }
        table = $('#inpatientsTable').DataTable({
        stateSave: false,
       // responsive: true,
        processing: true,
        serverSide : true,
        order : [1,'desc'],
        destroy: true,
        scrollX:true,
        scrollY:true,
        "lengthMenu": [
                                    [30, 50, 100, -1], //page length select box option values
                                    [30, 50, 100, "All"]],//page length select box option Text
        columnDefs: [{
        targets: [0],

        className: 'nw'
        }],
        "ajax": {
           url: url,
          method:'GET',
          data:{query:query},
          dataType:'json',
          error: function (errmsg) {
          alert('Unexpected Error');
          console.log(errmsg['responseText']);
          },
      },
        columns: [
              { "data": "patient" },
              { "data": "admission" },
              { "data": "msstype" },
              { "data": "doctor" },
              { "data": "clerk" },
              { "data": "actions" }
         ]
      });
                 }
  </script>
   <script>
    $('#inpatientsTable').on('click','.btnEdit[data-edit]',function(e){
        e.preventDefault();
        var id =$(this).data('id');
         var res = id.split('/').join('-');
              var url ='{{ route("admission.edit", ":id")}}';
              url = url.replace(':id', res);
        swal({
              title: "Are you sure want to Edit Admission?",
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
                            $('#id').val(data.enccode);
                            $('#edit_name').val(data.patientname);
                            $('#edit_admdate').val(data.admdate);
                            $('#edit_admnotes').val(data.admnotes);
                            $('#edit_admtxt').val(data.admtxt);
                            $('#edit_tacode').val(data.tacode);
                            $('#edit_tscode').val(data.tscode);
                            //  var $doctor = $('#edit_licno');
                            $('#edit_licno').val(data.licno);
                           // $doctor.append('<option selected value=' + data.licno + '>' + data.doctor + '</option>');
                           // var $admittype = $('#edit_tacode');


                           // $admittype.append('<option selected value=' + data.tacode + '>' + data.admissiontype + '</option>');
                            $('#edit_hsepriv').val(data.hsepriv);
                            //var $servicetype = $('#edit_hsepriv');
                            //$servicetype.append('<option selected value=' + data.hsepriv + '>' + data.servicetype + '</option>');
                            $('#patientage').val(data.patientage);
                            $('#newold').val(data.newold);
                            $("#modalEditAdmission").modal({
                            backdrop: 'static',
                            keyboard: false,
                            show: true,
                            });
                        }

                    });
                }
        });
    });//BTN-EDIT


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
                             $('#disch_diagcodeext').val(data.code);
                             $('#disc_diagtext').val(data.diagnosis);
                             $('#bed').val(data.bed);
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
    });

   // updating data infomation
    $('#btnUpdate').on('click',function(e){
       e.preventDefault();
       query = $('#id').val();
       var res = query.split('/').join('-');
        var url = '{{ route("admission.update", ":id") }}';
                url = url.replace(':id', res);

        var frm = $('#frmDataEdit');
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

//View Inpatient List
function printInpatientsList(){

        //e.preventDefault();
       // var id =$(this).data('id');
       // var res = id.split('/').join('-');
        var url ='{{ route("admission.print_inpatientslist")}}';
             // url = url.replace(':id', res);
             document.location.href=url;
    };

    //View Clinical COver Sheet
    $('#inpatientsTable').on('click','.btnAdmissionSlip[data-admissionslip]',function(e){
        e.preventDefault();
        var id =$(this).data('id');
        var res = id.split('/').join('-');
        var url ='{{ route("admission.admissionslip", ":id")}}';
              url = url.replace(':id', res);
              document.location.href=url;
    });

    //View Clinical Abstract
    $('#inpatientsTable').on('click','.btnClinicalAbstract[data-clinicalabstract]',function(e){
        e.preventDefault();
        var id =$(this).data('id');
        var res = id.split('/').join('-');
        var url ='{{ route("admitting.clinicalabstract", ":id")}}';
              url = url.replace(':id', res);
              document.location.href=url;
    });

     //View Clinical COver Sheet
     $('#inpatientsTable').on('click','.btnCoversheet[data-coversheet]',function(e){
        e.preventDefault();
      //  alert(me);
        var id =$(this).data('id');
        var res = id.split('/').join('-');
        var url ='{{ route("admitting.coversheet", ":id")}}';
              url = url.replace(':id', res);
              document.location.href=url;
    });

   //View Patient Doctors
   $('#inpatientsTable').on('click','.btnAdmissionDoctors[data-admissiondoctor]',function(e){
        e.preventDefault();
        var id =$(this).data('id');
        var res = id.split('/').join('-');
        var url ='{{ route("admission.admissiondoctors", ":id")}}';
        url = url.replace(':id', res);
        document.location.href=url;
    });

     //View Patient Doctors
   $('#inpatientsTable').on('click','.btnAdmissionRooms[data-admissionrooms]',function(e){
        e.preventDefault();
        var id =$(this).data('id');
        var res = id.split('/').join('-');
        var url ='{{ route("admission.admissionrooms", ":id")}}';
        url = url.replace(':id', res);
        document.location.href=url;
    });


$(document).on('change', '#edit_tacode', function(){
                    var query = $(this).val();
                  //  alert(query);
                     if(query =='ADPAY'){
                    // fetch_customer_data(query);
                    // }else{
                         var hsepriv = 'HP';
                         $('#edit_hsepriv').val('HP');
                    //     Table.innerHTML = "";
                     }else{
                        $('#edit_hsepriv').val('CP');
                     }
                });
    </script>
    <script>
        $('#tableitems tbody').on('click', 'td', function () {
            var currentRow=$(this).closest("tr");
            var query=currentRow.find("td:eq(0)").text();
            //console.log(query)      //call t
           // alert(query) ;     //call t
            $('#modalPatientSearch').modal('hide');
            $('#tableItems').DataTable().destroy();

            var url ='{{ route("admissions.add", ":id")}}';
            url = url.replace(':id', query);
            $.ajax({
                    url:url,
                    type : 'GET',
                    datatype : 'json',
                    success:function(data){
                            $('#add_id').val(data.hpercode);
                             $('#add_name').val(data.patient_name);
                             $('#add_newold').val(data.oldnew);
                          //   $('#css_licno').val(data.licno);
                        //     $('#css_name').val(data.patientname);
                            $("#modalAddAdmission").modal({
                            backdrop: 'static',
                            keyboard: false,
                            show: true,
                            });
                        }

                     });


       });
     </script>
@endsection
