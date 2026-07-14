# TailAdmin-Style Redesign Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Redesign all SupermarketKu Blade views to match the TailAdmin (free version) visual design language using existing Tailwind CSS + Alpine.js stack.

**Architecture:** Create reusable TailAdmin-style Blade components (card, table, button, badge, alert, page-header, input-group), then rebuild layout (sidebar with grouped menus + header with avatar dropdown), then refactor all 60+ pages to use new components. Keep all backend logic untouched.

**Tech Stack:** Laravel 13 + Blade + Tailwind CSS 3 + Alpine.js 3 + Chart.js 4 + Vite 8

## Global Constraints

- No backend changes (controllers, models, routes, service classes) — only Blade views + CSS + JS
- No npm packages to install — use existing Tailwind + Alpine
- Keep all existing functionality (POS cart, payment methods, dark mode, search, etc.)
- Responsive (mobile sidebar toggle, proper grid breakpoints)
- Dark mode `class` strategy (already implemented, keep)
- Font: Figtree (already imported)
- All text in Bahasa Indonesia

## File Structure Map

### New Files
- `tailwind.config.js` — update with extended colors (existing, modified)
- `resources/views/components/card.blade.php`
- `resources/views/components/table.blade.php`
- `resources/views/components/button.blade.php`
- `resources/views/components/badge.blade.php`
- `resources/views/components/alert.blade.php`
- `resources/views/components/page-header.blade.php`
- `resources/views/components/input-group.blade.php`

### Modified Files (Layout)
- `resources/views/layouts/app.blade.php` — new header with avatar dropdown + notification bell
- `resources/views/layouts/sidebar.blade.php` — grouped menus with SVG icons, expandable submenus
- `resources/views/layouts/guest.blade.php` — TailAdmin-style auth layout

### Modified Files (Pages)
- `resources/views/dashboard.blade.php`
- `resources/views/auth/*.blade.php` (6 files)
- `resources/views/products/*.blade.php` (index, create, edit — 3 files)
- `resources/views/categories/*.blade.php` (3 files)
- `resources/views/suppliers/*.blade.php` (3 files)
- `resources/views/customers/*.blade.php` (3 files)
- `resources/views/users/*.blade.php` (3 files)
- `resources/views/promotions/*.blade.php` (3 files)
- `resources/views/purchases/*.blade.php` (index, create, show — 3 files)
- `resources/views/transactions/*.blade.php` (index, show, receipt — 3 files)
- `resources/views/pos/*.blade.php` (index, checkout — 2 files)
- `resources/views/stock-opname/index.blade.php`
- `resources/views/stock-movements/index.blade.php`
- `resources/views/notifications/index.blade.php`
- `resources/views/reports/sales.blade.php`
- `resources/views/profile/edit.blade.php`
- `resources/views/profile/partials/*.blade.php` (3 files)

### Deleted Files
- `resources/views/layouts/navigation.blade.php` — obsolete Breeze top nav (no longer referenced anywhere)

---

### Task 1: Tailwind Config + Design Tokens

**Files:**
- Modify: `tailwind.config.js`
- Modify: `resources/css/app.css`

**Interfaces:**
- Consumes: nothing
- Produces: `tailwind.config.js` with extended colors matching TailAdmin palette + CSS utility classes

- [ ] **Step 1: Update tailwind.config.js**

Replace the minimal config with an extended one that adds TailAdmin's color palette and design tokens:

```javascript
import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',
    content: [
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './resources/**/*.vue',
    ],
    theme: {
        extend: {
            colors: {
                primary: {
                    50: '#eff6ff',
                    100: '#dbeafe',
                    200: '#bfdbfe',
                    300: '#93c5fd',
                    400: '#60a5fa',
                    500: '#3b82f6',
                    600: '#2563eb',
                    700: '#1d4ed8',
                    800: '#1e40af',
                    900: '#1e3a8a',
                },
            },
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
        },
    },
    plugins: [forms],
};
```

Note: `dark:` colors from `gray.900` etc. are already available in Tailwind's default palette and work perfectly for the dark mode cards/dropdowns in TailAdmin style.

- [ ] **Step 2: Build to verify**

Run: `npm run build`
Expected: Compiles without errors

- [ ] **Step 3: Commit**

```bash
git add tailwind.config.js
git commit -m "feat(tailwind): add extended design tokens with primary color palette"
```

---

### Task 2: Blade Component Library

**Files:**
- Create: `resources/views/components/card.blade.php`
- Create: `resources/views/components/table.blade.php`
- Create: `resources/views/components/button.blade.php`
- Create: `resources/views/components/badge.blade.php`
- Create: `resources/views/components/alert.blade.php`
- Create: `resources/views/components/page-header.blade.php`
- Create: `resources/views/components/input-group.blade.php`

**Interfaces:**
- Consumes: nothing (standalone components)
- Produces: 7 reusable Blade components used by all pages

- [ ] **Step 1: Create x-card component**

`resources/views/components/card.blade.php` — TailAdmin-style card container with optional header/footer slots:

```blade
@props(['padding' => 'p-5 sm:p-6'])

<div {{ $attributes->merge(['class' => "bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 shadow-sm $padding"]) }}>
    @if(isset($header))
        <div class="flex items-center justify-between mb-5">
            {{ $header }}
        </div>
    @endif
    {{ $slot }}
    @if(isset($footer))
        <div class="mt-5 pt-4 border-t border-gray-100 dark:border-gray-800">
            {{ $footer }}
        </div>
    @endif
</div>
```

- [ ] **Step 2: Create x-button component**

`resources/views/components/button.blade.php` — variants: primary, secondary, success, danger, warning; sizes: sm, default, lg; supports href for link buttons:

