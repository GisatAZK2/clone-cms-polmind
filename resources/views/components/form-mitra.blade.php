<div class='px-2 py-2'>
    <form method="POST" action="/tambah-mitra" enctype="multipart/form-data">
        @csrf
        <label for="nama_partner">Nama Partner</label>
        <input type="text" name="nama_partner" class="rounded-2xl px-2 border border-gray-300" />

        <label for="url_image">Logo Partner</label>
        <input type="file" name="url_image" class="px-2 border border-gray-300" />
        <button type="submit" class="bg-blue-400 rounded-2xl px-2 py-1">Tambah</button>
    </form >
</div >