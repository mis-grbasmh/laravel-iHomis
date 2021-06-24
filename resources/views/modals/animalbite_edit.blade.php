<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Edit Animal Bite Log</h4>
        </div>
            <form action="" role="form" method='POST' id="frmDataEdit">
            @csrf
            <input type="hidden" class="form-control" id="id" name="enccode" disabled>
            <div class="modal-body ">
            @csrf
            <div class="form-row">
                <div class="form-group col-md-5">
                    <label><strong>Patient Name:</strong></label>
                    <input type="text" class="form-control" id="edit_name"" disabled>
                </div>
                <div class="form-group col-md-3">
                    <label><strong>Age at time of Admission:</strong></label>
                    <input type="text" class="form-control" id="patientage" disabled>
                </div>
                <div class="form-group col-md-3">
                    <label><strong>New/Old Patient:</strong></label>
                    <input type="text" class="form-control" id="newold" disabled>

                </div>
            </div>
            <div class="form-row">


            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label><strong>Type of Admisson:<span class="required">*</span></strong></label>
                     <select class="form-control" id="edit_tacode" name="tacode" required>
                        {{-- @foreach($admissiontypes as $key => $admissiontype)
                        <option value="{{$key}}"><strong>{{$admissiontype}}</strong></option>
                        @endforeach --}}
                      </select>
                </div>

                <div class="form-group col-md-6">
                        <label><strong>Type of Service:<span class="required">*</span></strong></label>
                        <select class="form-control" id="edit_tscode" name="tscode">

                          {{-- @foreach($servicetypes as $servicetype)
                        <option value="{{$servicetype->tscode}}"><strong>{{$servicetype->tsdesc}}</strong></option required>
                        @endforeach --}}
                        </select>
                </div>
            </div>
            <div class="form-group">
                <label for="edit_dodate" class="control-label">
                Admission Date and Time:<span class="required">*</span>
                </label>
                <input type="datetime-local" id="edit_admdate" name="admdate" class="form-control floating-label" step="any">
                <p class="edit_erroradmdate text-danger hidden"></p>
            </div>
            <div class="form-group">
                <label><strong>Reason for Admission:</strong></label>
                <input type="text" class="form-control" id="edit_admnotes" name="admnotes" placeholder="Please type reason for admission...">
                {{-- <textarea rows="2" id="edit_admnotes" name="edit_admnotes" aria-required="true" aria-invalid="false" class="form-control no-resize" >
                </textarea>   --}}
                {{-- <input type="text" class="form-control" id="edit_admnotes" name="admnotes"> --}}
            </div>
            <div class="form-group">
                <label><strong>Admitting Diagnosis:<span class="required">*</span></strong></label>
                <textarea rows="8" id="edit_admtxt" name="admtxt" required="" aria-required="true" aria-invalid="false" class="form-control no-resize" placeholder="Please type admitting diagnosis...">
                </textarea>

                {{-- <input type="text" class="form-control" id="edit_admtxt" name="admtxt" required> --}}
            </div>

            <div class="form-row">
                <div class="form-group col-md-8">
                <label><strong>Admitting Physician:<span class="required">*</span></strong></label>
                <select class="form-control" id="edit_licno" name="licno">
                {{-- @foreach($doctors as $doctor)
                    @if($doctor->licno == old('$doctor->licno'))
                            <option value="{{$doctor->lico}}" selected>{{getdoctorinfo($doctor->licno)}} </option>
                            @else
                             <option value="{{$doctor->licno}}">{{getdoctorinfo($doctor->licno)}} </option>
                            @endif


                 <option value="{{$doctor->licno}}"><strong>{{getdoctorinfo($doctor->licno)}}</strong></option required>
                @endforeach --}}
                </select>
            </div>

            <div class="form-group col-md-4">
                <label><strong>Case Type:<span class="required">*</span></strong></label>

                <select class="form-control" id="edit_hsepriv" name="hsepriv">
                    @foreach(ServicecaseType() as $key => $servicecasetype)
                    <option value="{{$key}}"><strong>{{$servicecasetype}}</strong></option required>
                    @endforeach

                    {{-- @foreach($servicecasetypes as $key => $servicecasetype)
                <option value="{{$key}}"><strong>{{$servicecasetype}}</strong></option required>
                @endforeach --}}
                </select>

            </div>
        </form>
        </div>

    <div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
    <button type="submit" class="btn btn-primary" id="btnUpdate"><i class="glyphicon glyphicon-save"></i>&nbsp;Save</button>
    {{-- <button type="submit" class="btn btn-primary"><i class="glyphicon glyphicon-save"></i>&nbsp;update</button> --}}
    </div>
        </div>
    </div>
</div>


@push('js')
<script>

new SlimSelect({
            select: '.form-select'
        })



</script>

@endpush
