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
                            <h6 class="mb-0">Emergency Room</h6>
                        </div>
                    </div>
                    <h6 class="title d-inline">ER Patients For Admission</h6>
                    
                    <div class="pull-right">
                       
                    </div>
                </div>
                <div class="card-body "><hr/>
                    <div class="">
                      {{--   <table class="table tablesorter " id="inpatient"> --}}
                        <div class="">
                        <table id="erpatientsTable" class="display" cellspacing="0" style="width:100%" >
                            <thead class=" text-primary">
                                <th>Patient Details</th>
                                <th>Complaint</th>
                                <th>Admission Details</th>
                                <th>Doctor</th>
                                <th>Clerk</th>
                                <th>Actions</th>
                            </thead>
                        </table>
                    </div>
                    </div>
                </div>
            </div>
           
   <script>
    $(document).ready(function(){
    table = $('#erpatientsTable').DataTable({ 
      stateSave: true,
      responsive: {
        breakpoints: [
        { name: 'bigdesktop', width: Infinity},
        { name: 'meddesktop', width: 1480},
        { name: 'smalldesktop', width: 1280},
        { name: 'medium', width: 1188},
        { name: 'tabletl', width: 1024},
        { name: 'btwtabllandp', width: 848},
        { name: 'tabletp', width: 768},
        { name: 'mobilel', width: 480},
        { name: 'mobilep', width: 320}
        ]
      },
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
          url: "{{route('admitting.foradmission','')}}",
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
              { "data": "complaint" }, 
              { "data": "admission" },
              { "data": "doctor" },
              { "data": "clerk"},
              { "data": "actions" }
         ]
      });
});
  </script>
   
@endsection
