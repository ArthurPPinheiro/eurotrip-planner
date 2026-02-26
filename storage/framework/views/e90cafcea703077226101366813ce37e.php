<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $__env->yieldContent('title', 'EuroTrip Planner'); ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,700;1,400&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --ink: #1a1a2e;
            --paper: #f8f5f0;
            --cream: #ede8e1;
            --gold: #c9a84c;
            --gold-light: #f0d98a;
            --accent: #2d6a4f;
            --accent-light: #52b788;
            --muted: #7a7060;
            --danger: #c1440e;
            --radius: 12px;
            --shadow: 0 4px 24px rgba(26,26,46,0.08);
        }
        body { font-family: 'DM Sans', sans-serif; background: var(--paper); color: var(--ink); min-height: 100vh; }
        a { color: inherit; text-decoration: none; }
        /* NAV */
        nav { background: var(--ink); padding: 0 2rem; display: flex; align-items: center; justify-content: space-between; height: 64px; position: sticky; top: 0; z-index: 100; }
        .nav-brand { font-family: 'Playfair Display', serif; font-size: 1.4rem; color: var(--gold); letter-spacing: 0.02em; }
        .nav-brand span { font-style: italic; color: white; }
        .nav-links { display: flex; align-items: center; gap: 1rem; }
        .nav-links a { color: #ccc; font-size: 0.875rem; padding: 0.4rem 0.8rem; border-radius: 6px; transition: all 0.2s; }
        .nav-links a:hover { color: white; background: rgba(255,255,255,0.1); }
        .nav-user { display: flex; align-items: center; gap: 0.75rem; }
        .avatar { width: 34px; height: 34px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 0.75rem; font-weight: 600; color: white; flex-shrink: 0; }
        .btn-logout { background: none; border: 1px solid rgba(255,255,255,0.2); color: #ccc; padding: 0.35rem 0.8rem; border-radius: 6px; cursor: pointer; font-size: 0.8rem; font-family: inherit; transition: all 0.2s; }
        .btn-logout:hover { border-color: var(--danger); color: var(--danger); }
        /* MAIN */
        main { max-width: 1100px; margin: 0 auto; padding: 2.5rem 1.5rem; }
        /* ALERTS */
        .alert { padding: 0.875rem 1.25rem; border-radius: var(--radius); margin-bottom: 1.5rem; font-size: 0.9rem; }
        .alert-success { background: #d1fae5; color: #065f46; border: 1px solid #6ee7b7; }
        .alert-error { background: #fee2e2; color: #991b1b; border: 1px solid #fca5a5; }
        /* BUTTONS */
        .btn { display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.625rem 1.25rem; border-radius: 8px; font-family: inherit; font-size: 0.9rem; font-weight: 500; cursor: pointer; border: none; transition: all 0.2s; text-decoration: none; }
        .btn-primary { background: var(--ink); color: white; }
        .btn-primary:hover { background: #2d2d4e; transform: translateY(-1px); box-shadow: 0 4px 12px rgba(26,26,46,0.25); }
        .btn-gold { background: var(--gold); color: var(--ink); }
        .btn-gold:hover { background: var(--gold-light); }
        .btn-outline { background: transparent; border: 1.5px solid var(--ink); color: var(--ink); }
        .btn-outline:hover { background: var(--ink); color: white; }
        .btn-sm { padding: 0.4rem 0.875rem; font-size: 0.8rem; }
        .btn-danger { background: var(--danger); color: white; }
        .btn-danger:hover { background: #a3390c; }
        .btn-ghost { background: transparent; color: var(--muted); }
        .btn-ghost:hover { background: var(--cream); color: var(--ink); }
        /* CARDS */
        .card { background: white; border-radius: var(--radius); box-shadow: var(--shadow); overflow: hidden; }
        .card-header { padding: 1.25rem 1.5rem; border-bottom: 1px solid var(--cream); }
        .card-body { padding: 1.5rem; }
        /* FORMS */
        .form-group { margin-bottom: 1.25rem; }
        .form-label { display: block; font-size: 0.85rem; font-weight: 500; margin-bottom: 0.4rem; color: var(--muted); letter-spacing: 0.03em; text-transform: uppercase; }
        .form-control { width: 100%; padding: 0.625rem 0.875rem; border: 1.5px solid var(--cream); border-radius: 8px; font-family: inherit; font-size: 0.9rem; color: var(--ink); background: white; transition: border-color 0.2s; outline: none; }
        .form-control:focus { border-color: var(--gold); box-shadow: 0 0 0 3px rgba(201,168,76,0.12); }
        textarea.form-control { resize: vertical; min-height: 80px; }
        select.form-control { appearance: none; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%237a7060' d='M6 8L1 3h10z'/%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right 0.875rem center; }
        .form-error { font-size: 0.8rem; color: var(--danger); margin-top: 0.3rem; }
        /* GRID */
        .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; }
        .grid-3 { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.25rem; }
        @media (max-width: 768px) { .grid-2, .grid-3 { grid-template-columns: 1fr; } }
        /* MODAL */
        .modal-backdrop { position: fixed; inset: 0; background: rgba(26,26,46,0.5); z-index: 200; display: none; align-items: center; justify-content: center; padding: 1rem; }
        .modal-backdrop.open { display: flex; }
        .modal { background: white; border-radius: 16px; width: 100%; max-width: 500px; max-height: 90vh; overflow-y: auto; box-shadow: 0 20px 60px rgba(0,0,0,0.2); }
        .modal-header { padding: 1.5rem; border-bottom: 1px solid var(--cream); display: flex; align-items: center; justify-content: space-between; }
        .modal-header h3 { font-family: 'Playfair Display', serif; font-size: 1.3rem; }
        .modal-close { background: none; border: none; font-size: 1.4rem; cursor: pointer; color: var(--muted); line-height: 1; }
        .modal-body { padding: 1.5rem; }
        /* HELPERS */
        .flex { display: flex; }
        .flex-center { display: flex; align-items: center; }
        .flex-between { display: flex; align-items: center; justify-content: space-between; }
        .gap-1 { gap: 0.5rem; } .gap-2 { gap: 1rem; } .gap-3 { gap: 1.5rem; }
        .mt-1 { margin-top: 0.5rem; } .mt-2 { margin-top: 1rem; } .mt-3 { margin-top: 1.5rem; }
        .mb-1 { margin-bottom: 0.5rem; } .mb-2 { margin-bottom: 1rem; }
        .text-muted { color: var(--muted); font-size: 0.875rem; }
        .text-sm { font-size: 0.85rem; }
        .badge { display: inline-flex; align-items: center; padding: 0.2rem 0.6rem; border-radius: 20px; font-size: 0.75rem; font-weight: 500; }
        .badge-gold { background: #fef3c7; color: #92400e; }
        .badge-green { background: #d1fae5; color: #065f46; }
        .badge-blue { background: #dbeafe; color: #1e40af; }
        .badge-purple { background: #ede9fe; color: #5b21b6; }
        .page-title { font-family: 'Playfair Display', serif; font-size: 2rem; color: var(--ink); }
        .page-subtitle { color: var(--muted); margin-top: 0.25rem; }
        .divider { border: none; border-top: 1px solid var(--cream); margin: 1.5rem 0; }
        .empty-state { text-align: center; padding: 3rem 1rem; color: var(--muted); }
        .empty-state .emoji { font-size: 3rem; display: block; margin-bottom: 1rem; }
    </style>
    <?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body>
    <nav>
        <a href="<?php echo e(route('trips.index')); ?>" class="nav-brand">Euro<span>Trip</span> ✈</a>
        <?php if(auth()->guard()->check()): ?>
        <div class="nav-links">
            <a href="<?php echo e(route('trips.index')); ?>">My Trips</a>
        </div>
        <div class="nav-user">
            <div class="avatar" style="background: <?php echo e(auth()->user()->avatar_color); ?>"><?php echo e(auth()->user()->initials()); ?></div>
            <span style="color:#ccc; font-size:0.85rem"><?php echo e(auth()->user()->name); ?></span>
            <form method="POST" action="<?php echo e(route('logout')); ?>">
                <?php echo csrf_field(); ?>
                <button type="submit" class="btn-logout">Logout</button>
            </form>
        </div>
        <?php endif; ?>
    </nav>
    <main>
        <?php if(session('success')): ?>
            <div class="alert alert-success"><?php echo e(session('success')); ?></div>
        <?php endif; ?>
        <?php if(session('error') || $errors->any()): ?>
            <div class="alert alert-error">
                <?php if(session('error')): ?> <?php echo e(session('error')); ?>

                <?php else: ?> <?php echo e($errors->first()); ?> <?php endif; ?>
            </div>
        <?php endif; ?>
        <?php echo $__env->yieldContent('content'); ?>
    </main>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        function openModal(id) { document.getElementById(id).classList.add('open'); }
        function closeModal(id) { document.getElementById(id).classList.remove('open'); }
        document.addEventListener('keydown', e => {
            if (e.key === 'Escape') document.querySelectorAll('.modal-backdrop.open').forEach(m => m.classList.remove('open'));
        });
    </script>
    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH /Users/arthurpinheiro/Documents/repos/eurotrip/resources/views/layouts/app.blade.php ENDPATH**/ ?>