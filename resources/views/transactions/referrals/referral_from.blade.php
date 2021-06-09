<script src="{{ asset('assets') }}/js/core/jquery.min.js"></script>
@extends('layouts.app', ['page' => __('User Management'), 'pageSlug' => 'users', 'section' => 'users'])

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card ">
                <div class="card-header">
                    <div class="row">
                        <div class="col-8">
                            <h4 class="card-title">{{ __('Referral From') }}</h4>
                        </div>
                        <div class="col-4 text-right">
                            <a href="{{ route('referralsfrom.create') }}" class="btn btn-sm btn-primary">{{ __('New Referral') }}</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @include('alerts.success')

                    <div class="table-responsive">
                        {{-- <table id="referralsTable"  class="table tablesorter cellspacing="0" style="width:100%" > --}}
                            <table id="referralsTable" class="display" cellspacing="0" style="width:100%" >
                            <thead class=" text-primary">
                                <th>No</th>
                <th>Name</th>
                <th>Email</th>
                <th width="100px">Action</th>
                            </thead>
                          <tbody>
                            {{--       @foreach ($patients as $patient)
                                    <tr>
                                        <td><strong>{{ getpatientinfo($patient->hpercode) }}</strong> <br/>
                                            {{ $patient->hpercode }}
                                        </td>
                                        <td>
                                            {{ getpatientAddress($patient->hpercode) }}
                                        </td>
                                        <td>{{ $patient->datemod }}</td>
                                        <td>
                                        @if( $patient->rfstat == null)
                                              <span class="badge badge-info">Inactive</span>
                                        @else
                                            {{ $patient->rfstat}}
                                            {{ $patient->rfstat}}
                                            @endif
                                        </td>
                                        <td></td>
                                       
                                    </tr>
                                @endforeach--}}
                            </tbody> 
                        </table>
                    </div>
                </div>
                <div class="card-footer py-4">
                    <nav class="d-flex justify-content-end" aria-label="...">
                        {{-- {{ $patients->links() }} --}}
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
         
        });     
        getList();
         });
       </script>
    <script>
        function getList(){
          var table = $('.referralsTable').DataTable({
            table = $('#inpatientsTable').DataTable({ 
        stateSave: true,
        responsive: true,
        processing: false,
        serverSide : true,
        order : [1,'desc'],
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
           url: {{route('admisson.referralsfrom')}},
          method:'GET',
          data:{query:query},
          dataType:'json',
          error: function (errmsg) {
          alert('Unexpected Error');
          console.log(errmsg['responseText']);
          },
      },
              columns: [
                  {data: 'id', name: 'id'},
                  {data: 'hpercode', name: 'hpercode'},
                  {data: 'hpercode', name: 'hpercode'},
                  {data: 'action', name: 'action', orderable: false, searchable: false},
              ]
          });
          
        }
      </script>
@endsection
