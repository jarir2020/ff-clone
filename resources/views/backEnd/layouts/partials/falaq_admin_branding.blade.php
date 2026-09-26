<style>
    :root {
        --falaq-green: #179d55;
        --falaq-green-dark: #107340;
        --falaq-ink: #173b29;
    }

    .navbar-custom,
    .left-side-menu {
        border-color: rgba(23, 157, 85, .16);
    }

    .navbar-custom .logo-lg img,
    .navbar-custom .logo-sm img {
        display: none;
    }

    .navbar-custom .logo-lg::after {
        content: 'Falaq Food';
        color: #fff;
        font-family: Georgia, 'Times New Roman', serif;
        font-size: 21px;
        font-weight: 700;
        letter-spacing: -.25px;
        line-height: 50px;
    }

    .navbar-custom .logo-sm::after {
        content: 'F';
        color: #fff;
        font-family: Georgia, 'Times New Roman', serif;
        font-size: 25px;
        font-weight: 700;
        line-height: 50px;
    }

    #side-menu > li > a:hover,
    #side-menu > li > a:focus,
    #side-menu > li.mm-active > a {
        color: var(--falaq-green-dark);
    }

    #side-menu > li > a:hover i,
    #side-menu > li > a:focus i,
    #side-menu > li.mm-active > a i {
        color: var(--falaq-green);
    }

    .falaq-admin-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        margin-left: 8px;
        padding: 4px 9px;
        border: 1px solid rgba(23, 157, 85, .22);
        border-radius: 999px;
        color: var(--falaq-green-dark);
        background: #f0fdf4;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: .35px;
        text-transform: uppercase;
    }

    .falaq-admin-badge::before {
        content: '';
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: var(--falaq-green);
    }
</style>
