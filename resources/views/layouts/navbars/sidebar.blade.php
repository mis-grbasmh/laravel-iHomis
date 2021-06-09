
<div class="sidebar">

    <div class="sidebar-wrapper">
        <div class="logo">
            <a href="#" class="simple-text logo-mini">GBA</a>
            <a href="#" class="simple-text logo-normal">iHOMIS WEB</a>
        </div>
        <ul class="nav">
            <li @if ($pageSlug == 'dashboard') class="active " @endif>
                <a href="{{ route('home') }}">
                    <i class="tim-icons icon-chart-bar-32"></i>
                    <p>Dashboard</p>

                </a>
            </li>
            @if(in_array(auth()->user()->roles->first()->name, ['Admin', 'Medical Records']))
            <li>
                <a data-toggle="collapse" href="#admitting" {{ $section == 'admitting' ? 'aria-expanded=true' : '' }}>
                    <i class="tim-icons icon-bank" ></i>
                    <span class="nav-link-text">Admitting</span>
                    <b class="caret mt-1"></b>
                </a>

                <div class="collapse {{ $section == 'inpatients' ? 'show' : '' }}" id="admitting">
                    <ul class="nav pl-4">
                         <li @if ($pageSlug == 'inpatients') class="active " @endif>
                            {{-- <a href="{{ route('transactions.type', ['type' => 'payment'])  }}"> --}}
                                <a href="/admitting/inpatients">
                                <i class="tim-icons icon-laptop"></i>
                                <p>In-patients</p>
                            </a>
                        </li>
                        <li @if ($pageSlug == 'dailyadmissions') class="active " @endif>
                            {{-- <a href="{{ route('transactions.type', ['type' => 'payment'])  }}"> --}}
                                <a href="/admitting/dailyadmissions">
                                <i class="tim-icons icon-bullet-list-67"></i>
                                <p>Daily Admission</p>
                            </a>
                        </li>



                       {{-- @if(in_array(auth()->user()->roles->first()->name, ['Admin','Nurse'])) --}}
                       <li @if ($pageSlug == 'discharges') class="active " @endif>
                            <a href="{{ route('patient.dailydischarges')  }}">

                                <i class="tim-icons icon-user-run"></i>
                                <p>Daily Discharges</p>
                            </a>
                        </li>
                        {{-- @endif --}}
                        <li @if ($pageSlug == 'patientcharges') class="active " @endif>
                            <a href="{{ route('wards.index','')  }}">
                                <i class="tim-icons icon-money-coins"></i>
                                <p>Patient Charges</p>
                            </a>
                        </li>
                        <li @if ($pageSlug == 'ertransferredfrom') class="active " @endif>
                            <a href="{{ route('admisson.referralsfrom')  }}">
                                <i class="tim-icons icon-sound-wave" ></i>
                                <p>Referral To</p>
                            </a>
                        </li>
                        {{-- <li @if ($pageSlug == 'tstats') class="active " @endif>
                            <a href="{{ route('transactions.stats')  }}">
                                <i class="tim-icons icon-chart-pie-36"></i>
                                <p>Statistics</p>
                            </a>
                        </li> --}}
                        {{-- <li @if ($pageSlug == 'transactions') class="active " @endif>
                            <a href="{{ route('transactions.index')  }}">
                                <i class="tim-icons icon-bullet-list-67"></i>
                                <p>All</p>
                            </a>
                        </li> --}}
                        {{-- <li @if ($pageSlug == 'sales') class="active " @endif>
                            <a href="{{ route('sales.index')  }}">
                                <i class="tim-icons icon-bag-16"></i>
                                <p>Sales</p>
                            </a>
                        </li>
                        <li @if ($pageSlug == 'expenses') class="active " @endif>
                            <a href="{{ route('transactions.type', ['type' => 'expense'])  }}">
                                <i class="tim-icons icon-coins"></i>
                                <p>Billing</p>
                            </a>
                        </li>
                        <li @if ($pageSlug == 'incomes') class="active " @endif>
                            <a href="{{ route('transactions.type', ['type' => 'income'])  }}">
                                <i class="tim-icons icon-credit-card"></i>
                                <p>Income</p>
                            </a>
                        </li>
                        <li @if ($pageSlug == 'transfers') class="active " @endif>
                            <a href="{{ route('transfer.index')  }}">
                                <i class="tim-icons icon-send"></i>
                                <p>Transfers</p>
                            </a>
                        </li> --}}

                    </ul>
                </div>
            </li>
            @endif
            @if(in_array(auth()->user()->roles->first()->name, ['Admin', 'Billing']))
            <li>
                <a data-toggle="collapse" href="#billing" {{ $section == 'billing' ? 'aria-expanded=true' : '' }}>
                    <i class="tim-icons icon-money-coins"></i>
                    <span class="nav-link-text">Billing</span>
                    <b class="caret mt-1"></b>
                </a>

                <div class="collapse {{ $section == 'billing' ? 'show' : '' }}" id="billing">
                    <ul class="nav pl-4">
                       <li @if ($pageSlug == 'soa') class="active " @endif>
                            <a href="{{ route('billing.soa','')  }}">
                                <i class="tim-icons icon-user-run"></i>
                                <p>SOA</p>
                            </a>
                        </li>

                        {{-- <li @if ($pageSlug == 'fordischarge') class="active " @endif>
                            <a href="{{ route('billing.index','')  }}">
                                <i class="tim-icons icon-user-run"></i>
                                <p>For Discharge Patients</p>
                            </a>
                        </li> --}}
                        {{-- <li @if ($pageSlug == 'billingstatement') class="active " @endif>
                            <a href="{{ route('billing.statement')  }}">
                                <i class="tim-icons icon-user-run"></i>
                                <p>Billing Statement</p>
                            </a></li>
                        <li @if ($pageSlug == 'summaryreport') class="active " @endif>
                            <a href="{{ route('cashiering.rptsummary')  }}">
                                <i class="tim-icons icon-user-run"></i>
                                <p>Summary Report</p>
                            </a></li> --}}
                    </ul>
                </div>
            </li>
            @endif
            @if(in_array(auth()->user()->roles->first()->name, ['Doctor']))
            <li class="">
                <a data-toggle="collapse" href="#doctor" {{ $section == 'doctor' ? 'aria-expanded=true' : '' }}>
                    <i class="tim-icons icon-single-02"></i>
                    <span class="nav-link-text">Doctor</span>
                    <b class="caret mt-1"></b>
                </a>
                <div class="collapse {{ $section == 'doctor' ? 'show' : '' }}" id="doctor">
                    <ul class="nav pl-4">
                        <li @if ($pageSlug == 'wards') class="active " @endif>
                            <a href="/doctors/patientlist">
                                <i class="tim-icons icon-bullet-list-67"></i>
                                <p>Patient List</p>
                            </a>
                        </li>

                        <li @if ($pageSlug == 'erdeaths') class="active " @endif>
                            <a href="{{ route('erdeaths')  }}">
                                <i class="tim-icons icon-image-02" ></i>
                                <p>Examintion Results</p>
                            </a>
                        </li>
                    </ul>
                </div>

                 {{-- <ul class="ml-menu">
                 <li class=""><a
                             href="/doctors/patientlist">Patient List
                             </a></li>
                 <li class=""><a
                             href="/patient/cf4">
                             Patient CF4</a></li>
                     <li class=""><a href="/patients/cf4/status"> Patient CF4 Status</a></li>
                     <li class=""><a
                             href="/patient/clinicalabstract">Clinical Abstract</a></li>
                     <li class=""><a
                             href="">Progress Notes</a></li>
                     <li class=""><a
                             href=""></a></li>
                 </ul>  --}}
             </li>
             @endif
             @if(in_array(auth()->user()->roles->first()->name, ['Admin', 'Dietetics']))
             <li>
                 <a data-toggle="collapse" href="#dietetics" {{ $section == 'phic' ? 'aria-expanded=true' : '' }}>
                     <i class="tim-icons icon-headphones"></i>
                     <span class="nav-link-text">Dietetics</span>
                     <b class="caret mt-1"></b>
                 </a>

                 <div class="collapse {{ $section == 'dietetics' ? 'show' : '' }}" id="dietetics">
                     <ul class="nav pl-4">
                         <li @if ($pageSlug == 'dietlist') class="active " @endif>
                            <a href="{{ route('dietetics.index')  }}">
                                 <i class="tim-icons icon-single-copy-04" ></i>
                                 <p>Diet List</p>
                             </a>
                         </li>
                     </ul>
                 </div>
             </li>
             @endif
             @if(in_array(auth()->user()->roles->first()->name, ['Admin', 'Medical Records']))
             <li>
                <a data-toggle="collapse" href="#emergency" {{ $section == 'emergency' ? 'aria-expanded=true' : '' }}>
                    <i class="tim-icons icon-bus-front-12"></i>
                    <span class="nav-link-text">Emergency</span>
                    <b class="caret mt-1"></b>
                </a>


                <div class="collapse {{ $section == 'emergency' ? 'show' : '' }}" id="emergency">
                    <ul class="nav pl-4">
                        <li @if ($pageSlug == 'emergencyroom') class="active " @endif>
                            <a href="{{ route('emergencyroom.index','')  }}">
                                <i class="tim-icons icon-user-run"></i>
                                <p>ER Patients</p>
                            </a>
                        </li>
                        <li @if ($pageSlug == 'animalbites') class="active " @endif>
                            <a href="{{ route('animalbites')  }}">
                                <i class="tim-icons icon-sound-wave" ></i>
                                <p>Animal Bites</p>
                            </a>
                        </li>


                        <li @if ($pageSlug == 'erdeaths') class="active " @endif>
                            <a href="{{ route('erdeaths')  }}">
                                <i class="tim-icons icon-sound-wave" ></i>
                                <p>ER Death</p>
                            </a>
                        </li>



                    </ul>
                </div>
            </li>
            @endif
            @if(in_array(auth()->user()->roles->first()->name, ['Admin', 'Laboratory']))
            <li>
                <a data-toggle="collapse" href="#laboratory" {{ $section == 'laboratory' ? 'aria-expanded=true' : '' }}>
                    <i class="tim-icons icon-atom"></i>
                    <span class="nav-link-text">Laboratory</span>
                    <b class="caret mt-1"></b>
                </a>

                <div class="collapse {{ $section == 'laboratory' ? 'show' : '' }}" id="laboratory">
                    <ul class="nav pl-4">
                        <li @if ($pageSlug == 'cf4') class="active " @endif>
                            <a href="{{ route('phic.cf4')  }}">
                                <i class="tim-icons is-spinning icon-bullet-list-67" ></i>
                                <p>Laboratory Orders</p>
                            </a>
                        </li>



                    </ul>
                </div>
            </li>
            @endif
            {{-- , 'Medical Records' --}}
            @if(in_array(auth()->user()->roles->first()->name, ['Admin']))
            <li>
                <a data-toggle="collapse" href="#statistical" {{ $section == 'medicalrecords' ? 'aria-expanded=true' : '' }}>
                    <i class="tim-icons icon-molecule-40"></i>
                    <span class="nav-link-text">Statistical Reports</span>
                    <b class="caret mt-1"></b>
                </a>

                <div class="collapse {{ $section == 'medrec' ? 'show' : '' }}" id="statistical">
                    <ul class="nav pl-4">
                        <li @if ($pageSlug == 'translogs') class="active " @endif>
                            <a href="{{ route('medicalrecords.index')  }}">
                                <i class="tim-icons icon-bullet-list-67" ></i>
                                <p>Treatment/Condition <br/>(Discharges)</p>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            @endif
            {{-- , 'Medical Records' --}}
            @if(in_array(auth()->user()->roles->first()->name, ['Admin']))
            <li>
                <a data-toggle="collapse" href="#medrec" {{ $section == 'medicalrecords' ? 'aria-expanded=true' : '' }}>
                    <i class="tim-icons icon-molecule-40"></i>
                    <span class="nav-link-text">Medical Records</span>
                    <b class="caret mt-1"></b>
                </a>

                <div class="collapse {{ $section == 'medrec' ? 'show' : '' }}" id="medrec">
                    <ul class="nav pl-4">
                        <li @if ($pageSlug == 'translogs') class="active " @endif>
                            <a href="{{ route('medicalrecords.index')  }}">
                                <i class="tim-icons icon-bullet-list-67" ></i>
                                <p>OutPatients</p>
                            </a>
                        </li>

                        <li @if ($pageSlug == 'translogs') class="active " @endif>
                            <a href="{{ route('medicalrecords.index')  }}">
                                <i class="tim-icons icon-bullet-list-67" ></i>
                                <p>Patient Record Mgt.</p>
                            </a>
                        </li>
                        <li @if ($pageSlug == 'translogs') class="active " @endif>
                            <a href="{{ route('medicalrecords.index')  }}">
                                <i class="tim-icons icon-bullet-list-67" ></i>
                                <p>Transaction Logs</p>
                            </a>
                        </li>
                        <li @if ($pageSlug == 'codediagnosis') class="active " @endif>
                            <a href="{{ route('medicalrecords.codediagnosis')  }}">
                                <i class="tim-icons icon-bullet-list-67" ></i>
                                <p>Code Diagnosis</p>
                            </a>
                        </li>


                    </ul>
                </div>
            </li>
            @endif
            @if(in_array(auth()->user()->roles->first()->name, ['Admin', 'Nursing']))
            <li>
                <a data-toggle="collapse" href="#nursing" {{ $section == 'nursing' ? 'aria-expanded=true' : '' }}>
                    <i class="tim-icons icon-molecule-40"></i>
                    <span class="nav-link-text">Nursing</span>
                    <b class="caret mt-1"></b>
                </a>

                <div class="collapse {{ $section == 'nursing' ? 'show' : '' }}" id="nursing">
                    <ul class="nav pl-4">
                        <li @if ($pageSlug == 'wards') class="active " @endif>
                            <a href="{{ route('wards.index','')  }}">
                                <i class="tim-icons icon-badge" ></i>
                                <p>Nursing Wards</p>
                            </a>
                        </li>
                        <li @if ($pageSlug == 'discharges') class="active " @endif>
                            <a href="{{ route('patient.dailydischarges')  }}">

                                <i class="tim-icons icon-user-run"></i>
                                <p>Daily Discharges</p>
                            </a>
                        </li>
                        {{-- <li @if ($pageSlug == 'wards') class="active " @endif>
                            <a href="{{ route('wards.index','')  }}">
                                <i class="tim-icons icon-badge" ></i>
                                <p>Emergency Room</p>
                            </a>
                        </li>
                        <li @if ($pageSlug == 'wards') class="active " @endif>
                            <a href="{{ route('wards.index','')  }}">
                                <i class="tim-icons icon-badge" ></i>
                                <p>Outpatients</p>
                            </a>
                        </li> --}}
                        <li @if ($pageSlug == 'patientcharges') class="active " @endif>
                            <a href="{{ route('patient.charges','')  }}">
                                <i class="tim-icons icon-paper" ></i>
                                <p>Patient Charges</p>
                            </a>
                        </li>


                    </ul>
                </div>
            </li>
            @endif

            @if(in_array(auth()->user()->roles->first()->name, ['Admin', 'Philhealth']))
            <li>
                <a data-toggle="collapse" href="#phic" {{ $section == 'phic' ? 'aria-expanded=true' : '' }}>
                    <i class="tim-icons icon-wallet-43"></i>
                    <span class="nav-link-text">Philhealth</span>
                    <b class="caret mt-1"></b>
                </a>

                <div class="collapse {{ $section == 'phic' ? 'show' : '' }}" id="phic">
                    <ul class="nav pl-4">
                        <li @if ($pageSlug == 'cf4') class="active " @endif>
                            <a href="{{ route('phic.cf4')  }}">
                                <i class="tim-icons icon-single-copy-04" ></i>
                                <p>CF4</p>
                            </a>
                        </li>

                        <li @if ($pageSlug == 'norasys') class="active " @endif>
                            <a href="{{ route('phic.norasys')  }}">
                                <i class="tim-icons icon-bullet-list-67" ></i>
                                <p>NORASYS</p>
                            </a>
                        </li>

                        <li @if ($pageSlug == 'mmhr') class="active " @endif>
                            <a href="{{ route('phic.mmhr','')  }}">
                                <i class="tim-icons icon-bullet-list-67" ></i>
                                <p>MMH Report</p>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            @if(in_array(auth()->user()->roles->first()->name, ['Admin', 'Radiology']))
            <li>
                <a data-toggle="collapse" href="#radiology" {{ $section == 'phic' ? 'aria-expanded=true' : '' }}>
                    <i class="tim-icons icon-headphones"></i>
                    <span class="nav-link-text">Radiology/CT</span>
                    <b class="caret mt-1"></b>
                </a>

                <div class="collapse {{ $section == 'radiology' ? 'show' : '' }}" id="radiology">
                    <ul class="nav pl-4">
                        <li @if ($pageSlug == 'cf4') class="active " @endif>
                            <a href="{{ route('phic.cf4')  }}">
                                <i class="tim-icons icon-single-copy-04" ></i>
                                <p>Radiology Orders</p>
                            </a>
                        </li>



                    </ul>
                </div>
            </li>
            @endif
            {{-- <li @if ($pageSlug == 'clients') class="active " @endif>
                <a href="{{ route('clients.index') }}">
                    <i class="tim-icons icon-single-02"></i>
                    <p>Clients</p>
                </a>
            </li>  --}}

           {{-- <li @if ($pageSlug == 'providers') class="active " @endif>
                <a href="{{ route('providers.index') }}">
                    <i class="tim-icons icon-delivery-fast"></i>
                    <p>Providers</p>
                </a>
            </li> --}}

             {{-- <li @if ($pageSlug == 'methods') class="active " @endif>
                <a href="{{ route('methods.index') }}">
                    <i class="tim-icons icon-wallet-43"></i>
                    <p>Methods and Accounts</p>
                </a>
            </li> --}}


            {{-- <li>
                <a data-toggle="collapse" href="#clients">
                    <i class="tim-icons icon-single-02" ></i>
                    <span class="nav-link-text">Clients</span>
                    <b class="caret mt-1"></b>
                </a>

                <div class="collapse" id="clients">
                    <ul class="nav pl-4">
                        <li @if ($pageSlug == 'clients-list') class="active " @endif>
                            <a href="{{ route('clients.index')  }}">
                                <i class="tim-icons icon-notes"></i>
                                <p>Administrar Clients</p>
                            </a>
                        </li>
                        <li @if ($pageSlug == 'clients-create') class="active " @endif>
                            <a href="{{ route('clients.create')  }}">
                                <i class="tim-icons icon-simple-add"></i>
                                <p>New Client</p>
                            </a>
                        </li>
                    </ul>
                </div>--}}
            </li>
            @endif
            {{-- @if(in_array(auth()->user()->roles->first()->name, ['Admin']))
            <li>
                <a data-toggle="collapse" href="#reference">
                    <i class="tim-icons icon-settings" ></i>
                    <span class="nav-link-text">Reference</span>
                    <b class="caret mt-1"></b>
                </a>
                <div class="collapse" id="reference">
                    <ul class="nav pl-4">
                        <li @if ($pageSlug == 'clients-list') class="active " @endif>
                            <a href="{{ route('clients.index')  }}">
                                <i class="tim-icons icon-notes"></i>
                                <p>Departments</p>
                            </a>
                        </li>
                        <li @if ($pageSlug == 'clients-create') class="active " @endif>
                            <a href="{{ route('clients.create')  }}">
                                <i class="tim-icons icon-simple-add"></i>
                                <p>New Client</p>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            @endif --}}

        </ul>
    </div>
</div>
