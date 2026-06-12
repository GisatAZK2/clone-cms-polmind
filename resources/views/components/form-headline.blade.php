<form method="POST" action="/tambah-headline" enctype="multipart/form-data">
    @csrf
    <label for="title">Judul Headline</label>
    <input class='border border-gray-500 rounded-2xl' type="text" name="title" id="title" required>
    <label for="url_image">Pilih Foto</label>
    <input class='border border-gray-500 rounded-2xl' type="file" name="url_image" id="url_image" required>
    <label for="alt">Deksripsi Singkat</label>
    <input class='border border-gray-500 rounded-2xl' type="text" name="alt" id="alt" required>
    <button type="submit" class="rounded-2xl bg-blue-500 px-2 py-1 text-white">Submit</button>
</form>