```blade
@props([
    'variant' => 'primary',
    'size' => 'default',
    'href' => null,
])

@php
$base = 'inline-flex items-center justify-center font-semibold rounded-xl transition-all duration-200 shadow-sm';

$variants = [
    'primary' => 'bg-primary-600 hover:bg-primary-700 text-white shadow-primary-500/10',
    'secondary' => 'bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 shadow-none',
    'success' => 'bg-emerald-600 hover:bg-emerald-700 text-white shadow-emerald-500/10',
    'danger' => 'bg-red-600 hover:bg-red-700 text-white shadow-red-500/10',
    'warning' => 'bg-amber-500 hover:bg-amber-600 text-white shadow-amber-500/10',
];

$sizes = [
    'sm' => 'px-3 py-1.5 text-xs',
    'default' => 'px-5 py-2.5 text-sm',
    'lg' => 'px-6 py-3 text-base',
];
@endphp

@if($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => "$base {$variants[$variant]} {$sizes[$size]}"]) }}>
        {{ $slot }}
    </a>
@else
    <button {{ $attributes->merge(['class' => "$base {$variants[$variant]} {$sizes[$size]}"]) }}>
        {{ $slot }}
    </button>
@endif
```

- [ ] **Step 3: Create x-badge component**

`resources/views/components/badge.blade.php` — small label with color variants (gray, primary, success, danger, warning, indigo):

```blade
@props(['variant' => 'gray'])

@php
$classes = [
    'gray' => 'bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-300',
    'primary' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-400',
    'success' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-400',
    'danger' => 'bg-red-100 text-red-700 dark:bg-red-900/40 dark:text-red-400',
    'warning' => 'bg-amber-100 text-amber-700 dark:bg-amber-900/40 dark:text-amber-400',
    'indigo' => 'bg-indigo-100 text-indigo-700 dark:bg-indigo-900/40 dark:text-indigo-400',
];
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {$classes[$variant]}"]) }}>
    {{ $slot }}
</span>
```

- [ ] **Step 4: Create x-alert component**

`resources/views/components/alert.blade.php` — alert boxes for success, error, warning, info:

```blade
@props(['type' => 'info'])

@php
$classes = [
    'success' => 'bg-emerald-50 dark:bg-emerald-950/30 border-emerald-200 dark:border-emerald-800 text-emerald-700 dark:text-emerald-400',
    'error' => 'bg-red-50 dark:bg-red-950/30 border-red-200 dark:border-red-800 text-red-700 dark:text-red-400',
    'warning' => 'bg-amber-50 dark:bg-amber-950/30 border-amber-200 dark:border-amber-800 text-amber-700 dark:text-amber-400',
    'info' => 'bg-blue-50 dark:bg-blue-950/30 border-blue-200 dark:border-blue-800 text-blue-700 dark:text-blue-400',
];
@endphp

<div {{ $attributes->merge(['class' => "rounded-xl border px-4 py-3 text-sm {$classes[$type]}"]) }}>
    {{ $slot }}
</div>
```

- [ ] **Step 5: Create x-table component**

`resources/views/components/table.blade.php` — responsive wrapper with header/slot/footer slots:

```blade
@props(['minWidth' => '600px'])

<div {{ $attributes->merge(['class' => 'w-full overflow-x-auto rounded-xl border border-gray-100 dark:border-gray-800 shadow-inner bg-white dark:bg-gray-900']) }}>
    <table class="w-full text-left border-collapse" style="min-width: {{ $minWidth }}">
        @if(isset($header))
            <thead>
                <tr class="bg-gray-50 dark:bg-gray-800/60 text-gray-700 dark:text-gray-300 border-b border-gray-200 dark:border-gray-800">
                    {{ $header }}
                </tr>
            </thead>
        @endif
        <tbody class="divide-y divide-gray-100 dark:divide-gray-800 text-gray-600 dark:text-gray-300">
            {{ $slot }}
        </tbody>
        @if(isset($footer))
            <tfoot class="bg-gray-50 dark:bg-gray-800/60 border-t border-gray-200 dark:border-gray-800">
                <tr>
                    {{ $footer }}
                </tr>
            </tfoot>
        @endif
    </table>
</div>
```

- [ ] **Step 6: Create x-page-header component**

`resources/views/components/page-header.blade.php` — page title + optional subtitle:

```blade
@props(['title', 'subtitle' => null])

<div {{ $attributes->merge(['class' => 'space-y-1']) }}>
    <h2 class="text-2xl font-bold text-gray-900 dark:text-white tracking-tight">{{ $title }}</h2>
    @if($subtitle)
        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $subtitle }}</p>
    @endif
</div>
```

- [ ] **Step 7: Create x-input-group component**

`resources/views/components/input-group.blade.php` — label + input + error helper (text, select, textarea):

```blade
@props([
    'label' => null,
    'name' => null,
    'type' => 'text',
    'value' => null,
    'placeholder' => null,
    'required' => false,
    'error' => null,
])

<div {{ $attributes->whereDoesntStartWith('wire:model') }}>
    @if($label)
        <label for="{{ $name }}" class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
            {{ $label }}
            @if($required) <span class="text-red-500">*</span> @endif
        </label>
    @endif

    @if($type === 'select')
        <select name="{{ $name }}" id="{{ $name }}" {{ $attributes->merge(['class' => 'w-full border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white rounded-xl text-sm focus:ring-2 focus:ring-primary-500 mt-1.5']) }}>
            {{ $slot }}
        </select>
    @elseif($type === 'textarea')
        <textarea name="{{ $name }}" id="{{ $name }}" placeholder="{{ $placeholder }}" {{ $attributes->merge(['class' => 'w-full border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white rounded-xl text-sm focus:ring-2 focus:ring-primary-500 mt-1.5']) }}>{{ $value }}</textarea>
    @else
        <input type="{{ $type }}" name="{{ $name }}" id="{{ $name }}" value="{{ old($name, $value) }}" placeholder="{{ $placeholder }}" {{ $attributes->merge(['class' => 'w-full border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white rounded-xl text-sm focus:ring-2 focus:ring-primary-500 mt-1.5']) }}>
    @endif

    @if($error)
        <p class="text-xs text-red-500 mt-1">{{ $error }}</p>
    @endif
</div>
```

