
export const LoginPage = {
        init() {
            const pwdInput = document.getElementById('password');
            if (!pwdInput) return;

            document.querySelector('.btn-pwd-toggle')?.addEventListener('click', () => {
                const isPass = pwdInput.type === 'password';
                pwdInput.type = isPass ? 'text' : 'password';
                const icon = document.getElementById('pwdIcon');
                if (icon) icon.className = isPass ? 'bi bi-eye-slash' : 'bi bi-eye';
            });

            document.querySelector('form[action*="login"]')?.addEventListener('submit', () => {
                const btn = document.querySelector('.btn-login-submit');
                if (btn) {
                    btn.disabled = true;
                    btn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Memproses...';
                }
            });
        },
    };