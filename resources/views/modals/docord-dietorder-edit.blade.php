<!-- start editmodal-->

    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Edit Diet</h4>
            </div>
            <div class="modal-body">
            <form role="form" id="frmDietEdit">
                <input type="hidden" class="form-control" id="edit_ID" name="edit_ID" disabled>
                <div class="form-group">
                    <label for="edit_dodate" class="control-label">
                    Date and Time of Order:<span class="required">*</span>
                    </label>
                    <input type="datetime-local" id="edit_dodate" name="dodate" class="form-control floating-label" step="any">
                    {{-- <input type="text" class="form-control" id="edit_name" name="edit_name"> --}}
                    <p class="edit_errordodate text-danger hidden"></p>
                </div>           
                <div class="form-group">
                    <label for="edit_doctor" class="control-label">
                    Ordered By<span class="required">*</span>
                    </label>
                    <select class="form-control" id="edit_licno" name="licno">                                     
                    <option name="doctor"><span id="edit_doctor"></span></option>
                    @foreach($doctors as $doctor)
                   <option value="{{ $doctor->licno }}"><strong>{{getdoctorinfo($doctor->licno)}}</strong></option>
                    @endforeach
                    </select>
            
                    <p class="edit_errorDoctor text-danger hidden"></p>
                </div>
                <div class="form-group">
                    <label><strong>Breakfast:</strong></label>
                    <select class="form-control" id="edit_dietcode" name="dietcode">
                    @foreach($diettypes as $diettype)
                    <option value="{{$diettype->dietcode}}"><strong>{{$diettype->dietdesc}}</strong></option>
                    @endforeach
                    </select>
                 </div>
                <div class="form-group">
                    <label><strong>Lunch:</strong></label>
                    <select class="form-control" id="edit_dietlunch" name="dietlunch">
                    @foreach($diettypes as $diettype)
                    <option value="{{$diettype->dietcode}}"><strong>{{$diettype->dietdesc}}</strong></option>
                    @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label><strong>Supper:</strong></label>
                    <select class="form-control" id="edit_dietdinner" name="dietdinner">
                    @foreach($diettypes as $diettype)
                    <option value="{{$diettype->dietcode}}"><strong>{{$diettype->dietdesc}}</strong></option>
                    @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="edit_remarks" class="control-label">
                    Diet Remarks<span class=""></span>
                    </label>
                    <input type="text" class="form-control" id="edit_remarks" name="remarks">
                    <p class="edit_errorRemarks text-danger hidden"></p>
                </div>
                <div class="form-group">
                    <label for="edit_doctornotes" class="control-label">
                    Doctor Notes<span class="">*</span>
                    </label>
                    <textarea class="form-control" id="edit_doctornotes" name="edit_doctornotes"></textarea>
                    <p class="edit_errordoctornotes text-danger hidden"></p>
                </div>   
                <div class="form-group">
                    <label for="edit_doctornotes" class="control-label">
                        <label>Date and Time Posted<span class="">*</span>
                        </label>
                        <input type="datetime-local" id="edit_dodtepost" name="dodtepost" class="form-control floating-label" step="any" required>
                    </div>
                </div> 
            </form>
                <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="btnUpdate"><i class="glyphicon glyphicon-save"></i>&nbsp;Save</button>
                </div>
            </div>
        </div>
    </div>



    
<!-- end editmodal-->

    {{-- <script>
   
$(document).ready(function(){
$('#dodate').val(new Date().toLocaleString("sv-SE", {
    year: "numeric",
    month: "2-digit",
    day: "2-digit",
    hour: "2-digit",
    minute: "2-digit",
    second: "2-digit"
}).replace(" ", "T"));

    $('#dodtepost').val(new Date().toLocaleString("sv-SE", {
        year: "numeric",
        month: "2-digit",
        day: "2-digit",
        hour: "2-digit",
        minute: "2-digit",
        second: "2-digit"
    }).replace(" ", "T"));
});

</script> --}}