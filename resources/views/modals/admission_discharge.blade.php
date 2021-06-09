<div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Discharge Patient</h4>
        </div>
      
            <form action="" role="form" method='POST' id="frmDataDischarge">
            @csrf   
                <input type="hidden" class="form-control" id="disc_id" name="enccode" disabled>
                <input type="hidden" class="form-control" id="disc_hpercode" name="hpercode" disabled>
                <input type="hidden" name="doctor" id="disc_licno">
            <div class="modal-body ">
                @csrf
            <div class="form-group">
                <label><strong>Patient Name:</strong></label>
                <input type="text" class="form-control" id="disc_name" name="name" disabled>
            </div>
            <div class="form-group">
                <label for="edit_dodate" class="control-label">
                Discharge Date and Time*:<span class="required"></span>
                </label>
                {{-- date('Y-m-d\TH:i', strtotime($data->dodate)) --}}
                <input type="datetime-local" id="disc_disdate" name="disdate"  value="<?php echo date('Y-m-d\TH:i'); ?>" class="form-control floating-label" step="any">
                <p class="disc_errordisdate text-danger hidden"></p>
            </div> 
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label><strong>Disposition:<span class="required"></span></strong></label>
                        <select class="form-control" id="ddldisposition" name="dispcode" onchange="ShowHideDivDisposition()" required>
                            @foreach (DispositionType() as $key => $disposition)
                                <option value="{{$key}}"><strong>{{ $disposition }}</strong> </option>
                            @endforeach
                        </select>
                </div>
                <div class="form-group col-md-6">
                        <label><strong>Condition upon Discharge:<span class="required"></span></strong></label>
                        <select class="form-control" id="condcode" name="condcode" required>
                            @foreach (Conditiontype() as $key => $condition)
                                <option value="{{$key}}"><strong>{{ $condition }}</strong> </option>
                            @endforeach
                        </select>
                </div>
            </div>
            <div class="form-row">
                <div class="col-sm-12"   id="dvtransferred" style="display: block;">
                    <label><strong>Transferred To:</strong></label>
                    <select class="form-control"  name="refto"">
                    <option value="0000040">MARIANO MARCOS MEM. HOSPITAL & MED CTR</option>
                   
                    </select>
           

                    <label><strong>Reason for Transfer:</strong></label>
                    <select class="form-control" name="reftxt">
                    @foreach (ReasonforTransfer() as $key =>$reasonsfortran)
                    <option value="{{$reasonsfortran->reftxt}}">{{$reasonsfortran->reftxt}}</option>
                    @endforeach
                    </select>
           
                </div><!-- col-->
            </div>
            <div class="form-group">
                <label><strong>Doctors Instructions:</strong></label>
                    <textarea rows="2" id="disc_dcspinst" name="dcspinst" aria-required="true" aria-invalid="false" class="form-control no-resize" placeholder="Please type doctors instruction here...">
                </textarea>  
          
            <div class="form-group">
                <label><strong>Final Diagnosis:</strong><span class="required">*</label>
                    <textarea rows="5" id="disc_diagtext" name="diagtext" aria-required="true" aria-invalid="false" class="form-control no-resize" placeholder="Please type final diagnosis..." required>
                </textarea>  
                {{-- <input type="text" class="form-control" id="edit_admnotes" name="admnotes"> --}}
            </div>
           
            <div class="form-group col-md-4">
                <label><strong>ICD CODE:</span></strong></label>
                <input type="text" class="form-control" id="disch_diagcodeext" name="diagcode_ext">
            </div>
        </div>
        </form>
        </div>

    <div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
    <button type="submit" class="btn btn-primary" id="btnDischarge"><i class="glyphicon glyphicon-save"></i>&nbsp;Discharge</button>
    {{-- <button type="submit" class="btn btn-primary"><i class="glyphicon glyphicon-save"></i>&nbsp;update</button> --}}
    </div>
        </div>
    </div>
</div>


<!-- for Disposition -->
<script type="text/javascript">
    function ShowHideDivDisposition() {
    var transferred = document.getElementById("ddldisposition");
        // alert(transferred.value);
        dvtransferred.style.display = transferred.value == "TRANS" ? "block" : "none";
    
     //   dvadmission.style.display = transferred.value == "ADMIT" ? "block" : "none";
    }//
    </script>
   
   
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


<script>
    $(function() {
           var availableTags = [
"ActionScript",
"AppleScript",
"Asp",
"BASIC",
"C",
"C++",
"Clojure",
"COBOL",
"ColdFusion",
"Erlang",
"Fortran",
"Groovy",
"Haskell",
"Java",
"JavaScript",
"Lisp",
"Perl",
"PHP",
"Python",
"Ruby",
"Scala",
"Scheme","one","two",
"two three"
];
       $("#disc_diagtext").autocomplete({
            source: availableTags,
            //minLength: 1,
            open:function (event,ui) {
                event.onkeyup();
                $('#disc_diagtext').value;

            }
        });
    });



</script>