- [ ] **Step 8: Run view clear**

```bash
php artisan view:clear
```

Expected: No errors

- [ ] **Step 9: Commit**

```bash
git add resources/views/components/
git commit -m "feat(components): add TailAdmin-style reusable Blade components"
```

---

### Task 3: Layout + Sidebar Redesign

**Files:**
- Rewrite: `resources/views/layouts/sidebar.blade.php`
- Rewrite: `resources/views/layouts/app.blade.php`
- Delete: `resources/views/layouts/navigation.blade.php`

**Interfaces:**
- Consumes: nothing directly (components used in page tasks, not in layout)
- Produces: New layout with grouped sidebar + header avatar dropdown

- [ ] **Step 1: Rewrite sidebar.blade.php**

Replace flat menu with grouped sections. Each collapsible group uses Alpine.js `x-data="{ open: true }"` for expand/collapse.

Sidebar menu structure with role-based visibility:

```
DASHBOARD
  [grid] Dashboard (all roles)

PENJUALAN
  [cash] Kasir / POS (Owner, Admin, Kasir)
  [receipt] Transaksi (Owner, Admin, Kasir)

MASTER DATA
  [box] Produk (Owner, Admin, Gudang)
  [tag] Kategori (Owner, Admin, Gudang)
  [truck] Supplier (Owner, Admin, Gudang)
  [users] Pelanggan (Owner, Admin)

MANAJEMEN STOK
  [package] Pembelian Stok (Owner, Admin, Gudang)
  [clipboard] Stock Opname (Owner, Admin, Gudang)
  [activity] Riwayat Stok (Owner, Admin, Gudang)

PROMO & LAPORAN
  [percent] Promo (Owner, Admin)
  [bar-chart] Laporan (Owner, Admin)

LAINNYA
  [bell] Notifikasi (all roles)
  [shield] Kelola Pegawai (Owner)
  [settings] Edit Profil (all roles)
```

Implementation per group:
```blade
<div x-data="{ open: true }">
    <button @click="open = !open" class="flex items-center justify-between w-full px-3 py-2 text-[10px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest hover:text-gray-600 dark:hover:text-gray-300 transition-colors">
        <span>NAMA GROUP</span>
        <svg class="w-3 h-3 transition-transform duration-200" :class="{'rotate-180': open}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
        </svg>
    </button>
    <div x-show="open" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" class="mt-1 space-y-0.5">
        <!-- menu items -->
    </div>
</div>
```

Each menu item uses the sidebar-item classes from the CSS (or inline):
```blade
<a href="{{ route('dashboard') }}"
   class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all duration-200
   {{ request()->routeIs('dashboard') ? 'bg-primary-600 text-white shadow-md shadow-primary-500/20' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-white' }}">
    <!-- SVG icon 20x20 -->
    <span>Dashboard</span>
</a>
```

Keep the mobile responsive behavior: hidden by default on mobile (`-translate-x-full`), toggled by hamburger + backdrop overlay, same as current implementation.

- [ ] **Step 2: Rewrite app.blade.php**

Replace the current header (dark mode button + name + logout) with a TailAdmin-style sticky header:

```blade
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" id="html">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>SupermarketKu</title>
    <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('images/logo-supermarketku.png') }}">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-50 dark:bg-gray-950 text-gray-900 dark:text-gray-100 min-h-screen">
    <div class="min-h-screen flex">
        @include('layouts.sidebar')

        <!-- Main content area -->
        <div class="flex-1 flex flex-col min-w-0 lg:ml-64">
            <!-- Sticky top bar -->
            <header class="bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-800 px-4 sm:px-6 py-3 flex items-center justify-between shadow-sm sticky top-0 z-30">
                <!-- Left: mobile hamburger + page title -->
                <div class="flex items-center gap-3">
                    <button id="sidebarToggle" class="lg:hidden p-2 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-800 text-gray-500 dark:text-gray-400 transition-colors">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                    <div class="hidden lg:block">
                        {{ $header ?? '' }}
                    </div>
                </div>

                <!-- Right: actions -->
                <div class="flex items-center gap-2">
                    <!-- Dark mode toggle -->
                    <button onclick="toggleDarkMode()" class="p-2 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-800 text-gray-500 dark:text-gray-400 transition-colors" title="Toggle Dark Mode">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                        </svg>
                    </button>

                    <!-- Notification bell -->
                    <a href="{{ route('notifications.index') }}" class="relative p-2 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-800 text-gray-500 dark:text-gray-400 transition-colors" title="Notifikasi">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                    </a>

                    <!-- User avatar dropdown (Alpine) -->
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" class="flex items-center gap-2 p-1.5 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                            <div class="w-8 h-8 rounded-full bg-primary-600 flex items-center justify-center text-white text-sm font-bold">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </div>
                            <span class="hidden sm:block text-sm font-medium text-gray-700 dark:text-gray-300">{{ Auth::user()->name }}</span>
                            <svg class="hidden sm:block w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div x-show="open" @click.outside="open = false" class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-900 rounded-xl shadow-lg border border-gray-200 dark:border-gray-800 py-2 z-50"
                             x-transition:enter="transition ease-out duration-100"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="opacity-100 scale-100"
                             x-transition:leave-end="opacity-0 scale-95">
                            <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                                Edit Profil
                            </a>
                            <div class="border-t border-gray-100 dark:border-gray-800 my-1"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button class="w-full text-left px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                                    Keluar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Mobile page header (visible only on mobile, below sticky header) -->
            <div class="lg:hidden px-4 sm:px-6 py-4 bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-800">
                {{ $header ?? '' }}
            </div>

            <!-- Content -->
            <main class="flex-1 p-4 sm:p-6 lg:p-8 bg-gray-50 dark:bg-gray-950">
                {{ $slot }}
            </main>
        </div>
    </div>

    <!-- Dark mode script (same as current) -->
    <script>
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }

        function toggleDarkMode() {
            document.documentElement.classList.toggle('dark');
            localStorage.setItem('theme', document.documentElement.classList.contains('dark') ? 'dark' : 'light');
        }

        // Mobile sidebar toggle
        document.addEventListener('DOMContentLoaded', function () {
            const sidebar = document.getElementById('sidebar');
            const toggle = document.getElementById('sidebarToggle');
            const backdrop = document.getElementById('sidebarBackdrop');

            if (toggle && sidebar && backdrop) {
                toggle.addEventListener('click', function () {
                    sidebar.classList.toggle('-translate-x-full');
                    backdrop.classList.toggle('hidden');
                });
                backdrop.addEventListener('click', function () {
                    sidebar.classList.add('-translate-x-full');
                    backdrop.classList.add('hidden');
                });
            }

            // Search debounce (same as current)
            var timers = {};
            document.querySelectorAll('input[name="search"]').forEach(function (input) {
                var form = input.closest('form');
                if (!form) return;
                input.addEventListener('input', function () {
                    clearTimeout(timers[form.action]);
                    timers[form.action] = setTimeout(function () {
                        form.submit();
                    }, 300);
                });
            });
        });
    </script>
</body>
</html>
```

