<div>
    @error('invalidCredentials')
    <span>{{ $message }}</span>
    @enderror

    @error('throttle')
    {{ $message }}
    @enderror
</div>
