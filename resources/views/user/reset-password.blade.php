<form method="POST" action="{{ route('user.password.reset') }}">
    @csrf
    <input type="hidden" name="token" value="{{ $token }}">

    <div class="input-group">
        <label for="email">Email Address</label>
        <input type="email" id="email" name="email" value="{{ old('email') }}" required>
    </div>

    <div class="input-group">
        <label for="password">New Password</label>
        <input type="password" id="password" name="password" required>
    </div>

    <div class="input-group">
        <label for="password_confirmation">Confirm Password</label>
        <input type="password" id="password_confirmation" name="password_confirmation" required>
    </div>

    <button type="submit">Reset Password</button>
</form>
