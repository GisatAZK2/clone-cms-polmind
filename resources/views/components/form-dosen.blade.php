<div class="mb-8 border-2 border-gray-300 p-4 flex flex-col gap-4 ">
    <form method="POST" action="/tambah-dosen" enctype="multipart/form-data" class="justify-center" >
        @csrf
        <input class="border border-gray-500 px-2 py-2 mx-2 rounded-2xl" type="text" name="name" placeholder="Nama Dosen" required>
        <input class="border border-gray-500 px-2 py-2 mx-2 rounded-2xl" type="file" name="url_image" accept="image/*" required>
        <select class="border border-gray-500 px-2 py-2 mx-2 rounded-2xl"name="type" required>
            <option value="Dosen_Internal">Dosen Internal</option>
            <option value="Expert_industri">Expert Industri</option>
        </select>
        <input class="border border-gray-500 px-2 py-2 mx-2 rounded-2xl"type="text" name="alt" placeholder="Alt_Text" required>
        <button class="bg-blue-500 px-2 py-2 mx-2 rounded-2xl text-white" type="submit">Tambah Dosen</button>
    </form>
</div>


