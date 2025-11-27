@extends('layouts.app')

@section('title', 'LAYANAN RSHP - RSHP UNAIR')

@section('content')
    <header class="head">
        <h1>Layanan RSHP Universitas Airlangga</h1>
    </header>

    <section class="content">
        <h2 class="subjudul">DAFTAR LAYANAN</h2>
        <ul>
            <li>Pemeriksaan Umum Hewan</li>
            <li>Vaksinasi</li>
            <li>Bedah dan tindakan medis</li>
            <li>Rawat inap hewan kecil dan besar</li>
            <li>Konsultasi kesehatan hewan</li>
            <li>Laboratorium diagnostik</li>
        </ul>
    </section>

    <section style="padding: 2rem;" class="content">
        <div>
            <h2 class="subjudul">JAM OPERASIONAL</h2>
        </div>

        <table class="operasional-table">
            <thead>
                <tr>
                    <th>HARI</th>
                    <th>PELAYANAN</th>
                    <th>JAM</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td rowspan="2">Senin - Jumat</td>
                    <td>Pelayanan Umum</td>
                    <td>08.30 - 21.30 WIB</td>
                </tr>
                <tr>
                    <td>IGD</td>
                    <td>21.30 - 08.00 WIB</td>
                </tr>
                <tr>
                    <td rowspan="2">Sabtu</td>
                    <td>Pelayanan Umum</td>
                    <td>09.00 - 16.00 WIB</td>
                </tr>
                <tr>
                    <td>IGD</td>
                    <td>16.00 - 08.00 WIB</td>
                </tr>
                <tr>
                    <td>Minggu</td>
                    <td colspan="2" class="full-igd" style="text-align:center">
                        🚨 FULL IGD 🚨
                    </td>
                </tr>
            </tbody>
        </table>
    </section>

    <section class="content">
        <h2 class="subjudul">LOKASI RSHP</h2>
        <p style="text-align:center">GEDUNG RS HEWAN PENDIDIKAN</p>
        <p style="text-align:center">Kampus C Universitas Airlangga, Mulyorejo, Kec. Mulyorejo, Surabaya, Jawa Timur 60115,
            Indonesia.</p>

        <div style="text-align: center;"><iframe style="text-align:center"
                src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d7915.482022032093!2d112.788135!3d-7.270285!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dd7fbd40a9784f5%3A0xe756f6ae03eab99!2sAnimal%20Hospital%2C%20Universitas%20Airlangga!5e0!3m2!1sen!2sus!4v1755250825759!5m2!1sen!2sus"
                width="600" height=auto style="border:0;" allowfullscreen="" loading="lazy"
                referrerpolicy="no-referrer-when-downgrade">
        </div></iframe>
    </section>
@endsection