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
    </script>
@endsection
