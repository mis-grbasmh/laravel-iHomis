<script src="{{ asset('assets') }}/js/core/jquery.min.js"></script>
<script src="{{ asset('assets') }}/js/jquery.dataTables.min.js"></script> 
<script src="{{ asset('assets') }}/css/jquery.dataTables.min.css"></script>

@extends('layouts.app', ['page' => 'Patient Charges', 'pageSlug' => 'transactions', 'section' => 'transactions'])
@section('content')
    <div class="row">
       <div class="col-lg-12 col-md-12">
        <div class="row">
            <div class="col-6">
                <div class="card">
                    <div class="card-header">
                        <h6 class="title d-inline">Patient Information</h6>
                        <p class="card-category d-inline">Shows the patient personal details...</p>
                    </div>
                    <div class="card-body text-left py-4">
                        <h4 class="m-t-0 m-b-0"><strong> @if($admdiagnosis) {{ getpatientinfo($admdiagnosis->hpercode)}} @endif</strong></h4>
                        <span>Health Record No.:   @if($admdiagnosis) {{  $admdiagnosis->hpercode}} @endif </span><br/>
                        <span>Gender:  @if($admdiagnosis) {{  $admdiagnosis->patsex}} @endif </span><br/>
                        <span>Address:  @if($admdiagnosis) {{  getPatientAddress($admdiagnosis->hpercode)}} @endif </span>
                    </div>
                </div>
            </div> <!-- col-6 -->
            <div class="col-6">
                <div class="card ">
                    <div class="card-header">
                        <h6 class="title d-inline">Admission Details</h6>
                        <p class="card-category d-inline">Shows the patient confinement informations...</p>
                     
                        <div class="pull-right">
                            <a data-toggle="modal" href="#"  data-target="#newdietorder"  data-backdrop="static" class="btn btn-defaultb btn-sm">Patient Search</i></a>
                        </div>
                    </div>
                    <div class="card-body text-left py-4">
                        <h4 class="m-t-0 m-b-0"><strong> @if($admdiagnosis) DR. {{ getdoctorinfo($admdiagnosis->licno)}} @endif</strong></h4>
                        <span>Admission: @if($admdiagnosis) @if($admdiagnosis->admdate <> NULL) {{$admdiagnosis->admdate}} @endif @endif</span><br/>
                        <span>Discharge: @if($admdiagnosis) @if($admdiagnosis->disdate <> NULL) {{$admdiagnosis->disdate}} @endif @endif</span> <br/>
                        <span>Length of Stay in Days:  <td class="text-center">@if($admdiagnosis) @if($admdiagnosis->admdate <> NULL) {{\Carbon\Carbon::parse($admdiagnosis->admdate)->diffInDays(\Carbon\Carbon::parse($admdiagnosis->disdate))}} @endif @endif day(s) </span>
                    </div>
                </div>
            </div> <!-- col-6 -->
        </div>

            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-8">
                            <h3 class="mb-0">Patient Charges List</h3>
                        </div>
                        <div class="col-4 text-right">
                            @if($enccode) <a data-toggle="modal" href="#"  data-target="#newdietorder"  data-backdrop="static" class="btn btn-primary btn-sm">New Charges</i></a>@endif
                        </div>
                    </div>
              <div class="card-body">
                @include('alerts.success') 
                  <div class="row">
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
                        @php $total=0; @endphp
                        @if($patientcharges)
                            @foreach ($patientcharges as $key => $row)
                                @php
                                    $total += $row->pcchrgamt;
                                @endphp
                                <tr>
                                    <td>{{ $row->pcchrgcod}}</td>
                                    <td>{{ $row->chrgdesc}}</td>
                                    <td>{{ getItem_desc($row->itemcode,$row->chargcode)}}</td>
                                    <td class="text-center">{{ $row->uomcode}}</td>
                                    <td class="text-center">{{ $row->pchrgqty}}</td>
                                    <td class="text-right">{{ $row->pchrgup}}</td>
                                    <td class="text-right">{{ $row->pchrgqty * $row->pchrgup}}</td>
                                    <td></td>
                                    <td class="text-center py-0 align-middle">
                                        <div class="dropdown">
                                            <button type="button" class="btn btn-link dropdown-toggle btn-icon" data-toggle="dropdown" aria-expanded="false">
                                             <i class="tim-icons icon-settings-gear-63"></i>
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-center" aria-labelledby="dropdownMenuLink" x-placement="top-end" style="position: absolute; transform: translate3d(-122px, -341px, 0px); top: 0px; left: 0px; will-change: transform;" x-out-of-boundaries="">
                                                <a href="#editmodal" data-toggle="modal" data-hpercode="{{$row->itemcode}}" data-enccode="{{ $enccode }} " data-target="#editerlog" class="dropdown-item"><i class="fa fa-edit"></i> Edit</a>
                                                <a data-toggle="modal" data-itemcode="{{ $row->itemcode}}" data-enccode="{{ $enccode}}" href="#dischargemodal"  data-target="#erdischarge" class="dropdown-item"><i class="fa fa-warning"></i>Delete</a>
                                            </div>
                                        </div>   

                                        
                                </tr>

                            @endforeach
                            <tr>
                                <td colspan="4"></td>

                                <td><strong>Total Amount </strong></td>
                                <td colspan="2" class="text-right"><strong>@php echo $total;  @endphp</strong></td>
                                <td></td>
                                <td></td>
                            </tr> @else
                            <tr> No Charges Found!!!</tr>
                        @endif
                        </tbody>
                        </table>
                    
                  </div>
              </div>
            </div>
        <div class="row">
            
            <div class="card-body ">
                
       
                <div class="">
                    
                       
                    </div>
                </div>
            </div>

           
    

   
   

    
  
  

   


<script type="text/javascript">
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