Key changes from current app.blade.php:
- Sticky header
- Avatar with first-letter initial (instead of name + logout button)
- Notification bell icon
- User dropdown (profile link + logout)
- Mobile header shows on mobile only
- Dark mode toggle is icon-only

- [ ] **Step 3: Delete navigation.blade.php**

```bash
git rm resources/views/layouts/navigation.blade.php
```

This file is no longer referenced anywhere (confirmed by code review).

- [ ] **Step 4: Verify layout**

```bash
php artisan view:clear
```

Open dashboard page, verify:
- Sidebar renders with groups
- Header shows avatar, bell icon
- Avatar dropdown works (click toggles, click outside closes)
- Dark mode toggle works
- Mobile: hamburger toggles sidebar

- [ ] **Step 5: Commit**

```bash
git add resources/views/layouts/
git rm resources/views/layouts/navigation.blade.php
git commit -m "feat(layout): redesign with TailAdmin-style sidebar groups + header avatar dropdown"
```

---

### Task 4: Guest Layout + Auth Pages

**Files:**
- Modify: `resources/views/layouts/guest.blade.php`
- Modify: `resources/views/auth/login.blade.php`
- Modify: `resources/views/auth/register.blade.php`
- Modify: `resources/views/auth/forgot-password.blade.php`
- Modify: `resources/views/auth/reset-password.blade.php`
- Modify: `resources/views/auth/confirm-password.blade.php`
- Modify: `resources/views/auth/verify-email.blade.php`

**Interfaces:**
- Consumes: nothing (inline Tailwind)
- Produces: TailAdmin-style auth pages

- [ ] **Step 1: Rewrite guest.blade.php**

Replace Breeze-style layout with TailAdmin-inspired centered card:

```blade
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>SupermarketKu</title>
    <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('images/logo-supermarketku.png') }}">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-100 dark:bg-gray-950 min-h-screen flex flex-col items-center justify-center p-4">
    <div class="w-full sm:max-w-md">
        <!-- Logo -->
        <div class="mb-6 text-center">
            <div class="w-16 h-16 mx-auto mb-3 rounded-2xl bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 shadow-lg flex items-center justify-center p-3">
                <img src="{{ asset('images/logo-supermarketku.png') }}" alt="SupermarketKu" class="w-full h-full object-contain">
            </div>
        </div>

        <!-- Card -->
        <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl shadow-xl p-6 sm:p-8">
            {{ $slot }}
        </div>

        <p class="mt-6 text-center text-xs text-gray-400 dark:text-gray-600">
            SupermarketKu &mdash; Aplikasi Kasir Modern
        </p>
    </div>
</body>
</html>
```

- [ ] **Step 2: Rewrite login.blade.php**

TailAdmin Sign In style: social buttons (decorative, non-functional), separator, email/password fields, remember me, forgot password link, submit button, register link.

Structure:
```blade
<x-guest-layout>
    <h1 class="text-xl font-bold text-gray-900 dark:text-white text-center mb-1">Masuk</h1>
    <p class="text-sm text-gray-500 dark:text-gray-400 text-center mb-6">Masukkan email dan password untuk masuk</p>

    <!-- Social login buttons (decorative) -->
    <div class="grid grid-cols-2 gap-3 mb-5">
        <button type="button" class="flex items-center justify-center gap-2 border border-gray-300 dark:border-gray-700 rounded-xl px-4 py-2.5 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor"><path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92a5.06 5.06 0 01-2.2 3.32v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.1z"/><path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/><path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/><path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/></svg>
            Google
        </button>
        <button type="button" class="flex items-center justify-center gap-2 border border-gray-300 dark:border-gray-700 rounded-xl px-4 py-2.5 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
            X
        </button>
    </div>

    <div class="relative flex items-center mb-5">
        <div class="flex-grow border-t border-gray-200 dark:border-gray-800"></div>
        <span class="flex-shrink mx-3 text-xs text-gray-400">Atau</span>
        <div class="flex-grow border-t border-gray-200 dark:border-gray-800"></div>
    </div>

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf
        <div>
            <label for="email" class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                   class="w-full border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white rounded-xl text-sm focus:ring-2 focus:ring-primary-500 mt-1.5">
            @error('email') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <div class="flex items-center justify-between">
                <label for="password" class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Password</label>
                @if(Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-xs font-medium text-primary-600 dark:text-primary-400 hover:underline">Lupa password?</a>
                @endif
            </div>
            <input id="password" type="password" name="password" required
                   class="w-full border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white rounded-xl text-sm focus:ring-2 focus:ring-primary-500 mt-1.5">
            @error('password') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
        </div>

        <label class="flex items-center gap-2 cursor-pointer">
            <input type="checkbox" name="remember" class="rounded border-gray-300 dark:border-gray-700 text-primary-600 focus:ring-primary-500">
            <span class="text-sm text-gray-600 dark:text-gray-400">Ingat saya</span>
        </label>

        <button type="submit" class="w-full bg-primary-600 hover:bg-primary-700 text-white rounded-xl px-4 py-2.5 text-sm font-semibold transition-colors shadow-sm">
            Masuk
        </button>
    </form>

    <p class="mt-5 text-center text-sm text-gray-500 dark:text-gray-400">
        Belum punya akun?
        <a href="{{ route('register') }}" class="font-semibold text-primary-600 dark:text-primary-400 hover:underline">Daftar</a>
    </p>
</x-guest-layout>
```

