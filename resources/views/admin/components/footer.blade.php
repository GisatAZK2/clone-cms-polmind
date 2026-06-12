<!-- Footer -->
<style>
    .admin-footer {
        background-color: var(--footer-bg, #ffffff);
        color: var(--footer-text, #333333);
        border-top: 1px solid var(--footer-border, #e0e0e0);
        padding: 12px 20px;
        transition: background-color 0.3s ease, color 0.3s ease;
    }

    .footer-content p {
        margin: 0;
        font-size: 14px;
    }

    /* Dark Mode Override */
    [data-theme="dark"] .admin-footer {
        --footer-bg: #2d2d2d;
        --footer-text: #b0b0b0;
        --footer-border: #444444;
    }
</style>

<footer class="admin-footer">
    <div class="footer-content">
        <p>&copy; {{ date('Y') }} CMS POLMIND. All rights reserved.</p>
    </div>
</footer>