@extends('template.scan')
@section('content')


<style>
    #guest-welcome {
        font-size: 36px;
        margin-top: 20px;
        text-align: center;
        font-weight: bold;
        color: #ceb732;
    }

    #guest-details {
        margin-top: 10px;
    }

    #guest-name {
        font-weight: bold;
        color: #006932;
        margin-left: 10px;
    }

    #guest-university {
        font-weight: bold;
        color: #555;
        margin-left: 10px;
    }

    #guest-faculty {
        color: #555;
        margin-left: 10px;
    }
</style>

<title>Scan In - {{ mySetting()->name_app != '' ? mySetting()->name_app : env('APP_NAME') }}</title>

<div class="container pt-5">
    <div class="form-group mt-5">
        <h2 class="text-center" style="color: #555;">CHECK-IN DI SINI</h2> <br>

        <!-- Input untuk Scanner Manual -->
        <div class="input-group">
            <div class="input-group-prepend camera-on" style="cursor: pointer">
                <div class="input-group-text">
                    <i class="fas fa-camera"></i>
                </div>
            </div>
            <input id="qrcode" type="text" class="form-control" autofocus autocomplete="off" placeholder="Scan barcode atau QR Code di sini">
        </div>

        <!-- Area Kamera untuk Scan Otomatis -->
        <div id="reader" class="mt-3" style="width: 100%; max-width: 500px; margin: auto; display: none;"></div>

        <!-- Menampilkan Status -->
        <div id="scan-status" class=" text-light mt-3"></div>
        <div class="text-light mt-3" id="guest-details" style="display: none;">
            <div class="form-control mt-3" style="min-height: fit-content;">
                <h4 id="guest-welcome">Selamat Datang</h4>
                <h3>Nama Peserta : <span id="guest-name"></span></h3>
                <h3>Universitas : <span id="guest-university"></span></h3>
                <h3>Fakultas : <span id="guest-faculty"></span></h3>
            </div>
        </div>


    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/html5-qrcode/2.3.8/html5-qrcode.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        let qrcodeInput = document.getElementById("qrcode");
        let scanStatus = document.getElementById("scan-status");
        let cameraOn = document.querySelector(".camera-on");
        let scannerActive = false;
        let lastScanned = "";
        let scanTimeout = null; // Untuk jeda 3 detik

        // Fungsi untuk mengirim data ke server
        function sendScanData(qrcode) {
            if (qrcode === lastScanned) return; // Hindari pengiriman duplikat
            lastScanned = qrcode;

            fetch("{{ url('scan/in-process') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({
                        qrcode: qrcode
                    })
                })
                .then(response => response.json())
                .then(data => {
                    scanStatus.innerHTML = `<div class="alert alert-${data.status === 'success' ? 'success' : 'danger'}">${data.message}</div>`;

                    // 👉 Tampilkan university_guest & faculty_guest jika ada
                    if (data.status === 'success') {
                        document.getElementById('guest-name').textContent = data.name_guest || '';
                        document.getElementById('guest-university').textContent = data.university_guest || '';
                        document.getElementById('guest-faculty').textContent = data.faculty_guest || '';
                        document.getElementById('guest-details').style.display = 'block';
                    } else {
                        document.getElementById('guest-details').style.display = 'none';
                    }

                    // Tunggu 3 detik, lalu refresh halaman
                    setTimeout(() => {
                        location.reload();
                    }, 5000);
                })
                .catch(error => {
                    scanStatus.innerHTML = `<div class="alert alert-danger">Terjadi kesalahan, coba lagi.</div>`;
                });
        }

        // Scanner Manual (Menggunakan Scanner Fisik)
        qrcodeInput.addEventListener("input", function() {
            let scannedCode = qrcodeInput.value.trim();
            if (scannedCode !== "") {
                clearTimeout(scanTimeout); // Hapus timeout sebelumnya jika ada
                scanTimeout = setTimeout(() => {
                    sendScanData(scannedCode);
                    qrcodeInput.value = ""; // Reset input setelah berhasil scan
                }, 3000); // Jeda 3 detik sebelum mengirim data
            }
        });

        // Scanner Otomatis (Menggunakan Kamera)
        cameraOn.addEventListener("click", function() {
            let readerDiv = document.getElementById("reader");

            if (!scannerActive) {
                readerDiv.style.display = "block";
                scannerActive = true;

                let html5QrCode = new Html5Qrcode("reader");
                html5QrCode.start({
                        facingMode: "environment"
                    }, // Kamera belakang
                    {
                        fps: 10,
                        qrbox: 250
                    },
                    (decodedText) => {
                        html5QrCode.stop();
                        scannerActive = false;
                        readerDiv.style.display = "none";
                        sendScanData(decodedText);
                    },
                    (errorMessage) => {
                        // Tidak perlu menangani error scanning
                    }
                );
            }
        });
    });
</script>

@endsection