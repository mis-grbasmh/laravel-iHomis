<html>
    <header>
    </header>

<body>

    <div>
    <a href="{{url('/patients/erpatient_pdf/pdf')}}">Convert to pdf</a>
    </div>

    <table>
       
<tr>
    <th>Health Rec. No.</th>
    <th>Full name</th>
    <th>Gender</th>
    <th>DOB</th>
    <th>NIC</th>
    <th>Address1</th>
    <th>Address2</th>
    <th>City</th>
    <th>phone</th>
    <th>Email</th>


</tr>
<tbody>
    @foreach ($erpatient_data as $patient)
    <tr>

<td>{{$patient->hpercode}}</td>
<td>{{$patient->patlast}}, {{$patient->patfirst}} {{$patient->patmiddle}}</td>
<td>{{$patient->patsex}}</td>
<td>{{ asDateTime($patient->erdate) }}</td>
<td>{{$patient->nic}}</td>
<td>{{$patient->address1}}</td>
<td>{{$patient->address2}}</td>
<td>{{$patient->city}}</td>
<td>{{$patient->phone}}</td>
<td>{{$patient->email}}</td>
    </tr>
    @endforeach

</tbody>

    </table>
</body>
</html>