<script src="{{ asset('assets') }}/js/core/jquery.min.js"></script>
<script src="{{ asset('assets') }}/js/jquery.dataTables.min.js"></script>
<script src="{{ asset('assets') }}/css/jquery.dataTables.min.css"></script>

@extends('layouts.app', ['page' => 'Patient Charges', 'pageSlug' => 'transactions', 'section' => 'transactions'])
@section('content')
<style>th { font-size: 12px; }
    td { font-size: 13px; }
    </style>
    <div class="row">
        <input type="hidden" id="enccode" name="enccode" value="{{$enccode}}">
        <input type="hidden" id="hpercode" name="hpercode" value="{{$hpercode}}">
       <div class="col-lg-12 col-md-12">
        <div class="row">

                <div class="card">
                    <div class="card-header">
                        <h6 class="title d-inline">Patient Information</h6>
                        <p class="card-category d-inline">Shows the patient personal details...</p>
                        <div class="col-4 text-right pull-right">
                            <a data-target="#modalPatientSearch" data-toggle="modal" class="btn btn-sm btn-primary" id="MainNavHelp" href="#modalPatientSearch">{{ __('Patient Search') }}</a>
                            @if($enccode)
                            <a data-target="#modalhistory" data-toggle="modal" class="btn btn-sm btn-primary" id="MainNavHelp" href="#modalhistory" onclick='return gethistory()'>{{ __('Select History') }}</a>
                            @endif
                        </div>
                    </div>
                    <hr>
                    <div class="card-body">
                        <div class="form-row">
                            <div class="form-group col-md-4">
                              <label for="inputName">Patient Name:<br/> <strong><span id="inputName">{{$patientname }}</span></strong></label>
                              {{-- <input type="text" class="form-control" id="inputName" value="{{$patientname }}" disabled> --}}
                            </div>
                            <div class="form-group col-md-2">
                              <label for="inputHpercode">Health Record No.:<br/><strong><span id="inputHpercode">{{$hpercode}}</span></strong></label>
                              {{-- <input type="text" class="form-control" id="inputHpercode" value="{{$hpercode }}" disabled> --}}

                            </div>
                            <div class="form-group col-md-2">
                              <label for="inputDatetime">Encounter Details:<br/><strong><span id="inputDatetime">{{getLongDateFormat($admdate) }} {{$admtime}}</span></strong></label>
                              {{-- <input type="text" class="form-control" id="inputDatetime" value="" disabled> --}}
                            </div>
                            <div class="form-group col-md-2">
                                <label for="inputDatetime">Accomodation:<br/><strong><span id="inputaccomptype">{{$accomptype}}</span></strong></label>
                                {{-- <input type="text" class="form-control" id="inputaccomptype"  value="{{$accomptype }}" disabled> --}}
                              </div>
                            <div class="form-group col-md-2">
                                <label for="inputDatetime">Membership:<br/><strong><span id="inputMembership">{{$accomptype}}</span></strong></label>
                                {{-- <input type="text" class="form-control" id="inputMembership" disabled> --}}
                            </div>
                          </div>
                          </div>

