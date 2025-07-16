@extends('template.scan')
@section('content')





<title>Registration Form - {{ mySetting()->name_app != '' ? mySetting()->name_app : env('APP_NAME') }}</title>
<div class="container">
    <div class="row justify-content-center mt-4">
        <div class="col-md-8 col-xl-6">
            <div class="card mt-5">
                <div class="card-header d-block text-center" style="padding: 75px; background-image: url({{ asset('img/event/' . $event->image_register_event) }}); background-size: contain;background-repeat: no-repeat;">
                    <!-- <img src="{{ mySetting()->logo_app != '' ? asset('img/app/'.mySetting()->logo_app) : asset('template/assets/img/logo.png') }}" alt="logo" width="200"> -->
                    <!-- <h4 style="color:rgb(253, 253, 253);"> FORM ATTENDANCE CONFIRMATION</h4> -->
                </div>
                <div class="card-body" style="text-align: justify; text-justify: inter-word;">
                    <h5> Mohon Maaf Pendaftaran Sudah Ditutup</h5>
                    <br>
                    Luar biasa! Antusiasme untuk bergabung di {{ mySetting()->name_app != '' ? mySetting()->name_app : env('APP_NAME') }} sangat tinggi! Dengan berat hati sekaligus bangga, kami mengumumkan bahwa pendaftaran resmi kami tutup karena kuota telah terpenuhi.
                    Terima kasih banyak untuk semua yang sudah mendaftar!
                    <br>
                    <br>
                    Bagi yang belum berkesempatan mendaftar, Anda dapat menyaksikan secara live di channel Youtube @@Kemenperin_RI. dan terus ikuti Instagram kami di @aigis.events untuk mendapatkan informasi terbaru.
                    <br>
                    <br>
                    Terimakasih.
                </div>
            </div>

            <div class="simple-footer text-muted">
                Copyright &copy; 2025 - AIGIS
            </div>

        </div>
    </div>
</div>

@endsection