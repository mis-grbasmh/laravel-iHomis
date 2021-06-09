<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">New Admission</h4>
        </div>
            <form action="" role="form" method='POST' id="frmDataAdd">
            @csrf   
            <input type="hidden" class="form-control" id="add_id" name="hpercode" disabled>
            <div class="modal-body ">
                @csrf
             
        
        
        
                <div class="form-group">
                <label><strong>Patient Name:</strong></label>
                <input type="text" class="form-control" id="add_name"" disabled>
            </div>
            <div class="form-row">
            <div class="form-group col-md-7">
                <label><strong>Age at time of Admission:</strong></label>
                <input type="text" class="form-control" id="apatientage" disabled>
            </div>
            <div class="form-group col-md-5">
                <label><strong>New/Old Patient:</strong></label>
                <input type="text" class="form-control" id="add_newold" disabled>
            </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label><strong>Type of Admisson:<span class="required">*</span></strong></label>
                     <select class="form-control" id="add_tacode" name="tacode">
                        @foreach($admissiontypes as $key => $admissiontype)
                        <option value="{{$key}}"><strong>{{$admissiontype}}</strong></option required>
                        @endforeach
                      </select>
                </div>
                <div class="form-group col-md-6">
                        <label><strong>Type of Service:<span class="required">*</span></strong></label>
                        <select class="form-control" id="add_tscode" name="tscode">
                        @foreach($servicetypes as $servicetype)
                        <option value="{{$servicetype->tscode}}"><strong>{{$servicetype->tsdesc}}</strong></option required>
                        @endforeach
                        </select>
                </div>
            </div>
            <div class="form-group">
                <label for="edit_dodate" class="control-label">
                Admission Date and Time:<span class="required">*</span>
                </label>
                <input type="datetime-local" id="add_admdate" name="admdate" class="form-control floating-label" step="any">
                <p class="edit_erroradmdate text-danger hidden"></p>
            </div>  
            <div class="form-group">
                <label><strong>Reason for Admission:</strong></label>
                <textarea rows="4" id="add_admnotes" name="admnotes" aria-required="true" aria-invalid="false" class="form-control no-resize" placeholder="Please type reason for admission...">
                </textarea>  
                {{-- <input type="text" class="form-control" id="edit_admnotes" name="admnotes"> --}}
            </div>
            <div class="form-group">
                <label><strong>Admitting Diagnosis:<span class="required">*</span></strong></label>
                <textarea rows="6" id="add_admtxt" name="admtxt" required="" aria-required="true" aria-invalid="false" class="form-control no-resize" placeholder="Please type admitting diagnosis...">
                </textarea>  
                
                {{-- <input type="text" class="form-control" id="edit_admtxt" name="admtxt" required> --}}
            </div>
            
            <div class="form-row">
            <div class="form-group col-md-8">
                <label><strong>Admitting Physician:<span class="required">*</span></strong></label>
                <select class="form-control" id="add_licno" name="licno">
                @foreach($doctors as $doctor)
                <option value="{{$doctor->licno}}"><strong>DR. {{getdoctorinfo($doctor->licno)}}</strong></option required>
                @endforeach
                </select>
            </div>
           
            <div class="form-group col-md-4">
                <label><strong>Case Type:<span class="required">*</span></strong></label>
                <select class="form-control" id="add_hsepriv" name="hsepriv">
                @foreach($servicecasetypes as $key => $servicecasetype)
                <option value="{{$key}}"><strong>{{$servicecasetype}}</strong></option required>
                @endforeach
                </select>
            </div>
        </form>
        </div>

    <div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
    <button type="submit" class="btn btn-primary" id="btnSave"><i class="glyphicon glyphicon-save"></i>&nbsp;Save</button>
    {{-- <button type="submit" class="btn btn-primary"><i class="glyphicon glyphicon-save"></i>&nbsp;update</button> --}}
    </div>
        </div>
    </div>
</div>

<script>

</script>