- [ ] **Step 3: Rewrite register.blade.php**

Same card layout with fields: name, email, password, password_confirmation. Structure mirrors login.

- [ ] **Step 4: Rewrite remaining auth pages**

`forgot-password.blade.php`, `reset-password.blade.php`, `confirm-password.blade.php`, `verify-email.blade.php` — all follow same card pattern. Keep the same form content, only restyle with:
- Same card wrapper
- Same input styling
- `x-primary-button` replaced with styled `<button>`
- TailAdmin color scheme

- [ ] **Step 5: Verify all auth routes**

Run: Visit `/login`, `/register`, `/forgot-password`
Expected: All pages render with TailAdmin styling, forms work correctly (test login with seed user)

- [ ] **Step 6: Commit**

```bash
git add resources/views/layouts/guest.blade.php resources/views/auth/
git commit -m "feat(auth): redesign with TailAdmin-style auth pages"
```

---

### Task 5: Dashboard Redesign

**Files:**
- Modify: `resources/views/dashboard.blade.php`
- Modify: `resources/js/dashboard.js` (update chart colors)
- Read: Check dashboard controller to confirm data variable names

**Interfaces:**
- Consumes: x-card, x-table, x-badge, x-page-header, x-button
- Data: `$todaySales`, `$todayTransactions`, `$totalProducts`, `$totalCustomers`, `$salesChart`, `$lowStockProducts`, `$bestSellingProducts`

- [ ] **Step 1: Read DashboardController to confirm data variables**

```bash
php -r "print_r(array_keys(\$app->make(\App\Http\Controllers\DashboardController::class)->__invoke(\\request())->getData()));"
```

(Or read `app/Http/Controllers/DashboardController.php`)

Expected: Confirms variable names: todaySales, todayTransactions, totalProducts, totalCustomers, salesChart, lowStockProducts, bestSellingProducts

- [ ] **Step 2: Rewrite dashboard.blade.php**

Replace gradient stat cards with TailAdmin-style stat cards (white card + icon + value + label):

```blade
<x-app-layout>
    <x-slot name="header">
        <x-page-header title="Dashboard" subtitle="Ringkasan aktivitas penjualan dan stok barang" />
    </x-slot>

    <!-- Stat Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-6">
        <x-card padding="p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Penjualan Hari Ini</p>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mt-1">Rp {{ number_format($todaySales, 0, ',', '.') }}</h3>
                </div>
                <div class="w-12 h-12 rounded-xl bg-blue-50 dark:bg-blue-900/30 flex items-center justify-center text-blue-600 dark:text-blue-400">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z" />
                    </svg>
                </div>
            </div>
            <p class="mt-2 text-xs text-gray-400 dark:text-gray-500">Total omzet hari ini</p>
        </x-card>

        <x-card padding="p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Transaksi Hari Ini</p>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mt-1 counter" data-target="{{ $todayTransactions }}">0</h3>
                </div>
                <div class="w-12 h-12 rounded-xl bg-indigo-50 dark:bg-indigo-900/30 flex items-center justify-center text-indigo-600 dark:text-indigo-400">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
            </div>
            <p class="mt-2 text-xs text-gray-400 dark:text-gray-500">Jumlah transaksi hari ini</p>
        </x-card>

        <x-card padding="p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Produk</p>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mt-1 counter" data-target="{{ $totalProducts }}">0</h3>
                </div>
                <div class="w-12 h-12 rounded-xl bg-emerald-50 dark:bg-emerald-900/30 flex items-center justify-center text-emerald-600 dark:text-emerald-400">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                </div>
            </div>
            <p class="mt-2 text-xs text-gray-400 dark:text-gray-500">Variasi produk aktif</p>
        </x-card>

        <x-card padding="p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Pelanggan</p>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mt-1 counter" data-target="{{ $totalCustomers }}">0</h3>
                </div>
                <div class="w-12 h-12 rounded-xl bg-violet-50 dark:bg-violet-900/30 flex items-center justify-center text-violet-600 dark:text-violet-400">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </div>
            </div>
            <p class="mt-2 text-xs text-gray-400 dark:text-gray-500">Pelanggan terdaftar</p>
        </x-card>
    </div>

    <!-- Chart + Low Stock side by side -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Sales Chart -->
        <x-card padding="p-5" class="lg:col-span-2">
            <x-slot name="header">
                <h3 class="font-bold text-lg text-gray-900 dark:text-white">Grafik Penjualan 7 Hari Terakhir</h3>
                <x-badge variant="primary">7 Hari</x-badge>
            </x-slot>
            <div class="w-full h-[300px] md:h-[400px] relative">
                <canvas id="salesChart"
                    data-labels='@json($salesChart->pluck('date'))'
                    data-values='@json($salesChart->pluck('total'))'></canvas>
            </div>
        </x-card>

        <!-- Low Stock -->
        <x-card padding="p-5">
            <x-slot name="header">
                <h3 class="font-bold text-lg text-gray-900 dark:text-white">Produk Stok Menipis</h3>
                <x-badge variant="danger">Perlu Restok</x-badge>
            </x-slot>
            <x-table min-width="auto">
                <x-slot name="header">
                    <th class="px-4 py-3 text-sm font-semibold">Produk</th>
                    <th class="px-4 py-3 text-sm font-semibold text-center">Stok</th>
                </x-slot>
                @forelse ($lowStockProducts as $product)
                    <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-800/20 transition-colors">
                        <td class="px-4 py-3 text-sm font-medium text-gray-900 dark:text-white">{{ $product->name }}</td>
                        <td class="px-4 py-3 text-sm text-center">
                            <x-badge variant="danger">{{ $product->stock }}</x-badge>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="2" class="px-4 py-8 text-center text-sm text-gray-400">✅ Semua stok produk dalam batas aman.</td>
                    </tr>
                @endforelse
            </x-table>
        </x-card>
    </div>

    <!-- Best Selling -->
    <x-card padding="p-5" class="mt-6">
        <x-slot name="header">
            <h3 class="font-bold text-lg text-gray-900 dark:text-white">Produk Terlaris</h3>
            <x-badge variant="success">Best Seller</x-badge>
        </x-slot>
        <x-table>
            <x-slot name="header">
                <th class="px-4 py-3.5 text-sm font-bold tracking-wider">Produk</th>
                <th class="px-4 py-3.5 text-sm font-bold tracking-wider text-right">Terjual</th>
            </x-slot>
            @forelse ($bestSellingProducts as $item)
                <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-800/20 transition-colors">
                    <td class="px-4 py-3.5 text-sm font-medium text-gray-900 dark:text-white">{{ $item->product->name ?? '-' }}</td>
                    <td class="px-4 py-3.5 text-sm text-right">
                        <x-badge variant="success">{{ $item->total_sold }} pcs</x-badge>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="2" class="px-4 py-8 text-center text-sm text-gray-400">Belum ada data transaksi penjualan.</td>
                </tr>
            @endforelse
        </x-table>
    </x-card>

    @vite('resources/js/dashboard.js')
</x-app-layout>
```

