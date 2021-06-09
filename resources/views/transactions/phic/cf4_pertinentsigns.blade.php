        <div class="card">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col-8">
                        <h6 class="mb-0">Pertinent Signs and Symptoms on Admission <small> (Please tick checkbox if applicable...)</small></h6>
                    </div>
                </div>
                <div class="pull-right">
                    <div class="dropdown">
                        {{-- <h6 class="title d-inline">Option</h6> --}}
                         <button type="button" class="btn btn-link dropdown-toggle btn-icon text-center" data-toggle="dropdown">
                             <i class="tim-icons icon-settings-gear-63"></i>
                         </button>
                         <div class="dropdown-menu dropdown-menu-right dropdown-white" aria-labelledby="dropdownMenuLink">
                             <h6 class="dropdown-header">Select Option</h6>
                             <a class="dropdown-item" href="javascript:void(0);">ER Patients</a>
                             <a class="dropdown-item" href="javascript:void(0);">OPD Patients</a>
                             <a class="dropdown-item" href="javascript:void(0);">Print</a>
                             
                         </div>
                     </div>
                </div>
                @if($complaint)
                <div class="row clearfix">
                    <div class="col-lg-4 col-md-6">
                        <div class="card card-tasks" >
                            <div class="card-header">
                            <h6 class="title d-inline">Patient Name</h6>
                        </div>
                        <div class="card-body ">
                            sadsad
                        </div>
                        </div>


                  
                    <div class="d-flex flex-wrap">
                        <div class="p-4">
                            <div class="card">
                                <div class="card-header"></div>
                                <div class="card-body">
                                    <h5 class="card-title"><Strong>Chief Complaint</strong></h5>
                                    <p class="card-text">
                                        <textarea spellcheck="true" rows="2" id="complaint" name="complaint" class="form-control no-resize" placeholder="Please type course in the ward..." required>{{$complaint}}</textarea>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="p-">
                            <div class="card">
                                <div class="card-header"></div>
                                <div class="card-body">
                                    <h5 class="card-title">>Admitting Diagnosis</h5>
                                    <p class="card-text">
                                            <textarea spellcheck="true" rows="4" id="admtxt" name="admtxt" class="form-control no-resize" placeholder="Please type course in the ward..." required>{{ $admdiagnosis->admtxt }}</textarea>   
                                    </p>
                                </div>
                            </div>
                    </div>
                </div>

            </div>
        </div>
       
<div class="card">
    <form class="form-horizontal" role="form" method="GET" action="{{ route('cf4.achob_update',['id' => $enccode]) }}">
    {{ csrf_field() }}                        
    @csrf   
    {{-- <input type="hidden" id="enccode" name="enccode" type="text" value="{{ $enccode }}" >             --}}
    {{-- <input type="hidden" id="hpercode" name="hpercode" type="text" value="{{ $hpercode }}" >             --}}
  
    
        <div class="container">
               
    
        </div>
    </div>
    
    
    <div class="card-body">
    
       
       
       
        <div class="col-md-12">
            <div class="form-group"> 
                <p><Strong>Chief Complaint</strong></p>
              
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group"> 
                
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
                                <a data-target="#empModal" data-toggle="modal" class="MainNavText" id="MainNavHelp" href="#empModal">dsd</a>
                                <!-- <button class="btn btn-primary btn-round btn-md icon" type="submit"><i class="fa fa-search"></i></button> -->
                                </div>
                            </div>

                </div>
       
       
        <div class="col-md-12">
            <div class="form-group"> 
        
        
                <p><Strong>Final Diagnosis</strong></p>
                <textarea spellcheck="true" rows="4" id="finaldiagnosis" name="finaldiagnosis" class="form-control no-resize" placeholder="Please type course in the ward..." required>{{ $finaldiagnosis }}</textarea>   
             
            </div>
          
        </div>
        <div class="col-md-12">
            <div class="form-group"> 
                <p><Strong>History of Present Illness</strong></p>
                <textarea spellcheck="true" rows="6" class="form-control no-resize" name="presentillness" placeholder="Please type present illness here..."> @if($history <> NULL) {{$history}} @endif </textarea>
            </div>
        </div>

        @if($admdiagnosis->tscode =='002')


        <div class="col-md-12">
            <p><Strong>OB/GYN History</strong></p>
        </div>
        <div class="col-md-12">
            <div class="row clearfix">
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="bp">No. of Preg. to Date-Gravidity</label>
                        <input spellcheck="true" type="text" name="ob_g" id="obg" class="form-control"  value="@if($prenatals) @if($prenatals->ob_g <> NULL) {{$prenatals->ob_g}} @endif @endif">    
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="hr">No. of Del. to Date-Parity</label>
                        <input spellcheck="true" type="text" name="ob_p" id="obp" class="form-control"  value="@if($prenatals) @if($prenatals->ob_p <> NULL) {{$prenatals->ob_p}} @endif @endif">    
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="rr">No. of Full Term Pregnancy:</label>
                        <input spellcheck="true" type="text" name="ob_t" id="obt" class="form-control"  value="@if($prenatals) @if($prenatals->ob_t <> NULL) {{$prenatals->ob_t}} @endif @endif">    
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="temp">No. of Premature Pregnancy</label>
                        <input spellcheck="true" type="text" name="ob_p1" id="obp1" class="form-control"  value="@if($prenatals) @if($prenatals->ob_p1 <> NULL) {{$prenatals->ob_p1}} @endif @endif">    
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="temp">No. of Abortion</label>
                        <input spellcheck="true" type="text" name="ob_a" id="oba" class="form-control"  value="@if($prenatals) @if($prenatals->ob_a <> NULL) {{$prenatals->ob_a}} @endif @endif">    
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="temp">Last Menstrual Period</label>
                        <input type="datetime-local" name="lmp" id="lmp" class="form-control"  step="any" value="@if($prenatals) @if($prenatals->lmp <> NULL) {{$prenatals->lmp}} @endif @endif">    
                
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="temp">No. of Living Children</label>
                        <input type="text" name="ob_l" id="obl" class="form-control"  value="@if($prenatals) @if($prenatals->ob_l <> NULL) {{$prenatals->ob_l}} @endif @endif">    
                    </div>
                </div>
            </div>
        </div>
    @endif
    
       


    </div><!-- body -->
    @endif
    @if($admdiagnosis)
    <div class="col-md-12">
        <button type="submit" class="btn btn-info btn-round">Save Changes</button>
    </div>
    @endif
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