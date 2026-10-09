{{-- تنسيقات صفحات المصادقة (الدخول، نسيت كلمة المرور، إعادة التعيين) --}}
@push('styles')
<style>
.auth-page {
    min-height: calc(100vh - 72px);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 3rem 1rem;
    background: linear-gradient(135deg, var(--color-bg) 0%, var(--color-bg-2) 100%);
}

.auth-card {
    background: var(--color-white);
    border-radius: var(--radius-lg);
    padding: 2.5rem;
    width: 100%;
    max-width: 440px;
    box-shadow: var(--shadow-lg);
    border-top: 4px solid var(--color-gold);
}

.auth-header { text-align: center; margin-bottom: 2rem; }

.auth-logo {
    font-size: 1.75rem;
    font-weight: 700;
    color: var(--color-gold);
    margin-bottom: .5rem;
}

.auth-logo span { color: var(--color-brown); }

.auth-header h2 {
    font-size: 1.25rem;
    color: var(--color-brown);
    font-weight: 600;
}

.auth-header p {
    color: var(--color-text-muted);
    font-size: .9rem;
    margin-top: .25rem;
}

/* ─── Form Fields ─── */
.form-group { margin-bottom: 1.25rem; }

.form-label {
    display: block;
    font-size: .875rem;
    font-weight: 600;
    color: var(--color-brown);
    margin-bottom: .4rem;
}

.form-input {
    width: 100%;
    padding: .75rem 1rem;
    border: 1.5px solid var(--color-border);
    border-radius: var(--radius);
    font-family: var(--font-main);
    font-size: .9rem;
    color: var(--color-text);
    background: var(--color-bg);
    transition: var(--transition);
    direction: rtl;
}

.form-input:focus {
    outline: none;
    border-color: var(--color-gold);
    background: var(--color-white);
    box-shadow: 0 0 0 3px rgba(182,138,74,.15);
}

.form-input.is-invalid { border-color: #DC2626; }

.invalid-feedback {
    color: #DC2626;
    font-size: .8rem;
    margin-top: .3rem;
    display: flex;
    align-items: center;
    gap: .25rem;
}

/* ─── Remember + Forgot ─── */
.form-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 1.5rem;
    font-size: .875rem;
}

.form-check {
    display: flex;
    align-items: center;
    gap: .4rem;
    cursor: pointer;
    color: var(--color-text-muted);
}

.form-check input { accent-color: var(--color-gold); }

.forgot-link {
    color: var(--color-gold);
    font-weight: 500;
}

.forgot-link:hover { text-decoration: underline; }

/* ─── Submit Button ─── */
.btn-submit {
    width: 100%;
    padding: .875rem;
    background: var(--color-brown);
    color: var(--color-gold);
    border: none;
    border-radius: var(--radius);
    font-family: var(--font-main);
    font-size: 1rem;
    font-weight: 700;
    cursor: pointer;
    transition: var(--transition);
    letter-spacing: .3px;
}

.btn-submit:hover {
    background: var(--color-gold);
    color: var(--color-brown);
    transform: translateY(-1px);
    box-shadow: var(--shadow-md);
}

/* ─── Divider ─── */
.auth-divider {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin: 1.5rem 0;
    color: var(--color-text-muted);
    font-size: .8rem;
}

.auth-divider::before,
.auth-divider::after {
    content: '';
    flex: 1;
    height: 1px;
    background: var(--color-border);
}

/* ─── Footer Link ─── */
.auth-footer {
    text-align: center;
    color: var(--color-text-muted);
    font-size: .875rem;
    margin-top: 1.5rem;
}

.auth-footer a { color: var(--color-gold); font-weight: 600; }
.auth-footer a:hover { text-decoration: underline; }
</style>
@endpush