{{--
                    <div class="card-body text-left py-4">
                        <h4 class="m-t-0 m-b-0"><strong> @if($admdiagnosis) {{ getpatientinfo($admdiagnosis->hpercode)}} @endif</strong></h4>
                        <span>Health Record No.:   @if($admdiagnosis) {{  $admdiagnosis->hpercode}} @endif </span><br/>
                        <span>Gender:  @if($admdiagnosis) {{  $admdiagnosis->patsex}} @endif </span><br/>
                        <span>Address:  @if($admdiagnosis) {{  getPatientAddress($admdiagnosis->hpercode)}} @endif </span>
                    </div> --}}
                </div>
            </div> <!-- col-6 -->
            {{-- <div class="col-6">
                <div class="card ">
                    <div class="card-header">
                        <h6 class="title d-inline">Admission Details</h6>
                        <p class="card-category d-inline">Shows the patient confinement informations...</p>
                    </div>
                    <div class="card-body text-left py-4">
                        <h4 class="m-t-0 m-b-0"><strong> @if($admdiagnosis) DR. {{ getdoctorinfo($admdiagnosis->licno)}} @endif</strong></h4>
                        <span>Admission: @if($admdiagnosis) @if($admdiagnosis->admdate <> NULL) {{$admdiagnosis->admdate}} @endif @endif</span><br/>
                        <span>Discharge: @if($admdiagnosis) @if($admdiagnosis->disdate <> NULL) {{$admdiagnosis->disdate}} @endif @endif</span> <br/>
                        <span>Length of Stay in Days:  <td class="text-center">@if($admdiagnosis) @if($admdiagnosis->admdate <> NULL) {{\Carbon\Carbon::parse($admdiagnosis->admdate)->diffInDays(\Carbon\Carbon::parse($admdiagnosis->disdate))}} @endif @endif day(s) </span>
                    </div>
                </div>
            </div> <!-- col-6 --> --}}
        </div><!-- row -->
                <div class="card">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h5 class="mb-0"><strong>Patient's Admission Charges</strong></h5>
                            </div>
                        </div>
                        <div class="nav nav-tabs  pull-right" role="tablist">
                            <div class="btn-group btn-group-toggle float-right" >
                                <label class="btn btn-sm btn-primary btn-simple" id="0">
                                <input id="optDaily" checked name="intervaltype" type="radio" data-target="#itemscharges" onclick="get_itemscharges('{{$enccode}}');return false;">
                                <span class="d-none d-sm-block d-md-block d-lg-block d-xl-block">Patient Charges</span>
                                <span class="d-block d-sm-none">
                                <i class="tim-icons icon-money-coins"></i>
                                </span>
                            </div>
                            <div class="btn-group btn-group-toggle float-right" >
                                <label class="btn btn-sm btn-primary btn-simple" id="1">
                                <input type="radio" class="d-none d-sm-none" name="intervaltype" data-target="#rooms" onclick="get_roomcharges('{{$enccode}}');return false;">
                                <span class="d-none d-sm-block d-md-block d-lg-block d-xl-block">Laboratory</span>
                                <span class="d-block d-sm-none">
                                        <i class="tim-icons icon-single-copy-04"></i>
                                        </span>
                                        </label>
                                    </div>
                            <div class="btn-group btn-group-toggle float-right" >
                                <label class="btn btn-sm btn-primary btn-simple" id="2">
                                <input type="radio" class="d-none d-sm-none" name="intervaltype" data-target="#profservice" onclick="get_profservcharges('{{$enccode}}');return false;">
                                <span class="d-none d-sm-block d-md-block d-lg-block d-xl-block">Professional Charges</span>
                                <span class="d-block d-sm-none">
                                <i class="tim-icons icon-single-02"></i>
                                </span>
                                </label>
                            </div>
                            <div class="btn-group btn-group-toggle float-right" >
                                <label class="btn btn-sm btn-primary btn-simple" id="3">
                                <input type="radio" class="d-none d-sm-none" title="Click to view Itemize Charges" name="intervaltype" data-target="#drugsmeds" onclick="get_drugmedscharges('{{$enccode}}');return false;">
                                <span class="d-none d-sm-block d-md-block d-lg-block d-xl-block">Drugs and Medications</span>
                                <span class="d-block d-sm-none">
                                <i class="tim-icons icon-cart"></i>
                                </span>
                                </label>
                            </div>
                            <div class="btn-group btn-group-toggle float-right" >
                                <label class="btn btn-sm btn-primary btn-simple" id="4">
                                <input type="radio" class="d-none d-sm-none" name="intervaltype" data-target="#rooms" onclick="get_roomcharges('{{$enccode}}');return false;">
                                <span class="d-none d-sm-block d-md-block d-lg-block d-xl-block">Room Charges</span>
                                <span class="d-block d-sm-none">
                                        <i class="tim-icons icon-single-copy-04"></i>
                                        </span>
                                        </label>
                                    </div>

                            <div class="btn-group btn-group-toggle float-right" >
                                <label class="btn btn-sm btn-primary btn-simple" id="5" disabled>
                                <input type="radio" class="d-none d-sm-none" name="intervaltype" data-target="#phicsoa" onclick="get_drugmedscharges('{{$enccode}}');return false;">
                                <span class="d-none d-sm-block d-md-block d-lg-block d-xl-block">PHIC SOA</span>
                                <span class="d-block d-sm-none">
                                        <i class="tim-icons icon-single-copy-04"></i>
                                        </span>
                                        </label>
                                    </div>
                                </div>
                    </div>

                        {{-- <div class="tab-content">
                            <div id="scheduleDaily" class="tab-pane active">Daily</div>
                            <div id="scheduleWeekly" class="tab-pane">Weekly</div>
                            <div id="scheduleMonthly" class="tab-pane">Montly</div>
                        </div> --}}


                    <div class="tab-content tab-space active">
                        <div class="tab-pane fade body" id="itemscharges">
                            @include('transactions.billing.items_charges')
                        </div>
                    <div class="tab-pane fade body" id="rooms">
                        @include('transactions.billing.room_charges')
                    </div>
                    <div class="tab-pane fade body" id="profservice">
                        @include('transactions.billing.profserv_charges')
                    </div>
                    <div class="tab-pane fade body" id="drugsmeds">
                        @include('transactions.billing.drugsmeds_charges')
                        {{-- Drugs and Meds --}}
                    </div>
                    <div class="tab-pane fade body" id="phicsoa">
                      sdsd
                    </div>
                </div>


                  </div>
              </div>
            </div>



