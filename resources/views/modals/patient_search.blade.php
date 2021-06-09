<div class="modal-dialog modal-lg">
    <!-- Modal content-->
   <div class="modal-content">
       <div class="modal-header">
       <h6><strong>Patient</strong> Search <small></small> </h6>                   
           <button type="button" class="close" data-dismiss="modal">&times;</button>
       </div>
       <div class="modal-body">
       <div class="row clearfix">
           <div class="col-sm-4">
               <label for="doctor"><strong>Filter By:</strong></label>
               <select class="form-control" name="filter" id="filter">
               <option value="0">Select Filter By</option>
               <option value="1" >Health Record No.</option>
               <option value="2"selected>Name</option>
               <option value="3">Address</option>
               </select>
           </div>
           <div class="col-sm-7">
               <div class="form-group">
                   <label for="name"><strong>Search Key</strong><small> [Lastname, Firstname] or [Hopsital No.]</small></label>
                   <input type="text" name="search" id="search" class="form-control" placeholder="Search..." />
               </div>
           </div>
       </div>
       <hr>
       <div class="table-responsive">
           <small> <p>Total Result(s) : <span id="total_records"> </span> Found</p></small>
           <table class="table table-bordered table-striped table-hover js-basic-example dataTable" id="tableitems">
               <thead>
               <tr>
               <th style="display:none;" >Health Rec. No.</th>
               <th>Patient Name</th>
               <th>Sex/Age</th>
               <th>Address</th>
               <th>Birthdate</th>
               </tr>
               </thead>
               <tbody id="results">
               </tbody>
           </table>
       </div>
   </div><!-- modal-content-->
       <div class="modal-footer">
        <a href="{{ route('patient.create') }}" class="btn btn-info btn-round">New Patient</a>
        {{-- <button type="button" class="btn btn-info btn-round">New Patient</button> --}}
       <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
   </div>
    </div>
   </div>

   <script>
    $(document).ready(function(){
        fetch_customer_data();
            function fetch_customer_data(query = '',filter='')
            {
                var filter = $('#filter').val();
                $.ajax({
                    url:"{{ route('live_search.action') }}",
                    method:'GET',
                    data:{query:query,filter:filter},
                    dataType:'json',
                    success:function(data)
                    {
                            $('#results').html(data.table_data);
                            $('#total_records').text(data.total_data);
                    }
                })
            }//function
                $(document).on('keyup', '#search', function(){
                    var query = $(this).val();
                    if(query !=''){
                    fetch_customer_data(query);
                    }else{
                        var Table = document.getElementById("results");
                        Table.innerHTML = "";
                    }
                });
                $(document).on('change', '#search', function(){
                    var query = $(this).val();
                    if(query !=''){
                    fetch_customer_data(query);
                    }else{
                        var Table = document.getElementById("results");
                        Table.innerHTML = "";
                    }
                });
            });
</script>
