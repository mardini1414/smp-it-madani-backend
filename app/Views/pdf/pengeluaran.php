<style>
    body {
        font-family: Arial, Helvetica, sans-serif;
        font-weight: 400;
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
    <h2 id="title">Laporan pengeluaran</h2>
    <table>
        <tr>
            <th>No</th>
            <th>Nama pengeluaran</th>
            <th>Kode rekening</th>
            <th>Kode kegiatan</th>
            <th>Bulan/tahun</th>
            <th>Jumlah</th>
        </tr>
        <?php foreach ($data['pengeluaran'] as $i => $pengeluaran): ?>
            <tr>
                <td>
                    <?= $i + 1 ?>
                </td>
                <td>
                    <?= $pengeluaran->nama_belanja ?>
                </td>
                <td>
                    <?= $pengeluaran->kode_rekening ?>
                </td>
                <td>
                    <?= $pengeluaran->kode_kegiatan ?>
                </td>
                <td>
                    <?php
                    $timestamp = strtotime($pengeluaran->bulan_tahun);
                    $date = date('m-Y', $timestamp);
                    echo $date;
                    ?>
                </td>
                <td>
                    <?= $pengeluaran->jumlah ?>
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