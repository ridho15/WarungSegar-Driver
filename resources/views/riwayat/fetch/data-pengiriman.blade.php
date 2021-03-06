@if (count($configWaktuPengiriman) > 0)
  @foreach($configWaktuPengiriman as $item)
    <div class="col-12 card-data">
      <span>Sesi {{ $item->id_config_waktu_pengiriman }} ({{ $item->awal }} - {{ $item->akhir }})</span>
    </div>
    @foreach($item->configWaktuPengirimanLog as $configWaktuPengirimanLog)
      @foreach($configWaktuPengirimanLog->transaksiApps as $transaksiApps)
        @if ($transaksiApps->status == 4 && date('Y-m-d', strtotime($transaksiApps->tw4)) == date('Y-m-d', strtotime($tanggal)) && $transaksiApps->id_kota == 1 && $transaksiApps->tipe_keranjang == 1 && $transaksiApps->id_driver == $driver->id_driver)
        <div class="col-12 card-data">
          <div class="card">
            <div class="card-header bg-secondary text-white">
              <div class="card-title">
                <div class="row">
                  <div class="col-6">
                    <span class="title d-block h6">
                      {{ $transaksiApps->user->nama_depan }} {{ $transaksiApps->user->nama_belakang }}
                    </span>
                    <span class="d-block">
                      INV{{ sprintf('%08d', $transaksiApps->id_transaksi_apps) }}
                    </span>
                    <span class="badge @if ($transaksiApps->status == 1 || $transaksiApps->status == 2)badge-warning @elseif($transaksiApps->status == 3) badge-primary @elseif($transaksiApps->status == 4) badge-success @endif">
                      @if ($transaksiApps->status == 1)
                          Check Out
                      @elseif($transaksiApps->status == 2)
                          Siap Dikirim
                      @elseif($transaksiApps->status == 3)
                          Sedang Dikirim
                      @elseif($transaksiApps->status == 4)
                          Selesai Dikirim
                      @endif
                    </span>
                  </div>
                  <div class="col-6 text-right">
                    <span class="d-block h6">
                      {{ $function->formatTanggal($transaksiApps->tw4) }}
                    </span>
                    <span class="d-block">
                      {{ date('H:i:s', strtotime($transaksiApps->tw4)) }}
                    </span>
                  </div>
                </div>
              </div>
            </div>
            <div class="card-body">
              <div class="row mt-2">
                <div class="col-3 align-self-center">
                  @foreach ($transaksiApps->transaksiAppsDetail as $transaksiAppsDetail)
                      @if (count($transaksiAppsDetail->produkB2CHarga->produkB2C->produkSub->produkMaster->produkMasterGambar) > 0)
                      <img src="{{ $transaksiAppsDetail->produkB2CHarga->produkB2C->produkSub->produkMaster->produkMasterGambar[0]->gambar }}" alt="Gambar Produk" width="60" height="60">
                      @else
                          Gambar Produk tidak tersedia
                      @endif
                      @php
                          break;
                      @endphp
                  @endforeach
                </div>
                <div class="col align-self-center">
                  <span class="d-block h6 mb-0">
                    @if (count($transaksiApps->transaksiAppsDetail) > 0)
                        {{ $transaksiApps->transaksiAppsDetail[0]->produkB2CHarga->produkB2C->produkSub->produkMaster->nama_produk }}
                    @else
                        Nama Produk tidak ditemukan
                    @endif
                  </span>
                  <span class="d-block">
                    @if (count($transaksiApps->transaksiAppsDetail) > 1)
                        Dan {{ count($transaksiApps->transaksiAppsDetail) - 1 }} Lainnya
                    @endif
                  </span>
                </div>
                <div class="col-4 text-left font-weight-bold align-self-center">
                  <span class="text-primary h6 d-block mb-0">Total Jual</span>
                  <span>Rp {{ number_format($transaksiApps->total, 0, ',', '.') }}</span>
                </div>
              </div>
              <hr>
              <div class="row">
                <div class="col">
                  <span class="d-block font-weight-bold">Metode Pembayaran :</span> 
                  @if ($transaksiApps->id_metode_pembayaran_waktu == 0)
                      <span class="badge @if ($transaksiApps->status_pembayaran == 0) badge-warning @else badge-success @endif text-white">Bayar Ditempat</span>
                  @else
                      <span class="badge badge-warning">{{ $transaksiApps->transaksiJenisPembayaranWaktu->transaksiJenisPembayaranMulai->transaksiJenisPembayaranMetode->transaksiJenisPembayaran->nama }}</span>
                  @endif
                  @if ($transaksiApps->status_pembayaran == 1)
                      <span class="badge badge-success text-white">Lunas</span>
                  @else
                      <span class="badge badge-warning text-white">Belum Lunas</span>
                  @endif</span>
                  <span class="d-block mt-2 font-weight-bold">Ongkir : Rp{{ number_format($transaksiApps->ongkir, 0, ',', '.') }}</span>
                  <small class="mt-2 font-italic">{{ $transaksiApps->alamat->geolocation }}</small>
                  <small class="font-weight-bold">({{ $transaksiApps->alamat->jarak }} Km)</small>
                  <small class="font-weight-bold d-block"> => {{ $transaksiApps->alamat->nama_alamat }}</small>
                </div>
              </div>
              <hr>
              <div class="row">
                <div class="col">
                  <span>Total Item : </span>
                  <span class="font-weight-bold">{{ count($transaksiApps->transaksiAppsDetail) }}</span>
                </div>
                <div class="col text-right">
                  <a href="/pesanan/detail?id={{ $crypt->crypt($transaksiApps->id_transaksi_apps) }}" class="btn btn-primary btn-sm">
                    Detail
                    <i class="fas fa-angle-double-right"></i>
                  </a>
                </div>
              </div>
              <div class="row mt-2">
                @if ($transaksiApps->driver)
                  <div class="col">
                    <span>Diantar Oleh : </span> <span class="font-weight-bold">{{ $transaksiApps->driver->nama_driver }}</span>
                  </div>
                @endif
              </div>
            </div>
          </div>
        </div>
        @endif
      @endforeach
    @endforeach
  @endforeach
@else
  <div class="col-12 text-center mt-3 card-data">
    <img src="https://storage.googleapis.com/assets-warungsegar/images/no%20data.gif" alt="Gambar" width="300" height="300" style="">
    <span class="d-block">Tidak Ada Pengiriman</span>
  </div>
@endif