- [ ] **Step 3: Update dashboard.js chart colors**

Update the Chart.js configuration to use primary-600 color and gradient:

```javascript
// In the line chart config, change:
// borderColor to '#2563eb' (primary-600)
// backgroundColor to gradient of primary-50 to transparent
// pointBackgroundColor to '#2563eb'
```

Current chart code is in `resources/js/dashboard.js` — read first, then update the color values.

- [ ] **Step 4: Verify dashboard**

Run: Visit `/dashboard`
Expected: Stat cards show data, chart renders with new colors, tables show content

- [ ] **Step 5: Commit**

```bash
git add resources/views/dashboard.blade.php resources/js/dashboard.js
git commit -m "feat(dashboard): redesign with TailAdmin-style cards and layout"
```

---

### Task 6: CRUD Index Pages Redesign

**Files (batch — ~11 files):**
- `resources/views/products/index.blade.php`
- `resources/views/categories/index.blade.php`
- `resources/views/suppliers/index.blade.php`
- `resources/views/customers/index.blade.php`
- `resources/views/users/index.blade.php`
- `resources/views/promotions/index.blade.php`
- `resources/views/purchases/index.blade.php`
- `resources/views/stock-opname/index.blade.php`
- `resources/views/stock-movements/index.blade.php`
- `resources/views/transactions/index.blade.php`
- `resources/views/notifications/index.blade.php`

**Interfaces:**
- Consumes: x-page-header, x-card, x-button, x-table, x-badge, x-alert
- Produces: Consistent TailAdmin-style index pages

**Pattern for each index page:**

```blade
<x-app-layout>
    <x-slot name="header">
        <x-page-header title="Nama Halaman" subtitle="Deskripsi" />
    </x-slot>

    @if(session('success'))
        <x-alert type="success" class="mb-5">{{ session('success') }}</x-alert>
    @endif

    <x-card>
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-5">
            <x-button variant="primary" href="{{ route('[resource].create') }}">
                + Tambah Nama
            </x-button>
            <!-- search form if applicable (products, categories, etc.) -->
        </div>

        <x-table>
            <x-slot name="header">
                <th class="px-4 py-3.5 text-sm font-bold tracking-wider">No</th>
                @foreach($columns as $col)
                <th class="px-4 py-3.5 text-sm font-bold tracking-wider {{ $col['align'] ?? '' }}">{{ $col['label'] }}</th>
                @endforeach
                <th class="px-4 py-3.5 text-sm font-bold tracking-wider text-center">Aksi</th>
            </x-slot>

            @forelse($items as $item)
                <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-800/20 transition-colors">
                    <td class="px-4 py-3.5 text-sm">{{ $loop->iteration }}</td>
                    @foreach($columns as $col)
                    <td class="px-4 py-3.5 text-sm {{ $col['align'] ?? '' }}">{!! data_get($item, $col['key']) !!}</td>
                    @endforeach
                    <td class="px-4 py-3.5 text-sm text-center">
                        <div class="flex items-center justify-center gap-1.5">
                            <x-button variant="warning" size="sm" href="{{ route('[resource].edit', $item->id) }}">Edit</x-button>
                            <form action="{{ route('[resource].destroy', $item->id) }}" method="POST" class="m-0 inline">
                                @csrf @method('DELETE')
                                <x-button variant="danger" size="sm" onclick="return confirm('Yakin hapus?')">Hapus</x-button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="{{ count($columns) + 2 }}" class="px-4 py-8 text-center text-sm text-gray-400">Data belum ada.</td>
                </tr>
            @endforelse
        </x-table>

        @if(method_exists($items, 'links'))
            <div class="mt-5">{{ $items->withQueryString()->links() }}</div>
        @endif
    </x-card>
</x-app-layout>
```

