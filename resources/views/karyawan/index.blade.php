@extends('layouts.dashboard')
@section('isi')
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                    <a href="{{ url('/pegawai/tambah-pegawai') }}" class="btn btn-sm btn-primary">+ Tambah Data Pegawai</a>
                <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#import">
                    <i class="fa fa-table mr-2"></i>Import Data
                </button>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <table id="tableprint" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Nama</th>
                            <th>Username</th>
                            <th>Jabatan</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data_user as $du)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $du->name }}</td>
                                <td>{{ $du->username }}</td>
                                <td>{{ $du->Jabatan->nama_jabatan }}</td>
                                <td>
                                    <a href="{{ url('/pegawai/detail/'.$du->id) }}" class="btn btn-sm btn-info" title="Detail Pegawai"><i class="fa fa-solid fa-eye"></i></a>
                                    <a href="{{ url('/pegawai/edit-password/'.$du->id) }}" class="btn btn-sm btn-warning" title="Edit Password"><i class="fa fa-solid fa-key"></i></a>
                                    <form action="{{ url('/pegawai/delete/'.$du->id) }}" method="post" class="d-inline">
                                        @method('delete')
                                        @csrf
                                        <button class="btn btn-danger btn-sm btn-circle" title="Delete Pegawai" onClick="return confirm('Are You Sure')"><i class="fa fa-solid fa-trash"></i></button>
                                    </form>
                                    <a href="{{ url('/pegawai/shift/'.$du->id) }}" class="btn btn-sm btn-success" title="Mapping Shift Pegawai"><i class="fa fa-solid fa-clock"></i></a>
                                    <a href="{{ url('/pegawai/dinas-luar/'.$du->id) }}" class="btn btn-sm btn-primary" title="Mapping Dinas Luar Pegawai"><i class="fa fa-solid fa-route"></i></a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                  </table>
            </div>
            <!-- /.card-body -->
          </div>
    </div>
    <br>

    <div class="modal fade" id="import" tabindex="-1" role="dialog" aria-labelledby="importLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="importLabel">Import Data Karyawan</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form action="{{ url('/pegawai/import') }}" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    @csrf
                    <div class="form-group">
                        <label for="file_excel">File Excel</label>
                        <input type="file" name="file_excel" id="file_excel" class="form-control @error('file_excel') is-invalid @enderror">
                        @error('file_excel')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                  <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
          </div>
        </div>
    </div>
@endsection




