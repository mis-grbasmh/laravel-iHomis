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
                        <h6 class="mb-0">List of Admitted Patients (Active)</h6>
                    </div>
                </div>
                <h6 class="title d-inline">Total Patients:<strong> {{$inpatients->count() }}</strong>  Total Male: <strong>{{ $male}}</strong> Total Female: <strong>{{ $female}}</strong></h6>

                <div class="pull-right">
                    <div class="dropdown">
                        {{-- <h6 class="title d-inline">Option</h6> --}}
                         <button type="button" class="btn btn-link dropdown-toggle btn-icon text-center" data-toggle="dropdown">
                             <i class="tim-icons icon-settings-gear-63"></i>
                         </button>
                         <div class="dropdown-menu dropdown-menu-right dropdown-white" aria-labelledby="dropdownMenuLink">
                             <h6 class="dropdown-header">Select Option</h6>
                             <a class="dropdown-item" href="javascript:void(0);">ER Patients</a>
                             <a class="dropdown-item" href="javascript:void(0);">OPD Patients</a>
                             <a class="dropdown-item" href="javascript:void(0);">Print</a>

                         </div>
                     </div>
                </div>
                <div class="row clearfix">
                    @if($inpatients)
                    @foreach ($inpatients as $key => $inpatient)
                    @php  $type='';
                                       foreach($doctortypes as $key=>$doctortype){
                                        if($key === $inpatient->doctype){
                                            $type = $doctortype;
                                        }
                                       }

                  @endphp
                    <div class="col-lg-4 col-md-6">
                        <div class="card card-tasks">
                        <div class="card-header ">
                        <h6 class="title d-inline">Patient Name</h6>
                        <div class="pull-right">
                            <div class="dropdown">
                                {{-- <h6 class="title d-inline">Option</h6> --}}
                                 <button type="button" class="btn btn-link dropdown-toggle btn-icon text-center" data-toggle="dropdown">
                                     <i class="tim-icons icon-settings-gear-63"></i>
                                 </button>
                                 <div class="dropdown-menu dropdown-menu-right dropdown-white" aria-labelledby="dropdownMenuLink">
                                     <h6 class="dropdown-header">Select Option</h6>
                                     <a class="dropdown-item" href="#"title="Click to view laboratory results" onclick="getexamination('{{$inpatient->enccode}}');return false;">Laboratory Results</a>
                                     <a class="dropdown-item" href="#" title="Click to view radiology results" onclick="radiologyresults('{{$inpatient->enccode}}');return false;">Radiologyd Results</a>
                                     <a class="dropdown-item" href="#" title="Click to view Doctors order" onclick="doctorsorder('{{$inpatient->enccode}}');return false;">Doctors Order</a>
                                     <a class="dropdown-item" href="#" title="Click to view Medication summary" onclick="getmedication('{{$inpatient->enccode}}');return false;">Medication</a>
                                     <a class="dropdown-item" href="#" title="Click to do add progress notes" onclick="progressnotes('{{$inpatient->enccode}}');return false;">Progress Notes</a>
                                 </div>
                             </div>
                        </div>
                                <h4><strong> {{ getpatientinfo($inpatient->hpercode)}}</strong><br/>
                                <small>Health Rec. No.: {{$inpatient->hpercode}}
                                <p class="pmd-list-title">Age: {{number_format($inpatient->patage)}} Gender: {{$inpatient->patsex}}</p></small></h4>
                    </div>

                    <div class="card-body ">
                    <span class="badge badge-warning">{{$type}}</span>

                    <hr>
                    <h6 class="text-default">Admission Diagnosis</h6>
                    <p style="overflow:hidden; text-overflow: ellipsis;white-space: nowrap;">{{$inpatient->admtxt }}</p>
                    <hr>
                        <h6 class="text-default mt-3">Admission Date/Time:</h6>
                        <h6 class="text-info">{{getFormattedDate($inpatient->admdate)}} @ {{asDateTime($inpatient->admdate)}}</h6>
                        <p class="text-muted"><i class="zmdi zmdi-home mr-2"></i>Length of Stay: {{ \Carbon\Carbon::parse($inpatient->admdate)->diffInDays(\Carbon\Carbon::now())}} day(s)<p>
                        <p class="text-muted"><i class="zmdi zmdi-hotel mr-2"></i>{{$inpatient->wardname}}  - {{$inpatient->rmname }} - {{$inpatient->bdname}}</p>
                        <hr>
                        <p class="text-muted">{{ $inpatient->history}}{{ $inpatient->history}}</p>
                        <div class="d-flex justify-content-between mt-3 p-3 bg-light">
                        <!-- <button type="button" id="progressnotes" data-target="#progressnotes" href="#" data-id="{{ $inpatient->enccode }}" class="btn btn-primary btn-icon  btn-icon-mini btn-round"><i class="zmdi zmdi-attachment"></i></button> -->



                        <a href="#" title="Click to view laboratory results" onclick="cf4('{{$inpatient->enccode}}');return false;">CF4</a></li>
                            <a href="#" title="Click to view laboratory results" onclick="getexamination('{{$inpatient->enccode}}');return false;">Laboratory Results</a></li>
                            <a href="#" title="Admissions"><i class="tim-icons icon-camera-18"></i><span>{{ count_admission($inpatient->hpercode) }}</span></a>
                            <a href="#" title="ER Visits"><i class="zmdi zmdi-car-taxi mr-2"></i><span>{{ count_er($inpatient->hpercode) }}</span></a>
                            <a href="#" title="OPD Consultations"><i class="zmdi zmdi-home mr-2"></i><span>{{ count_opd($inpatient->hpercode) }}</span></a>

                        </div>
                    </div>
                </div>
            </div>
            @endforeach @else
            @endif
        </div>
    </div>
