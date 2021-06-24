<script src="{{ asset('assets') }}/js/core/jquery.min.js"></script>
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
                            <h4 class="card-title">{{ __('Admitted Patients by Admitting Clerk as of Today') }} </h4>

                        </div>
                        <div class="col-4 text-right pull-right">
                           <!-- <a data-toggle="modal" href="#"  data-target="#newdietorder"  data-backdrop="static" class="btn btn-info btn-sm">Print</i></a>
                            <a data-toggle="modal" href="#"  data-target="#newdietorder"  data-backdrop="static" class="btn btn-info btn-sm animation-on-hover">Import</i></a>-->
                            <a class="btn btn-sm btn-primary" href="#" onclick="printInpatientsList()" data-placement="bottom" data-print="/admission/inpatient_pdf">Print</a>
                            <a data-target="#modalPatientSearch" data-toggle="modal" class="btn btn-sm btn-primary" id="MainNavHelp" href="#modalPatientSearch">{{ __('Admit Patient') }}</a>
                            {{-- <a href="{{ route('admitting.foradmission') }}" class="btn btn-sm btn-primary">{{ __('For Admission') }}</a> --}}

                        </div>
                    </div>

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
                                <th class="colspan">Total Charges</th>
                                <th class="colspan">Doctor</th>
                                <th class="colspan">Clerk</th>
                                <th>Actions</th>
                            </thead>

                        </table>
                    </div>
                </div>
            </div>
@endsection

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
                    var url = '{{ route("admitting.canceladmission") }}';
                    //url = url.replace(':id', query);
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
