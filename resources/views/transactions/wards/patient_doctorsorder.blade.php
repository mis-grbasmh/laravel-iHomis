<script src="{{ asset('assets') }}/js/core/jquery.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.24/datatables.min.css"/>
<script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.24/datatables.min.js"></script>
@extends('layouts.app', ['page' => 'Doctors Order', 'pageSlug' => 'transactions', 'section' => 'transactions'])
@section('content')
    @include('partials.message')
    <div class="row">
       <div class="col-lg-12 col-md-12">
            <div class="card card-task">
                <div class="card-header">
                  <div class="row">
                      <input type="hidden" id="enccode" name="enccode" value="{{$enccode}}">
                      <input type="hidden" id="hpercode" name="hpercode" value="{{$hpercode}}">
                      <div class="col-sm-6 text-left">
                        <h6 class="title d-inline">Doctors Order</h6>
                        <p class="card-category d-inline">Displays and manage Doctors order of the patient</p>

                      </div>
                        <div class="col-lg-12 col-md-12">
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label for="inputName">Patient Name:<br/> <strong><span id="inputName">{{getpatientinfo($hpercode) }}</span></strong></label>
                                </div>
                            </div>
                            <div class="pull-right">
                            <div class="row">
                                <ul class="nav nav-pills nav-pills-primary" >
                                <li class="nav-item">
                                 <a class="nav-link" data-toggle="tab" href="#diet" title="Click to view Diet Orders" onclick="getdiet('{{$enccode}}');return false;">
                                    Diet
                                </a>
                                </li>&nbsp;
                                <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#laboratories" title="Click to view Medication summary" onclick="getlaboratory('{{$enccode}}');return false;">
                                    Laboratory
                                </a>
                                </li>&nbsp;
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#radiologyorder" title="Click to view Medication summary" onclick="getradiologyorder('{{$enccode}}');return false;">
                                    Radiology/CT-Scans
                                    </a>
                                </li>&nbsp;
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#medications" title="Click to view Medication summary" onclick="getmedication('{{$enccode}}');return false;">
                                    Medication
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
                        <div class="tab-pane fade body" id="laboratories">
                         @include('transactions.doctors.order_laboratories')
                        </div>
                    <div class="tab-pane fade body" id="radiologyorder">
                        @include('transactions.doctors.order_radiology')
                    </div>
                    <div class="tab-pane fade body" id="medications">
                        @include('transactions.doctors.order_medication')

                    </div>
                    <div class="tab-pane fade body" id="diet">
                        @include('transactions.doctors.order_diet')
                    </div>
                    <div class="tab-pane fade body" id="fordischarge">
                      {{-- @include('transactions.doctors.order_discharge') --}}
                    </div>
                </div>
            </div>
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
<script>
  $('document').ready(function(){
    $().ready(function() {
        getlaboratory('{{$enccode}}');
      })
    });
  </script>
    <script>
    $(document).ready(function(){
      // we call the function
      validate();
    });
 </script>
<script>
    // We define the function
    function validate(){
      $("laboratory").click();
      getlaboratory('{{$enccode}}');
    }
    </script>

<script>
  function editdiet(id) {
    if(id){
      console.log(id);
      $.ajax({
                    url:"{{ route('editdietorder') }}",
                    method:'GET',
                    data:{query:id},
                    dataType:'json',
                    success:function(data)
                    {
                    //    if(data.message == false){
                         $('#editdietorder').modal('show');
                         $('#result').html(data.table_data);

                    //} else {
                     //   alert('No result found');
                   // }
                    //console.log(query);
                    }
                });


    }


  }
  </script>



