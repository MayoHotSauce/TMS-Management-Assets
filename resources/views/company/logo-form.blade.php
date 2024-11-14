<form action="{{ route('company.logo.update') }}" method="POST" enctype="multipart/form-data">
    @csrf
    
    <div>
        <label for="logo">Company Logo</label>
        <input type="file" name="logo" id="logo" accept="image/*" required>
    </div>

    @error('logo')
        <div class="text-red-500">{{ $message }}</div>
    @enderror

    <button type="submit">Update Logo</button>
</form> 