- [ ] **Step 1: Products index**

Rewrite `products/index.blade.php` with the pattern above. Columns: No, Barcode, Nama Produk (with category badge), Supplier, Harga Jual (right-aligned, formatted), Stok (center, with badge color based on stock level), Aksi.

- [ ] **Step 2: Categories index**

Rewrite `categories/index.blade.php`. Columns: No, Nama, Deskripsi, Aksi.

- [ ] **Step 3: Suppliers index**

Rewrite `suppliers/index.blade.php`. Columns: No, Nama, Telepon, Alamat, Aksi.

- [ ] **Step 4: Customers index**

Rewrite `customers/index.blade.php`. Columns: No, Nama, Telepon, Poin, Aksi.

- [ ] **Step 5: Users index**

Rewrite `users/index.blade.php`. Columns: No, Nama, Email, Role (badge), Aksi.

- [ ] **Step 6: Promotions index**

Rewrite `promotions/index.blade.php`. Columns: No, Nama, Diskon, Periode, Status (badge), Aksi.

- [ ] **Step 7: Purchases index**

Rewrite `purchases/index.blade.php`. Columns: No, Invoice, Supplier, Tanggal, Total (formatted), Aksi.

- [ ] **Step 8: Stock Opname index**

Rewrite `stock-opname/index.blade.php`.

- [ ] **Step 9: Stock Movements index**

Rewrite `stock-movements/index.blade.php`. Columns: No, Produk, Tipe (badge: masuk/keluar), Jumlah, Tanggal.

- [ ] **Step 10: Transactions index**

Rewrite `transactions/index.blade.php`. Columns: No, Invoice, Kasir, Pelanggan (badge), Tanggal, Total (formatted), Aksi.

- [ ] **Step 11: Notifications index**

Rewrite `notifications/index.blade.php`.

- [ ] **Step 12: Verify all index pages**

Run: Visit each index route, confirm table renders, data shows, search/filter works, pagination works.

- [ ] **Step 13: Commit**

```bash
git add resources/views/products/index.blade.php resources/views/categories/index.blade.php resources/views/suppliers/index.blade.php resources/views/customers/index.blade.php resources/views/users/index.blade.php resources/views/promotions/index.blade.php resources/views/purchases/index.blade.php resources/views/stock-opname/index.blade.php resources/views/stock-movements/index.blade.php resources/views/transactions/index.blade.php resources/views/notifications/index.blade.php
git commit -m "feat(crud): redesign all index pages with TailAdmin components"
```

---

### Task 7: CRUD Form Pages Redesign

**Files (batch — ~13 files):**
- `resources/views/products/create.blade.php`, `products/edit.blade.php`
- `resources/views/categories/create.blade.php`, `categories/edit.blade.php`
- `resources/views/suppliers/create.blade.php`, `suppliers/edit.blade.php`
- `resources/views/customers/create.blade.php`, `customers/edit.blade.php`
- `resources/views/users/create.blade.php`, `users/edit.blade.php`
- `resources/views/promotions/create.blade.php`, `promotions/edit.blade.php`

**Interfaces:**
- Consumes: x-page-header, x-card, x-button, x-input-group
- Produces: Consistent form pages using TailAdmin-style inputs

**Pattern for each create/edit page:**

```blade
<x-app-layout>
    <x-slot name="header">
        <x-page-header title="{{ $edit ? 'Edit' : 'Tambah' }} Nama" subtitle="Deskripsi" />
    </x-slot>

    <div class="max-w-5xl mx-auto">
        <x-card>
            <form action="{{ $edit ? route('resource.update', $item->id) : route('resource.store') }}" method="POST" class="space-y-5">
                @csrf
                @if($edit) @method('PUT') @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <x-input-group label="Nama" name="name" :value="old('name', $item->name ?? '')" :error="$errors->first('name')" required />
                    <!-- more fields -->
                </div>

                <div class="flex items-center gap-3 pt-4 border-t border-gray-100 dark:border-gray-800">
                    <x-button type="submit">{{ $edit ? 'Perbarui' : 'Simpan' }}</x-button>
                    <x-button variant="secondary" href="{{ route('resource.index') }}">Kembali</x-button>
                </div>
            </form>
        </x-card>
    </div>
</x-app-layout>
```

Key points for each form:
- Each file must be read first to understand its specific fields
- Replace all `<label>`, `<input>`, `<select>`, `<textarea>` with x-input-group
- Keep `@error()` — pass `$errors->first('field')` to x-input-group's `error` prop
- Keep the same form action, method, and hidden fields

- [ ] **Step 1: Products create page**

Fields: category_id (select), supplier_id (select), barcode, name, purchase_price, selling_price, stock, minimum_stock, expired_date (date)

- [ ] **Step 2: Products edit page**

Same fields + `@method('PUT')` + pre-filled values

- [ ] **Step 3: Categories create + edit**

Fields: name, description (textarea)

- [ ] **Step 4: Suppliers create + edit**

Fields: name, phone, address (textarea)

- [ ] **Step 5: Customers create + edit**

Fields: name, phone, points

- [ ] **Step 6: Users create + edit**

Fields: name, email, password, role_id (select), password_confirmation

- [ ] **Step 7: Promotions create + edit**

Fields: name, discount_type, discount_value, start_date, end_date, description

- [ ] **Step 8: Purchases create page**

Note: This is a special multi-item form. Read the file first, restyle carefully.

- [ ] **Step 9: Verify all form pages**

Visit each create/edit page, verify form renders correctly, validation errors show, form submission works.

- [ ] **Step 10: Commit**

