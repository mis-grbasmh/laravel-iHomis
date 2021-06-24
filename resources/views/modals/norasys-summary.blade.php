<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <!-- Modal content-->
    <div class="modal-content">
        <div class="modal-header">
            <h6><strong>NORA SYS</strong> Summary <small>by Month of Discharge </small> </h6>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
            <div class="form-row">
                <div class="form-group col-md-4">
                    <label for="inputCity">Select Month</label>
                    <select class="form-control" name="month" id="filter_month" onchange="get_summary();">
                    <option value="1" >January</option>
                    <option value="2">February</option>
                    <option value="3">March</option>
                    <option value="4">April</option>
                    <option value="5">May</option>
                    <option value="6">June</option>
                    <option value="7">July</option>
                    <option value="8">August</option>
                    <option value="9">September</option>
                    <option value="10">October</option>
                    <option value="11">November</option>
                    <option value="12">December</option>
                    </select>
                </div>
            <div class="form-group col-md-4">
                <label for="inputState">Year</label>
                <select class="form-control" name="year" id="filter_year" onchange="get_summary();">
                    <option value="2017" >2017</option>
                    <option value="2018" >2018</option>
                    <option value="2019">2019</option>
                    <option value="2020">2020</option>
                    <option value="2021" selected>2021</option>
                </select>
            </div>
        </div><!-- modal body -->
        <hr>

        <div class="table-responsive">
            <table class="table">
                <thead class=" text-primary">
                    <th class="text-center">STATUS</th>
                    <th style="width: 30%;" class="text-center">No. of Claims</th>
                    <th class="text-center">Amount</th>
                    <th class="text-right">Actions</th>
                </thead>
                <tbody>
                    <tr>
                        <td>WITH CHEQUE</th>
                        <td style="text-align:center"><label><strong><span id="row_withcheque"></span></strong></label></td>
                        <td class="text-right"><label><strong><span id="withcheque"></span></strong></label></td>
                        <td class="td-actions text-right">

                            <button type="button" rel="tooltip" class="btn btn-info btn-sm btn-icon">
                                <i class="tim-icons icon-bullet-list-67"></i>
                            </button>
                        </td>
                    </tr>
                    <tr>
                        <th>IN-PROCESS</th>
                        <td style="text-align:center"><label><strong><span id="row_inprocess"></span></strong></label></td>
                        <td class="text-right"><label><strong><span id="inprocess"></span></strong></label></td>
                        <td class="td-actions text-right">
                            <button type="button" rel="tooltip" class="btn btn-info btn-sm btn-icon">
                                <i class="tim-icons icon-bullet-list-67"></i>
                            </button>
                        </td>
                    </tr>
                    <tr>
                        <th>RETURNED</th>
                        <td style="text-align:center"><label><strong><span id="row_returned"></span></strong></label></td>
                        <td class="text-right"><label><strong><span id="returned"></span></strong></label></td>
                        <td class="td-actions text-right">
                            <button type="button" rel="tooltip" class="btn btn-info btn-sm btn-icon">
                                <i class="tim-icons icon-bullet-list-67"></i>
                            </button>
                        </td>
                    </tr>
                    <tr>
                        <th>DENIED</th>
                        <td style="text-align:center"><label><strong><span id="row_denied"></span></strong></label></td>
                        <td class="text-right"><label><strong><span id="denied"></span></strong></label></td>
                        <td class="td-actions text-right">
                            <button type="button" rel="tooltip" class="btn btn-info btn-sm btn-icon">
                                <i class="tim-icons icon-bullet-list-67"></i>
                            </button>
                        </td>
                    </tr>
                    <tr>
                        <th>VOUCHERING</th>
                        <td style="text-align:center"><label><strong><span id="row_vouchering"></span></strong></label></td>
                        <td class="text-right"><label><strong><span id="vouchering"></span></strong></label></td>
                        <td class="td-actions text-right">
                            <button type="button" rel="tooltip" class="btn btn-info btn-sm btn-icon">
                                <i class="tim-icons icon-bullet-list-67"></i>
                            </button>
                        </td>
                    </tr>
                    <tr>
                        <th>TOTAL</th>
                        <td style="text-align:center"><label><strong><span id="row_vouchering"></span></strong></label></td>
                        <td class="text-right"><label><strong><span id="vouchering"></span></strong></label></td>
                        <td>&nbsp;</td>
                    </tr>
                </tbody>
            </table>
       </div>
       <div class="modal-footer">
           <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
   </div>
  </div>
</div><!-- modal-dialog-->
<script>
    function get_summary(){
        var year= document.getElementById("filter_year").value;
        var month= document.getElementById("filter_month").value;
        if(year){
            $.ajax({
                url:"{{ route('getnorasys.summary') }}",
                method:'GET',
                data:{year:year,month:month},
                dataType:'json',
                success:function(data)
                {
                    $('#withcheque').text(data.withcheque);
                    $('#inprocess').text(data.inprocess);
                    $('#denied').text(data.denied);
                    $('#returned').text(data.returned);
                    $('#row_withcheque').text(data.row_withcheque);
                    $('#row_returned').text(data.row_returned);
                    $('#row_denied').text(data.row_denied);
                    $('#row_inprocess').text(data.row_inprocess);
                }
            });
        }
    }//end function handler
</script>
