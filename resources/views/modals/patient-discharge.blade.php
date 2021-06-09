<div class="modal-dialog" role="document">
    <div class="modal-content modal-black ">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Discharge Patient <span id="patname"></span> </h5>
            
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
          
        </div>
        <form action="{{ route('patient.discharge') }}" enctype="multipart/form-data">
            <div class="card-main ">
                @csrf
                <div class="modal-body ">
                    <input type="hidden" id="enccode" name="enccode">
                    <input type="hidden" id="hpercode" name="hpercode">
                    <input type="hidden" name="licno" id="licno">
                    
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label><strong>Patient Name</strong></label>
                            <input type="text" id="patname" name="patname" class="form-control" readonly>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label><strong>Discharge Date and Time:*</strong></label>
                            <input type="datetime-local" id="disdate" name="disdate" value="<?php echo date('Y-m-d'); ?>" class="form-control floating-label" step="any" required>
                        </div>
                    </div>
                   
                    <div class="col-md-12">
                    <div class="row">
                    <div class="col-sm-6">
                        <label><strong>Disposition:*</strong></label>
                        <select class="form-control" id="ddldisposition" name="dispcode" onchange="ShowHideDivDisposition()" required>
                            @foreach ($dispositions as $key => $disposition)
                                <option value="{{$key}}"><strong>{{ $disposition }}</strong> </option>
                            @endforeach
                        </select>
                    </div><!-- col-->

                    <div class="col-sm-6">
                        <label><strong>Condition upon Discharge:*</strong></label>
                        <select class="form-control" id="condcode" name="condcode" required>
                            @foreach ($conditions as $key => $condition)
                                <option value="{{$key}}"><strong>{{ $condition }}</strong> </option>
                            @endforeach
                        </select>
                    </div><!-- col-->
                </div>
            </div>
                        <div class="col-sm-12"  id="dvtransferred" style="display: block;">
                        <label><strong>Transferred To:</strong></label>
                        <select class="form-control"  name="refto"">
                            <option value="0000040">MARIANO MARCOS MEM. HOSPITAL & MED CTR</option>
                           
                            </select>
                   

                        <label><strong>Reason for Transfer:</strong></label>
                        <select class="form-control" name="reftxt">
                            @foreach ($reasonsfortrans as $key =>$reasonsfortran)
                            <option value="{{$reasonsfortran->reftxt}}">{{$reasonsfortran->reftxt}}</option>
                            @endforeach
                            </select>
                   
                    </div><!-- col-->
                   
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label><strong>Doctors Instructions:</strong></label>
                            <textarea rows="2" name="dcspinst" id="dcspinst" class="form-control no-resize" placeholder="Please type Doctors instructions here..."></textarea>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label><strong>Diagnosis:</strong></label>
                            <div class="form-line">
                                <textarea rows="4" name="diagtext" id="diagtext" class="form-control no-resize" placeholder="Please type final diagnosis..."></textarea>
                           </div>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label><strong>ICD Code</strong></label>
                            <input type="text" id="diagcodeext" name="diagcodeext" class="form-control" placeholder="Enter final diagnosis" value ="">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>   
                   
                    </div>   
                </div>
            </div>
        </form>
        
</div>
<script>
    $(document).on("click", ".discharge", function () {
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

<script>
$(document).ready(function(){

document.getElementById('dvtransferred').style.display='block'

$('#disdate').val(new Date().toLocaleString("sv-SE", {
year: "numeric",
month: "2-digit",
day: "2-digit",
hour: "2-digit",
minute: "2-digit",
second: "2-digit"
}).replace(" ", "T"));
});
</script>

<!-- for Disposition -->
<script type="text/javascript">
function ShowHideDivDisposition() {
var transferred = document.getElementById("ddldisposition");
    // alert(transferred.value);
    dvtransferred.style.display = transferred.value == "TRANS" ? "block" : "none";

 //   dvadmission.style.display = transferred.value == "ADMIT" ? "block" : "none";
}//
</script>


{{-- <script type="text/javascript">
$(document).ready(function() {
$('#example').DataTable( {
    dom: 'Bfrtip',
    buttons: [
        'copy', 'csv', 'excel', 'pdf', 'print'
    ]
} );
} );
</script> --}}
<script type="text/javascript">

$(document).ready(function(){
    $("select").change(function(){
        $(this).find("option:selected").each(function(){
            var optionValue = $(this).attr("value");
            if(optionValue){
                $(".box").not("." + optionValue).hide();
                $("." + optionValue).show();
            } else{
                $(".box").hide();
            }
        });
    }).change();
});
</script>