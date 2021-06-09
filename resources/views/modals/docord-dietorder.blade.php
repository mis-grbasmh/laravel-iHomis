<div class="modal-dialog" role="document">
    <div class="modal-content modal-black ">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Diet New Order<span id="patname"></span> </h5>
            
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
          
        </div>
        <form action="{{ route('patient.dietorder') }}" enctype="multipart/form-data">

                <div class="card-main ">
                    <div class="modal-body p-0">
                <div class="card m-0">
                   <input type="hidden" id="enccode" name="enccode" value={{ $enccode}}>
                   <input type="hidden" id="hpercode" name="hpercode" value={{ $hpercode}}>
                   
                   @csrf
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label><strong>Date and Time of Order:</strong></label>
                                        <input type="datetime-local" id="dodate" name="dodate" value="<?php echo date('Y-m-d\TH:i'); ?>" class="form-control floating-label" step="any" required autofocus>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <label><strong>Ordered By:</strong></label>
                                   
                                   
                                    <select class="form-control" id="licno" name="licno">
                                         @if($doctors)   
                                            @foreach ($doctors as $key => $doctor)
                                                <option value="{{$doctor->licno}}"><strong>{{getdoctorinfo($doctor->licno)}}</strong> MD.</strong></option>
                                            @endforeach
                                        @else
                                                <option value="$admdiagnosis->licno"><strong>getdoctorinfo($admdiagnosis->licno1)</strong> MD.</strong></option>
                                        @endif
                                        
                                    </select>
                                
                                </div><!-- col-->
                                <div class="col-sm-12">
                                        <label><strong>Breakfast:</strong></label>
                                        <select class="form-control" id="dietcode" name="dietcode">
                                            @foreach ($diettypes as $diettype)
                                                <option value="{{$diettype->dietcode}}"><strong>{{$diettype->dietdesc}}</strong></option>
                                            @endforeach
                                        </select>   
                                    
                                </div>
                                <div class="col-sm-12">
                                        <label><strong>Lunch:</strong></label>
                                        <select class="form-control" id="dietlunch" name="dietlunch">
                                            @foreach ($diettypes as $diettype)
                                                <option value="{{$diettype->dietcode}}"><strong>{{$diettype->dietdesc}}</strong></option>
                                            @endforeach
                                        </select>   
                                </div>
                                <div class="col-sm-12">
                                        <label><strong>Supper:</strong></label>
                                        <select class="form-control" id="dietdinner" name="dietdinner">
                                            @foreach ($diettypes as $diettype)
                                                <option value="{{$diettype->dietcode}}"><strong>{{$diettype->dietdesc}}</strong></option>
                                            @endforeach
                                        </select>   
                                </div>
                                
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label><strong>Diet Remarks</strong></label>
                                        <input type="text" class="form-control" id="remarks" name="remarks" placeholder="Remarks">
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label><strong>Doctors Notes</strong></label>
                                        <input type="text" class="form-control" id="donotes" name="donotes" placeholder="Doctors Notes">
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label><strong>Date and Time Posted:</strong></label>
                                        <input type="datetime-local" id="dodtepost" name="dodtepost" value="<?php echo date('Y-m-d\TH:i'); ?>" class="form-control floating-label" step="any" required>
                                    </div>
                                </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </div>
                    </div>
                        </form>
                    </div>


                </div>
            </div>
        </div>
    </div>

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