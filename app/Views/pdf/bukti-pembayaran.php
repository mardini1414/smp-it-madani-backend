<style>
    body {
        font-family: Arial, Helvetica, sans-serif;
        color: #313131;
    }

    #title {
        text-align: center;
        margin-bottom: 3rem;
    }

    .data {
        font-size: 1.5rem;
        display: block;
        font-weight: 500;
        margin-bottom: 2rem;
    }

    .data-inner {
        display: block;
    }

    #status {
        font-size: 5rem;
        font-weight: 400;
        text-align: center;
    }
</style>
<div>
    <h2 id="title">Bukti Pembayaran</h2>
    <div>
        <div>
            <div class="data">
                <span class="data-inner">Nama</span>
                <span>
                    :
                    <?= $data->nama_siswa ?>
                </span>
            </div>
            <div class="data">
                <span>Email</span>
                <span>
                    :
                    <?= $data->email_siswa ?>
                </span>
            </div>
            <div class="data">
                <span>ID Transaksi</span>
                <span>
                    :
                    <?= $data->transaksi_id ?>
                </span>
            </div>
            <div class="data">
                <span>Nama Tagihan</span>
                <span> :
                    <?= $data->nama_tagihan ?>
                </span>
            </div>
            <div class="data">
                <span>Jumlah Tagihan</span>
                <span> :
                    <?= $data->jumlah ?>
                </span>
            </div>
            <div class="data">
                <span>Jatuh Tempo</span>
                <span> :
                    <?= $data->jatuh_tempo ?>
                </span>
            </div>
            <div class="data">
                <span>Waktu/Tanggal Pembayaran</span>
                <span> :
                    <?= $data->updated_at ?>
                </span>
            </div>
        </div>
    </div>
    <h1 id="status">LUNAS</h1>
</div>