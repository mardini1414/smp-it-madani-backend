<style>
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
        padding: 8px;
    }

    tr:nth-child(even) {
        background-color: #dddddd;
    }

    #title {
        text-align: center;
    }

    #total {
        text-align: right;
        margin-top: 2rem;
    }
</style>
<div>
    <h2 id="title">Laporan rekapitulasi</h2>
    <table>
        <tr>
            <th>No</th>
            <th>Nama siswa</th>
            <th>NIS</th>
            <th>Kelas</th>
            <th>Email</th>
            <th>Nama tagihan</th>
            <th>Jumlah</th>
            <th>Jatuh tempo</th>
            <th>Tanggal bayar</th>
            <th>Status</th>
        </tr>
        <?php foreach ($data['data'] as $i => $rekapitulasi): ?>
            <tr>
                <td>
                    <?= $i + 1 ?>
                </td>
                <td>
                    <?= $rekapitulasi->nama_siswa ?>
                </td>
                <td>
                    <?= $rekapitulasi->NIS ?>
                </td>
                <td>
                    <?= $rekapitulasi->kelas ?>
                </td>
                <td>
                    <?= $rekapitulasi->email_siswa ?>
                </td>
                <td>
                    <?= $rekapitulasi->nama_tagihan ?>
                </td>
                <td>
                    <?= $rekapitulasi->jumlah ?>
                </td>
                <td>
                    <?php
                    $timestamp = strtotime($rekapitulasi->jatuh_tempo);
                    $date = date('d-m-Y', $timestamp);
                    echo $date;
                    ?>
                </td>
                <td>
                    <?php
                    if ($rekapitulasi->tanggal_bayar !== '-') {
                        $timestamp = strtotime($rekapitulasi->tanggal_bayar);
                        $date = date('d-m-Y', $timestamp);
                        echo $date;
                    } else {
                        echo '-';
                    }
                    ?>
                </td>
                <td>
                    <?= $retVal = ($rekapitulasi->status === 'success') ? 'Lunas' : 'Belum'; ?>
                </td>
            </tr>
        <?php endforeach ?>
    </table>
    <div id="total">
        <h5>Total</h5>
        <h5>
            Rp
            <?= $data['total'] ?>
        </h5>
    </div>
</div>