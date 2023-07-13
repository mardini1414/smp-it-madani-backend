<style>
    * {
        padding: 0;
        margin: 0;
    }

    body {
        font-family: Arial, Helvetica, sans-serif;
        font-weight: 400;
        font-size: 0.6rem;
        color: #313131;
    }

    table {
        font-family: arial, sans-serif;
        border-collapse: collapse;
        width: 100%;
    }

    td,
    th {
        border: 1px solid #dddddd;
        text-align: left;
        padding: 4px;
    }

    tr:nth-child(even) {
        background-color: #dddddd;
    }

    #title {
        text-align: center;
    }
</style>
<div>
    <h2 id="title">Data siswa SMP IT MADANI</h2>
    <table>
        <tr>
            <th>No</th>
            <th>Nama siswa</th>
            <th>NIS</th>
            <th>Jenis kelamin</th>
            <th>Kelas</th>
            <th>Status</th>
            <th>Nama Wali</th>
            <th>TTL</th>
            <th>Alamat</th>
        </tr>
        <?php foreach ($data as $i => $siswa): ?>
            <tr>
                <td>
                    <?= $i + 1 ?>
                </td>
                <td>
                    <?= $siswa['nama'] ?>
                </td>
                <td>
                    <?= $siswa['nisn'] ?>
                </td>
                <td>
                    <?= $value = ($siswa['jenis_kelamin'] === 'L') ? 'laki laki' : 'perempuan' ?>
                </td>
                <td>
                    <?= $siswa['kelas'] ?>
                </td>
                <td>
                    <?= $siswa['status'] ?>
                </td>
                <td>
                    <?= $siswa['nama_wali_murid'] ?>
                </td>
                <td>
                    <?= $siswa['tempat_lahir'] ?>
                    <?php
                    $timestamp = strtotime($siswa['tanggal_lahir']);
                    $date = date('d-m-Y', $timestamp);
                    echo $date;
                    ?>
                </td>
                <td>
                    <?= $siswa['alamat'] ?>
                </td>
            </tr>
        <?php endforeach ?>
    </table>
</div>