</div>
</section>

<div class="modal fade" id="history" role="dialog">
        <div class="modal-dialog modal-lg">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h6><strong>Patient</strong>  <small></small> </h6>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="row clearfix">
                        <small> <p>Total Result(s) : <span id="total_history"> </span> Found</p></small>
                        <div class="table-responsive">

                        <table class="table table-bordered table-striped table-hover text-left no-margin dataTable js-exportable">
                            <!-- <table class="table table-bordered" > -->
                            <thead>
                            <tr>
                            <th>Date</th>
                            <th>Description</th>
                            <th>Qty Issued</th>
                            <th>Balance</th>
                            </tr>
                            </thead>
                            <tbody id="history_table">
                            </tbody>
                            </table>
                        </div><!-- responsive -->
                    </div>
                </div>
            </div>


        </div>
    </div>


<script>
function cf4(id){
      if(id){
            var res = id.split('/').join('-');
          var url = '{{ route("cf4.show", ":id") }}';
              url = url.replace(':id', res);
            document.location.href=url;
        }
}
</script>
    <script>
    function progressnotes(id){

        // alert(filter);
        if(id){
            var res = id.split('/').join('-');
          var url = '{{ route("doctors.show", ":id") }}';
              url = url.replace(':id', res);
            document.location.href=url;
        }
    }
    </script>

  <script>
    function doctorsorder(id){

        // alert(filter);
        if(id){
            var res = id.split('/').join('-');
          var url = '{{ route("doctors.order", ":id") }}';
              url = url.replace(':id', res);
            document.location.href=url;
        }
    }
    </script>

  <script>
    function radiologyresults(id){
        alert(id);
      //   alert('Soon to be available');
        if(id){
          var res = id.split('/').join('-');
          var url = '{{ route("doctors.radiologyresult", ":id") }}';
              url = url.replace(':id', res);
            document.location.href=url;
        }
    }
    </script>
<script>


function gethistory(){
    var query= document.getElementById("hpercode").value;
    if(query){
        $.ajax({
                    url:"{{ route('getPatient.medication') }}",
                    method:'GET',
                    data:{query:query},
                    dataType:'json',
                    success:function(data)
                    {
                        $('#history_table').html(data.table_data);
                        $('#history').modal('show');
                        $('#total_history').text(data.total_data);
                    }
                });
    }//if query
   // return false;
}
</script>

    <script>
    function getmedication(query){
   //   alert(query);
        if(query){
          //  var res = id.split('/').join('-');
          $.ajax({
                    url:"{{ route('getPatient.medication') }}",
                    method:'GET',
                    data:{query:query},
                    dataType:'json',
                    success:function(data)
                    {
                        $('#history_table').html(data.table_data);
                        $('#history').modal('show');
                        $('#total_history').text(data.total_data);
                    }

                });

        }
    }
    </script>

<script>



<script>
    $(function () {
    $('.js-basic-example').DataTable();

    //Exportable table
    $('.js-exportable').DataTable({
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ]
    });
});
</script>


<script>
    $('#tableHistory tbody').on('click', 'tr', function () {
        alert('me');
      //  var history = $('#tableHistory').DataTable().row(this).data();
        var currentRow=$(this).closest("tr");
        var history=currentRow.find("td:eq(0)").text();
      //  console.log(history);
        alert(history);
        //var query = history[0];

    });
 </script>

@endsection