```bash
git add resources/views/products/create.blade.php resources/views/products/edit.blade.php resources/views/categories/create.blade.php resources/views/categories/edit.blade.php resources/views/suppliers/create.blade.php resources/views/suppliers/edit.blade.php resources/views/customers/create.blade.php resources/views/customers/edit.blade.php resources/views/users/create.blade.php resources/views/users/edit.blade.php resources/views/promotions/create.blade.php resources/views/promotions/edit.blade.php resources/views/purchases/create.blade.php
git commit -m "feat(crud): redesign all form pages with TailAdmin components"
```

---

### Task 8: POS Page Redesign

**Files:**
- Modify: `resources/views/pos/index.blade.php`
- Modify: `resources/views/pos/checkout.blade.php`

**Interfaces:**
- Consumes: x-card, x-button, x-badge, x-input-group, x-alert
- Same backend data: `$products`, `$cart`, `$customers`

- [ ] **Step 1: Redesign pos/index.blade.php**

Read current file, then restyle keeping 2-column layout:
- Left (2/3): Product grid with cards
- Right (1/3): Cart with items, totals, payment methods

Replace product card styling with TailAdmin card look:
```blade
@forelse ($products as $product)
    <form action="{{ route('pos.add') }}" method="POST"
          class="border border-gray-200 dark:border-gray-800 rounded-2xl p-4 bg-white dark:bg-gray-900 hover:shadow-md hover:border-primary-500 dark:hover:border-primary-500 transition-all flex flex-col justify-between">
        @csrf
        <input type="hidden" name="product_id" value="{{ $product->id }}">
        <div>
            <h4 class="font-bold text-gray-900 dark:text-white text-base">{{ $product->name }}</h4>
            <p class="text-xs text-gray-400 mt-1">BC: {{ $product->barcode }}</p>
            <p class="text-xs mt-0.5">
                Stok: <span class="{{ $product->stock <= 5 ? 'text-red-600 font-bold' : 'text-gray-500' }}">{{ $product->stock }}</span>
            </p>
        </div>
        <div class="mt-4 flex items-center justify-between border-t border-gray-100 dark:border-gray-800 pt-3">
            <span class="font-extrabold text-primary-600 dark:text-primary-400">Rp {{ number_format($product->selling_price, 0, ',', '.') }}</span>
            <button type="submit" {{ $product->stock <= 0 ? 'disabled' : '' }}
                    class="bg-primary-600 hover:bg-primary-700 text-white px-3 py-1.5 rounded-xl text-xs font-semibold transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                {{ $product->stock <= 0 ? 'Habis' : '+ Tambah' }}
            </button>
        </div>
    </form>
@endforelse
```

Cart section: same structure, styled with x-card, x-badge, and x-button.

Payment method fields: keep same functionality, restyle with TailAdmin input/select styling.

- [ ] **Step 2: Redesign pos/checkout.blade.php**

Read current file first, restyle the receipt/checkout confirmation page.

- [ ] **Step 3: Verify POS flow**

Run: Click through POS flow — search products, add to cart, adjust quantity, change payment method, complete transaction.
Expected: Full functionality preserved, styled with TailAdmin.

- [ ] **Step 4: Commit**

```bash
git add resources/views/pos/
git commit -m "feat(pos): redesign with TailAdmin styling"
```

---

### Task 9: Remaining Pages Redesign

**Files:**
- `resources/views/purchases/show.blade.php`
- `resources/views/transactions/show.blade.php`
- `resources/views/transactions/receipt.blade.php`
- `resources/views/reports/sales.blade.php`
- `resources/views/reports/sales_pdf.blade.php` (keep minimal — PDF-optimized, don't restyle)
- `resources/views/profile/edit.blade.php`
- `resources/views/profile/partials/update-profile-information-form.blade.php`
- `resources/views/profile/partials/update-password-form.blade.php`
- `resources/views/profile/partials/delete-user-form.blade.php`

- [ ] **Step 1: Purchases show page**

- [ ] **Step 2: Transactions show page**

- [ ] **Step 3: Transactions receipt page**

- [ ] **Step 4: Reports sales page**

- [ ] **Step 5: Profile pages**

- [ ] **Step 6: Verify all pages render correctly**

Run: Visit each route, verify no layout breaks

- [ ] **Step 7: Commit**

```bash
git add resources/views/purchases/show.blade.php resources/views/transactions/ resources/views/reports/sales.blade.php resources/views/profile/
git commit -m "feat(pages): redesign remaining views with TailAdmin components"
```

---

### Task 10: Final Polish + Verification

**Files:**
- All view files (verify)
- Run full test suite
- Build frontend

- [ ] **Step 1: Clean up obsolete component references**

Check if any pages still reference old Breeze components that have been replaced:
- `x-nav-link` (no longer used if navigation.blade.php is deleted)
- `x-responsive-nav-link` (same)
- `x-dropdown`, `x-dropdown-link` (still used? check profile pages)

Only remove components if they are truly unused. Keep `x-input-label`, `x-text-input`, `x-input-error` as they may still be useful.

- [ ] **Step 2: Verify dark mode across all pages**

Toggle dark mode, check:
- Sidebar renders correctly
- Header matches
- All cards show dark variant
- All tables show dark variant
- Auth pages render in dark mode
- POS page renders in dark mode

- [ ] **Step 3: Verify responsive layout**

Check at mobile width:
- Sidebar hidden by default
- Hamburger menu visible
- Content stack properly
- Tables scroll horizontally

- [ ] **Step 4: Run full test suite**

```bash
php artisan test
```

Expected: 153 tests, all passing (299 assertions)

- [ ] **Step 5: Build frontend assets**

```bash
npm run build
```

Expected: Clean build, no errors

- [ ] **Step 6: Final commit**

```bash
git add .
git commit -m "chore: final polish and verification after TailAdmin redesign"
```