<style>
    .modal { overflow: auto !important; }
    </style>

<div class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" id="modalPatientSearch" aria-labelledby="patientsearchModal" aria-hidden="true">
    @include('modals.patient_search')
  </div>

  <div class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" id="modalhistory" tabindex="-1"  aria-labelledby="patienthistoryModal" aria-hidden="true">
    {{-- <div class="modal fade" id="history" role="dialog"> --}}
      @include('modals.patient-history')
    </div><!-- modal-history-->

    <div class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" role="dialog" id="modalNewChargeitems" aria-labelledby="newchargeModal" aria-hidden="true">
        @include('modals.patientcharges_add')
    </div>
<script type="text/javascript">
   $(document).ready(function(){
                                       // Display Modal
                                      var  id = document.getElementById("enccode").value;
                                      if(id==''){
                                          $("#patientsearchModal").modal({
                                                              backdrop: 'static',
                                                              keyboard: false,
                                                              show: true,
                                                              });
                                      }
                                      });
                                      $('#tableitems tbody').on('click', 'td', function () {

                          var currentRow=$(this).closest("tr");
                            var query=currentRow.find("td:eq(0)").text();
                            //console.log(query)      //call t
                            $('#modalPatientSearch').modal('hide');
                            $('#tableItems').DataTable().destroy();
                            $('#history').modal('show');
                            if(query !=''){
                                // fetch_patient_history();
                               document.getElementById("hpercode").value = query;
                               var queryres = $('#hpercode').value;
                                    $.ajax({
                                        url:"{{ route('getPatient.history') }}",
                                        method:'GET',
                                        data:{query:query},
                                        dataType:'json',
                                        success:function(data)
                                        {
                                                 $('#history_table').html(data.table_data);
                                                // $('#history').modal('show');
                                                 $("#modalhistory").modal({
                                                backdrop: 'static',
                                                keyboard: false,
                                                show: true,
                                                });
                                                //$('#tableHistory').html(data.table_data);
                                                $('#total_history').text(data.total_data);
                                        }
                                    });
                             }
                        });

                        $('#tableHistory tbody').on('click', 'tr', function () {
                                var currentRow=$(this).closest("tr");
                                var history=currentRow.find("td:eq(0)").text();
                                var query = history;
                                $('#history').modal('hide');
                                alert(query)
                                if(query){
                                    var res = query.split('/').join('-');
                                    var url = '{{ route("billing.soa", ":id") }}';
                                    url = url.replace(':id', res);
                                    document.location.href=url;
                                }
                                else{
                                    alert('Please ')
                                }
                            });


                       $(document).ready(function() {
                        $('#example').DataTable( {
                            dom: 'Bfrtip',
                            buttons: [
                                'copy', 'csv', 'excel', 'pdf', 'print'
                            ]
                        } );
                    } );
</script>


@endsection
