<script src="{{ asset('assets') }}/js/core/jquery.min.js"></script>
@extends('layouts.app', ['page' => 'Patients', 'pageSlug' => 'animalbites', 'section' => 'animalbites'])

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card ">
                <div class="card-header">
                    <div class="row">
                        <div class="col-8">
                            <h4 class="card-title">Animal Bite Patients</h4>
                        </div>
                        <div class="col-4 text-right">
                            <a href="" class="btn btn-sm btn-primary">Search Patient</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @include('alerts.success')

                    <div class="table-responsive">
                        <table id="animalbiteTable" class="display" cellspacing="0" style="width:100%" >

                            <thead class=" text-primary">
                                <th>Patient Name</th>
                              <th>Encounter Type</th>
                                  <th>Consultation Details</th>
                                  <th>Physician</th>
                                  <th>Discharge Date</th>
                                 <th></th>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer py-4">
                    <nav class="d-flex justify-content-end" aria-label="...">
                        {{-- {{ $clients->links() }} --}}
                    </nav>
                </div>
            </div>
        </div>
    </div>

 {{-- Discharge Patient Modal --}}
 <div class="modal fade" tabindex="-1" data-backdrop="static" role="dialog" id="modalAnimalBiteEdit" aria-labelledby="dischargeModal" aria-hidden="true">
    @include('modals.animalbite_edit')
 </div>
    {{-- Discharge Patient Modal --}}
 <div class="modal fade" tabindex="-1" data-backdrop="static" role="dialog" id="modalDischargeOPD" aria-labelledby="dischargeModal" aria-hidden="true">
    @include('modals.opd_discharge')
 </div>


    <script>
          $(document).ready(function(){
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });//end ajax
            table = $('#animalbiteTable').DataTable({
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
                url: "{{route('animalbites.get_patientlist')}}",
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
                { "data": "type" },
                { "data": "admission" },
                { "data": "doctor" },
                { "data": "dischargedate" },
                { "data": "actions" }
            ]
        });
    //  { "data": "diagnosis"},
    });

    $('#animalbiteTable').on('click','.btnAnimalBiteForm[data-animalbiteform]',function(e){
        e.preventDefault();
        var id =$(this).data('id');
        // var res = id.split('/').join('-');
        var url ='{{ route("animalbite.form", ":id")}}';
              url = url.replace(':id', id);
              document.location.href=url;
    });


    $('#animalbiteTable').on('click','.btnDischarge[data-discharge]',function(e){
        e.preventDefault();
        //var id =$(this).data('id');
         var query =$(this).data('id');
        // var res = id.split('/').join('-');
        // var url ='{{ route("opd.dischargeinfo", ":id")}}';
        //       url = url.replace(':id', res);
        var url ='{{ route("opd.dischargeinfo")}}';
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
                    data:{query:query},
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
                            $("#modalDischargeOPD").modal({
                            backdrop: 'static',
                            keyboard: false,
                            show: true,
                            });
                        }

                    });
                }
        });
    });


    $('#animalbiteTable').on('click','.btnEdit[data-edit]',function(e){
        e.preventDefault();
        var id =$(this).data('id');
        // var res = id.split('/').join('-');
              var url ='{{ route("animalbitelog.edit", ":id")}}';
              url = url.replace(':id', id);
        swal({
              title: "Are you sure want to Edit OPD Log?",
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
                          //  $('#edit_admdate').val(data.admdate);
                          //  $('#edit_admnotes').val(data.admnotes);
                            // $('#edit_admtxt').val(data.admtxt);
                            // $('#edit_tacode').val(data.tacode);
                            // $('#edit_tscode').val(data.tscode);
                            //  var $doctor = $('#edit_licno');
                            $('#edit_licno').val(data.licno);
                           // $doctor.append('<option selected value=' + data.licno + '>' + data.doctor + '</option>');
                           // var $admittype = $('#edit_tacode');


                           // $admittype.append('<option selected value=' + data.tacode + '>' + data.admissiontype + '</option>');
                          ////  $('#edit_hsepriv').val(data.hsepriv);
                            //var $servicetype = $('#edit_hsepriv');
                            //$servicetype.append('<option selected value=' + data.hsepriv + '>' + data.servicetype + '</option>');
                        //    $('#patientage').val(data.patientage);
                       //     $('#newold').val(data.newold);
                            $("#modalAnimalBiteEdit").modal({
                            backdrop: 'static',
                            keyboard: false,
                            show: true,
                            });
                        }

                    });
                }
        });
    });//BTN-EDIT


    </script>
@endsection
