<script src="{{ asset('assets') }}/js/core/jquery.min.js"></script>
@extends('layouts.app', ['page' => 'Transactions', 'pageSlug' => 'medicalrecords', 'section' => 'codediagnosis'])

@section('content')
    <div class="row">
        <div class="col-lg-12 col-md-12">
            @include('alerts.success')
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="float-right col-md-8">
                            <h4 class="card-title">Medical Records</h4>
                        </div>

                    </div>
                </div>
            </div>

            <div class="row">
                <div class="card-body">
                <div class="row">
                    <div class="col-8">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="title d-inline">Encounter Details</h6>
                                <p class="card-category d-inline">Shows all the list of patients encounter with diagnosis...</p>
                                <div class="float-right col-md-4">
                                    <div class="form-row">
                                        <div class="form-group col-md-9">
                                          <label for="inputCity">Search Dignosis</label>
                                          <input type="text" id="criteria" name="criteria" onchange="handler(event);"  class="form-control floating-label">
                                        </div>
                                        <div class="form-group col-md-2">
                                            <label for="inputCity">&nbsp;</label>
                                         <button class="btn btn-sm btn-primary">Search</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body text-left py-6">
                                <div class="table-responsive">
                                    <table class="table tablesorter " id="">
                                        <thead class=" text-primary">
                                            <th style="display:none"scope="col">{{ __('Enccode') }}</th>
                                            <th  width="20%" scope="col">Patient Details</th>
                                            <th width="30%" scope="col">Encounter Details</th>

                                            {{-- <th scope="col">Physician</th> --}}
                                            <th scope="col">Chief Complaint/<br/>Admitting Diagnosis</th>
                                            <th scope="col"></th>
                                        </thead>
                                        <tbody>
                                        @foreach($transactions as $row)
                                        <tr>
                                            <td style="display:none">
                                                {{$row->enccode}}
                                            </td>

                                            <td><strong>{{getpatientinfo($row->hpercode)}}</strong> <br/> {{ number_format($row->patage)}} yr(s) old </td>
                                             <td>{{$row->type}} <br/>{{getFormattedDate($row->encdate)}} @ {{asDateTime($row->encdate)}}<br/>
                                                <br/><strong>{{getdoctorinfo($row->doctor)}}</strong> <br/>
                                                {{$row->service}}
                                            </td>

                                             {{-- <td>{{getdoctorinfo($row->doctor)}}</td> --}}
                                             <td><small>{{$row->diagnosis}}</small></td>
                                             <td><a href="#">Select</a></td>
                                        </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="card-footer py-4">
                                <nav class="d-flex justify-content-end" aria-label="...">
                                    {{-- {{ $transactions->links() }} --}}
                                </nav>
                            </div>
                        </div>
                    </div> <!-- col-6 -->
                    <div class="col-4">
                        <div class="card ">
                            <div class="card-header">
                                <h6 class="title d-inline">Patient Information of</h6>
                                <p class="card-category d-inline">Salacup, Jozzle Charlie M. (41,MALE)</p>
                        </div>
                        <div class="card-body text-left py-3">
                            <input type="text" class="form-control" id="enccode" aria-describedby="emailHelp" placeholder="Admission details">
                            <form>
                                <div class="form-group">
                                  <label for="patientname">Admission Details</label>
                                  <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Admission details" disabled>
                                </div>

                                <div class="form-group">
                                    <label for="diagnosis">Diagnosis</label>
                                    <textarea class="form-control" id="diagnosis" name="diag_text" placeholder="Final/Clinical Diagnosis"></textarea>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="icd10">ICD Code 10</label>
                                        <input type="text" class="form-control" id="icdcode" placeholder="ICD Code 10">
                                    </div>

                                    <div class="form-group col-md-2">
                                      <label for="inputZip">&nbsp;</label>
                                      <button type="button" class="btn btn-sm btn-primary" href="#" id="btnDiagnosis">ICD Diagnosis</button>
                                    </div>

                                </div>
                                <div class="form-group">
                                    <label for="address">Operation Date and Time</label>
                                    <input type="date" id="date2" name="disdate" onchange="handler(event);" value="{{ old('date', $date) }}" class="form-control floating-label" step="any" required>
                                </div>


                                <div class="form-check">
                                    <label class="form-check-label">
                                        <input class="form-check-input" type="checkbox" value="">
                                        Final Diagnosis?
                                        <span class="form-check-sign">
                                            <span class="check"></span>
                                        </span>
                                    </label>
                                </div>
                                <hr/>
                                <div class="form-group">
                                    <label for="address">Procedure Done</label>
                                    <input type="text" class="form-control" id="address" placeholder="Patient Address" disabled>
                                </div>
                                <div class="form-group">
                                    <label for="address">Address</label>
                                    <input type="text" class="form-control" id="address" placeholder="Patient Address" disabled>
                                </div>

                                  <button type="submit" class="btn btn-primary">Submit</button>


                              </form>
                            </div>
                        </div>
                    </div>
                </div> <!-- col-6 -->
            </div>
                </div>
                <div class="card-footer py-4">
                    <nav class="d-flex justify-content-end" aria-label="...">

                    </nav>
                </div>
            </div>
        </div>
    </div>

