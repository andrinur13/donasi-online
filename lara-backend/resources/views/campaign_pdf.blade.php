<!DOCTYPE html>
<html>
<head>
	<title>Campaign</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>
<body>
	<style type="text/css">
		table tr td,
		table tr th{
			font-size: 9pt;
		}
	</style>

    <div class="row">
        <div class="col">
            <div class="text-center">
                Campaign Report
            </div>
        </div>
    </div>
 
	<table class='table table-bordered'>
		<thead>
			<tr>
				<th>No</th>
				<th>Campaign</th>
				<th>Total Fund</th>
				<th>Current Fund</th>
			</tr>
		</thead>
		<tbody>
			@php $i=1 @endphp
			@foreach($campaign as $p)
			<tr>
				<td>{{ $i++ }}</td>
				<td>{{$p->name}}</td>
				<td>Rp. {{ number_format($p->goal_amount) }}</td>
				<td>Rp. {{ number_format($p->current_amount) }}</td>
			</tr>
			@endforeach
		</tbody>
	</table>
 
</body>
</html>