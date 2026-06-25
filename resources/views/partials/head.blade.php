<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />

<title>{{ $title ?? 'Laravel' }}</title>

<link rel="preconnect" href="https://fonts.bunny.net">
<link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

<!-- Inline theme script to apply dark/light before CSS loads (prevents FOUC) -->
<script>
	(function () {
		try {
			var theme = localStorage.getItem('color-theme');
			if (theme === 'dark') {
				document.documentElement.classList.add('dark');
			} else if (theme === 'light') {
				document.documentElement.classList.remove('dark');
			} else if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
				document.documentElement.classList.add('dark');
			} else {
				document.documentElement.classList.remove('dark');
			}
		} catch (e) {
			// ignore
		}
	})();
</script>

@vite(['resources/css/app.css', 'resources/js/app.js'])
@fluxAppearance
