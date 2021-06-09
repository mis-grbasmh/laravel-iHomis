<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <!-- Modal content-->
  <div class="modal-content">
   <div class="modal-header">
   <h6><strong>Patient</strong> History <small></small> </h6>
       <button type="button" class="close" data-dismiss="modal">&times;</button>
   </div>
   <div class="modal-body">
       <div class="row clearfix">
           <hr>
           <div class="table-responsive">
               <small> <p>Total Result(s) : <span id="total_history"> </span> Found</p></small>
                   <table class="table table-hover" id="tableHistory">
                       <!-- <table class="table table-bordered" > -->
                       <thead>
                       <tr>
                       <th>Encounter Type</th>
                       <th>Service</th>
                       <th>Date & Time Encountered</th>
                       <th>Physician</th>
                       <th>Discharge Date</th>
                       </tr>
                       </thead>
                       <tbody id="history_table">
                       </tbody>
                   </table>
           </div>
       </div>
       <div class="modal-footer">
           <!-- <button type="submit" class="btn btn-info btn-round">Save Changes</button> -->
           <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
   </div>
  </div>
</div><!-- modal-dialog-->

<script>
      function gethistory(){
        var query= document.getElementById("hpercode").value;
        if(query){
        $.ajax({
                    url:"{{ route('getPatient.history') }}",
                    method:'GET',
                    data:{query:query},
                    dataType:'json',
                    success:function(data)
                    {
                        $('#history_table').html(data.table_data);
                        $('#modalhistory').modal('show');
                        $('#total_history').text(data.total_data);
                    }
            });
        }//if query
    // return false;
    }
</script>
