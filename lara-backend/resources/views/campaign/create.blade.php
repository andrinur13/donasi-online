@extends('template.default')

@section('content')

    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">Data Campaign</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-8">
                            <form action="{{url('/dashboard/campaign/store')}}" method="POST" enctype="multipart/form-data">
                                {{ csrf_field() }}
                                <div class="form-group">
                                    <label for="name">Nama Campaign</label>
                                    <input type="text" class="form-control" id="name" name="name">
                                </div>
                                <hr>
                                <div class="form-group">
                                    <label for="short_description">Deskripsi Singkat</label>
                                    <input type="text" class="form-control" id="short_description" name="short_description">
                                </div>
                                <hr>
                                <div class="form-group">
                                    <label for="description">Deskripsi</label>
                                    <input type="text" class="form-control" id="description" name="description">
                                </div>
                                <hr>
                                <div class="form-group">
                                    <label for="goal_amount">Total Fund</label>
                                    <input type="number" class="form-control" id="goal_amount" name="goal_amount">
                                </div>

                                <div class="form-group">
                                    <label for="gambar">Gambar</label>
                                    <input type="file" class="form-control" id="gambar" name="gambar">
                                </div>

                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary">Tambah Campaign</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
