@extends('layouts.dashboard')
@section('isi')
@php
use Carbon\Carbon;
@endphp
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <form action="{{ url('/data-dinas-luar') }}">
                <span>Filter Nama dan Rentang Tanggal</span><br><br>
                <div class="form-row">
                    <!-- <div class="col-3">
                        <select name="user_id" id="user_id" class="form-control selectpicker" data-live-search="true">
                            <option value="" selected>Pilih Pegawai</option>
                            @foreach($user as $u)
                            @if(request('user_id') == $u->id)
                            <option value="{{ $u->id }}" selected>{{ $u->name }}</option>
                            @else
                            <option value="{{ $u->id }}">{{ $u->name }}</option>
                            @endif
                            @endforeach
                        </select>
                    </div> -->
                    <div class="col-3">
                        <input type="datetime" class="form-control" name="mulai" placeholder="Tanggal Mulai" id="mulai" value="{{ request('mulai') }}">
                    </div>
                    <div class="col-3">
                        <input type="datetime" class="form-control" name="akhir" placeholder="Tanggal Akhir" id="akhir" value="{{ request('akhir') }}">
                    </div>
                    <div>
                        <button type="submit" id="search" class="form-control btn btn-primary"><i class="fas fa-search"></i></button>
                    </div>
                </div>
            </form>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            <table id="tableprintdinas" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>NRP</th>
                        <th>Nama Lengkap</th>
                        <th>Shift</th>
                        <th>Tanggal Masuk</th>
                        <th>Jam Masuk</th>
                        <!-- 
                            Tidak Terpakai
                            <th>Telat</th>
                            <th>Pulang Cepat</th>
                         -->
                        <th>Lokasi Masuk</th>
                        <th>District</th>
                        <th>Foto Masuk</th>
                        <th>Jam Pulang</th>
                        <th>Lokasi Pulang</th>
                        <th>Foto Pulang</th>
                        <th>Status Absen</th>
                        <th>Tanggal Pulang</th>
                        <th>Total Jam Kerja</th>
                        <th>Uraian Pekerjaan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data_absen as $da)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $da -> User -> username }}</td>
                        <td>{{ $da->User->name }}</td>
                        <td>{{ $da->Shift->nama_shift }} ({{ $da->Shift->jam_masuk }} - {{ $da->Shift->jam_keluar }})</td>
                        <td>{{ $da->tanggal }}</td>
                        <td>
                            @if($da->status_absen == 'Libur')
                            <span class="badge badge-info">Libur</span>
                            @elseif($da->status_absen == 'Cuti')
                            <span class="badge badge-warning">Sedang Cuti</span>
                            @elseif($da->jam_absen == null)
                            <span class="badge badge-danger">Belum Absen</span>
                            @else
                            @php

                            if ($da->full_jam_absen !== null) {
                            $carbonDate = Carbon::parse($da->full_jam_absen);

                            if ($carbonDate->offset === 25200) {
                            $formattedTime = $carbonDate->format('H:i') . ' WIB';
                            } elseif ($carbonDate->offset === 28800) {
                            $carbonDate->addHour();
                            $formattedTime = $carbonDate->format('H:i') . ' WITA';
                            } elseif ($carbonDate->offset === 32400) {
                            $carbonDate->addHours(2);
                            $formattedTime = $carbonDate->format('H:i') . ' WIT';
                            } else {
                            $formattedTime = $carbonDate->format('H:i');
                            }
                            } else {
                            $formattedTime = '';
                            }

                            @endphp
                            @if ($da->full_jam_absen)
                            {{ $formattedTime }}
                            @else
                            {{$da -> jam_absen}}
                            @endif
                            @endif
                        </td>
                        <!-- 
                            ini comment telat
                            <td>
                            @if($da->status_absen == 'Libur')
                            <span class="badge badge-info">Libur</span>
                            @elseif($da->status_absen == 'Cuti')
                            <span class="badge badge-warning">Sedang Cuti</span>
                            @elseif($da->status_absen == 'Izin Telat')
                            <span class="badge badge-warning">Izin Telat</span>
                            @elseif($da->jam_absen == null)
                            <span class="badge badge-danger">Belum Absen</span>
                            @else
                            <?php
                            $telat = $da->telat;
                            $jam   = floor($telat / (60 * 60));
                            $menit = $telat - ($jam * (60 * 60));
                            $menit2 = floor($menit / 60);
                            $detik = $telat % 60;
                            ?>
                            @if($jam <= 0 && $menit2 <=0) <span class="badge badge-success">Tepat Waktu</span>
                                @else
                                <span class="badge badge-danger">{{ $jam." Jam ".$menit2." Menit" }}</span>
                                @endif
                                @endif
                        </td> -->
                        <td>
                            @if($da->status_absen == 'Libur')
                            <span class="badge badge-info">Libur</span>
                            @elseif($da->status_absen == 'Cuti')
                            <span class="badge badge-warning">Sedang Cuti</span>
                            @elseif($da->jam_absen == null)
                            <span class="badge badge-danger">Belum Absen</span>
                            @else
                            @php
                            $jarak_masuk = explode(".", $da->jarak_masuk);
                            @endphp
                            <a href="{{ url('/maps/'.$da->lat_absen.'/'.$da->long_absen.'/'.$da->User->id) }}" class="btn btn-sm btn-secondary" target="_blank">{{ $da -> lat_absen}},{{$da -> long_absen}}</a>
                            @endif
                        </td>
                        <td>{{ $da -> User -> Lokasi -> nama_lokasi }}</td>
                        <td>
                            @if($da->status_absen == 'Libur')
                            <span class="badge badge-info">Libur</span>
                            @elseif($da->status_absen == 'Cuti')
                            <span class="badge badge-warning">Sedang Cuti</span>
                            @elseif($da->jam_absen == null)
                            <span class="badge badge-danger">Belum Absen</span>
                            @else
                            <img src="{{ url('storage/' . $da->foto_jam_absen) }}" style="width: 60px">
                            @endif
                        </td>
                        <td>
                            @if($da->status_absen == 'Libur')
                            <span class="badge badge-info">Libur</span>
                            @elseif($da->status_absen == 'Cuti')
                            <span class="badge badge-warning">Sedang Cuti</span>
                            @elseif($da->jam_absen == null)
                            <span class="badge badge-danger">Belum Absen</span>
                            @elseif($da->jam_pulang == null)
                            <span class="badge badge-warning">Belum Pulang</span>
                            @else
                            @php

                            if ($da->full_jam_pulang !== null) {
                            $carbonDate = Carbon::parse($da->full_jam_pulang);

                            if ($carbonDate->offset === 25200) {
                            $formattedTime = $carbonDate->format('H:i') . ' WIB';
                            } elseif ($carbonDate->offset === 28800) {
                            $carbonDate->addHour();
                            $formattedTime = $carbonDate->format('H:i') . ' WITA';
                            } elseif ($carbonDate->offset === 32400) {
                            $carbonDate->addHours(2);
                            $formattedTime = $carbonDate->format('H:i') . ' WIT';
                            } else {
                            $formattedTime = $carbonDate->format('H:i');
                            }
                            } else {
                            $formattedTime = '';
                            }

                            @endphp
                            @if ($da->full_jam_pulang)
                            {{ $formattedTime }}
                            @else
                            {{$da -> jam_pulang}}
                            @endif
                            @endif
                        </td>
                        <!-- 
                            Ini comment pulang cepat
                            <td>
                            @if($da->status_absen == 'Libur')
                            <span class="badge badge-info">Libur</span>
                            @elseif($da->status_absen == 'Cuti')
                            <span class="badge badge-warning">Sedang Cuti</span>
                            @elseif($da->status_absen == 'Izin Pulang Cepat')
                            <span class="badge badge-warning">Izin Pulang Cepat</span>
                            @elseif($da->jam_absen == null)
                            <span class="badge badge-danger">Belum Absen</span>
                            @elseif($da->jam_pulang == null)
                            <span class="badge badge-warning">Belum Pulang</span>
                            @else
                            <?php
                            $pulang_cepat = $da->pulang_cepat;

                            $jam   = floor($pulang_cepat / (60 * 60));
                            $menit = $pulang_cepat - ($jam * (60 * 60));
                            $menit2 = floor($menit / 60);
                            $detik = $pulang_cepat % 60;
                            ?>
                            @if($jam <= 0 && $menit2 <=0) <span class="badge badge-success">Tidak Pulang Cepat</span>
                                @else
                                <span class="badge badge-danger">{{ $jam." Jam ".$menit2." Menit" }}</span>
                                @endif
                                @endif
                        </td> -->
                        <td>
                            @if($da->status_absen == 'Libur')
                            <span class="badge badge-info">Libur</span>
                            @elseif($da->status_absen == 'Cuti')
                            <span class="badge badge-warning">Sedang Cuti</span>
                            @elseif($da->jam_absen == null)
                            <span class="badge badge-danger">Belum Absen</span>
                            @elseif($da->jam_pulang == null)
                            <span class="badge badge-warning">Belum Pulang</span>
                            @else
                            @php
                            $jarak_pulang = explode(".", $da->jarak_pulang);
                            @endphp
                            <a href="{{ url('/maps/'.$da->lat_pulang.'/'.$da->long_pulang.'/'.$da->User->id) }}" class="btn btn-sm btn-secondary" target="_blank">{{ $da -> lat_pulang}},{{$da -> long_pulang}}</a>
                            @endif
                        </td>
                        <td>
                            @if($da->status_absen == 'Libur')
                            <span class="badge badge-info">Libur</span>
                            @elseif($da->status_absen == 'Cuti')
                            <span class="badge badge-warning">Sedang Cuti</span>
                            @elseif($da->jam_absen == null)
                            <span class="badge badge-danger">Belum Absen</span>
                            @elseif($da->jam_pulang == null)
                            <span class="badge badge-warning">Belum Pulang</span>
                            @else
                            <img src="{{ url('storage/' . $da->foto_jam_pulang) }}" style="width: 60px">
                            @endif
                        </td>
                        <td>
                            @if($da->status_absen == 'Libur')
                            <span class="badge badge-info">{{ $da->status_absen }}</span>
                            @elseif($da->status_absen == 'Cuti' || $da->status_absen == 'Izin Telat' || $da->status_absen == 'Izin Pulang Cepat')
                            <span class="badge badge-warning">{{ $da->status_absen }}</span>
                            @elseif($da->status_absen == 'Masuk')
                            <span class="badge badge-success">{{ $da->status_absen }}</span>
                            @else
                            <span class="badge badge-danger">{{ $da->status_absen }}</span>
                            @endif
                        </td>
                        <td>{{ $da->tanggal_checkout }}</td>
                        <!-- Total Waktu Kerja  -->
                        <td>
                            @if($da->status_absen == 'Libur')
                            <span class="badge badge-info">Libur</span>
                            @elseif($da->status_absen == 'Cuti')
                            <span class="badge badge-warning">Sedang Cuti</span>
                            @elseif($da->status_absen == 'Izin Telat')
                            <span class="badge badge-warning">Izin Telat</span>
                            @elseif($da->jam_absen == null)
                            <span class="badge badge-danger">Belum Absen</span>
                            @else
                            <?php
                            $checkin = new DateTime($da->tanggal . ' ' . $da->jam_absen);
                            $checkout = new DateTime($da->tanggal_checkout . ' ' . $da->jam_pulang);

                            $seconds = $checkout->getTimestamp() - $checkin->getTimestamp();

                            // Convert seconds to hours and minutes
                            $hours = floor($seconds / 3600); // 3600 seconds in an hour
                            $minutes = floor(($seconds % 3600) / 60);
                            ?>

                            <span class="badge badge-danger">{{ $hours. "hours and ". $minutes. "minutes"}}</span>
                            @endif
                        </td>
                        <td>
                            @if($da->status_absen == 'Libur')
                            <span class="badge badge-info">Libur</span>
                            @elseif($da->status_absen == 'Cuti')
                            <span class="badge badge-warning">Sedang Cuti</span>
                            @elseif($da->jam_absen == null)
                            <span class="badge badge-danger">Belum Absen</span>
                            @elseif($da->jam_pulang == null)
                            <span class="badge badge-warning">Belum Pulang</span>
                            @else
                            <p>{{ $da -> uraian_pekerjaan }}</p>
                            @endif
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
@endsection