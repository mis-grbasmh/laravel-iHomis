<script src="{{ asset('assets') }}/js/core/jquery.min.js"></script>
@extends('layouts.app', ['page' => 'Transactions', 'pageSlug' => 'transactions', 'section' => 'transactions'])
@section('content')
<style>
     .ellipsis{
        display: inline-block;
        width: 200px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
        th { font-size: 12px; }
        td { font-size: 13px; }
  </style>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h6 class="title d-inline">Total Discharges: <strong>{{$discharges->count()}}</strong>  </h6>
                    <p class="card-category d-inline">as of {{getLongDateFormat($date)}}</p>
                    <div class="pull-right">
                        <div class="col-lg-12">
                            <div class="row">
                                <div class="col-sm-3">
                                    <label><strong>Select Date Discharge Date:</strong></label>
                                  </div>
                                  <div class="col-sm-4">
                                    <input type="date" id="date2" name="disdate" onchange="handler(event);" value="{{ old('date', $date) }}" class="form-control floating-label" step="any" required>
                                  </div>
                                  <div class="col">
                                  @if($discharges->count() > 0)<a class="btn btn-sm btn-info btn-round"  id="btn_print" title="Click to do print report">{{ __('Print') }}</a>@endif
                                  <a data-target="#modalsummary" data-toggle="modal" class="btn btn-sm btn-info btn-round" id="MainNavHelp" href="#modalsummary">{{ __('View Summary') }}</a>
                                  </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body ">
                    @include('alerts.success')
                 <div class="table-responsive">
                    <table id="norasysTable" class="display" width="100%">
                                <thead class=" text-primary">
                                <th class="text-center" style="display:none;"></th>
                                    <th class="text-center">#</th>
                                <th style="width: 30%;" class="text-center">Patient Name</th>
                                <th class="text-center">Confinement<br/> Details</th>
                                <th class="text-center">Room <br/>Assignment</th>
                                <th class="text-center">Member</th>
                                <th class="text-center">Type</th>
                                <th class="text-center">NBB</th>
                                <th class="text-center">Final Diagnosis</th>
                                <th class="text-center">Doctor</th>
                                <th class="ext-center">Actual HCI</th>
                                <th class="ext-center">Actual PF</th>
                                <th class="text-center">HCI</th>
                                <th class="text-center">PF</th>
                               
                                <th class="text-center">Philhelth <br/> Claim Amount</th>
                                <th class="text-center">Eclaims <br/>Status</th>
                                <th class="text-center">Options</th>
                            </thead>
                            <tbody>
                                @foreach($discharges as $key => $row)


                                <tr>
                                  <td style="display:none;">{{$row->enccode}}</td>
                                    <td>{{$key+1}}</td>
                                    <td style="width: 20%;"><strong>{{getpatientinfo($row->hpercode)}}</strong><br/> {{ number_format($row->patage) }} / {{ $row->patsex }}
                                        <br/>
                                        <strong>{{$row->hpercode}}</strong>
                                    <br/>
                                   <span class="ellipsis"> {{ get_reltomember($row->reltomem)}}</span>
                                    </small></td>
                                        <td class="text-left"><strong>{{getFormattedDate($row->admdate)}} - {{getFormattedDate($row->disdate)}}</strong>

                                        </td>
                                        <td class="text-center"><strong>
                                             {{$row->wardname}}-{{$row->rmname}}-{{$row->bdname}}</strong><br/>
                                            <span class="badge badge-info">@if($row->tacode =='SERVI') Service @else Pay @endif</span> </td>
                                        <td class="text-left">
                                            <strong>
                                            {{ $row->memfirst}} {{ $row->memmid}} {{ $row->memlast}}</strong> <br/>
                                            <span class="badge badge-info">{{ $row->phicnum }}</span>
                                        
                                        </td>
                                        <td> {{PhicMembershiptype($row->typemem)}} </td>
                                        <td class="text-center">
                                            @if($row->hsepriv == 'CP')<span class="badge badge-info">YES</span>@else<span class="badge badge-warning">NO</span>@endif
                                            {{-- @if($row->hsepriv == 'CP')<span class="badge badge-info">YES</span><span class="badge badge-warning">NO</span> @else @endif --}}
                                        </td>

                                    <td style="width: 50%;" class="text-left">
                                       {{ getPhicDiagosis($row->enccode)}} 
                                    </td>
                                    <td  style="width: 40%;" >
                                        DR. {{ getdoctorinfo($row->licno)}}
                                        <br/> {{ $row->tsdesc}} 
                                        <br/><span class="badge badge-info">{{ $row->hsepriv}}</span> 
                                    </td>
                                    <td>
                                        {{-- <small>{{ get_ActualHCI($row->enccode)}} </small> --}}
                                         {{number_format($row->ptotalactualchargeshci,2)}} 
                                   </td>
                                     <td>
                                         {{-- <small> {{ get_ActualPF($row->enccode) }}</small> --}}
                                          {{ number_format($row->ptotalactualchargespf,2)}}
                                    </td>
                                    <td>
                                        {{-- <small>  {{get_firstcaseHCI($row->enccode)}}</small> --}}
                                      {{ number_format($row->philhealthbenehci,2)}}
                                        
                                      
                                      </td>
                                    <td>
                                      {{-- <small>{{ get_firstcasePF($row->enccode) }}</small> --}}
                                      {{ number_format($row->philhealthbenepf,2) }}
                                    </td>
                                   

                                      <td>
                                        {{get_philhealthamount($row->enccode)}}
                                      </td>
                                      <td>
                                        {{get_eclaimstatus($row->enccode)}}
                                      </td>
                                    <td class="text-center">
                                        <div class="dropdown">
                                            <button type="button" class="btn btn-link dropdown-toggle btn-icon" data-toggle="dropdown" aria-expanded="false">
                                             <i class="tim-icons icon-settings-gear-63"></i>
                                            </button>

                                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink" x-placement="top-end" style="position: absolute; transform: translate3d(-122px, -341px, 0px); top: 0px; left: 0px; will-change: transform;" x-out-of-boundaries="">
                                                {{-- <a class="dropdown-item" href="#" title="Click to do add Patient Charges" onclick="undodischarge('{{$row->enccode}}');return false;">Undo Discharge</a> --}}

                                                {{-- <a data-toggle="modal" data-id="{{ $row->enccode}}" data-hpercode="{{ $row->hpercode}}" data-licno="{{ $row->licno}}" data-patient="{{getpatientinfo($row->hpercode)}}" href="#"  data-target="#editdischarge" class="dropdown-item editdischarge">Edit Discharge Date</i></a> --}}


                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {{-- <div class="card-footer py-4">
                            <nav class="d-flex justify-content-end" aria-label="...">
                            {{ $discharges->links() }}
                            </nav>
                        </div> --}}
                    </div>
                </div>
            </div>


             <div class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" id="modalsummary" tabindex="-1"  aria-labelledby="modalsummaryModal" aria-hidden="true">
                {{-- <div class="modal fade" id="history" role="dialog"> --}}
                  @include('modals.norasys-summary')
                </div><!-- modal-history-->

            <script>
                function handler(e){

                  var query = e.target.value;
                  if(query){
                            var url = '{{ route("phic.norasys", ":id") }}';
                            url = url.replace(':id', query);
                            document.location.href=url;
                         }
                         else{
                             alert('Please ')
                         }
                }//end function handler
                </script>
  <script>


    $(document).on("click", ".editdischarge", function () {
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

<script type="text/javascript">
    $(document).ready(function() {
     $('#norasysTable').DataTable( {
         dom: 'Bfrtip',
         order : [1,'asc'],
         buttons: [
             'copy', 'csv', 'excel', 'pdf', 'print'
         ]
     } );
 } );
 </script>


<script>
    $('#btn_print').on('click', function () {
        var query =  document.getElementById("date2").value
        if(query){
            var url = '{{ route("phic.norasys_report", ":id") }}';
            url = url.replace(':id', query);
            document.location.href=url;
         }
    });//
 </script>
            @endsection
