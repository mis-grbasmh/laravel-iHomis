@extends('layouts.app', ['page' => 'New Patient', 'pageSlug' => 'patient', 'section' => 'OPD'])

@section('content')
    <div class="container-fluid mt--7">
    @include('alerts.error')
        <div class="row">
            <div class="col-xl-12 order-xl-1">
                <div class="card">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h6 class="heading-small text-muted mb-4">Patient Information</h6>
                            </div>

                            <div class="col-4 text-right">
                                <a href = "javascript:history.back()" class="btn btn-sm btn-primary">Back to List</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">

                        <form method="post" action="{{ route('receipts.store') }}" autocomplete="off">
                            @csrf

                            <div class="pl-lg-4">
                                <input type="hidden" name="user_id" value="{{ Auth::id() }}">
                                <div class="form-row">

                                    <div class="form-group col-md-2">
                                        <label for="inputHpeprcode">Health Rec. No.</label>
                                        <input type="text" name="hpercode" id="input-hpercode" class="form-control form-control-alternative{{ $errors->has('hpercode') ? ' is-invalid' : '' }}" placeholder="Health Record No." value="{{ $hpercode->newcode }}" required disabled>
                                    </div>
                                </div>
                                <div class="form-row">
                                        <div class="form-group col-md-1">
                                            <label for="inputSuffix">Suffix</label>
                                            <select id="inputSuffix" class="form-control">
                                              @foreach(Suffix() as $key => $suffix)
                                              @if($key == old('key'))
                                                <option value="{{$key}}" selected>{{$suffix}}</option>
                                              @else
                                                <option value="{{$key}}">{{$suffix}}</option>
                                              @endif
                                              @endforeach
                                            </select>
                                          </div>
                                          <div class="form-group{{ $errors->has('lastname') ? ' has-danger' : '' }} col-md-3">
                                            <label class="form-control-label" for="input-lastname">Lastname</label>
                                            <input type="text" name="lastname" id="input-lastname" class="form-control form-control-alternative{{ $errors->has('lastname') ? ' is-invalid' : '' }}" placeholder="Lastname" value="{{ old('lastname') }}" required autofocus>
                                            @include('alerts.feedback', ['field' => 'lastname'])
                                          </div>
                                          <div class="form-group{{ $errors->has('firstname') ? ' has-danger' : '' }} col-md-3">
                                            <label class="form-control-label" for="input-firstname">Firstname</label>
                                            <input type="text" name="firstname" id="input-firstname" class="form-control form-control-alternative{{ $errors->has('firstname') ? ' is-invalid' : '' }}" placeholder="Firstname" value="{{ old('firstname') }}" required autofocus>
                                            @include('alerts.feedback', ['field' => 'firstname'])
                                          </div>

                                          <div class="form-group{{ $errors->has('middlename') ? ' has-danger' : '' }} col-md-3">
                                            <label class="form-control-label" for="input-middlename">Middlename</label>
                                            <input type="text" name="middlename" id="input-middlename" class="form-control form-control-alternative{{ $errors->has('middlename') ? ' is-invalid' : '' }}" placeholder="Middlename" value="{{ old('middlename') }}" required autofocus>
                                            @include('alerts.feedback', ['field' => 'middlename'])
                                          </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col-md-1">
                                                <label for="inputGender">Gender</label>
                                                <select id="inputGender" name="patsex"class="form-control" value="{{ old('patsex') }}">
                                                  <option disabled>Select Gender</option>
                                                  <option value="M" selected>Male</option>
                                                  <option value="F">Female</option>
                                                </select>
                                              </div>
                                            <div class="form-group{{ $errors->has('patbdate') ? ' has-danger' : '' }} col-md-2">
                                                <label class="form-control-label" for="input-patbdate">Date of Birth</label>
                                                <input type="datetime-local" id="input-patbdate" name="patbdate" class="form-control floating-label" step="any" value="{{ old('patbdate') }}" required autofocus>
                                                @include('alerts.feedback', ['field' => 'patbdate'])
                                            </div>
                                            <div class="form-group{{ $errors->has('patage') ? ' has-danger' : '' }} col-md-2">
                                                <label class="form-control-label" for="input-patage">Age</label>
                                                <input type="text" name="patage" id="input-patage" class="form-control form-control-alternative{{ $errors->has('patage') ? ' is-invalid' : '' }}" placeholder="Age" value="{{ old('patage') }}" disabled autofocus>
                                                @include('alerts.feedback', ['field' => 'patage'])
                                            </div>
                                            <div class="form-group col-md-3">
                                                <label for="inputBPlace">Place of Birth</label>
                                                <select id="inputBPlace" name="BPlace"class="form-control" value="{{ old('BPlace') }}">

                                                </select>
                                              </div>
                                              <div class="form-group col-md-1">
                                                <label for="inputbloodtype">Blood Type</label>
                                                <select id="inputbloodtype" name="bloodtype"class="form-control" value="{{ old('bloodtype') }}">

                                                </select>
                                              </div>
                                    </div>

                                        <div class="form-row">
                                            <div class="form-group{{ $errors->has('street') ? ' has-danger' : '' }} col-md-3">
                                                <label class="form-control-label" for="input-street">Street</label>
                                                <input type="text" name="street" id="input-street" class="form-control form-control-alternative{{ $errors->has('street') ? ' is-invalid' : '' }}" placeholder="Street" value="{{ old('street') }}" required autofocus>
                                                @include('alerts.feedback', ['field' => 'street'])
                                            </div>

                                                <div class="form-group{{ $errors->has('city_code') ? ' has-danger' : '' }} col-md-2">
                                                    <label class="form-control-label" for="input-city">City/Town</label>
                                                    <select name="city" id="input-city" class="form-select form-control-alternative{{ $errors->has('city') ? ' is-invalid' : '' }}">
                                                        <option value="">Not Specified</option>
                                                        @foreach ($citytowns as $city)
                                                            @if($city->ctycode == old('$city->ctycode'))
                                                                <option value="{{$city->ctycode}}" selected>{{$city->ctyname}} ({{ $city->provname}}) </option>
                                                            @else
                                                                <option value="{{$city->ctycode}}">{{$city->ctyname}} ({{ $city->provname}})</option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                    @include('alerts.feedback', ['field' => 'city'])
                                                </div>
                                                <div class="form-group{{ $errors->has('bgycode') ? ' has-danger' : '' }} col-md-2">
                                                    <label class="form-control-label" for="input-barangay">Barangay</label>
                                                    <select name="barangay" id="input-barangay" class="form-select form-control-alternative{{ $errors->has('bgycode') ? ' is-invalid' : '' }}">

                                                    @include('alerts.feedback', ['field' => 'bgycode'])
                                                </select>
                                                </div>
{{-- brg,ctycode,provcode,patzip,cntrycode --}}
                                                <div class="form-group{{ $errors->has('province') ? ' has-danger' : '' }} col-md-2">
                                                    <label class="form-control-label" for="input-province">Province</label>
                                                    <input type="hidden" name="provcode" id="input-provcode">
                                                    <input type="text" name="province" id="input-province" class="form-control form-control-alternative{{ $errors->has('province') ? ' is-invalid' : '' }}" placeholder="Province" value="{{ old('province') }}" required disabled>
                                                    @include('alerts.feedback', ['field' => 'province'])
                                                  </div>

                                                  <div class="form-group{{ $errors->has('region') ? ' has-danger' : '' }} col-md-1">
                                                    <label class="form-control-label" for="input-region">Region</label>
                                                    <input type="text" name="region" id="input-region" class="form-control form-control-alternative{{ $errors->has('region') ? ' is-invalid' : '' }}" placeholder="Region" value="{{ old('region') }}" required disabled>
                                                    @include('alerts.feedback', ['field' => 'region'])
                                                  </div>
                                            </div>


                                            <div class="form-row">
                                                <div class="form-group{{ $errors->has('client_id') ? ' has-danger' : '' }} col-md-2">
                                                    <label class="form-control-label" for="input-nationality">Nationality</label>
                                                    <select name="nationality" id="input-nationality" class="form-select form-control-alternative{{ $errors->has('client') ? ' is-invalid' : '' }}">
                                                        <option value="">Not Specified</option>
                                                        @foreach (nationalities('') as $key =>$nationality)
                                                            @if($key == old('key'))
                                                                <option value="{{$key}}" selected>{{$nationality}}</option>
                                                            @else
                                                                <option value="{{$key}}">{{$nationality}}</option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                    @include('alerts.feedback', ['field' => 'key'])
                                                </div>

                                                <div class="form-group{{ $errors->has('religion') ? ' has-danger' : '' }} col-md-2">
                                                <label class="form-control-label" for="input-religion">Religion</label>
                                                <select name="relcode" id="input-religion" class="form-select form-control-alternative{{ $errors->has('relcode') ? ' is-invalid' : '' }}">
                                                    <option value="NULL" selected disabled>Not Specified</option>
                                                    @foreach ($religions as $key =>$religion)
                                                        @if($key == old('key'))
                                                            <option value="{{$religion->relcode}}" selected>{{$religion->reldesc}}</option>
                                                        @else
                                                            <option value="{{$religion->relcode}}">{{$religion->reldesc}}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                                @include('alerts.feedback', ['field' => 'relcode'])
                                            </div>
                                            <div class="form-group col-md-3">
                                                <label for="inputOccupation">Employment Status</label>
                                                <select id="inputOccupation" name="occupation"class="form-control">
                                                  <option selected>Choose...</option>
                                                  <option value="EMPLO">Employed</option>
                                                  <option value="UNEMP">Unemployed</option>
                                                  <option value="SELFE">Self-employed</option>
                                                </select>
                                              </div>
                                                  <div class="form-group col-md-3">
                                                    <label for="inputGender">Occupation</label>
                                                    <select id="inputPatsex" name="patsex"class="form-control" value="{{ old('patsex') }}">
                                                      <option disabled>Select Gender</option>
                                                      <option value="M" selected>Male</option>
                                                      <option value="F">Female</option>
                                                    </select>
                                                  </div>
                                                </div>

                                    <div class="form-row">
                                        <div class="form-group{{ $errors->has('fatlast') ? ' has-danger' : '' }} col-md-3">
                                            <label class="form-control-label" for="input-fatlast">Father's First Name</label>
                                            <input type="text" name="fatlast" id="input-fatlast" class="form-control form-control-alternative{{ $errors->has('fatlast') ? ' is-invalid' : '' }}" placeholder="Father Lastname" value="{{ old('fatlast') }}" autofocus>
                                            @include('alerts.feedback', ['field' => 'fatlast'])
                                          </div>

                                            <div class="form-group{{ $errors->has('fatmiddle') ? ' has-danger' : '' }} col-md-3">
                                            <label class="form-control-label" for="input-fatmiddle">Father's Middlename</label>
                                            <input type="text" name="fatmiddle" id="input-fatmiddle" class="form-control form-control-alternative{{ $errors->has('fatmiddle') ? ' is-invalid' : '' }}" placeholder="Father Middlename" value="{{ old('fatmiddle') }}" autofocus>
                                            @include('alerts.feedback', ['field' => 'fatmiddle'])
                                            </div>
                                            <div class="form-group{{ $errors->has('fatlastname') ? ' has-danger' : '' }} col-md-3">
                                                <label class="form-control-label" for="input-fatlastname">Father's Lastname</label>
                                                <input type="text" name="fatlastname" id="input-fatlastname" class="form-control form-control-alternative{{ $errors->has('fatlastname') ? ' is-invalid' : '' }}" placeholder="Father Lastname" value="{{ old('fatlastname') }}" autofocus>
                                                @include('alerts.feedback', ['field' => 'fatlastname'])
                                                </div>
                                                <div class="form-group col-md-1">
                                                    <label for="inputSuffix">Suffix</label>
                                                    <select id="inputSuffix" class="form-control">
                                                        @foreach(Suffix() as $key => $suffix)
                                                        <option value="{{$key}}">{{$suffix}}</option>
                                                         @endforeach
                                                      {{-- <option value="NULL" selected>N/A</option>
                                                      <option value="SR">Sr.</option>
                                                      <option value="JR">Jr.</option>
                                                      <option value="II">II</option>
                                                      <option value="III">III</option>
                                                      <option value="IV">IV</option>
                                                      <option value="V">V</option>
                                                      <option value="VI">VI</option> --}}

                                                    </select>
                                                  </div>
                                                  <div class="form-group{{ $errors->has('fatdeceased') ? ' has-danger' : '' }} col-md-1">
                                                    <label class="form-control-label" for="input-fatdeceased">Deceased?</label>
                                                    <input type="checkbox" name="fatdeceased" id="input-fatdeceased" class="form-control form-check-alternative{{ $errors->has('fatdeceased') ? ' is-invalid' : '' }}" value="{{ old('fatdeceased') }}" autofocus>
                                                    @include('alerts.feedback', ['field' => 'fatdeceased'])
                                                  </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group{{ $errors->has('fataddr') ? ' has-danger' : '' }} col-md-4">
                                            <label class="form-control-label" for="input-fataddr">Father's Address</label>
                                            <input type="text" name="fataddr" id="input-fataddr" class="form-control form-control-alternative{{ $errors->has('fataddr') ? ' is-invalid' : '' }}" placeholder="Father Address" value="{{ old('fataddr') }}" autofocus>
                                            @include('alerts.feedback', ['field' => 'fataddr'])
                                        </div>
                                        <div class="form-group{{ $errors->has('fatcontact') ? ' has-danger' : '' }} col-md-2">
                                            <label class="form-control-label" for="input-fatcontact">Father's Address</label>
                                            <input type="text" name="fatcontact" id="input-fatcontact" class="form-control form-control-alternative{{ $errors->has('fatcontact') ? ' is-invalid' : '' }}" placeholder="Contact No." value="{{ old('fatcontact') }}" autofocus>
                                            @include('alerts.feedback', ['field' => 'fatcontact'])
                                        </div>
                                        <div class="form-group{{ $errors->has('fatcontact') ? ' has-danger' : '' }} col-md-2">
                                            <label class="form-control-label" for="input-fatcontact">Deceased?</label>
                                                <input class="form-check-input" type="checkbox" value="">

                                                <span class="form-check-sign">
                                                  <span class="check"></span>
                                                </span>
                                            </label>
                                          </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group{{ $errors->has('motfirst') ? ' has-danger' : '' }} col-md-3">
                                            <label class="form-control-label" for="input-motfirst">Mother's First Name</label>
                                            <input type="text" name="motfirst" id="input-motfirst" class="form-control form-control-alternative{{ $errors->has('motfirst') ? ' is-invalid' : '' }}" placeholder="Father Firstname" value="{{ old('motfirst') }}" autofocus>
                                            @include('alerts.feedback', ['field' => 'motfirst'])
                                          </div>

                                            <div class="form-group{{ $errors->has('motmiddle') ? ' has-danger' : '' }} col-md-3">
                                            <label class="form-control-label" for="input-motmiddle">Mother's Middlename</label>
                                            <input type="text" name="motmiddle" id="input-motmiddle" class="form-control form-control-alternative{{ $errors->has('motmiddle') ? ' is-invalid' : '' }}" placeholder="Mother Middlename" value="{{ old('motmiddle') }}" autofocus>
                                            @include('alerts.feedback', ['field' => 'motmiddle'])
                                            </div>
                                            <div class="form-group{{ $errors->has('motlast') ? ' has-danger' : '' }} col-md-3">
                                                <label class="form-control-label" for="input-motlast">Mother's Lastname</label>
                                                <input type="text" name="motlast" id="input-motlast" class="form-control form-control-alternative{{ $errors->has('motlast') ? ' is-invalid' : '' }}" placeholder="Mother Lastname" value="{{ old('motlast') }}" autofocus>
                                                @include('alerts.feedback', ['field' => 'motlast'])
                                                </div>
                                                <div class="form-group{{ $errors->has('fatdeceased') ? ' has-danger' : '' }} col-md-1">
                                                    <label class="form-control-label" for="input-fatdeceased">Deceased?</label>
                                                    <input type="checkbox" name="fatdeceased" id="input-fatdeceased" class="form-control form-check-alternative{{ $errors->has('fatdeceased') ? ' is-invalid' : '' }}" value="{{ old('fatdeceased') }}" autofocus>
                                                    @include('alerts.feedback', ['field' => 'fatdeceased'])
                                                  </div>
                                    </div>




                                    <div class="form-group">
                                      <div class="form-check">
                                        <label class="form-check-label">
                                            <input class="form-check-input" type="checkbox" value="">
                                            Check me out
                                            <span class="form-check-sign">
                                              <span class="check"></span>
                                            </span>
                                        </label>
                                      </div>
                                    </div>
                                    <button type="submit" class="btn btn-primary mt-4">Save Patient</button>
                                  </form>







                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
         //get province and Region by citycode
        $('#input-city').on('change',function(e){
            console.log(e.target);
            var citycode = e.target.value;
        
                $.ajax({
                    url : url,
                    type : 'GET',
                    data:{'query':citycode},
                    datatype : 'json',
                    success:function(data){
                        var len = data.length;
                        $("#input-barangay").empty();
                        for( var i = 0; i<len; i++){
                            var id = data[i]['bgycode'];
                            var name = data[i]['bgyname'];
                            $("#input-barangay").append("<option value='"+id+"'>"+name+"</option>");
                        }//end for
                    }//end sucess
                });//end ajax
            })


        // new SlimSelect({
        //     select: '.form-select'
        // })

        new SlimSelect({
        select: '#input-religion'
        })
        new SlimSelect({
        select: '#input-nationality'
        })

        new SlimSelect({
            select: '#input-city'
        })
        new SlimSelect({
            select: '#input-barangay'
        })
    </script>
@endpush
