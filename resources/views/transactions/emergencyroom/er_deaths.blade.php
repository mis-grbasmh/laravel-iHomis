<script src="{{ asset('assets') }}/js/core/jquery.min.js"></script>
@extends('layouts.app', ['page' => 'Transactions', 'pageSlug' => 'emergencyroom', 'section' => 'erdeaths'])
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h6 class="title d-inline">Total ER Deaths</strong> </h6>
                    <p class="card-category d-inline">From {{$date1}} to {{$date2}} </p>
                    <div class="pull-right">
                        <div class="col-lg-12">
                            <div class="row">
                                <div class="col-sm-3">
                                        <label><strong>Select Date Range:</strong></label>
                                </div>
                                <div class="col-sm-4">
                                    <input type="date" id="date1" name="disdate" value="{{ old('date1', $date1) }}" class="form-control floating-label" step="any" required>
                                </div>
                                <div class="col-sm-4">
                                        <input type="date" id="date2" name="disdate" onchange="handler(event);" value="{{ old('date2', $date2) }}" class="form-control floating-label" step="any" required>
                                </div>
                            </div>
                        </div>
                        {{-- <div class="col-sm-12">
                            <div class="form-group">
                                <label><strong>Select Date Discharge Date:</strong></label>
                                <input type="date" id="date2" name="disdate" onchange="handler(event);" value="<?php echo date('Y-m-d'); ?>" class="form-control floating-label" step="any" required>
                            </div>
                        </div> --}}
                    </div>
                </div>
                <div class="card-body "><hr/>
                    <div class="table-responsive">
                        <table id="erdeathsTable" class="display" cellspacing="0" style="width:100%" >
                            <thead class=" text-primary">
                                <th scope="col">Patient Details</th>
                                <th scope="col">Address</th>
                                <th scope="col">Confinement Details</th>
                                <th scope="col">Diagnosis</th>
                                <th scope="col">Actions</th>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>

            <script>
                function handler(e){
                  var date1 = document.getElementById("date1").value;
                  var date2 = document.getElementById("date2").value;
                  getData(date1,date2);
                }
                </script>

            <script>
                $(document).ready(function(){
                getdata('','');
            });
            </script>
            <script>
                 function getData(date1,date2){   
                    url = "{{route('erdeaths')}}";
                table = $('#erdeathsTable').DataTable({ 
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
                      url: url,
                      method:'GET',
                      data:{date1:'$date1',date2:'$date2'},
                      dataType:'json',
                        error: function (errmsg) {
                      alert('Unexpected Error');
                      console.log(errmsg['responseText']);
                      },
                  },
                    columns: [
                          { "data": "patient" }, 
                          { "data": "address" }, 
                          { "data": "admission" },
                          { "data": "diagnosis" },
                          { "data": "actions" }
                          
                     ]
                  });
                 }
            </script>
@endsection