<div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h6 class="modal-title" id="myModalLabel"><strong>E.R. Patient Discharge </strong></h6>
        </div>
            <div class="modal-body p-0">
                <div class="card m-0">
                <form action="{{ route('erpatient.discharge') }}" enctype="multipart/form-data">
                   <input type="text" id="enccode" name="enccode">
                   <input type="hidden" id="hpercode" name="hpercode">
                   <input type="hidden" id="licno" name="licno">
                   @csrf
                    <div class="body">
                            <div class="row clearfix">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label><strong>Discharge Date:</strong></label>
                                        <input type="datetime-local" id="erdtedis" name="erdtedis" value="<?php echo date('Y-m-d'); ?>" class="form-control floating-label" step="any" required>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <label><strong>Disposition:</strong></label>
                                    <select class="form-control" id="ddldisposition" name="dispcode" onchange="ShowHideDivDisposition()">
                                        <option selected value="TRASH">Treated and Sent Home</option>
                                        @foreach ($dispositions as $disposition)
                                        <option value="{{$disposition->herdispcode}}">{{$disposition->herdispdesc}}</option>
                                        @endforeach
                                        </select>
                                </div>
                                <div class="col-sm-12"  id="dvtransferred" style="display: block;">
                                    <label><strong>Transferred To:</strong></label>
                                    <select class="form-control" id="ddldisposition" name="refto" onchange="ShowHideDiv()">
                                        <option value="0000040">MARIANO MARCOS MEM. HOSPITAL & MED CTR</option>
                                        <!-- @foreach ($dispositions as $disposition)
                                        <option value="{{$disposition->herdispcode}}">{{$disposition->herdispdesc}}</option>
                                        @endforeach -->
                                        </select>
                               
                                    <label><strong>Reason for Transfer:</strong></label>
                                    <select class="form-control" id="ddldisposition" name="reftxt" onchange="ShowHideDiv()">
                                        @foreach ($reasonsfortrans as $key =>$reasonsfortran)
                                        <option value="{{$reasonsfortran->reftxt}}">{{$reasonsfortran->reftxt}}</option>
                                        @endforeach
                                        </select>
                               
                                </div><!-- col-->
                                <div class="col-sm-12"  id="dvadmission" style="display: block;">
                                    <div class="form-group" >
                                        <label><strong>Reasons for Admission:</strong></label>
                                        <input type="text" class="form-control"  id="resadmit" name="resadmit" placeholder="Type in reasons for admission">
                                    </div> <!-- form-group-->
                                </div>
                                <div class="col-sm-12">
                                <label><strong>Condition upon Discharge:</strong></label>
                                <select class="form-control"   id="condcode" name="condcode">
                                        <option value="" selected></option>
                                        @foreach ($conditiondischarges as $key => $conditiondischarge)
                                        <option value="{{$key}}">{{$conditiondischarge}}</option>
                                        @endforeach
                                </select>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                            <label><strong>Diagnosis Code:</strong></label>
                            <div class="form-line">
                                <input type="text" class="form-control"  id="diagcodeext" name="diagcodeext" placeholder="Type in diagnosis code">
                                    </div>
                        
                        
                          
                                <!-- <input type="text" class="form-control" placeholder="Discharge Time"> -->
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                            <label><strong>Diagnosis:</strong></label>
                            <div class="form-line">
                                        <textarea rows="4" name="diagtext" id="diagtext" class="form-control no-resize" placeholder="Please type final diagnosis..."></textarea>
                                    </div>
                        
                        
                          
                                <!-- <input type="text" class="form-control" placeholder="Discharge Time"> -->
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
  
  
    <script>
        $('#erdischarge').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget)
            var lname = button.data('lname')
          //  alert(lname);
            var modal = $(this)
            modal.find('.modal-body #lname').val(lname);

        })

    </script>      