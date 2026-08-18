<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<title>OTP Login | Speech Publications</title>

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

		.d-none { display: none !important; }

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

		.login-card { width: 100%; max-width: 440px; animation: pop .5s ease both; }

		@keyframes pop {
			from { opacity: 0; transform: translateY(20px); }
			to { opacity: 1; transform: translateY(0); }
		}

		.login-card .mobile-logo { display: none; align-items: center; gap: 12px; margin-bottom: 28px; }
		.login-card .mobile-logo img { width: 48px; height: 48px; border-radius: 12px; }
		.login-card .mobile-logo span { font-size: 20px; font-weight: 700; }

		.login-card h2 { font-size: 28px; font-weight: 700; margin-bottom: 8px; }
		.login-card .subtitle { color: var(--muted); font-size: 14px; margin-bottom: 32px; }

		.step-panel { animation: fadeIn .4s ease both; }
		@keyframes fadeIn { from { opacity: 0; transform: translateX(16px); } to { opacity: 1; transform: translateX(0); } }

		.form-group { margin-bottom: 22px; }
		.form-group label { display: block; font-size: 13px; font-weight: 600; margin-bottom: 8px; }

		.input-wrap { position: relative; }
		.input-wrap .field-icon {
			position: absolute; left: 16px; top: 50%; transform: translateY(-50%);
			width: 18px; height: 18px; opacity: .45; pointer-events: none;
		}

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
		.form-control.no-icon { padding-left: 16px; }

		.form-hint { font-size: 12px; color: var(--muted); margin-top: 6px; display: block; }

		/* OTP input */
		.otp-field {
			text-align: center;
			font-size: 26px;
			letter-spacing: 14px;
			font-weight: 700;
			padding: 14px 16px;
		}

		.otp-timer { text-align: center; font-size: 13px; font-weight: 600; color: var(--danger); margin: 12px 0; }

		.btn-primary-grad {
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
		}
		.btn-primary-grad:hover:not(:disabled) { transform: translateY(-2px); box-shadow: 0 14px 30px rgba(79, 70, 229, .45); }
		.btn-primary-grad:disabled { opacity: .55; cursor: not-allowed; }

		.btn-ghost {
			width: 100%;
			padding: 13px;
			margin-top: 12px;
			border-radius: 12px;
			font-size: 14px;
			font-weight: 500;
			font-family: 'Poppins', sans-serif;
			border: 1.5px solid var(--line);
			background: #fff;
			color: var(--ink);
			cursor: pointer;
			transition: border-color .2s, background .2s;
		}
		.btn-ghost:hover { border-color: var(--brand); background: #fafbff; color: var(--brand); }

		.btn-link-style {
			background: none; border: none; color: var(--brand);
			font-weight: 600; font-family: 'Poppins', sans-serif;
			cursor: pointer; font-size: 13px; text-decoration: none;
		}
		.btn-link-style:hover { text-decoration: underline; }

		/* Alert */
		.alert-toast {
			padding: 13px 16px; border-radius: 12px; font-size: 13px; font-weight: 500;
			margin-bottom: 18px; display: flex; align-items: center; justify-content: space-between; gap: 10px;
			animation: fadeIn .3s ease both;
		}
		.alert-success { background: #ecfdf5; border: 1px solid #a7f3d0; color: #047857; }
		.alert-danger { background: #fef2f2; border: 1px solid #fecaca; color: #b91c1c; }
		.alert-info { background: #eff6ff; border: 1px solid #bfdbfe; color: #1d4ed8; }
		.alert-toast .close-btn { background: none; border: none; font-size: 18px; cursor: pointer; opacity: .6; color: inherit; }

		.divider { display: flex; align-items: center; gap: 16px; margin: 26px 0; color: var(--muted); font-size: 12px; }
		.divider::before, .divider::after { content: ''; flex: 1; height: 1px; background: var(--line); }

		.btn-password {
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
		.btn-password:hover { border-color: var(--brand); background: #fafbff; color: var(--brand); }

		.register-link { text-align: center; margin-top: 26px; font-size: 14px; color: var(--muted); }
		.register-link a { color: var(--brand); font-weight: 600; text-decoration: none; }
		.register-link a:hover { text-decoration: underline; }

		/* Spinner */
		.spinner { display: inline-block; width: 18px; height: 18px; border: 2.5px solid rgba(255,255,255,.35); border-top-color: #fff; border-radius: 50%; animation: spin .7s linear infinite; vertical-align: middle; }
		@keyframes spin { to { transform: rotate(360deg); } }

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
			<h1>Quick &amp; secure access, without a password.</h1>
			<p>Log in with a one-time password sent straight to your email. No password to remember, no hassle — just secure access.</p>

			<div class="perks">
				<div class="perk">
					<div class="perk-ico">
						<svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
							<path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
						</svg>
					</div>
					<div><b>Passwordless login</b><span>No passwords to remember</span></div>
				</div>
				<div class="perk">
					<div class="perk-ico">
						<svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
							<path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
						</svg>
					</div>
					<div><b>Instant access</b><span>In your inbox within seconds</span></div>
				</div>
				<div class="perk">
					<div class="perk-ico">
						<svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
							<path stroke-linecap="round" stroke-linejoin="round" d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
						</svg>
					</div>
					<div><b>Secure &amp; private</b><span>Codes expire after 5 minutes</span></div>
				</div>
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

			<h2>OTP Login</h2>
			<p class="subtitle">We'll send a one-time code to your email.</p>

			<div id="alertContainer"></div>

			<!-- Email Section -->
			<div id="emailSection" class="step-panel">
				<div class="form-group">
					<label for="email">Email Address</label>
					<div class="input-wrap">
						<svg class="field-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
							<path stroke-linecap="round" stroke-linejoin="round" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
						</svg>
						<input type="email" class="form-control" id="email" placeholder="you@example.com" autocomplete="off">
					</div>
					<span class="form-hint">We'll send you a one-time password (OTP)</span>
				</div>
				<button type="button" id="sendOtpBtn" class="btn-primary-grad">
					<span id="sendOtpText">Send OTP</span>
					<span id="sendOtpSpinner" class="spinner d-none"></span>
				</button>
			</div>

			<!-- OTP Section -->
			<div id="otpSection" class="step-panel d-none">
				<div class="form-group">
					<label for="otp">Enter OTP</label>
					<input type="text" class="form-control otp-field" id="otp" placeholder="000000" maxlength="6" inputmode="numeric" autocomplete="off">
					<span class="form-hint">Check your email for the 6-digit code</span>
				</div>

				<div class="otp-timer" id="otpTimer"></div>
				<div id="otpStatus"></div>

				<button type="button" id="resendOtpBtn" class="btn-link-style d-none mb-2" style="display:block;margin:0 auto 14px;">Resend OTP</button>

				<button type="button" id="verifyOtpBtn" class="btn-primary-grad" disabled>
					<span id="verifyOtpText">Verify OTP</span>
					<span id="verifyOtpSpinner" class="spinner d-none"></span>
				</button>
				<button type="button" id="backBtn" class="btn-ghost">Back to Email</button>
			</div>

			<div class="divider">or</div>

			<a href="{{ route('login') }}" class="btn-password">Login with Password</a>

			<p class="register-link">Don't have an account? <a href="{{ route('otp.register') }}">Register here</a></p>
		</div>
	</div>
</div>

<script>
	const emailInput = document.getElementById('email');
	const otpInput = document.getElementById('otp');
	const sendOtpBtn = document.getElementById('sendOtpBtn');
	const verifyOtpBtn = document.getElementById('verifyOtpBtn');
	const resendOtpBtn = document.getElementById('resendOtpBtn');
	const backBtn = document.getElementById('backBtn');
	const emailSection = document.getElementById('emailSection');
	const otpSection = document.getElementById('otpSection');
	const alertContainer = document.getElementById('alertContainer');
	const otpTimer = document.getElementById('otpTimer');

	let otpExpiryTime = null;
	let timerInterval = null;

	// Send OTP
	sendOtpBtn.addEventListener('click', async () => {
		const email = emailInput.value.trim();

		if (!email) {
			showAlert('Please enter your email', 'danger');
			return;
		}

		if (!isValidEmail(email)) {
			showAlert('Please enter a valid email', 'danger');
			return;
		}

		try {
			sendOtpBtn.disabled = true;
			document.getElementById('sendOtpSpinner').classList.remove('d-none');

			const response = await fetch('{{ route("otp.send") }}', {
				method: 'POST',
				headers: {
					'Content-Type': 'application/json',
					'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
				},
				body: JSON.stringify({ email })
			});

			const data = await response.json();

			if (data.success) {
				showAlert('OTP sent to your email!', 'success');
				emailSection.classList.add('d-none');
				otpSection.classList.remove('d-none');

				// Set expiry time (5 minutes)
				otpExpiryTime = Date.now() + (5 * 60 * 1000);
				startTimer();
			} else {
				showAlert(data.message || 'Failed to send OTP', 'danger');
				sendOtpBtn.disabled = false;
				document.getElementById('sendOtpSpinner').classList.add('d-none');
			}
		} catch (error) {
			showAlert('Error sending OTP. Please try again.', 'danger');
			sendOtpBtn.disabled = false;
			document.getElementById('sendOtpSpinner').classList.add('d-none');
		}
	});

	// Real-time OTP verification
	otpInput.addEventListener('input', (e) => {
		e.target.value = e.target.value.replace(/[^\d]/g, '');
		const otp = e.target.value.trim();

		// Enable verify button only if OTP has 6 digits
		if (otp.length === 6 && /^\d{6}$/.test(otp)) {
			verifyOtpBtn.disabled = false;
			// Auto-verify on 6th digit
			verifyOtp();
		} else {
			verifyOtpBtn.disabled = true;
		}
	});

	// Verify OTP
	async function verifyOtp() {
		const email = emailInput.value.trim();
		const otp = otpInput.value.trim();

		if (otp.length !== 6 || !/^\d{6}$/.test(otp)) {
			return;
		}

		try {
			verifyOtpBtn.disabled = true;
			document.getElementById('verifyOtpSpinner').classList.remove('d-none');

			const response = await fetch('{{ route("otp.verify") }}', {
				method: 'POST',
				headers: {
					'Content-Type': 'application/json',
					'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
				},
				body: JSON.stringify({ email, otp })
			});

			const data = await response.json();

			if (data.success) {
				showAlert('Login successful! Redirecting...', 'success');
				setTimeout(() => {
					window.location.href = data.redirect;
				}, 1500);
			} else {
				showAlert(data.message || 'Invalid OTP', 'danger');
				otpInput.value = '';
				verifyOtpBtn.disabled = true;
				document.getElementById('verifyOtpSpinner').classList.add('d-none');
			}
		} catch (error) {
			showAlert('Error verifying OTP. Please try again.', 'danger');
			verifyOtpBtn.disabled = false;
			document.getElementById('verifyOtpSpinner').classList.add('d-none');
		}
	}

	verifyOtpBtn.addEventListener('click', verifyOtp);

	// Back button
	backBtn.addEventListener('click', () => {
		emailSection.classList.remove('d-none');
		otpSection.classList.add('d-none');
		otpInput.value = '';
		alertContainer.innerHTML = '';
		clearInterval(timerInterval);
		sendOtpBtn.disabled = false;
		document.getElementById('sendOtpSpinner').classList.add('d-none');
	});

	// Resend OTP
	resendOtpBtn.addEventListener('click', async () => {
		sendOtpBtn.disabled = false;
		document.getElementById('sendOtpText').innerText = 'Resend OTP';
		sendOtpBtn.click();
		resendOtpBtn.classList.add('d-none');
	});

	// Timer function
	function startTimer() {
		if (timerInterval) clearInterval(timerInterval);

		timerInterval = setInterval(() => {
			const now = Date.now();
			const remaining = otpExpiryTime - now;

			if (remaining <= 0) {
				clearInterval(timerInterval);
				otpTimer.innerText = 'OTP Expired';
				otpInput.disabled = true;
				verifyOtpBtn.disabled = true;
				resendOtpBtn.classList.remove('d-none');
				return;
			}

			const minutes = Math.floor(remaining / 60000);
			const seconds = Math.floor((remaining % 60000) / 1000);
			otpTimer.innerText = `OTP expires in ${minutes}:${seconds.toString().padStart(2, '0')}`;
		}, 1000);
	}

	// Alert function
	function showAlert(message, type) {
		alertContainer.innerHTML = `
			<div class="alert-toast alert-${type}" role="alert">
				<span>${message}</span>
				<button type="button" class="close-btn" onclick="this.parentElement.remove()">×</button>
			</div>
		`;

		// Auto-dismiss after 5 seconds
		setTimeout(() => {
			const alert = alertContainer.querySelector('.alert-toast');
			if (alert) {
				alert.remove();
			}
		}, 5000);
	}

	// Email validation
	function isValidEmail(email) {
		const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
		return emailRegex.test(email);
	}

	// Focus on OTP input when section appears
	const observer = new MutationObserver(() => {
		if (!otpSection.classList.contains('d-none')) {
			otpInput.focus();
		}
	});

	observer.observe(otpSection, { attributes: true });
</script>

</body>
</html>