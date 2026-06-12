<form action="/tambah-slider" enctype="multipart/form-data" method="POST">
    @csrf
    <label for="url_image">Upload foto</label>
    <input type="file" class="border border-gray-600" name="url_image" id="url_image" required/>

    <label for="alt">Masukkan Alt</label>
    <input type="text" name="alt" id="alt" class="border border-gray-600" /> 

    <button type="submit" class="bg-blue-400 text-white px-2 rounded-2xl py-1">Submit</button>
</form>