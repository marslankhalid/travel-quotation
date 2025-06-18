<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Travel Quotation Form</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light py-5">
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="mb-4 p-3 border rounded bg-light">
                <h5>🔐 Get Token</h5>
                <form id="loginForm">
                    <div class="mb-2">
                        <input type="email" name="email" class="form-control" placeholder="Email" value="demo@example.com" required>
                    </div>
                    <div class="mb-2">
                        <input type="password" name="password" class="form-control" placeholder="Password" value="password" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Generate Token</button>
                </form>
                <div id="tokenBox" class="mt-2 text-success d-none">
                    <label class="form-label mt-2">Your Token:</label>
                    <textarea id="tokenOutput" class="form-control" rows="2" readonly></textarea>
                    <button class="btn btn-sm btn-outline-secondary mt-2" onclick="copyToken()">Copy to Clipboard</button>
                </div>
            </div>
            <div id="quoteFormWrapper" class="card d-none">
                <div class="card-header">
                    <h4 class="mb-0">Travel Quotation Form</h4>
                </div>
                <div class="card-body">
                    <form id="quoteForm">
                        <div class="mb-3">
                            <label for="age" class="form-label">Ages (comma-separated)</label>
                            <input type="text" name="age" id="age" class="form-control" placeholder="e.g. 28,35" required>
                        </div>
                        <div class="mb-3">
                            <label for="currency_id" class="form-label">Currency</label>
                            <select name="currency_id" id="currency_id" class="form-select" required>
                                <option value="EUR">EUR</option>
                                <option value="USD">USD</option>
                                <option value="GBP">GBP</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="start_date" class="form-label">Start Date</label>
                            <input type="date" name="start_date" id="start_date" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="end_date" class="form-label">End Date</label>
                            <input type="date" name="end_date" id="end_date" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-success">Get Quotation</button>
                    </form>
                    <div id="responseBox" class="mt-4 alert d-none"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap & JS -->
<script>
	let authToken = null;

	// Disable form inputs on a load
	document.querySelectorAll('#quoteForm input, #quoteForm select, #quoteForm button').forEach(el => {
		el.disabled = true;
	});

	// Token generator
	document.getElementById('loginForm').addEventListener('submit', async function(e) {
		e.preventDefault();
		const formData = new FormData(e.target);
		const res = await fetch('/api/login', {
			method: 'POST',
			headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
			body: JSON.stringify(Object.fromEntries(formData.entries()))
		});

		const data = await res.json();

		if (res.ok) {
			authToken = data.token;
			document.getElementById('loginForm').classList.add('d-none');
			document.getElementById('tokenOutput').value = data.token;
			document.getElementById('tokenBox').classList.remove('d-none');
			document.getElementById('quoteFormWrapper').classList.remove('d-none');

			// Enable all quote form inputs
			document.querySelectorAll('#quoteForm input, #quoteForm select, #quoteForm button').forEach(el => {
				el.disabled = false;
			});

			// Scroll to form
			document.getElementById('quoteFormWrapper').scrollIntoView({ behavior: 'smooth' });
		} else {
			alert(data.error || 'Login failed');
		}
	});

	// Quotation form submission
	document.getElementById('quoteForm').addEventListener('submit', async function (e) {
		e.preventDefault();

		const formData = new FormData(e.target);
		const jsonData = Object.fromEntries(formData.entries());

		const response = await fetch('/api/quotation', {
			method: 'POST',
			headers: {
				'Content-Type': 'application/json',
				'Accept': 'application/json',
				'Authorization': `Bearer ${authToken}`
			},
			body: JSON.stringify(jsonData)
		});

		const result = await response.json();
		const responseBox = document.getElementById('responseBox');

		if (response.ok) {
			responseBox.className = 'mt-4 alert alert-success';
			responseBox.innerHTML = `
            <strong>Quotation Successful!</strong><br>
            Total: ${result.total} ${result.currency_id}<br>
            Quotation ID: ${result.quotation_id}
        `;
		} else {
			responseBox.className = 'mt-4 alert alert-danger';
			responseBox.innerText = result.error || result.message || 'Something went wrong.';
		}

		responseBox.classList.remove('d-none');
	});

	// Copy Token
	function copyToken() {
		const token = document.getElementById('tokenOutput');
		token.select();
		token.setSelectionRange(0, 99999); // For mobile
		document.execCommand('copy');
		alert("Token copied to clipboard!");
	}
</script>
</body>
</html>
