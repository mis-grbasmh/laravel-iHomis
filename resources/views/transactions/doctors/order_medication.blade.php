<div class="card">
  <div class="card-header">
      <div class="row align-items-center">
          <div class="col-8">
              <h4 class="mb-0">Drugs and Medications Order</h4>
          </div>
          <div class="col-4 text-right">
              {{-- <a data-toggle="modal" href="#"  data-target="#newmedorder"  data-backdrop="static" class="btn btn-primary btn-sm">New Meds Order</i></a> --}}
              <button type="button" class="btn btn-sm btn-primary" id="btnAddMeds">New Medication Order</button>
          </div>
      </div>
      <div class="card-body "><hr/>
          @if (count($errors) > 0)
               @include('alerts.error')
          @endif
          <div class="table-responsive">
              <table id="drugsTable" class="display" cellspacing="0" style="width:100%" >
                  <thead class=" text-primary">
                    <th>Date Requested</th>
                    <th>Drugs Description</th>
                    <th>Qty Issued</th>
                    <th>Balance</th>
                    <th>Charge Slip No.</th>
                    <th>Status</th>
                    <th>Entry By</th>
                  {{-- <th scope="col"></th> --}}
                  </thead>
              </table>
          </div>
  </div>
</div>
</div>



<!-- start addmodal-->
<div class="modal fade" tabindex="-1" role="dialog" id="modalAddMeds">
    <div class="modal-dialog modal-lg role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">New Medication Order</h4>
            </div>
            <div class="modal-body">
                <input type="hidden" id="enccode" name="enccode" value="{{$enccode}}">
                <input type="text" id="add_dmdctr" name="dmdctr">
                <input type="text" id="add_dmdcomb" name="dmdcomb">
                <div class="form-row">
                    <div class="form-group{{ $errors->has('dodate') ? ' has-danger' : '' }} col-md-3">
                        <label for="add_dodate" class="control-label">
                        Date and Time of Order:<span class="required">*</span>
                        </label>
                        <input type="datetime-local" id="add_dodate" name="dodate" value="<?php echo date('Y-m-d'); ?>" class="form-control floating-label" step="any">
                        {{-- <input type="datetime-local" id="add_dodate" name="dodate" class="form-control floating-label"  value="<?php echo date('Y-m-d'); ?>" step="any"> --}}
                        <p class="add_errordodate text-danger hidden"></p>
                    </div>

                    <div class="form-group{{ $errors->has('licno') ? ' has-danger' : '' }} col-md-6">
                        <label for="add_doctor" class="control-label">
                        Ordered By<span class="required">*</span>
                        </label>
                        <select class="form-control" id="add_licno" name="licno">
                        @foreach($doctors as $doctor)
                            <option value="{{ $doctor->licno }}"><strong>{{getdoctorinfo($doctor->licno)}}</strong></option>
                        @endforeach
                        </select>
                        <p class="add_errorDoctor text-danger hidden"></p>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group{{ $errors->has('add_item') ? ' has-danger' : '' }} col-md-8">
                        <label><strong>Drug Item:</strong></label>
                        <input type="text" class="form-control" id="add_item"" disabled>
                    </div>
                    <div class="form-group{{ $errors->has('add_item') ? ' has-danger' : '' }} col-md-2">
                        <label><strong>&nbsp;</strong></label>
                        <button type="button" class="btn btn-sm btn-primary" id="btnSelectItem">Select Item...</button>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group{{ $errors->has('add_qty') ? ' has-danger' : '' }} col-md-2">
                        <label><strong>QTY:</strong></label>
                        <input type="number" class="form-control" id="add_pchrgqty" name="pchrgqty" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" value="1" maxlength="2" minlength="1" required>
                    </div>
                    <div class="form-group col-md-4">
                        <label><strong>UOM:</strong></label>
                        <select class="form-select" id="add_uom" name="uom">
                            @foreach (UomTypes() as $key => $row)
                                <option value="{{ $key}}">{{$row->uomdesc}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-2">
                        <label><strong>FREQ.:</strong></label>
                        <input type="number" class="form-control" id="add_reppatrn1" name="reppatru1" maxlength="2" minlength="1" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" required>
                    </div>
                    <div class="form-group col-md-2">
                        <label><strong>UOM:</strong></label>
                        <select class="form-control" id="add_uom" name="uom">
                            <option selected>Hour(s)</option>
                            <option>Day(s)</option>
                            <option>Week(s)</option>
                            <option>Month(s)</option>
                            <option>Year(s)</option>
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="edit_dodate" class="control-label">
                        Start of Medication:<span class="required">*</span>
                        </label>
                        <input type="datetime-local" id="add_admdate" name="admdate" value="<?php echo date('Y-m-d'); ?>" class="form-control floating-label" step="any">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- end addmodal-->

<div class="modal fade" tabindex="-1" role="dialog" id="modalMedicationItems">
    <div class="modal-dialog modal-lg role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Select Drug Item</h4>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table id="MedicationTable" class="display" cellspacing="0" style="width:100%" >
                        <thead class=" text-primary">
                            <th>Generic Name</th>
                            <th>Brand Name</th>
                            <th>Details</th>
                            <th>Available Stocks</th>
                            <th>Remarks</th>
                            <th>Action</th>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@push('js')
<script>
  function getmedication(query){
  if(query){
    table = $('#drugsTable').DataTable({
    stateSave: true,
  //  responsive: true,
    processing: true,
    serverSide : true,
    order : [0,'desc'],
    destroy: true,
    scrollX:true,
    scrollY:true,

    columnDefs: [
    {
      targets: [0],
      className: 'nw'
    }
    ],
    processing: true,
    serverSide: true,
    "ajax": {
        url: "{{route('getPatient.medication')}}",
        method:'GET',
        data:{query:query},
        dataType:'json',
          error: function (errmsg) {
        alert('Unexpected Error');
        console.log(errmsg['responseText']);
        },
    },
      columns: [
            { "data": "dodate" },
            { "data": "gendesc" },
            { "data": "qtyissued"},
            { "data": "qtybal" },
            { "data": "pcchrgcod" },
            { "data": "estatus" },
            { "data": "entryby" }

       ]
    });
  }
}
</script>

<script>
    $('#btnAddMeds').click(function(e){
        $("#modalAddMeds").modal({
                            backdrop: 'static',
                            keyboard: false,
                            show: true,
                            });
        // $('#modalAddMeds').modal('show');
    });

    $('#btnSelectItem').click(function(e){
        table = $('#MedicationTable').DataTable({
        stateSave: true,
      //  responsive: true,
        processing: true,
        serverSide : true,
        order : [0,'ASC'],
        destroy: true,
        scrollX:true,
        scrollY:true,
        columnDefs: [
        {
        targets: [0],
        className: 'nw'
        }
        ],
        processing: true,
        serverSide: true,
        "ajax": {
            url: "{{route('ajax.get_Itemsmedication')}}",
            method:'GET',
            dataType:'json',
            error: function (errmsg) {
            alert('Unexpected Error');
            console.log(errmsg['responseText']);
            },
        },
        columns: [
                { "data": "gendesc" },
                { "data": "brandname" },
                { "data": "details" },
                { "data": "stockbal" },
                { "data": "dmdrem" },
                { "data": "action" }
        ]
        });
        $("#modalMedicationItems").modal({
                            backdrop: 'static',
                            keyboard: false,
                            show: true,
                            });
        });

        $('#MedicationTable').on('click','.btnSelect[data-select]',function(e){
        e.preventDefault();
        var id =$(this).data('id');
        var details =$(this).data('details');
        document.getElementById("add_dmdctr").value = $(this).data('dmdctr')
        document.getElementById("add_dmdcomb").value = $(this).data('id')
        document.getElementById("add_item").value = details;
        $('#modalMedicationItems').modal('hide');
    });
</script>


<script>
new SlimSelect({
            select: '.form-select'
        })
</script>
@endpush
