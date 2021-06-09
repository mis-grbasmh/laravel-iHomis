<script src="{{ asset('assets') }}/js/core/jquery.min.js"></script>
@extends('layouts.app', ['page' => 'Transactions', 'pageSlug' => 'transactions', 'section' => 'transactions'])
@section('content')
<style>
    .ellipsis{
       display: inline-block;
       width: 200px;
       white-space: nowrap;
       overflow: hidden;
       text-overflow: ellipsis;
   }
 </style>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h6 class="title d-inline"><strong>{{$discharges->count()}} Total Discharged Patients:</strong>  </h6>
                    <p class="card-category d-inline"> as of {{$date}}</p>
                    <div class="float-right col-md-3">
                        <div class="col-lg-12">
                            <div class="row">
                                <div class="col-sm-8">
                                    <div class="form-group">
                                        <label><strong>Select Date Discharge Date:</strong></label>
                                    </div>
                                </div>
                                
                                <div class="col-sm-8">
                                    <div class="form-group">
                                        <input type="date" id="date2" name="disdate" onchange="handler(event);" value="{{ old('date', $date) }}" class="form-control floating-label" step="any" required>
                                    </div>
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
                <hr/>
               
                <div class="card-body ">
                    @include('alerts.success') 
                 <div class="table-responsive">      
                    <table id="daily_discharges" class="display" style="width:100%" >
                                <thead class=" text-primary">
                                <th class="text-center" style="display:none;"></th>
                                    <th class="text-center">#</th>
                                <th class="text-center">Patient Name</th>
                                <th class="text-center">Confinement<br/> Details</th>
                                <th class="text-center">Room Assignment</th>
                                <th class="text-center">Length of Stay</th>
                                <th class="text-center">Disposition</th>
                                <th class="text-center">Final Diagnosis</th>
                                <th class="text-center">Doctor</th>
                                {{-- <th class="text-center">Disharge By</th> --}}
                                <th class="text-center">Actions</th>
                            </thead>
                            <tbody>
                                @foreach($discharges as $key => $row)
                             
                                @php
                                    $dispositiontype='';
                                    foreach($dispositions as $dispkey=>$disposition){
                                       if($dispkey == $row->dispcode){
                                           $dispositiontype = $disposition;
                                       }  
                                    }
                                @endphp
                                <tr>
                                  <td style="display:none;">{{$row->enccode}}</td>
                                    <td>{{$key+1}}</td>
                                    <td><strong>{{getpatientinfo($row->hpercode)}}</strong>, {{ number_format($row->patage) }} / {{ $row->patsex }}
                                        <br/>
                                        <small><strong>{{$row->hpercode}}</strong></small></td>
                                        <td class="text-left"><strong>{{getFormattedDate($row->admdate)}} - </strong>
                                            <br/>
                                            <strong>{{getFormattedDate($row->disdate)}}</strong>
                                        </td>
                                        <td class="text-center"><strong>{{$row->wardname}} / {{ $row->rmname}} / {{$row->bdname}}</strong>
                                        
                                            <br/>  <span class="badge badge-primary">{{$row->tacode}}</span></td>
                                        <td class="text-center">
                                        {{ \Carbon\Carbon::parse($row->admdate)->diffInDays(\Carbon\Carbon::parse($row->disdate))+1}} day(s)</td>
                                        </td>
                                        <td class="text-center">{{ $dispositiontype}}</td>
                                    <td class="text-left">
                                        <span class="ellipsis">{{ $row->findx}}</span>
                                    </td>
                                    <td>
                                        DR. {{ getdoctorinfo($row->licno)}}
                                        <br/><small>{{ $row->tsdesc}}</small>
                                        <br/><span class="badge badge-info">{{$row->hsepriv}}</span>
                                        
                                    </td>
                                    <td>
                                        {{-- {{GetemployeeinfobyID($row->user_id)}} --}}
                                        
                                    </td>
                                    <td class="text-center">
                                        @if(in_array(auth()->user()->roles->first()->name, ['Admin', 'Medical Records'])) 
                                        <div class="dropdown">
                                            <button type="button" class="btn btn-link dropdown-toggle btn-icon" data-toggle="dropdown" aria-expanded="false">
                                             <i class="tim-icons icon-settings-gear-63"></i>
                                            </button>
 
                                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink" x-placement="top-end" style="position: absolute; transform: translate3d(-122px, -341px, 0px); top: 0px; left: 0px; will-change: transform;" x-out-of-boundaries="">
                                                <a class="dropdown-item" href="#" title="Click to do add Patient Charges" onclick="undodischarge('{{$row->enccode}}');return false;">Undo Discharge</a>
                                                
                                                <a data-toggle="modal" data-id="{{ $row->enccode}}" data-hpercode="{{ $row->hpercode}}" data-licno="{{ $row->licno}}" data-patient="{{getpatientinfo($row->hpercode)}}" href="#"  data-target="#editdischarge" class="dropdown-item editdischarge">Edit Discharge Date</i></a>
                                                <a class="dropdown-item btnEdit" data-toggle="tooltip" data-placement="bottom"  data-id="{{$row->enccode}}" data-edit="/admission/edit">Edit Admission</a>
                                                <a class="dropdown-item btnCoversheet" data-toggle="tooltip" title="Click to view clinical cover sheet " data-placement="bottom" data-id="{{$row->enccode}}" data-coversheet="/admission/coversheet">Cover Sheet</a>                                
                                            </div>
                                        </div>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {{-- <div class="card-footer py-4">
                            <nav class="d-flex justify-content-end" aria-label="...">
                            {{ $discharges->links() }}  
                            </nav>
                        </div> --}}
                    </div>
                </div>
            </div>
            
                   <!-- start editmodal-->
