@extends('template.default')

@section('content')

    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">Data Campaign</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3 mt-1">
                        <a href="{{url('dashboard/campaign/create')}}" class="btn btn-sm btn-primary">
                            <i class="fas fa-plus"></i>
                            Tambah Data
                        </a>
                        <a href="{{url('dashboard/campaign/report')}}" class="btn btn-sm btn-primary">
                            <i class="fas fa-download"></i>
                            Report
                        </a>
                    </div>
                    <div>
                        @if(session('error'))
                        <div class="alert alert-danger">
                            {{session('error')}}
                        </div>
                        @endif

                        @if(session('success'))
                        <div class="alert alert-success">
                            {{session('success')}}
                        </div>
                        @endif
                    </div>
                    <hr>
                    <form action="{{url('/dashboard/campaign?search=')}}" method="GET">
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <input type="text" class="form-control" placeholder="Cari campaign.."
                                        name="search_campaign">
                                </div>
                            </div>
                            <div class="col-auto">
                                <button type="submit" class="btn btn-primary">Cari</button>
                            </div>
                        </div>
                    </form>
                    <div class="row">
                        <div class="col">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th scope="col">No</th>
                                        <th scope="col">Campaign</th>
                                        <th scope="col">Total Fund</th>
                                        <th scope="col">PIC</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php($i = 1)
                                    @foreach ($campaign as $item)
                                        <tr>
                                            <th scope="row"> {{ $i }} </th>
                                            <td> {{ $item->name }} </td>
                                            <td> Rp. {{ number_format($item->goal_amount) }} </td>
                                            <td> {{ $item->pic }} </td>
                                            <td>
                                                <a href="{{url('/dashboard/campaign/delete/' . $item->id)}}" class="badge badge-danger">Delete</a>
                                                <a href="{{url('/dashboard/campaign/edit/' . $item->id)}}" class="badge badge-warning">Update</a>
                                            </td>
                                        </tr>
                                        @php($i++)
                                    @endforeach

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
