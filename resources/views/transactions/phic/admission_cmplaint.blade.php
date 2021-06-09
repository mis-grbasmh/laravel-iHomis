<div class="card">
    <div class="card-header">
        <div class="row align-items-center">
            <div class="col-8">
                <h5 class="card-category">Pertinent Signs and Symptoms on Admission</h5>
                <p class="card-category d-inline">(Please tick checkbox if applicable...)</p>
            </div>
            
        </div>
    <div class="card-body ">


   
    {{-- <form class="form-horizontal" role="form" method="GET" action="{{ route('cf4.achob_update',['id' => $enccode]) }}"> --}}
    {{ csrf_field() }}                        
    @csrf   
        <div class="col-md-12">
            <div class="form-group"> 
                <p><Strong>Chief Complaint</strong></p>
                <textarea spellcheck="true" rows="2" id="show_complaint" name="complaint" class="form-control no-resize" placeholder="Please type in chief complaint..." required></textarea>
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group"> 
                <p><Strong>Admitting Diagnosis</strong></p>
                <textarea spellcheck="true" rows="4" id="admtxt" name="admtxt" class="form-control no-resize" placeholder="Please type in admitting/clinical diagnosis..." required></textarea>
            </div>
        </div>
        <div class="row clearfix">
     
                            <div class="col-md-2">
                          
                                <div class="form-group">
                                <p><Strong>ICD Code</strong></p>
                                <input type='text' id="icdcode" name="icdcode" class="form-control" placeholder='ICD Code'>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                <p>&nbsp;</p>
                                <a data-target="#empModal" data-toggle="modal" class="MainNavText" id="MainNavHelp" href="#empModal">select diagnosis</a>
                                <!-- <button class="btn btn-primary btn-round btn-md icon" type="submit"><i class="fa fa-search"></i></button> -->
                                </div>
                            </div>

                </div>
       
       
        <div class="col-md-12">
            <div class="form-group"> 
        
        
                <p><Strong>Final Diagnosis</strong></p>
                <textarea spellcheck="true" rows="4" id="finaldiagnosis" name="finaldiagnosis" class="form-control no-resize" placeholder="Please type course in the ward..." required></textarea>   
             
            </div>
          
        </div>
        <div class="col-md-12">
            <div class="form-group"> 
                <p><Strong>History of Present Illness</strong></p>
                <textarea spellcheck="true" rows="6" class="form-control no-resize" name="presentillness" placeholder="Please type present illness here..."> </textarea>
            </div>
        </div>



        <div class="col-md-12">
            <p><Strong>OB/GYN History</strong></p>
        </div>
        <div class="col-md-12">
            <div class="row clearfix">
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="bp">No. of Preg. to Date-Gravidity</label>
                        <input spellcheck="true" type="text" name="ob_g" id="obg" class="form-control"  >    
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="hr">No. of Del. to Date-Parity</label>
                        <input spellcheck="true" type="text" name="ob_p" id="obp" class="form-control" >    
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="rr">No. of Full Term Pregnancy:</label>
                        <input spellcheck="true" type="text" name="ob_t" id="obt" class="form-control">    
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="temp">No. of Premature Pregnancy</label>
                        <input spellcheck="true" type="text" name="ob_p1" id="obp1" class="form-control">    
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="temp">No. of Abortion</label>
                        <input spellcheck="true" type="text" name="ob_a" id="oba" class="form-control">    
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="temp">Last Menstrual Period</label>
                        <input type="datetime-local" name="lmp" id="lmp" class="form-control"  step="any">    
                
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="temp">No. of Living Children</label>
                        <input type="text" name="ob_l" id="obl" class="form-control">    
                    </div>
                </div>
            </div>
        </div>
    
       


    </div><!-- body -->
    <div class="col-md-12">
        <button type="submit" class="btn btn-info btn-round">Save Changes</button>
    </div>
    </form>   
 </div><!-- card -->

 <script>
   
   $(document).ready(function(){
   $('#lmp').val(new Date().toLocaleString("sv-SE", {
       year: "numeric",
       month: "2-digit",
       day: "2-digit",
       hour: "2-digit",
       minute: "2-digit",
       second: "2-digit"
   }).replace(" ", "T"));
   });
   </script>


<script>
    function getPertinentsigns(query){
    if(query){
        var res = query.split('/').join('-');
                var url ='{{ route("cf4.getPertinentsigns", ":id")}}';
                    url = url.replace(':id', res);
                    $.ajax({
                    url : url,
                    type : 'GET',
                    datatype : 'json',
                    success:function(data){
                              $('#id').val(data.enccode);
                              $('#name').val(data.patientname);
                              $('#edit_admdate').val(data.admdate);
                              $('#edit_admnotes').val(data.admnotes);
                              $('#edit_admtxt').val(data.admtxt);
    });
  }
}
</script>