<div class="modal fade" tabindex="-1" data-backdrop="static" role="dialog" id="modalEditAdmission" aria-labelledby="editModal" aria-hidden="true">
    @include('modals.admission_edit')
 </div>


            <script>
                function handler(e){
                  var query = e.target.value;
                  if(query){
                            var url = '{{ route("dailydischarges", ":id") }}';
                            url = url.replace(':id', query);
                            document.location.href=url;      
                         }
                         else{
                             alert('Please ')
                         }
                
                
                }
                </script>
  <script>
    $(document).on("click", ".editdischarge", function () {
    var id = $(this).data('id');
    $(".modal-body #enccode").val( id );
    var hpercode = $(this).data('hpercode');
    $(".modal-body #hpercode").val( hpercode );
    var licno = $(this).data('licno');
    $(".modal-body #licno").val( licno );
    var patname = $(this).data('patient');
    $(".modal-body #patname").val( patname );
   });
</script>

<script type="text/javascript">
    $(document).ready(function() {
     $('#daily_discharges').DataTable( {
         dom: 'Bfrtip',
         buttons: [
             'copy', 'csv', 'excel', 'pdf', 'print'
         ]
     } );
 } );
 </script>

 
<script>
    $('#daily_discharges tbody').on('click', 'tr', function () {
        var currentRow=$(this).closest("tr"); 
        var history=currentRow.find("td:eq(0)").text();
        // alert(history);
        var query = history;
        $('#history').modal('hide');
        if(query){
            var res = query.split('/').join('-');
            url = url.replace(':id', res);
            document.location.href=url;      
         }
         else{
             alert('Please ')
         }
    });


    $('#daily_discharges').on('click','.btnCoversheet[data-coversheet]',function(e){
        e.preventDefault();
        var id =$(this).data('id');
        var res = id.split('/').join('-');
        var url ='{{ route("admission.coversheet", ":id")}}';
              url = url.replace(':id', res);
              document.location.href=url;
    });
   
 </script>
 <script>
      $('#daily_discharges').on('click','.btnEdit[data-edit]',function(e){
        e.preventDefault();
        var id =$(this).data('id');
         var res = id.split('/').join('-');
                var url ='{{ route("admission.edit", ":id")}}';
              url = url.replace(':id', res);
        swal({
              title: "Are you sure want to Edit Admission?",
              type: "info",
              showCancelButton: true,
              confirmButtonClass: "btn-info",
              confirmButtonText: "Confirm",
              cancelButtonText: "Cancel",
              closeOnConfirm: true,
              closeOnCancel: true
            }, 
            function(isConfirm) {
            if (isConfirm) {
                $.ajax({
                    url : url,
                    type : 'GET',
                    datatype : 'json',
                    success:function(data){
                            $('#id').val(data.enccode);
                            $('#edit_name').val(data.patientname);
                            $('#edit_admdate').val(data.admdate);
                            $('#edit_admnotes').val(data.admnotes);
                            $('#edit_admtxt').val(data.admtxt);
                            $('#edit_tacode').val(data.tacode);
                            $('#edit_tscode').val(data.tscode);
                            //  var $doctor = $('#edit_licno');
                            $('#edit_licno').val(data.licno);
                           // $doctor.append('<option selected value=' + data.licno + '>' + data.doctor + '</option>');
                           // var $admittype = $('#edit_tacode');
                           
                           
                           // $admittype.append('<option selected value=' + data.tacode + '>' + data.admissiontype + '</option>');
                            $('#edit_hsepriv').val(data.hsepriv);
                            //var $servicetype = $('#edit_hsepriv');
                            //$servicetype.append('<option selected value=' + data.hsepriv + '>' + data.servicetype + '</option>');
                            $('#patientage').val(data.patientage);
                            $('#newold').val(data.newold);
                            $("#modalEditAdmission").modal({
                            backdrop: 'static',
                            keyboard: false,
                            show: true,
                            });
                        }
    
                    });
                }
        });
    });//BTN-EDIT


     // updating data infomation
     $('#btnUpdate').on('click',function(e){
       e.preventDefault();
       query = $('#id').val();
       var res = query.split('/').join('-');
        var url = '{{ route("admission.update", ":id") }}';
                url = url.replace(':id', res);
               
        var frm = $('#frmDataEdit');
        swal({
              title: "Are you sure want to update Admission?",
              type: "info",
              showCancelButton: true,
              confirmButtonClass: "btn-info",
              confirmButtonText: "Confirm",
              cancelButtonText: "Cancel",
              closeOnConfirm: true,
              closeOnCancel: true
            }, 
            function(isConfirm) {
            if (isConfirm) {
                $.ajax({
            type :'GET',
            url : url,
            dataType : 'json',
          //  data:{'query':query},
             data : frm.serialize(),
            success:function(data){
                console.log(data);
                if (data.success == true) {
                    frm.trigger('reset');
                    $('#modalEditAdmission').modal('hide');
                    swal('Success!','Data Updated Successfully','success');
                    table.ajax.reload(null,false);
                }
            },
            error:function(err){
                console.log(err);
            }
            
            });
            }
        });
     });//Update Admission
     </script>
            @endsection
