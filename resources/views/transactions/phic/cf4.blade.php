<script src="{{ asset('assets') }}/js/core/jquery.min.js"></script>
{{-- <script src="{{ asset('assets') }}/js/core/jquery-3.6.0.min.js"></script> --}}
{{-- <script src="{{asset('DataTables/datatables.min.js')}}" type="text/javascript"></script> --}}
{{-- <script src="{{ asset('assets') }}/css/jquery.dataTables.min.css"></script> --}}
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.24/datatables.min.css"/>
<script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.24/datatables.min.js"></script>
@extends('layouts.app', ['page' => 'Doctors Order', 'pageSlug' => 'transactions', 'section' => 'transactions'])

@section('content')
@include('partials.message')
<style>
    .modal { overflow: auto !important; }
    </style>
    <div class="row">
       <div class="col-lg-12 col-md-12"  >
            <div class="card">
                <div class="card-header">
                  <div class="row">
                      <input type="hidden" id="enccode" name="enccode" value="{{$enccode}}">
                      <input type="hidden" id="hpercode" name="hpercode" value="{{$hpercode}}">
                      <div class="col-sm-6 text-left">
                        <h5 class="card-category">Patient CF4</h5>
                        <p class="card-category d-inline">Displays CF4 details</p>
                      </div>
                  </div>
                  <div class="pull-right">


                        <ul class="nav nav-pills nav-pills-primary" >
                            <li class="nav-item">
                              <a class="nav-link" data-toggle="tab" href="#pertinent"  id="#pertinent" title="Click to view Medication summary" onclick="getlaboratory('{{$enccode}}');return false;">
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
                        <div role="tabpanel active" class="tab-pane fade body active" id="pertinent">
                        @include('transactions.phic.cf4_pertinentsigns')
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

<div class="modal fade" id="history" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
{{-- <div class="modal fade" id="history" role="dialog"> --}}
  @include('modals.patient-history')
</div><!-- modal-history-->
@endsection
<div class="modal fade right" id="patientsearchModal" role="dialog">
  @include('modals.patient_search')
</div>
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
    function gethistory(){
        var query= document.getElementById("hpercode").value;
        if(query){
        $.ajax({
                    url:"{{ route('getPatient.history') }}",
                    method:'GET',
                    data:{query:query},
                    dataType:'json',
                    success:function(data)
                    {
                        $('#history_table').html(data.table_data);
                      $("#history").modal({
                            backdrop: 'static',
                            keyboard:' false,
                            show: true,
                            });
                        $('#total_history').text(data.total_data);
                    }
            });
        }//if query
    // return false;
    }
</script>
{{-- <script type="text/javascript">
   $('#history').on('show.bs.modal', function () {
                        $('#history').css("margin-top", $(window).height() / 2 - $('.modal-content').height() / 2);
                      });
</script> --}}

<script>
    $(document).ready(function(){
        fetch_customer_data();
            function fetch_customer_data(query = '',filter='')
            {
                var filter = $('#filter').val();
                $.ajax({
                    url:"{{ route('live_search.action') }}",
                    method:'GET',
                    data:{query:query,filter:filter},
                    dataType:'json',
                    success:function(data)
                    {
                            $('#results').html(data.table_data);
                            $('#total_records').text(data.total_data);
                    }
                })
            }//function
                $(document).on('keyup', '#search', function(){
                    var query = $(this).val();
                    if(query !=''){
                    fetch_customer_data(query);
                    }else{
                        var Table = document.getElementById("results");
                        Table.innerHTML = "";
                    }
                });
                $(document).on('change', '#search', function(){
                    var query = $(this).val();
                    if(query !=''){
                    fetch_customer_data(query);
                    }else{
                        var Table = document.getElementById("results");
                        Table.innerHTML = "";
                    }
                });
            });
</script>

 <script>


  $(document).ready(function(){
     // Display Modal
    var  id = document.getElementById("enccode").value;
    if(id==''){
        $("#patientsearchModal").modal({
                            backdrop: 'static',
                            keyboard: false,
                            show: true,
                            });
    }
    });
</script>

<script type="text/javascript">
   $('#patientsearchModal').on('show.bs.modal', function () {
              backdrop: 'static'
                      });
</script>

<script>
    $(document).ready(function(){
        $('.userinfo').click(function(){
            var userid = $(this).data('id');
            // AJAX request
            $.ajax({
            //url: 'ajaxfile.php',
            //type: 'post',
                data: {userid: userid},
                success: function(response){
                // Add response in Modal body
                //$('.modal-body').html(response);
                 // Display Modal
                //  $('#empModal').modal('show');
                }
            });
        });
    });
 </script>

<script>
    $('#tableHistory tbody').on('click', 'tr', function () {
        var currentRow=$(this).closest("tr");
        var history=currentRow.find("td:eq(0)").text();
        var query = history;
        alert(history);
        $('#history').modal('hide');
        if(query){
            var res = query.split('/').join('-');
            var url = '{{ route("cf4.show", ":id") }}';
            url = url.replace(':id', res);
            document.location.href=url;
         }
         else{
             alert('Please ')
         }
    });
 </script>

 <script>
    $('#tableitems tbody').on('click', 'td', function () {

      var currentRow=$(this).closest("tr");
        var query=currentRow.find("td:eq(0)").text();
        //console.log(query)      //call t
        $('#patientsearchModal').modal('hide');
        $('#tableItems').DataTable().destroy();
        $('#history').modal('show');
        if(query !=''){
            // fetch_patient_history();
           document.getElementById("hpercode").value = query;
           var queryres = $('#hpercode').value;
                $.ajax({
                    url:"{{ route('getPatient.history') }}",
                    method:'GET',
                    data:{query:query},
                    dataType:'json',
                    success:function(data)
                    {
                             $('#history_table').html(data.table_data);
                            // $('#history').modal('show');
                             $("#history").modal({
                            backdrop: 'static',
                            keyboard: false,
                            show: true,
                            });
                            //$('#tableHistory').html(data.table_data);
                            $('#total_history').text(data.total_data);
                    }
                });
         }
    });
 </script>




