<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>Register | Speech Publications</title>

	<link rel="preconnect" href="https://fonts.googleapis.com/">
	<link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

	<link rel="shortcut icon" href="{{ url('public/images/logo/Loggo3.png') }}" />

	<style>
		:root {
			--brand: #4f46e5;
			--brand-dark: #4338ca;
			--brand-light: #6366f1;
			--brand-soft: #eef2ff;
			--success: #10b981;
			--danger: #ef4444;
			--warning: #f59e0b;
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

		.brand-logo { display: flex; align-items: center; gap: 16px; margin-bottom: 40px; }
		.brand-logo img { height: 64px; width: auto; border-radius: 14px; box-shadow: 0 10px 30px rgba(0,0,0,.3); }
		.brand-logo .logo-text { font-size: 22px; font-weight: 700; letter-spacing: .5px; }
		.brand-logo .logo-text span { font-weight: 300; opacity: .85; }

		.brand-panel h1 { font-size: clamp(28px, 3vw, 42px); font-weight: 700; line-height: 1.25; margin-bottom: 18px; }
		.brand-panel p { font-size: 15px; line-height: 1.8; opacity: .85; margin-bottom: 40px; max-width: 420px; }

		.perks { display: flex; flex-direction: column; gap: 20px; }
		.perk { display: flex; align-items: center; gap: 16px; }
		.perk .perk-ico {
			width: 46px; height: 46px; border-radius: 12px; flex-shrink: 0;
			display: flex; align-items: center; justify-content: center;
			background: rgba(255,255,255,.12); border: 1px solid rgba(255,255,255,.2);
		}
		.perk b { display: block; font-size: 14px; font-weight: 600; }
		.perk span { font-size: 12.5px; opacity: .75; }

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

		.register-card { width: 100%; max-width: 480px; }

		.register-card .mobile-logo { display: none; align-items: center; gap: 12px; margin-bottom: 24px; }
		.register-card .mobile-logo img { width: 48px; height: 48px; border-radius: 12px; }
		.register-card .mobile-logo span { font-size: 20px; font-weight: 700; }

		.register-card h2 { font-size: 28px; font-weight: 700; margin-bottom: 8px; }
		.register-card .subtitle { color: var(--muted); font-size: 14px; margin-bottom: 28px; }

		.form-group { margin-bottom: 18px; }
		.form-group label { display: block; font-size: 13px; font-weight: 600; margin-bottom: 8px; }

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
		.form-control.has-error { border-color: var(--danger); }
		.form-control.is-valid { border-color: var(--success); }

		.error-msg { color: var(--danger); font-size: 12px; margin-top: 6px; display: block; }

		/* Password strength meter */
		.strength { margin-top: 10px; }
		.strength .bars { display: flex; gap: 6px; }
		.strength .bar {
			flex: 1; height: 5px; border-radius: 4px; background: var(--line);
			transition: background .3s;
		}
		.strength-label { font-size: 12px; margin-top: 6px; font-weight: 500; }

		/* Requirements checklist */
		.pass-reqs { margin-top: 12px; padding: 14px 16px; background: var(--brand-soft); border-radius: 12px; font-size: 12.5px; display: none; }
		.pass-reqs.show { display: block; }
		.pass-reqs .req { display: flex; align-items: center; gap: 8px; padding: 3px 0; color: var(--muted); transition: color .2s; }
		.pass-reqs .req .dot { width: 18px; height: 18px; border-radius: 50%; border: 2px solid var(--line); flex-shrink: 0; display: flex; align-items: center; justify-content: center; transition: all .2s; }
		.pass-reqs .req.ok { color: var(--success); }
		.pass-reqs .req.ok .dot { border-color: var(--success); background: var(--success); }
		.pass-reqs .req.ok .dot svg { display: block; }

		.match-info { font-size: 12.5px; margin-top: 8px; font-weight: 500; }
		.match-info.ok { color: var(--success); }
		.match-info.bad { color: var(--danger); }

		.btn-register {
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
			transition: transform .15s, box-shadow .15s, opacity .2s;
			margin-top: 6px;
		}
		.btn-register:hover:not(:disabled) { transform: translateY(-2px); box-shadow: 0 14px 30px rgba(79, 70, 229, .45); }
		.btn-register:disabled { opacity: .55; cursor: not-allowed; }

		.divider { display: flex; align-items: center; gap: 16px; margin: 26px 0 0; color: var(--muted); font-size: 12px; }
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
			margin-top: 26px;
		}
		.btn-otp:hover { border-color: var(--brand); background: #fafbff; color: var(--brand); }

		.login-link { text-align: center; margin-top: 24px; font-size: 14px; color: var(--muted); }
		.login-link a { color: var(--brand); font-weight: 600; text-decoration: none; }
		.login-link a:hover { text-decoration: underline; }

		/* Pop-in animation */
		.register-card { animation: pop .5s ease both; }
		@keyframes pop {
			from { opacity: 0; transform: translateY(20px); }
			to { opacity: 1; transform: translateY(0); }
		}

		@media (max-width: 900px) {
			.brand-panel { display: none; }
			.register-card .mobile-logo { display: flex; }
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
			<h1>Join the community of readers &amp; writers.</h1>
			<p>Create your free account and unlock a world of books, journals and publications. It takes less than a minute!</p>

			<div class="perks">
				<div class="perk">
					<div class="perk-ico">
						<svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
							<path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
						</svg>
					</div>
					<div><b>Instant access</b><span>Start exploring books right away</span></div>
				</div>
				<div class="perk">
					<div class="perk-ico">
						<svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
							<path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
						</svg>
					</div>
					<div><b>Track your orders</b><span>Manage purchases &amp; reading lists</span></div>
				</div>
				<div class="perk">
					<div class="perk-ico">
						<svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
							<path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.196-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
						</svg>
					</div>
					<div><b>Member rewards</b><span>Earn points on every purchase</span></div>
				</div>
			</div>
		</div>

		<div class="float-book b1"></div>
		<div class="float-book b2"></div>
		<div class="float-book b3"></div>
	</div>

	<!-- Right form panel -->
	<div class="form-panel">
		<div class="register-card">

			<div class="mobile-logo">
				<img src="{{ url('public/images/logo/Loggo3.png') }}" alt="Speech Publications">
				<span>Speech Publications</span>
			</div>

			<h2>Create account</h2>
			<p class="subtitle">Fill in your details to get started.</p>

			@if ($errors->any())
				<div class="alert alert-danger" style="padding:12px 16px;border-radius:10px;font-size:13px;margin-bottom:20px;background:#fef2f2;border:1px solid #fecaca;color:#b91c1c;">
					{{ __('There was a problem with your submission. Please check the fields below.') }}
				</div>
			@endif

			<form method="POST" action="{{ route('register') }}">
				@csrf

				<div class="form-group">
					<label for="name">Full name</label>
					<div class="input-wrap">
						<svg class="field-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
							<path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
						</svg>
						<input id="name" class="form-control {{ $errors->has('name') ? 'has-error' : '' }}" type="text" name="name" value="{{ old('name') }}" placeholder="John Doe" required autofocus autocomplete="name">
					</div>
					@error('name')<span class="error-msg">{{ $message }}</span>@enderror
				</div>

				<div class="form-group">
					<label for="email">Email address</label>
					<div class="input-wrap">
						<svg class="field-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
							<path stroke-linecap="round" stroke-linejoin="round" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
						</svg>
						<input id="email" class="form-control {{ $errors->has('email') ? 'has-error' : '' }}" type="email" name="email" value="{{ old('email') }}" placeholder="you@example.com" required autocomplete="username">
					</div>
					@error('email')<span class="error-msg">{{ $message }}</span>@enderror
				</div>

				<div class="form-group">
					<label for="password">Password</label>
					<div class="input-wrap">
						<svg class="field-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
							<path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
							<path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
						</svg>
						<input id="password" class="form-control {{ $errors->has('password') ? 'has-error' : '' }}" type="password" name="password" placeholder="Min. 8 characters" required autocomplete="new-password">
						<button type="button" class="toggle-pass" onclick="togglePassword('password')">
							<svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
								<path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
								<path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
							</svg>
						</button>
					</div>
					@error('password')<span class="error-msg">{{ $message }}</span>@enderror

					<!-- Strength meter -->
					<div class="strength">
						<div class="bars">
							<div class="bar" id="bar1"></div>
							<div class="bar" id="bar2"></div>
							<div class="bar" id="bar3"></div>
							<div class="bar" id="bar4"></div>
						</div>
						<div class="strength-label" id="strengthLabel"></div>
					</div>

					<!-- Requirements checklist -->
					<div class="pass-reqs" id="passReqs">
						<div class="req" id="reqLength"><span class="dot"><svg width="10" height="10" fill="none" stroke="#fff" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg></span> At least 8 characters</div>
						<div class="req" id="reqUpper"><span class="dot"><svg width="10" height="10" fill="none" stroke="#fff" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg></span> One uppercase letter (A-Z)</div>
						<div class="req" id="reqLower"><span class="dot"><svg width="10" height="10" fill="none" stroke="#fff" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg></span> One lowercase letter (a-z)</div>
						<div class="req" id="reqNumber"><span class="dot"><svg width="10" height="10" fill="none" stroke="#fff" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg></span> One number (0-9)</div>
					</div>
				</div>

				<div class="form-group">
					<label for="password_confirmation">Confirm password</label>
					<div class="input-wrap">
						<svg class="field-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
							<path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
							<path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
						</svg>
						<input id="password_confirmation" class="form-control" type="password" name="password_confirmation" placeholder="Repeat your password" required autocomplete="new-password">
						<button type="button" class="toggle-pass" onclick="togglePassword('password_confirmation')">
							<svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
								<path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
								<path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
							</svg>
						</button>
					</div>
					<div class="match-info" id="matchInfo"></div>
				</div>

				<button type="submit" class="btn-register" id="registerBtn" disabled>Create account</button>
			</form>

			<div class="divider">or</div>

			<a href="{{ route('otp.register') }}" class="btn-otp">Register with OTP</a>

			<p class="login-link">Already have an account? <a href="{{ route('login') }}">Log in</a></p>
		</div>
	</div>
</div>

<script>
	function togglePassword(id) {
		var input = document.getElementById(id);
		input.type = input.type === 'password' ? 'text' : 'password';
	}

	var passwordInput = document.getElementById('password');
	var confirmInput = document.getElementById('password_confirmation');
	var registerBtn = document.getElementById('registerBtn');
	var passReqs = document.getElementById('passReqs');
	var matchInfo = document.getElementById('matchInfo');

	var bars = [document.getElementById('bar1'), document.getElementById('bar2'), document.getElementById('bar3'), document.getElementById('bar4')];
	var strengthLabel = document.getElementById('strengthLabel');

	var reqs = {
		length: document.getElementById('reqLength'),
		upper: document.getElementById('reqUpper'),
		lower: document.getElementById('reqLower'),
		number: document.getElementById('reqNumber')
	};

	function checkPassword(value) {
		return {
			length: value.length >= 8,
			upper: /[A-Z]/.test(value),
			lower: /[a-z]/.test(value),
			number: /[0-9]/.test(value)
		};
	}

	function updateStrength() {
		var value = passwordInput.value;
		var checks = checkPassword(value);
		var score = 0;
		Object.values(checks).forEach(function (ok) { if (ok) score++; });

		reqs.length.classList.toggle('ok', checks.length);
		reqs.upper.classList.toggle('ok', checks.upper);
		reqs.lower.classList.toggle('ok', checks.lower);
		reqs.number.classList.toggle('ok', checks.number);

		if (value.length > 0) passReqs.classList.add('show');
		else passReqs.classList.remove('show');

		var colors = ['', '#ef4444', '#f59e0b', '#84cc16', '#10b981'];
		var labels = ['', 'Weak', 'Fair', 'Good', 'Strong'];
		var textColors = ['', '#ef4444', '#d97706', '#65a30d', '#059669'];

		for (var i = 0; i < 4; i++) {
			bars[i].style.background = i < score ? colors[score] : 'var(--line)';
		}
		strengthLabel.textContent = value.length > 0 ? labels[score] : '';
		strengthLabel.style.color = value.length > 0 ? textColors[score] : '';
	}

	function updateMatch() {
		var p = passwordInput.value;
		var c = confirmInput.value;
		if (c.length === 0) {
			matchInfo.textContent = '';
			confirmInput.classList.remove('is-valid', 'has-error');
			return;
		}
		if (p === c) {
			matchInfo.textContent = '✓ Passwords match';
			matchInfo.className = 'match-info ok';
			confirmInput.classList.add('is-valid');
			confirmInput.classList.remove('has-error');
		} else {
			matchInfo.textContent = '✗ Passwords do not match';
			matchInfo.className = 'match-info bad';
			confirmInput.classList.add('has-error');
			confirmInput.classList.remove('is-valid');
		}
	}

	function updateButton() {
		var checks = checkPassword(passwordInput.value);
		var allPass = Object.values(checks).every(function (ok) { return ok; });
		var match = passwordInput.value.length > 0 && passwordInput.value === confirmInput.value;
		registerBtn.disabled = !(allPass && match);
	}

	passwordInput.addEventListener('input', function () {
		updateStrength();
		updateMatch();
		updateButton();
	});

	confirmInput.addEventListener('input', function () {
		updateMatch();
		updateButton();
	});
</script>

</body>
</html>