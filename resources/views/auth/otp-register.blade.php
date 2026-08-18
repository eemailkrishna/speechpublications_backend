<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<meta name="csrf-token" content="{{ csrf_token() }}">
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

		.register-card { width: 100%; max-width: 500px; animation: pop .5s ease both; }

		@keyframes pop {
			from { opacity: 0; transform: translateY(20px); }
			to { opacity: 1; transform: translateY(0); }
		}

		.register-card .mobile-logo { display: none; align-items: center; gap: 12px; margin-bottom: 24px; }
		.register-card .mobile-logo img { width: 48px; height: 48px; border-radius: 12px; }
		.register-card .mobile-logo span { font-size: 20px; font-weight: 700; }

		.register-card h2 { font-size: 26px; font-weight: 700; margin-bottom: 8px; }
		.register-card .subtitle { color: var(--muted); font-size: 14px; margin-bottom: 28px; }

		/* Stepper */
		.steps { display: flex; align-items: center; justify-content: center; gap: 0; margin-bottom: 30px; }
		.step {
			display: flex; align-items: center; gap: 10px;
			color: var(--muted); font-size: 12.5px; font-weight: 600;
		}
		.step .step-num {
			width: 34px; height: 34px; border-radius: 50%;
			display: flex; align-items: center; justify-content: center;
			background: #fff; border: 2px solid var(--line);
			color: var(--muted); font-size: 14px; font-weight: 700;
			transition: all .3s;
		}
		.step.active { color: var(--brand); }
		.step.active .step-num { border-color: var(--brand); background: var(--brand); color: #fff; box-shadow: 0 6px 16px rgba(79,70,229,.35); }
		.step.done .step-num { border-color: var(--success); background: var(--success); color: #fff; }
		.step-line { width: 46px; height: 2px; background: var(--line); margin: 0 12px; border-radius: 2px; }
		.step-line.done { background: var(--success); }

		/* Steps content */
		.step-panel { animation: fadeIn .4s ease both; }
		@keyframes fadeIn { from { opacity: 0; transform: translateX(16px); } to { opacity: 1; transform: translateX(0); } }

		.step-title { font-size: 18px; font-weight: 700; margin-bottom: 6px; }
		.step-hint { color: var(--muted); font-size: 13px; margin-bottom: 22px; }

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
		.form-control.is-invalid { border-color: var(--danger); }
		.form-control.is-valid { border-color: var(--success); }
		.form-control.no-icon { padding-left: 16px; }
		textarea.form-control { resize: vertical; }

		.form-hint { font-size: 12px; color: var(--muted); margin-top: 6px; display: block; }
		.form-hint.ok { color: var(--success); }
		.form-hint.bad { color: var(--danger); }

		/* OTP input */
		.otp-field {
			text-align: center;
			font-size: 26px;
			letter-spacing: 14px;
			font-weight: 700;
			padding: 14px 16px;
		}

		.otp-timer {
			text-align: center; font-size: 13px; font-weight: 600;
			color: var(--danger); margin: 12px 0;
		}

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

		/* Photo preview */
		.photo-preview { margin-top: 10px; }
		.photo-preview img { width: 84px; height: 84px; border-radius: 50%; object-fit: cover; border: 3px solid var(--brand); box-shadow: 0 8px 20px rgba(79,70,229,.3); }

		/* Password match */
		.match-info { font-size: 12.5px; margin-top: 8px; font-weight: 500; }
		.match-info.ok { color: var(--success); }
		.match-info.bad { color: var(--danger); }

		.login-link { text-align: center; margin-top: 26px; font-size: 14px; color: var(--muted); }
		.login-link a { color: var(--brand); font-weight: 600; text-decoration: none; }
		.login-link a:hover { text-decoration: underline; }

		/* Spinner */
		.spinner { display: inline-block; width: 18px; height: 18px; border: 2.5px solid rgba(255,255,255,.35); border-top-color: #fff; border-radius: 50%; animation: spin .7s linear infinite; vertical-align: middle; }
		@keyframes spin { to { transform: rotate(360deg); } }

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
			<p>Create your free account in three easy steps and unlock a world of books, journals and publications.</p>

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
			<p class="subtitle">Sign up in three simple steps.</p>

			<!-- Stepper -->
			<div class="steps">
				<div class="step active" id="stepBadge1">
					<div class="step-num">1</div>
					<span>Email</span>
				</div>
				<div class="step-line" id="line1"></div>
				<div class="step" id="stepBadge2">
					<div class="step-num">2</div>
					<span>OTP</span>
				</div>
				<div class="step-line" id="line2"></div>
				<div class="step" id="stepBadge3">
					<div class="step-num">3</div>
					<span>Profile</span>
				</div>
			</div>

			<div id="alertContainer"></div>

			<!-- Step 1: Email -->
			<div id="step1" class="step-panel">
				<div class="step-title">Verify your email</div>
				<p class="step-hint">We'll send you a verification code to get started.</p>
				<div class="form-group">
					<label for="emailVerify">Email Address</label>
					<div class="input-wrap">
						<svg class="field-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
							<path stroke-linecap="round" stroke-linejoin="round" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
						</svg>
						<input type="email" class="form-control" id="emailVerify" placeholder="you@example.com" autocomplete="off">
					</div>
				</div>
				<button type="button" id="sendOtpBtn" class="btn-primary-grad">
					<span id="sendOtpText">Send OTP</span>
					<span id="sendOtpSpinner" class="spinner d-none" hidden></span>
				</button>
			</div>

			<!-- Step 2: OTP -->
			<div id="step2" class="step-panel d-none">
				<div class="step-title">Enter verification code</div>
				<p class="step-hint">Check your email for the 6-digit code we just sent.</p>
				<div class="form-group">
					<input type="text" class="form-control otp-field" id="otp" placeholder="000000" maxlength="6" inputmode="numeric" autocomplete="off">
				</div>
				<div class="otp-timer" id="otpTimer"></div>
				<button type="button" id="resendOtpBtn" class="btn-link-style d-none mb-2" style="display:block;margin:0 auto 14px;">Resend OTP</button>
				<button type="button" id="verifyOtpBtn" class="btn-primary-grad" disabled>
					<span id="verifyOtpText">Verify OTP</span>
					<span id="verifyOtpSpinner" class="spinner d-none"></span>
				</button>
				<button type="button" id="backToEmailBtn" class="btn-ghost">Back to Email</button>
			</div>

			<!-- Step 3: Complete Profile -->
			<div id="step3" class="step-panel d-none">
				<div class="step-title">Complete your profile</div>
				<p class="step-hint">Tell us a bit about yourself.</p>

				<div class="form-group">
					<label for="fullName">Full Name *</label>
					<div class="input-wrap">
						<svg class="field-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
							<path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
						</svg>
						<input type="text" class="form-control" id="fullName" placeholder="Enter your full name" minlength="2" maxlength="50" autocomplete="off">
					</div>
				</div>

				<input type="email" name="email" id="email" class="form-control no-icon d-none" placeholder="Enter your email" value="">

				<div class="form-group">
					<label for="username">Username *</label>
					<div class="input-wrap">
						<svg class="field-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
							<path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
						</svg>
						<input type="text" class="form-control" id="username" placeholder="lowercase, letters, numbers, underscore" minlength="3" maxlength="30" autocomplete="off">
					</div>
					<span class="form-hint" id="usernameHint"></span>
				</div>

				<div class="form-group">
					<label for="dob">Date of Birth *</label>
					<div class="input-wrap">
						<input type="date" class="form-control no-icon" id="dob">
					</div>
				</div>

				<div class="form-group">
					<label for="gender">Gender *</label>
					<div class="input-wrap">
						<select class="form-control no-icon" id="gender">
							<option value="">Select gender</option>
							<option value="male">Male</option>
							<option value="female">Female</option>
							<option value="other">Other</option>
						</select>
					</div>
				</div>

				<div class="form-group">
					<label for="bio">Bio (Optional)</label>
					<textarea class="form-control no-icon" id="bio" placeholder="Tell us about yourself (max 150 characters)" maxlength="150" rows="3"></textarea>
					<span class="form-hint" id="bioCount">0/150</span>
				</div>

				<div class="form-group">
					<label for="profilePhoto">Profile Photo (Optional)</label>
					<input type="file" class="form-control no-icon" id="profilePhoto" accept="image/jpeg,image/png,image/jpg,image/gif">
					<span class="form-hint">Max 2MB, supported: JPEG, PNG, GIF</span>
					<div id="photoPreview" class="photo-preview"></div>
				</div>

				<div class="form-group">
					<label for="password">Password *</label>
					<div class="input-wrap">
						<svg class="field-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
							<path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
							<path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
						</svg>
						<input type="password" class="form-control" id="password" placeholder="At least 8 characters" autocomplete="new-password">
						<button type="button" class="toggle-pass" onclick="togglePassword('password')">
							<svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
								<path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
								<path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
							</svg>
						</button>
					</div>
					<span class="form-hint">Password must be at least 8 characters long</span>
				</div>

				<div class="form-group">
					<label for="confirmPassword">Confirm Password *</label>
					<div class="input-wrap">
						<svg class="field-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
							<path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
							<path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
						</svg>
						<input type="password" class="form-control" id="confirmPassword" placeholder="Confirm your password" autocomplete="new-password">
						<button type="button" class="toggle-pass" onclick="togglePassword('confirmPassword')">
							<svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
								<path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
								<path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
							</svg>
						</button>
					</div>
					<div class="match-info" id="passwordMatchInfo"></div>
				</div>

				<button type="button" id="completeProfileBtn" class="btn-primary-grad">
					<span id="completeProfileText">Create Account</span>
					<span id="completeProfileSpinner" class="spinner d-none"></span>
				</button>
				<button type="button" id="backToOtpBtn" class="btn-ghost">Back to OTP</button>
			</div>

			<p class="login-link">Already have an account? <a href="{{ route('login') }}">Login here</a></p>
		</div>
	</div>
</div>

<script>
	function togglePassword(id) {
		var input = document.getElementById(id);
		input.type = input.type === 'password' ? 'text' : 'password';
	}

	const emailInput = document.getElementById('emailVerify');
	const otpInput = document.getElementById('otp');

	// Step 3 fields
	const fullNameInput = document.getElementById('fullName');
	const usernameInput = document.getElementById('username');
	const dobInput = document.getElementById('dob');
	const genderInput = document.getElementById('gender');
	const bioInput = document.getElementById('bio');
	const profilePhotoInput = document.getElementById('profilePhoto');
	const passwordInput = document.getElementById('password');
	const confirmPasswordInput = document.getElementById('confirmPassword');

	const sendOtpBtn = document.getElementById('sendOtpBtn');
	const verifyOtpBtn = document.getElementById('verifyOtpBtn');
	const resendOtpBtn = document.getElementById('resendOtpBtn');
	const completeProfileBtn = document.getElementById('completeProfileBtn');

	const step1 = document.getElementById('step1');
	const step2 = document.getElementById('step2');
	const step3 = document.getElementById('step3');

	const alertContainer = document.getElementById('alertContainer');
	const otpTimer = document.getElementById('otpTimer');
	const passwordMatchInfo = document.getElementById('passwordMatchInfo');

	let otpExpiryTime = null;
	let timerInterval = null;
	let tempToken = null;

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

			const response = await fetch('{{ route("otp.register.send") }}', {
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
				step1.classList.add('d-none');
				step2.classList.remove('d-none');
				updateSteps(2);

				otpExpiryTime = Date.now() + (5 * 60 * 1000);
				startTimer();
				otpInput.focus();
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
		if (otp.length === 6 && /^\d{6}$/.test(otp)) {
			verifyOtpBtn.disabled = false;
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

			const response = await fetch('{{ route("otp.register.verify") }}', {
				method: 'POST',
				headers: {
					'Content-Type': 'application/json',
					'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
				},
				body: JSON.stringify({ email, otp })
			});

			const data = await response.json();

			if (data.success) {
				tempToken = data.temp_token;
				showAlert('OTP verified! Please complete your profile', 'success');
				step2.classList.add('d-none');
				step3.classList.remove('d-none');
				updateSteps(3);
				// Auto-fill email in step 3
				document.getElementById('email').value = emailInput.value.trim();
				fullNameInput.focus();
				clearInterval(timerInterval);
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

	// Back buttons
	document.getElementById('backToEmailBtn').addEventListener('click', () => {
		step2.classList.add('d-none');
		step1.classList.remove('d-none');
		updateSteps(1);
		otpInput.value = '';
		alertContainer.innerHTML = '';
		clearInterval(timerInterval);
		sendOtpBtn.disabled = false;
		document.getElementById('sendOtpSpinner').classList.add('d-none');
	});

	document.getElementById('backToOtpBtn').addEventListener('click', () => {
		step3.classList.add('d-none');
		step2.classList.remove('d-none');
		updateSteps(2);
		resetStep3Form();
		alertContainer.innerHTML = '';
		otpInput.focus();
	});

	// Resend OTP
	resendOtpBtn.addEventListener('click', () => {
		sendOtpBtn.disabled = false;
		sendOtpBtn.click();
		resendOtpBtn.classList.add('d-none');
	});

	// Username validation - real-time
	usernameInput.addEventListener('input', (e) => {
		const username = e.target.value.toLowerCase();
		const regex = /^[a-z0-9_]*$/;
		const hint = document.getElementById('usernameHint');

		if (username && !regex.test(username)) {
			hint.innerHTML = '<span class="form-hint bad">✗ Only lowercase letters, numbers, and underscore allowed</span>';
			hint.className = 'form-hint bad';
			usernameInput.classList.add('is-invalid');
			usernameInput.classList.remove('is-valid');
		} else if (username && username.length < 3) {
			hint.innerHTML = '⚠ Minimum 3 characters';
			hint.className = 'form-hint';
			usernameInput.classList.remove('is-invalid');
			usernameInput.classList.remove('is-valid');
		} else if (username) {
			hint.innerHTML = '✓ Valid username';
			hint.className = 'form-hint ok';
			usernameInput.classList.remove('is-invalid');
			usernameInput.classList.add('is-valid');
		} else {
			hint.innerHTML = '';
			hint.className = 'form-hint';
		}

		validateForm();
	});

	// Bio counter
	bioInput.addEventListener('input', (e) => {
		const count = e.target.value.length;
		document.getElementById('bioCount').textContent = count + '/150';
		validateForm();
	});

	// Profile photo preview
	profilePhotoInput.addEventListener('change', (e) => {
		const file = e.target.files[0];
		const preview = document.getElementById('photoPreview');
		preview.innerHTML = '';

		if (file) {
			if (file.size > 2 * 1024 * 1024) {
				showAlert('File size must be less than 2MB', 'danger');
				profilePhotoInput.value = '';
				return;
			}

			const reader = new FileReader();
			reader.onload = (event) => {
				const img = document.createElement('img');
				img.src = event.target.result;
				img.style.maxWidth = '100px';
				img.style.marginTop = '10px';
				img.style.borderRadius = '50%';
				preview.appendChild(img);
			};
			reader.readAsDataURL(file);
		}
	});

	// Password matching
	confirmPasswordInput.addEventListener('input', () => {
		validatePasswordMatch();
		validateForm();
	});

	passwordInput.addEventListener('input', () => {
		if (confirmPasswordInput.value) {
			validatePasswordMatch();
		}
		validateForm();
	});

	function validatePasswordMatch() {
		const password = passwordInput.value;
		const confirmPassword = confirmPasswordInput.value;

		if (!confirmPassword) {
			passwordMatchInfo.innerHTML = '';
			return;
		}

		if (password === confirmPassword && password.length >= 8) {
			passwordMatchInfo.innerHTML = '<span class="match-info ok">✓ Passwords match</span>';
			passwordMatchInfo.className = 'match-info ok';
			confirmPasswordInput.classList.add('is-valid');
			confirmPasswordInput.classList.remove('is-invalid');
		} else if (password !== confirmPassword) {
			passwordMatchInfo.innerHTML = '<span class="match-info bad">✗ Passwords do not match</span>';
			passwordMatchInfo.className = 'match-info bad';
			confirmPasswordInput.classList.add('is-invalid');
			confirmPasswordInput.classList.remove('is-valid');
		} else {
			passwordMatchInfo.innerHTML = '<span class="match-info bad">⚠ Password must be at least 8 characters</span>';
			passwordMatchInfo.className = 'match-info bad';
		}
	}

	function validateForm() {
		// All validations removed - button always enabled
	}

	// Complete Profile
	completeProfileBtn.addEventListener('click', async () => {
		// Final validation
		const fullName = fullNameInput.value.trim();
		const username = usernameInput.value.trim().toLowerCase();
		const dob = dobInput.value;
		const gender = genderInput.value;
		const bio = bioInput.value.trim();
		const password = passwordInput.value;
		const confirmPassword = confirmPasswordInput.value;

		if (!fullName || fullName.length < 2) {
			showAlert('Please enter your full name (at least 2 characters)', 'danger');
			return;
		}

		if (!username || username.length < 3) {
			showAlert('Username must be at least 3 characters', 'danger');
			return;
		}

		if (!/^[a-z0-9_]+$/.test(username)) {
			showAlert('Username can only contain lowercase letters, numbers, and underscore', 'danger');
			return;
		}

		if (!dob) {
			showAlert('Please select your date of birth', 'danger');
			return;
		}

		if (!gender) {
			showAlert('Please select your gender', 'danger');
			return;
		}

		if (!password || password.length < 8) {
			showAlert('Password must be at least 8 characters', 'danger');
			return;
		}

		if (password !== confirmPassword) {
			showAlert('Passwords do not match', 'danger');
			return;
		}

		// Create FormData for file upload
		const formData = new FormData();
		const email = document.getElementById('email').value.trim();
		formData.append('temp_token', tempToken);
		formData.append('name', fullName);
		formData.append('username', username);
		formData.append('email', email);
		formData.append('dob', dob);
		formData.append('gender', gender);
		formData.append('bio', bio);
		formData.append('password', password);
		formData.append('password_confirmation', confirmPassword);

		if (profilePhotoInput.files[0]) {
			formData.append('profile_photo', profilePhotoInput.files[0]);
		}

		try {
			completeProfileBtn.disabled = true;
			document.getElementById('completeProfileSpinner').classList.remove('d-none');

			const response = await fetch('{{ route("otp.register.complete") }}', {
				method: 'POST',
				headers: {
					'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
				},
				body: formData
			});

			const data = await response.json();

			if (data.success) {
				showAlert('Account created successfully! Redirecting...', 'success');
				setTimeout(() => {
					window.location.href = data.redirect;
				}, 1500);
			} else {
				showAlert(data.message || 'Error creating account', 'danger');
				completeProfileBtn.disabled = false;
				document.getElementById('completeProfileSpinner').classList.add('d-none');
			}
		} catch (error) {
			showAlert('Error creating account. Please try again.', 'danger');
			completeProfileBtn.disabled = false;
			document.getElementById('completeProfileSpinner').classList.add('d-none');
		}
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

		setTimeout(() => {
			const alert = alertContainer.querySelector('.alert-toast');
			if (alert) {
				alert.remove();
			}
		}, 5000);
	}

	// Reset step 3 form
	function resetStep3Form() {
		fullNameInput.value = '';
		usernameInput.value = '';
		dobInput.value = '';
		genderInput.value = '';
		bioInput.value = '';
		profilePhotoInput.value = '';
		passwordInput.value = '';
		confirmPasswordInput.value = '';
		passwordMatchInfo.innerHTML = '';
		document.getElementById('usernameHint').innerHTML = '';
		document.getElementById('photoPreview').innerHTML = '';
		document.getElementById('bioCount').textContent = '0/150';
		usernameInput.classList.remove('is-invalid');
		usernameInput.classList.remove('is-valid');
		confirmPasswordInput.classList.remove('is-invalid');
		confirmPasswordInput.classList.remove('is-valid');
	}

	// Email validation
	function isValidEmail(email) {
		const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
		return emailRegex.test(email);
	}

	// Stepper progress
	function updateSteps(current) {
		const badges = [document.getElementById('stepBadge1'), document.getElementById('stepBadge2'), document.getElementById('stepBadge3')];
		const lines = [document.getElementById('line1'), document.getElementById('line2')];

		badges.forEach((b, i) => {
			b.classList.remove('active', 'done');
			if (i + 1 === current) b.classList.add('active');
			else if (i + 1 < current) b.classList.add('done');
		});
		lines.forEach((l, i) => {
			l.classList.toggle('done', i + 1 < current);
		});
	}

	// Focus on OTP input when section appears
	const observer = new MutationObserver(() => {
		if (!step2.classList.contains('d-none')) {
			otpInput.focus();
		}
	});

	observer.observe(step2, { attributes: true });
</script>

</body>
</html>