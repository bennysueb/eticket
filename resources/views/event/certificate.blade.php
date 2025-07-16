@extends('template.template')
@section('content')

<style>
    #certificate-container {
        position: relative;
        width: 100%;
        border: 1px solid #ccc;
        overflow: hidden;
    }

    #imagePreviewCertificate {
        width: 100%;
        height: auto;
        display: block;
    }

    #draggable-name {
        position: absolute;
        cursor: move;
        border: 1px dashed #000;
        padding: 10px;
        background-color: rgba(255, 255, 255, 0.75);
        font-size: 28px;
        font-weight: bold;
        color: #000000;
        white-space: nowrap;
        user-select: none;
        z-index: 10;
        /* Posisikan di tengah secara default untuk preview */
        left: 50%;
        transform: translateX(-50%);
    }
</style>

<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>E-Sertifikat</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ url('dashboard') }}">Dashboard</a></div>
                <div class="breadcrumb-item">E-Sertifikat</div>
            </div>
        </div>

        <div class="section-body">
            <h2 class="section-title">Setting E-Sertifikat</h2>

            <div class="card">
                <div class="card-body">
                    <form action="{{ url('event/uploadCertificate') }}" method="POST" enctype="multipart/form-data" autocomplete="off">
                        @method('PUT')
                        @csrf
                        <input type="hidden" name="certificate_name_x" id="certificate_name_x" value="{{ $event->certificate_name_x ?? 450 }}">
                        <input type="hidden" name="certificate_name_y" id="certificate_name_y" value="{{ $event->certificate_name_y ?? 550 }}">

                        <div class="row">
                            <div class="col-xl-6">
                                <div class="form-group">
                                    <label for="">Upload E-Sertifikat *</label>
                                    <input type="file" name="image_certificate" class="form-control @error('image_certificate') is-invalid @enderror" onchange="document.getElementById('imagePreviewCertificate').src = window.URL.createObjectURL(this.files[0])">
                                    @error('image_certificate')
                                    <small class="text-danger"> {{ $message }} </small>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-xl-6">
                                <div class="form-group">
                                    <label for="">Simpan Pengaturan</label>
                                    <button class="btn btn-primary btn-lg btn-block"><i class="fa fa-save"></i> Simpan</button>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-12 mt-4">
                            <div class="form-group">
                                <label>Preview (Seret nama ke posisi yang diinginkan)</label>
                                <div id="certificate-container">
                                    <div id="draggable-name">Nama Peserta</div>
                                    <img id="imagePreviewCertificate" src="{{ asset('img/event/'.$event->image_certificate) }}" onerror="this.src='{{ asset('img/placeholder.jpg') }}'" />
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const dragElement = document.getElementById('draggable-name');
        const container = document.getElementById('certificate-container');
        const posXInput = document.getElementById('certificate_name_x');
        const posYInput = document.getElementById('certificate_name_y');
        const image = document.getElementById('imagePreviewCertificate');
        const form = container.closest('form');

        let active = false,
            xOffset = 0,
            yOffset = 0,
            initialX = 0,
            initialY = 0;

        // Fungsi ini memposisikan ulang elemen yang dapat diseret berdasarkan koordinat (berskala) yang disimpan
        const positionElement = () => {
            // Hanya berjalan jika gambar dimuat dan memiliki dimensi
            if (!image.complete || image.naturalWidth === 0 || image.clientWidth === 0) return;

            const scaleY = image.naturalHeight / image.clientHeight;

            // Nilai dalam input adalah koordinat "nyata" untuk gambar ukuran penuh.
            // Kita perlu menurunkannya untuk memposisikan elemen pratinjau.
            yOffset = parseInt(posYInput.value, 10) / scaleY;

            // Atur posisi vertikal. Posisi horizontal akan tetap di tengah.
            dragElement.style.transform = `translate(-50%, ${yOffset}px)`;
        };

        // Posisikan ulang elemen saat gambar dimuat atau jendela diubah ukurannya
        image.onload = positionElement;
        window.addEventListener('resize', positionElement);
        if (image.complete) { // Jika gambar dari cache, onload mungkin tidak terpicu
            positionElement();
        }

        // --- Logika Drag and Drop ---
        const dragStart = (e) => {
            active = true;
            initialY = e.clientY - yOffset;
        };

        const dragEnd = () => {
            active = false;
        };

        const drag = (e) => {
            if (!active) return;
            e.preventDefault();
            yOffset = e.clientY - initialY;
            dragElement.style.transform = `translate(-50%, ${yOffset}px)`;
        };

        dragElement.addEventListener('mousedown', dragStart, false);
        document.addEventListener('mouseup', dragEnd, false);
        document.addEventListener('mousemove', drag, false);

        // Saat form disubmit, hitung koordinat berskala akhir dan perbarui input tersembunyi
        form.addEventListener('submit', function(e) {
            if (!image.complete || image.naturalHeight === 0) {
                e.preventDefault();
                alert("Harap tunggu gambar pratinjau dimuat sepenuhnya.");
                return;
            }

            const scaleY = image.naturalHeight / image.clientHeight;
            // Kita hanya perlu menyimpan koordinat Y karena X akan selalu di tengah
            posYInput.value = Math.round(yOffset * scaleY);
        });
    });
</script>
@endsection