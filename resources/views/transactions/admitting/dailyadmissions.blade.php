<script src="{{ asset('assets') }}/js/core/jquery.min.js"></script>
@extends('layouts.app', ['page' => 'Daily Admissions', 'pageSlug' => 'admissions', 'section' => 'admitting'])
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h6 class="title d-inline"><strong> {{$admissions->count()}} Total Admission</strong></h6>
                    <p class="card-category d-inline">as of {{$date}}</p>
                            <div class="float-right col-md-2">
                                    <div class="form-group">Select Admission Date
                                        <input type="date" id="date2" name="disdate" onchange="handler(event);" value="{{ old('date', $date) }}" class="form-control floating-label" step="any" required>
                                    </div>
                        </div>
                </div>
                <div class="card-body">
                    @include('alerts.success')

                    <div class="table-responsive">  
                        <table class="table tablesorter " id="">
                            <thead class=" text-primary">
                                <th>#</th>
                                        <th scope="col">Patient Name</th>
                                        <th scope="col">Gender</th>
                                        <th scope="col">Age</th>
                                        <th scope="col">Admission Date</th>
                                        <th scope="col">Physician</th>
                                        <th scope="col">Admitting <br/> Clerk</th>
                                        <th scope="col"></th>
                            </thead>
                            <tbody>
                                @foreach ($grouped as $row)
                                <tr>
                                  <td colspan ="8"><strong>{{ $row[0]->tsdesc }} </strong></td>
                                </tr>
                                @php $i = 0; @endphp   
                                   @foreach ($admissions as $key => $admission)
                                              
                                    @if($admission->tsdesc == $row[0]->tsdesc )   
                                    @php $i=$i+ 1; @endphp     
<tr>
                              
                                    <td>{{ $i }}</td>
                                        <td>{{getpatientinfo($admission->hpercode)}}</td>
                                        <td>{{$admission->patsex}}</td> 
                                        <td>{{number_format($admission->patage)}}</td>
                                        <td>{{$admission->admdate}}</td>
                                        <td>{{Getdoctorinfo($admission->licno)}}
                                        <td>{{getemployeeinfo($admission->entryby)}}</td>    
                                    </td>
                                    <td class="text-right">
                                        <div class="dropdown">
                                            <button type="button" class="btn btn-link dropdown-toggle btn-icon" data-toggle="dropdown" aria-expanded="false">
                                                <i class="tim-icons icon-settings-gear-63"></i>
                                            </button>
                                           

<div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
    <a class="dropdown-header">Select Action</a>
                                                        </div>
</div>
                                </td>
                                    </tr>
                                   
                                    @endif
                                   
                                    @endforeach
                                    <tr>
                                    <td colspan="2">Total: </td>
                                    <td >{{ $i}}</td>
                                    <td colspan="4"></td>
                                    </tr>
                                    @endforeach
                              
                                {{-- @foreach ($admissions as $transaction)
                                    <tr>
                                        <td> {{ date('d-m-y', strtotime($transaction->created_at)) }}</td>
                                        <td><a href="{{ route('providers.show', $transaction->provider) }}">{{ $transaction->provider->name }}</a></td>
                                        <td> {{ $transaction->title }}</td>
                                        <td><a href="{{ route('methods.show', $transaction->method) }}">{{ $transaction->method->name }}</a></td>
                                        <td>{{ format_money($transaction->amount) }}</td>
                                        <td>{{ $transaction->reference }}</td>
                                        <td></td>
                                        <td class="td-actions text-right">
                                            <a href="{{ route('transactions.edit', $transaction) }}" class="btn btn-link" data-toggle="tooltip" data-placement="bottom" title="Edit Payment">
                                                <i class="tim-icons icon-pencil"></i>
                                            </a>
                                            <form action="{{ route('transactions.destroy', $transaction) }}" method="post" class="d-inline">
                                                @csrf
                                                @method('delete')
                                                <button type="button" class="btn btn-link" data-toggle="tooltip" data-placement="bottom" title="Delete Payment" onclick="confirm('Are you sure you want to delete this payment? There will be no record left.') ? this.parentElement.submit() : ''">
                                                    <i class="tim-icons icon-simple-remove"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach --}}
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer py-4">
                    <nav class="d-flex justify-content-end" aria-label="...">
                        
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <script>
        function handler(e){
          var query = e.target.value;
          if(query){
                    var url = '{{ route("admitting.dailyadmissions", ":id") }}';
                    url = url.replace(':id', query);
                    document.location.href=url;      
                 }
                 else{
                     alert('Please ')
                 }
        }
        </script>
@endsection
