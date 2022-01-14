@extends('template.default')

@section('content')

    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">Data Transactions</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3 mt-1">
                        <a href="{{url('dashboard/campaign/create')}}" class="btn btn-sm btn-primary">
                            <i class="fas fa-plus"></i>
                            Tambah Data
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
                    <form action="{{url('/dashboard/transactions?search=')}}" method="GET">
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <input type="text" class="form-control" placeholder="Cari transaksi.."
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
                                        <th scope="col">Users</th>
                                        <th scope="col">Total</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Waktu</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php($i = 1)
                                    @foreach ($transactions as $item)
                                        <tr>
                                            <th scope="row"> {{ $i }} </th>
                                            <td> {{ $item->campaign_name }} </td>
                                            <td> {{ $item->users_name }} </td>
                                            <td> Rp. {{ number_format($item->amount) }} </td>
                                            <td>
                                                @if($item->status == 'paid')
                                                <span class="badge badge-success">Paid</span>
                                                @endif

                                                @if($item->status == 'unpaid')
                                                <span class="badge badge-danger">Unpaid</span>
                                                @endif
                                            </td>
                                            <td>
                                                {{$item->created_at}}
                                            </td>
                                            <td>
                                                @if($item->status == 'unpaid')
                                                <a href="{{url('/dashboard/transactions/approve/' . $item->id)}}" class="badge badge-success">Approve</a>
                                                @endif
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
