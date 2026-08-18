<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>Login | Speech Publications</title>

	<link rel="preconnect" href="https://fonts.googleapis.com/">
	<link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

	<link rel="shortcut icon" href="{{ url('public/images/logo/Loggo3.png') }}" />

	<style>
		:root {
			--brand: #4f46e5;
			--brand-dark: #4338ca;
			--brand-light: #6366f1;
			--ink: #1e293b;
			--muted: #64748b;
			--line: #e2e8f0;
			--bg: #f1f5f9;
		}

		* { margin: 0; padding: 0; box-sizing: border-box; }

		body {
			font-family: 'Poppins', sans-serif;
			background: var(--bg);
			color: var(--ink);
			min-height: 100vh;
			-webkit-font-smoothing: antialiased;
		}

		.auth-wrap { display: flex; min-height: 100vh; }

		/* ---------- Left branding panel ---------- */
		.brand-panel {
			flex: 1.1;
			position: relative;
			display: flex;
			flex-direction: column;
			justify-content: center;
			padding: 60px 6vw;
			overflow: hidden;
			color: #fff;
			background: linear-gradient(135deg, #1e1b4b 0%, #4338ca 55%, #6d28d9 100%);
		}

		.brand-panel::before {
			content: '';
			position: absolute;
			width: 520px; height: 520px;
			border-radius: 50%;
			background: radial-gradient(circle, rgba(255,255,255,.12), transparent 60%);
			top: -140px; right: -120px;
		}

		.brand-panel::after {
			content: '';
			position: absolute;
			width: 420px; height: 420px;
			border-radius: 50%;
			border: 2px solid rgba(255,255,255,.12);
			bottom: -160px; left: -120px;
		}

		.brand-inner { position: relative; z-index: 1; max-width: 520px; }

		.brand-logo {
			display: flex; align-items: center; gap: 16px; margin-bottom: 40px;
		}

		.brand-logo img { height: 64px; width: auto; border-radius: 14px; box-shadow: 0 10px 30px rgba(0,0,0,.3); }

		.brand-logo .logo-text { font-size: 22px; font-weight: 700; letter-spacing: .5px; }
		.brand-logo .logo-text span { font-weight: 300; opacity: .85; }

		.brand-panel h1 { font-size: clamp(28px, 3vw, 42px); font-weight: 700; line-height: 1.25; margin-bottom: 18px; }

		.brand-panel p { font-size: 15px; line-height: 1.8; opacity: .85; margin-bottom: 40px; max-width: 420px; }

		.brand-stats { display: flex; gap: 44px; }
		.brand-stats .stat b { display: block; font-size: 26px; font-weight: 700; }
		.brand-stats .stat span { font-size: 13px; opacity: .75; }

		.float-book {
			position: absolute; border-radius: 18px;
			box-shadow: 0 24px 60px rgba(0,0,0,.35);
			animation: float 6s ease-in-out infinite;
			opacity: .9;
		}
		.float-book.b1 { width: 130px; height: 170px; right: 6%; bottom: 12%; background: linear-gradient(160deg, #fbbf24, #f59e0b); animation-delay: 0s; }
		.float-book.b2 { width: 100px; height: 135px; right: 20%; bottom: 5%; background: linear-gradient(160deg, #34d399, #10b981); animation-delay: 1.5s; }
		.float-book.b3 { width: 80px; height: 110px; right: 32%; bottom: 14%; background: linear-gradient(160deg, #f472b6, #ec4899); animation-delay: 3s; }

		@keyframes float {
			0%, 100% { transform: translateY(0) rotate(-3deg); }
			50% { transform: translateY(-18px) rotate(3deg); }
		}

		/* ---------- Right form panel ---------- */
		.form-panel {
			flex: 1;
			display: flex;
			align-items: center;
			justify-content: center;
			padding: 40px 5vw;
		}

		.login-card { width: 100%; max-width: 440px; }

		.login-card .mobile-logo { display: none; align-items: center; gap: 12px; margin-bottom: 28px; }
		.login-card .mobile-logo img { width: 48px; height: 48px; border-radius: 12px; }
		.login-card .mobile-logo span { font-size: 20px; font-weight: 700; color: var(--ink); }

		.login-card h2 { font-size: 28px; font-weight: 700; margin-bottom: 8px; }
		.login-card .subtitle { color: var(--muted); font-size: 14px; margin-bottom: 32px; }

		.form-group { margin-bottom: 22px; }

		.form-group label {
			display: block; font-size: 13px; font-weight: 600; color: var(--ink);
			margin-bottom: 8px;
		}

		.input-wrap { position: relative; }

		.input-wrap .field-icon {
			position: absolute; left: 16px; top: 50%; transform: translateY(-50%);
			width: 18px; height: 18px; opacity: .45; pointer-events: none;
		}

		.input-wrap .toggle-pass {
			position: absolute; right: 14px; top: 50%; transform: translateY(-50%);
			background: none; border: none; cursor: pointer; opacity: .5; padding: 4px;
		}
		.input-wrap .toggle-pass:hover { opacity: .9; }

		.form-control {
			width: 100%;
			padding: 14px 44px;
			border: 1.5px solid var(--line);
			border-radius: 12px;
			font-size: 14px;
			font-family: 'Poppins', sans-serif;
			background: #fff;
			color: var(--ink);
			transition: border-color .2s, box-shadow .2s;
		}

		.form-control:focus {
			outline: none;
			border-color: var(--brand);
			box-shadow: 0 0 0 4px rgba(79, 70, 229, .12);
		}

		.form-control.has-error { border-color: #ef4444; }

		.error-msg { color: #ef4444; font-size: 12px; margin-top: 6px; display: block; }

		.row-options {
			display: flex; align-items: center; justify-content: space-between;
			margin-bottom: 26px; font-size: 13px;
		}

		.checkbox-label { display: flex; align-items: center; gap: 8px; cursor: pointer; color: var(--muted); }

		.checkbox-label input { width: 16px; height: 16px; accent-color: var(--brand); cursor: pointer; }

		.link-forgot { color: var(--brand); font-weight: 600; text-decoration: none; }
		.link-forgot:hover { text-decoration: underline; }

		.btn-login {
			width: 100%;
			padding: 15px;
			border: none;
			border-radius: 12px;
			font-size: 15px;
			font-weight: 600;
			font-family: 'Poppins', sans-serif;
			color: #fff;
			background: linear-gradient(135deg, var(--brand), var(--brand-light));
			box-shadow: 0 10px 24px rgba(79, 70, 229, .35);
			cursor: pointer;
			transition: transform .15s, box-shadow .15s;
		}

		.btn-login:hover { transform: translateY(-2px); box-shadow: 0 14px 30px rgba(79, 70, 229, .45); }
		.btn-login:active { transform: translateY(0); }

		.divider { display: flex; align-items: center; gap: 16px; margin: 26px 0; color: var(--muted); font-size: 12px; }
		.divider::before, .divider::after { content: ''; flex: 1; height: 1px; background: var(--line); }

		.btn-otp {
			width: 100%;
			padding: 13px;
			border-radius: 12px;
			font-size: 14px;
			font-weight: 500;
			font-family: 'Poppins', sans-serif;
			border: 1.5px solid var(--line);
			background: #fff;
			color: var(--ink);
			text-decoration: none;
			display: block;
			text-align: center;
			cursor: pointer;
			transition: border-color .2s, background .2s;
		}

		.btn-otp:hover { border-color: var(--brand); background: #fafbff; color: var(--brand); }

		.register-link { text-align: center; margin-top: 28px; font-size: 14px; color: var(--muted); }
		.register-link a { color: var(--brand); font-weight: 600; text-decoration: none; }
		.register-link a:hover { text-decoration: underline; }

		/* ---------- Responsive ---------- */
		@media (max-width: 900px) {
			.brand-panel { display: none; }
			.login-card .mobile-logo { display: flex; }
			.form-panel { padding: 40px 24px; }
		}
	</style>
</head>
<body>

<div class="auth-wrap">

	<!-- Left branding panel -->
	<div class="brand-panel">
		<div class="brand-inner">
			<div class="brand-logo">
				<img src="{{ url('public/images/logo/Loggo3.png') }}" alt="Speech Publications">
				<div class="logo-text">Speech <span>Publications</span></div>
			</div>
			<h1>Discover a world of knowledge &amp; inspiration.</h1>
			<p>Explore our rich collection of books, journals and publications. Sign in to access exclusive content, track your orders and manage your reading journey.</p>
			<div class="brand-stats">
				<div class="stat"><b>10K+</b><span>Books in Store</span></div>
				<div class="stat"><b>25K+</b><span>Happy Readers</span></div>
				<div class="stat"><b>4.9</b><span>Reader Rating</span></div>
			</div>
		</div>

		<div class="float-book b1"></div>
		<div class="float-book b2"></div>
		<div class="float-book b3"></div>
	</div>

	<!-- Right form panel -->
	<div class="form-panel">
		<div class="login-card">

			<div class="mobile-logo">
				<img src="{{ url('public/images/logo/Loggo3.png') }}" alt="Speech Publications">
				<span>Speech Publications</span>
			</div>

			<h2>Welcome back</h2>
			<p class="subtitle">Log in to your account to continue.</p>

			@if (session('status'))
				<div class="alert alert-success" style="padding:12px 16px;border-radius:10px;font-size:13px;margin-bottom:20px;">{{ session('status') }}</div>
			@endif

			<form method="POST" action="{{ route('login') }}">
				@csrf

				<div class="form-group">
					<label for="email">Email address</label>
					<div class="input-wrap">
						<svg class="field-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
							<path stroke-linecap="round" stroke-linejoin="round" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
						</svg>
						<input id="email" class="form-control {{ $errors->has('email') ? 'has-error' : '' }}"
							   type="email" name="email" value="{{ old('email') }}"
							   placeholder="you@example.com" required autofocus autocomplete="username">
					</div>
					@error('email')
						<span class="error-msg">{{ $message }}</span>
					@enderror
				</div>

				<div class="form-group">
					<label for="password">Password</label>
					<div class="input-wrap">
						<svg class="field-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
							<path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
							<path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
						</svg>
						<input id="password" class="form-control {{ $errors->has('password') ? 'has-error' : '' }}"
							   type="password" name="password" placeholder="••••••••" required autocomplete="current-password">
						<button type="button" class="toggle-pass" onclick="togglePassword()">
							<svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
								<path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
								<path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
							</svg>
						</button>
					</div>
					@error('password')
						<span class="error-msg">{{ $message }}</span>
					@enderror
				</div>

				<div class="row-options">
					<label class="checkbox-label">
						<input type="checkbox" name="remember" id="remember">
						Remember me
					</label>
					@if (Route::has('password.request'))
						<a href="{{ route('password.request') }}" class="link-forgot">Forgot password?</a>
					@endif
				</div>

				<button type="submit" class="btn-login">Log in</button>
			</form>

			<div class="divider">or</div>

			<a href="{{ route('otp.login') }}" class="btn-otp">Login with OTP</a>

			<p class="register-link">Don't have an account? <a href="{{ url('otp-register') }}">Register here</a></p>
		</div>
	</div>
</div>

<script>
	function togglePassword() {
		var input = document.getElementById('password');
		input.type = input.type === 'password' ? 'text' : 'password';
	}
</script>

</body>
</html>
