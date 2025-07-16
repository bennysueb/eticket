<!DOCTYPE html>
<html>

<head>
    <title>E-Sertifikat {{ myEvent()->name_event }}</title>
</head>

<body style="font-family: Arial, sans-serif; text-align: center; background-color: #f4f4f4; padding: 20px;">

    <div style="max-width: 800px; margin: auto; border: 1px solid #ddd; padding: 20px; background-color: #fff;">
        <h1 style="color: #333;">E-Sertifikat</h1>
        <p>
            Yth. <strong>{{ $invt->name_guest }}</strong>,
        </p>
        <p>
            Terima kasih atas partisipasi Anda dalam acara <strong>{{ myEvent()->name_event }}</strong>.
            <br>
            Berikut kami lampirkan e-sertifikat sebagai tanda penghargaan atas keikutsertaan Anda.
        </p>
        <hr>

        {{-- Menampilkan gambar sertifikat yang di-embed dari controller --}}
        <img src="cid:certificate_image" style="width:100%; max-width:100%; height:auto;" alt="E-Sertifikat">

        <hr>
        <p>
            Hormat kami,<br>
            <strong>Panitia {{ myEvent()->name_event }}</strong>
        </p>
        <p style="font-size: 10px; color: #777;">
            Email ini dibuat secara otomatis. Mohon tidak membalas email ini.
        </p>
    </div>

</body>

</html>