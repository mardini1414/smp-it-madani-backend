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
    <h2 id="title">Laporan tagihan</h2>
    <table>
        <tr>
            <th>No</th>
            <th>Nama tagihan</th>
            <th>Kode</th>
            <th>Jatuh tempo</th>
            <th>Jumlah</th>
        </tr>
        <?php foreach ($data['data'] as $i => $tagihan): ?>
            <tr>
                <td>
                    <?= $i + 1 ?>
                </td>
                <td>
                    <?= $tagihan['nama'] ?>
                </td>
                <td>
                    <?= $tagihan['kode'] ?>
                </td>
                <td>
                    <?= $tagihan['jatuh_tempo'] ?>
                </td>
                <td>
                    <?= $tagihan['jumlah'] ?>
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