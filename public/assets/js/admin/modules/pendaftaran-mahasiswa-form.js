import { Core } from '../core/core.js';

export const PendaftaranMahasiswaForm = {
        init() {
            const list   = document.getElementById('gelombang-list');
            const addBtn = document.getElementById('add-gelombang');
            if (!list || !addBtn) return;

            const biayaHtml = (gi, bi) => `
              <div class="biaya-item border rounded p-2 mb-2" data-biaya-index="${bi}">
                <div class="row gx-2 gy-2 align-items-end">
                  <div class="col-md-3"><label class="form-label" data-translate="pendaftaran.fieldNamaBiaya">Nama Biaya</label><input type="text" name="gelombang[${gi}][biaya][${bi}][nama_biaya]" class="form-control"></div>
                  <div class="col-md-2"><label class="form-label" data-translate="pendaftaran.fieldNominal">Nominal</label><input type="text" name="gelombang[${gi}][biaya][${bi}][nominal]" class="form-control"></div>
                  <div class="col-md-2"><label class="form-label" data-translate="pendaftaran.fieldPeriodeBayar">Periode Bayar</label><input type="text" name="gelombang[${gi}][biaya][${bi}][periode_bayar]" class="form-control"></div>
                  <div class="col-md-2"><div class="form-check mt-3"><input class="form-check-input btn-toggle-diskon" type="checkbox" value="1" id="diskon-${gi}-${bi}" name="gelombang[${gi}][biaya][${bi}][has_diskon]"><label class="form-check-label" for="diskon-${gi}-${bi}" data-translate="pendaftaran.checkboxDiskon">Ingin memberi diskon?</label></div></div>
                  <div class="col-md-2 text-end"><button type="button" class="btn btn-danger btn-sm btn-remove-biaya" data-translate="common.delete">Hapus</button></div>
                </div>
                <div class="row gx-2 gy-2 mt-1 biaya-diskon-group" style="display:none">
                  <div class="col-md-2"><label class="form-label" data-translate="pendaftaran.fieldHargaDiskon">Harga Diskon</label><input type="text" name="gelombang[${gi}][biaya][${bi}][harga_diskon]" class="form-control"></div>
                  <div class="col-md-4"><label class="form-label" data-translate="pendaftaran.fieldAlasanDiskon">Alasan Diskon</label><input type="text" name="gelombang[${gi}][biaya][${bi}][alasan_diskon]" class="form-control"></div>
                </div>
              </div>`;

            const gelombangHtml = gi => {
                const lbl = Core.t('pendaftaran.gelombangLabel', 'Gelombang');
                return `
                  <div class="card mb-3 p-3 gelombang-item" data-index="${gi}">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                      <strong class="gelombang-number">${lbl} #${gi + 1}</strong>
                      <button type="button" class="btn btn-sm btn-danger btn-delete-gelombang" data-translate="pendaftaran.hapusGelombang">Hapus Gelombang</button>
                    </div>
                    <div class="row gx-3 gy-2 mb-3">
                      <div class="col-md-6"><label class="form-label" data-translate="pendaftaran.fieldNamaGelombang">Nama Gelombang</label><input type="text" name="gelombang[${gi}][nama_gelombang]" class="form-control"></div>
                      <div class="col-md-6"><label class="form-label" data-translate="pendaftaran.fieldJadwalDaftarUlang">Jadwal Daftar Ulang</label><input type="text" name="gelombang[${gi}][jadwal_daftar_ulang]" class="form-control"></div>
                      <div class="col-md-4"><label class="form-label" data-translate="pendaftaran.fieldJadwalPendaftaran">Jadwal Pendaftaran</label><input type="text" name="gelombang[${gi}][jadwal_pendaftaran]" class="form-control"></div>
                      <div class="col-md-4"><label class="form-label" data-translate="pendaftaran.fieldJadwalUjian">Jadwal Ujian Seleksi</label><input type="text" name="gelombang[${gi}][jadwal_ujian]" class="form-control"></div>
                      <div class="col-md-4"><label class="form-label" data-translate="pendaftaran.fieldJadwalPengumuman">Jadwal Pengumuman</label><input type="text" name="gelombang[${gi}][jadwal_pengumuman]" class="form-control"></div>
                    </div>
                    <div class="mb-3">
                      <div class="d-flex justify-content-between align-items-center mb-2">
                        <h6 class="mb-0" data-translate="pendaftaran.sectionBiaya">Biaya</h6>
                        <button type="button" class="btn btn-sm btn-outline-secondary btn-add-biaya" data-translate="pendaftaran.tambahBiaya">Tambah Biaya</button>
                      </div>
                      <div class="biaya-list">${biayaHtml(gi, 0)}</div>
                    </div>
                  </div>`;
            };

            const reindex = () => {
                list.querySelectorAll('.gelombang-item').forEach((card, gi) => {
                    card.dataset.index = gi;
                    const nm = card.querySelector('.gelombang-number');
                    if (nm) nm.textContent = Core.t('pendaftaran.gelombangLabel', 'Gelombang') + ' #' + (gi + 1);
                    card.querySelectorAll('[name]').forEach(el => {
                        el.name = el.name.replace(/gelombang\[\d+\]/g, `gelombang[${gi}]`);
                    });
                    card.querySelectorAll('.biaya-item').forEach((biaya, bi) => {
                        biaya.dataset.biayaIndex = bi;
                        biaya.querySelectorAll('[name]').forEach(el => {
                            el.name = el.name.replace(/gelombang\[\d+\]\[biaya\]\[\d+\]/g, `gelombang[${gi}][biaya][${bi}]`);
                        });
                        const chk = biaya.querySelector('.btn-toggle-diskon');
                        if (chk) {
                            const newId = `diskon-${gi}-${bi}`;
                            const lbl   = biaya.querySelector(`label[for="${chk.id}"]`);
                            chk.id = newId;
                            if (lbl) lbl.htmlFor = newId;
                        }
                    });
                });
                window.AdminTranslate?.translatePage?.();
            };

            addBtn.addEventListener('click', () => {
                const gi  = list.querySelectorAll('.gelombang-item').length;
                const div = document.createElement('div');
                div.innerHTML = gelombangHtml(gi).trim();
                const el = div.firstElementChild;
                list.appendChild(el);
                Core.translateNode(el);
            });

            document.addEventListener('click', e => {
                if (e.target.closest('.btn-delete-gelombang')) {
                    if (list.querySelectorAll('.gelombang-item').length <= 1) return;
                    e.target.closest('.gelombang-item')?.remove(); reindex();
                }
                if (e.target.closest('.btn-add-biaya')) {
                    const card  = e.target.closest('.gelombang-item');
                    const bl    = card.querySelector('.biaya-list');
                    const bi    = bl.querySelectorAll('.biaya-item').length;
                    const gi    = parseInt(card.dataset.index, 10);
                    const div   = document.createElement('div');
                    div.innerHTML = biayaHtml(gi, bi).trim();
                    const el = div.firstElementChild;
                    bl.appendChild(el); Core.translateNode(el);
                }
                if (e.target.closest('.btn-remove-biaya')) {
                    const bl = e.target.closest('.biaya-list');
                    if (bl.querySelectorAll('.biaya-item').length <= 1) return;
                    e.target.closest('.biaya-item')?.remove(); reindex();
                }
            });

            document.addEventListener('change', e => {
                const chk = e.target.closest('.btn-toggle-diskon');
                if (!chk) return;
                const grp = chk.closest('.biaya-item')?.querySelector('.biaya-diskon-group');
                if (grp) grp.style.display = chk.checked ? 'flex' : 'none';
            });
        },
    };