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
                                      
                   
                </div></div>
                   
                   
                   
                   
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>   
                   
                    </div>   
                </div>
            </div>
        </form>
        
</div>