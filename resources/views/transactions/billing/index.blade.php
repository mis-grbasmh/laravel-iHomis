<script src="{{ asset('assets') }}/js/core/jquery.min.js"></script>
<script src="{{ asset('assets') }}/js/jquery.dataTables.min.js"></script>
<script src="{{ asset('assets') }}/css/jquery.dataTables.min.css"></script>

@extends('layouts.app', ['page' => 'Patient Charges', 'pageSlug' => 'transactions', 'section' => 'transactions'])
@section('content')
    <div class="row">
        <input type="hidden" id="enccode" name="enccode" value="">
        <input type="hidden" id="hpercode" name="hpercode" value="">
       <div class="col-lg-12 col-md-12">
        <div class="row">
                        <div class="col-6">
                <div class="card">
                    <div class="card-header">
                        <h6 class="title d-inline">Patient Information</h6>
                        <p class="card-category d-inline">Shows the patient personal details...</p>
                    </div>
                    <div class="card-body text-left py-4">
                        <h4 class="m-t-0 m-b-0"><strong></strong></h4>
                        <span>Health Record No.:   </span><br/>
                        <span>Gender/Age:  </span><br/>
                        <span>Address:   </span>
                    </div>
                </div>
            </div> <!-- col-6 -->
            <div class="col-6">
                <div class="card ">
                    <div class="card-header">
                        <h6 class="title d-inline">Admission Details</h6>
                        <p class="card-category d-inline">Shows the patient confinement informations...</p>
                    </div>
                    <div class="col-4 text-right pull-right">
                        <div class="col-sm-5">
                            <label><strong>Select Option</strong></label>
                          </div>
                    </div>
                    <div class="card-body text-left py-4">
                        <h4 class="m-t-0 m-b-0"><strong> </strong></h4>
                        <span>Admission:  </span><br/>
                        <span>Discharge: </span> <br/>
                        <span>Length of Stay in Days:  <td class="text-center"> day(s) </span>
                    </div>
                </div>
            </div> <!-- col-6 -->
        </div>

            <div class="card">

                <div class="card-header">
                    <div class="pull-right">

                    </div>
                    <div class="row align-items-center">

                        <div class="col-lg-12 col-md-12">
                            <div class="pull-right">
                                <ul class="nav nav-pills nav-pills-primary" >
                                <li class="nav-item">
                                    {{-- onclick="getdiet('{{$enccode}}');return false;" --}}
                                 <a class="nav-link" data-toggle="tab" href="#charges" title="Click to view Diet Orders" >
                                    Patient Charges
                                </a>
                                </li>&nbsp;
                                <li class="nav-item">
                                    {{-- onclick="get_roomcharges('{{$enccode}}');return false;" --}}
                                <a class="nav-link" data-toggle="tab" href="#rooms" title="Click to view Accomodation Room Charges" >
                                    Room Charges
                                </a>
                                </li>&nbsp;
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#profservice" title="Click to view Medication summary" >
                                        {{-- onclick="getradiologyorder('{{$enccode}}');return false;" --}}
                                        Professional Charges
                                    </a>
                                </li>&nbsp;
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#discounts" title="Click to view Medication summary" onclick="getmedication('{{$enccode}}');return false;">
                                    Discounts
                                    </a>
                                </li>&nbsp;
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#phicsoa">
                                    PHIC SOA
                                    </a>
                                </li>
                                </ul>
                    </div>

                            {{-- <a data-target="#modalPatientSearch" data-toggle="modal" class="btn btn-sm btn-primary" id="MainNavHelp" href="#modalPatientSearch">{{ __('Patient Search') }}</a>
                            <a data-target="#modalNewChargeitems" data-toggle="modal" class="btn btn-sm btn-primary" id="NewChargeitems" href="#modalNewChargeitems">{{ __('New Charges') }}</a>
                            <a data-target="#modalNewChargeitems" data-toggle="modal" class="btn btn-sm btn-primary" id="NewChargeitems" href="#modalNewChargeitems">{{ __('Room Charges') }}</a> --}}
                        </div>
                    </div>
              <div class="card-body">
                @include('alerts.success')
                  <div class="row">
                    <div class="tab-content tab-space">
                        <div class="tab-pane fade body" id="charges">
                       sadsad
                        </div>
                    <div class="tab-pane fade body" id="rooms">
                       Rooms
                    </div>
                    <div class="tab-pane fade body" id="profservice">
                        profservice

                    </div>
                    <div class="tab-pane fade body" id="discounts">
                        Disccounts
                    </div>
                    <div class="tab-pane fade body" id="phicsoa">
                      sdsd
                    </div>
                </div>

                      {{-- <div class="table-responsive"> --}}
                        <div class="table-full-width table-responsive ps ps--active-y">

                    <table id="example" class="display" style="width:100%" >
                        <thead class=" text-primary">
                             <th>CHARGE SLIP NO.</th>
                            <th class="text-center">TYPE OF CHARGE</th>
                                        <th class="text-center">ITEM</th>
                                        <th class="text-center">UOM</th>
                                        <th class="text-center">QTY</th>
                                        <th class="text-center">PRICE</th>
                                        <th class="text-center">AMOUNT</th>
                                        <th class="text-center">OR INCLUSION</th>
                                        <th>Actions</th>
                        </thead>

                            <tr>
                                <td colspan="4"></td>

                                <td><strong>Total Amount </strong></td>
                                {{-- <td colspan="2" class="text-right"><strong>@php echo number_format($total,2);  @endphp</strong></td> --}}
                                <td></td>
                                <td></td>
                            </tr>
                            <tr> No Charges Found!!!</tr>
                        </tbody>
                        </table>
                    </div>
                  </div>
              </div>
            </div>



<style>
    .modal { overflow: auto !important; }
    </style>

<div class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" role="dialog" id="modalPatientSearch" aria-labelledby="patientsearchModal" aria-hidden="true">
    @include('modals.patient_search')
  </div>

  <div class="modal fade" id="history" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
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
                                                 $("#history").modal({
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
                                    var url = '{{ route("patient.charges", ":id") }}';
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
