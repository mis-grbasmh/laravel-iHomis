<script src="{{ asset('assets') }}/js/core/jquery.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.24/datatables.min.css"/>
<script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.24/datatables.min.js"></script>
@extends('layouts.app', ['page' => 'Patient CF4', 'pageSlug' => 'transactions', 'section' => 'phic'])
@section('content')
@include('partials.message')
    <div class="row">
       <div class="col-lg-12 col-md-12">
            <div class="card">
                <div class="card-header">
                  <div class="row">
                      <input type="hidden" id="enccode" name="enccode" value="{{$enccode}}">
                      <input type="hidden" id="hpercode" name="hpercode" value="{{$hpercode}}">
                      <div class="col-sm-6 text-left">
                        <h4 class="card-title">Patient CF4 of <strong>{{ getpatientinfo($hpercode)}}</strong></h4>
                        <p class="card-category d-inline">sadsdsad</p>
                      </div>
                  </div>
                  <div class="pull-right">
                    

                    <ul class="nav nav-pills nav-pills-primary" >
                        <li class="nav-item">
                          <a class="nav-link active" data-toggle="tab" href="#pertinentsigns"  id="#pertinentsigns" title="Click to view Medication summary" onclick="getPertinentsigns('{{$enccode}}');return false;">
                            Pertinent Signs And Symptoms On Admission
                          </a>
                        </li>&nbsp;
                        <li class="nav-item">
                          <a class="nav-link" data-toggle="tab" href="#radiology">
                            Radiology/CT-Scans
                          </a>
                        </li>&nbsp;
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#medications" title="Click to view Medication summary" onclick="getmedication('{{$enccode}}');return false;">
                            
                                Medication
                            </a>
                          </li>&nbsp;
                          <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#diet" title="Click to view Diet Orders" onclick="getdiet('{{$enccode}}');return false;">
                            {{-- <a class="nav-link" data-toggle="tab" href="#diet"> --}}
                                Diet
                            </a>
                          </li>&nbsp;
                          <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#fordischarge">
                                For Discharge
                            </a>
                          </li>
                      </ul>
                </div> 
            </div>
            <div class="tab-content tab-space">
                <div role="tabpanel active" class="tab-pane fade body active" id="pertinentsigns">
                @include('transactions.phic.admission_cmplaint')
            </div>
            <div class="tab-pane fade body" id="radiology">
                
                {{-- @include('transactions.doctors.order_radiology') --}}
            </div>
            <div class="tab-pane fade body" id="medications">
                {{-- @include('transactions.doctors.order_medication') --}}
              
            </div>
            <div class="tab-pane fade body" id="diet">
                {{-- @include('transactions.doctors.order_diet') --}}
            </div>
            <div class="tab-pane fade body" id="fordischarge">
              {{-- @include('transactions.doctors.order_discharge') --}}
            </div>
        </div>
@endsection


{{-- <script>
  $(document).ready(function(){
    var d = new Date();
    var dodate = document.getElementById("dodate"); 
    var dodtepost = document.getElementById("dodtepost"); 
    dodate.value = d.toISOString().slice(0,16);  
    dodtepost.value = d.toISOString().slice(0,16);  
  });
  </script> --}}
  
{{-- <script type="text/javascript">
   $('#history').on('show.bs.modal', function () {
                        $('#history').css("margin-top", $(window).height() / 2 - $('.modal-content').height() / 2);
                      });
</script> --}}



 