<!-- start addmodal-->
<div class="modal fade" tabindex="-1" role="dialog" id="modalDiagnosis">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">New Diet Order</h4>
            </div>
            <div class="modal-body">
            <form role="form" id="frmDiagnosis">
                <div class="form-group{{ $errors->has('religion') ? ' has-danger' : '' }} col-md-12">
                    <label class="form-control-label" for="input-diagnosis">Select Diagnosis</label>
                        {{-- <select class="selectpicker" data-style="btn-info" multiple data-max-options="3" data-live-search="true" id="input-diagnosis"> --}}
                            <select name="diagnosisi" id="input-diagnosis"  multiple class="form-select form-control-alternative{{ $errors->has('relcode') ? ' is-invalid' : '' }}">
                                @foreach ($diagnosis as $key =>$diag)
                                <option value="{{$diag->diagcode}}">{{$diag->diagdesc}}({{$diag->diagcode}})</option>
                                @endforeach
                                {{-- <optgroup label="Web">

                            <option>PHP</option>

                            <option>CSS</option>

                            <option>HTML</option>

                            <option>CSS 3</option>

                            <option>Bootstrap</option>

                            <option>JavaScript</option>

                            </optgroup>

                            <optgroup label="Programming">

                            <option>Java</option>

                            <option>C#</option>

                            <option>Python</option>

                            </optgroup> --}}

                            </select>
                </div>

                <div class="form-group">
                    <label for="edit_dodate" class="control-label">
                    Date and Time of Order:<span class="required">*</span>
                    </label>
                    <input type="datetime-local" id="edit_dodate" name="dodate" class="form-control floating-label" step="any">
                    <p class="edit_errordodate text-danger hidden"></p>
                </div>
                <div class="form-group">
                    <label for="edit_doctor" class="control-label">
                    Ordered By<span class="required">*</span>
                    </label>
                    <select class="form-control" id="edit_licno" name="licno">
                    {{-- @foreach($doctors as $doctor) --}}
                   {{-- <option value="{{ $doctor->licno }}"><strong>{{getdoctorinfo($doctor->licno)}}</strong></option> --}}
                    {{-- @endforeach --}}
                    </select>
                    <p class="edit_errorDoctor text-danger hidden"></p>
                </div>


                <div class="form-group">
                    <label for="edit_remarks" class="control-label">
                        Diagnosis Code<span class=""></span>
                    </label>
                    <input type="text" class="form-control" id="edit_remarks" name="remarks">
                    <p class="edit_errorRemarks text-danger hidden"></p>
                </div>
                <div class="form-group">
                    <label for="edit_diagnosis" class="control-label">
                   Diagnosis<span class="">*</span>
                    </label>
                    <textarea class="form-control" id="add_diagnosis" name="donotes"></textarea>
                    <p class="edit_errordoctornotes text-danger hidden"></p>
                </div>

            </form>
                <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" id="btnSave"><i class="glyphicon glyphicon-save"></i>&nbsp;Save</button>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- end Diagnosismodal-->
@endsection
@push('js')
<script>
    //calling add modal
    $('#btnDiagnosis').click(function(e){
            $('#modalDiagnosis').modal('show');
        });


        new SlimSelect({
        select: '#input-diagnosis'
        })


        //<script src="../assets/plugins/multi-select/js/jquery.multi-select.js"></script> <!-- Multi Select Plugin Js -->
</script>
@endpush



