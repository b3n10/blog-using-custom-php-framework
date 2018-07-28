const email = document.getElementById('input_email');
const warning = document.getElementById('email_warning');

function validateEmail() {
	const xhr = new XMLHttpRequest();

	xhr.open('GET', `/account/validate-email?email=${email.value}`, true);

	xhr.onload = () => {
		if (xhr.status === 200) {
			warning.innerText = JSON.parse(xhr.responseText) ? 'Email already taken!' : '';
		}
	};

	xhr.send();
}

email.addEventListener('focus', validateEmail, true);
email.addEventListener('change', validateEmail);
email.addEventListener('blur', validateEmail);
