<script src="{{ asset('assets') }}/js/core/jquery.min.js"></script>
{{-- <script src="{{ asset('assets') }}/js/jquery.dataTables.min.js"></script>  --}}
{{-- <script src="{{ asset('assets') }}/css/jquery.dataTables.min.css"></script> --}}
@extends('layouts.app', ['page' => 'Transactions', 'pageSlug' => 'transactions', 'section' => 'transactions'])
@section('content')
@include('alerts.success') 
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-8">
                            <h3 class="mb-0">Diet List</h3>
                        </div>
                        <div class="col-4 text-right pull-right">
                            <a data-toggle="modal" href="#"  data-target="#newdietorder"  data-backdrop="static" class="btn btn-info btn-sm">Print</i></a>
                            <a data-toggle="modal" href="#"  data-target="#newdietorder"  data-backdrop="static" class="btn btn-info btn-sm animation-on-hover">Import</i></a>
                        
                            <div class="dropdown">
                               {{-- <h6 class="title d-inline">Option</h6> --}}
                                <button type="button" class="btn btn-link dropdown-toggle btn-icon text-center" data-toggle="dropdown">
                                    <i class="tim-icons icon-settings-gear-63"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-right dropdown-white" aria-labelledby="dropdownMenuLink">
                                    <h6 class="dropdown-header">Select Ward</h6>
                                    @foreach ($wards as $key => $row)
                                        <a class="dropdown-item" href="{{ route('inpatients.index', ['id'=>$row->wardname]) }}">{{$row->wardname}}</a>
                                        {{-- <a class="dropdown-item" href="#" onclick="getPatientList('{{$row->wardname}}');return false;">{{$row->wardname}}</a> --}}
                                        {{-- <a class="dropdown-item" href="#" title="Click to do add Patient Charges" onclick="getPatientList('{{$row->wardname}}');return false;">{{$row->wardname}}</a> --}}
                                    @endforeach
                                        <a  class="dropdown-item" href="{{ route('inpatients.index',['']) }}">View All</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <h6 class="title d-inline">Total Male:</h6>
                    {{-- <p>Legend: <span style="background-color:yellow;" class="badge badge-danger">&nbsp;</span> <strong>  For Discharge</strong></p> --}}
                    <div class="pull-right">
                       
                    </div>
                </div>
                <div class="card-body "><hr/>
                      {{--   <table class="table tablesorter " id="inpatient"> --}}
                        <div class="table-responsive">
                        <table id="inpatientsTable" class="display" cellspacing="0" style="width:100%" >
                            <thead class=" text-primary">
                                <th class="colspan">Patient Details</th>
                                <th class="colspan">Religion</th>
                                <th class="colspan">Admission<br/>Details</th>
                                <th class="colspan">Diet Order</th>
                                <th class="colspan">Diet Remarks</th>
                                <th class="colspan">Ordered By</th>
                                <th class="colspan">BMI Details</th>
                                {{-- <th>Actions</th> --}}
                            </thead>
                           
                        </table>
                    </div>
                </div>
            </div>
   <script>
    $(document).ready(function(){
    table = $('#inpatientsTable').DataTable({ 
      stateSave: true,
      responsive: true,
      processing: true,
      serverSide : true,
      order : [0,'desc'],
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
           url: "{{route('dietetics.index')}}",
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
              { "data": "religion" }, 
               { "data": "admission" },
               { "data": "dietorders" },
               { "data": "dietnotes" },
              
               { "data": "doctor" },
               { "data": "bmi" },
              //{ "data": "actions" }
              
         ]
      });
    
     
});
  </script>
   
   
@endsection
