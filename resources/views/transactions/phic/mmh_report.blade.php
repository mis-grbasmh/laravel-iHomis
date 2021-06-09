<script src="{{ asset('assets') }}/js/core/jquery.min.js"></script>
@extends('layouts.app', ['page' => 'Transactions', 'pageSlug' => 'philhealth', 'section' => 'mmhreport'])
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h6 class="title d-inline">Mandatory Hospital Report:<strong> </strong>  </h6>
                    <p class="card-category d-inline">for the month of Total </p>
                    <div class="float-right col-md-4">
                        <div class="col-lg-12">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-row">
                                        <div class="form-group col-md-5">
                                            <label><strong>Select Month</strong></label>
                                            <select class="form-control" id="month" name="month" onchange="ShowHideDivDisposition()" required>
                                                @foreach ($months as $key => $month)
                                                    <option value="{{$key}}"><strong>{{ $month }}</strong> </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="inputState">Year</label>
                                            <select class="form-control" id="year" name="year" required>
                                                <option value="2021"><strong>2021</strong> </option>
                                                <option value="2020"><strong>2020</strong> </option>
                                                <option value="2019"><strong>2019</strong> </option>
                                                <option value="2018"><strong>2018</strong> </option>
                                            </select>
                                        </div>
                                    </div>

                                        <div class="form-group col-md-1">
                                            <label for="inputZip">&nbsp;</label>
                                            <a class="btn btn-primary btnGenerate" onclick="handler(event);" data-toggle="tooltip" title="Click to generate mmh report " data-placement="bottom" data-mmhr="/phic/mmhr">Generate</a>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- <div class="col-sm-12">
                            <div class="form-group">
                                <label><strong>Select Date Discharge Date:</strong></label>
                                <input type="date" id="date2" name="disdate" onchange="handler(event);" value="<?php echo date('Y-m-d'); ?>" class="form-control floating-label" step="any" required>
                            </div>
                        </div> --}}
                    </div>
                </div>
                <hr/>


                <table class="table" >
                    <thead>
                    <tr>
                        <th></th>
                        <th>NHIP</th>
                        <th>NON NHIP</th>
                        <th>TOTAL</th>
                        <th></th>
                        <th></th>
                        <th></th>
                    </tr>
                    </thead>
                <tbody id="mmh">

                </tbody>

                </table>


{{--                --}}{{----}}

{{--                <tr style="height: 27px;">--}}
{{--                <td style="width: 11.0698%; height: 27px; text-align: center;">DATE</td>--}}
{{--                <td style="width: 33.4356%; height: 27px; text-align: center;" colspan="3">CENSUS</td>--}}
{{--                <td style="width: 11.1441%; height: 27px; text-align: center;">DATE</td>--}}
{{--                <td style="width: 33.2094%; height: 27px; text-align: center;" colspan="3">DISCHARGES</td>--}}
{{--                </tr>--}}
{{--                <tr style="height: 27px;">--}}
{{--                <td style="width: 11.0698%; height: 27px; text-align: center;"></td>--}}
{{--                <td style="width: 11.0698%; height: 27px; text-align: center;">a. NHIP</td>--}}
{{--                <td style="width: 11.0698%; height: 27px; text-align: center;">b. Non-NHIP</td>--}}
{{--                <td style="width: 11.296%; height: 27px; text-align: center;">c. Total</td>--}}
{{--                <td style="width: 11.1441%; height: 27px; text-align: center;"></td>--}}
{{--                <td style="width: 11.0698%; height: 27px; text-align: center;">a. NHIP</td>--}}
{{--                <td style="width: 11.0698%; height: 27px; text-align: center;">b. Non-NHIP</td>--}}
{{--                <td style="width: 11.0698%; height: 27px; text-align: center;">c. Total</td>--}}
{{--                </tr>--}}
{{--                <tr >--}}
{{--                </tr> --}}
{{--                --}}{{-- <tbody>--}}
{{--                </tbody> --}}


{{--                --}}{{-- </table> --}}


                @endsection



                <script type="text/javascript">
                    $(document).ready(function() {
                        $('#daily_discharges').DataTable( {
                            dom: 'Bfrtip',
                            buttons: [
                                'copy', 'csv', 'excel', 'pdf', 'print'
                            ]
                        } );
                    } );
                </script>
                <script>
                    function handler(e){
                        var  month = $('#month').val();
                        var  year = $('#year').val();
                        var url = '{{ route("phic.mmhr") }}';
                        // url = url.replace(':month', month);
                        alert(url);
                        $.ajax({
                            url:url,
                            method:'GET',
                            data:{'month':month, 'year':year},
                            dataType:'json',
                            success:function(data)
                            {
                                alert(success);
                                console.log(data);
                                $('#mmh').html(data.table_data);
                                // $('#history_table').html(data.table_data);
                                // $("#history").modal({
                                //     backdrop: 'static',
                                //     show: true,
                                // });
                                // $('#total_history').text(data.total_data);
                            }
                        });




                    }
                </script>



