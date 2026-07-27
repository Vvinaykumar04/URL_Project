<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Sembark URL Shortner') }}</title>
    <style>
        :root {
            --bg: #f4f5f7;
            --panel: #eef1f5;
            --ink: #2d3640;
            --muted: #697586;
            --line: #34383f;
            --accent: #f59e0b;
            --blue: #4090eb;
            --green-wash: #b9f0c3;
            --blue-wash: #acd7ff;
            --yellow-wash: #ffe999;
            --danger: #c53030;
            --success: #256f3a;
        }

        * { box-sizing: border-box; }
        body {
            margin: 0;
            background: var(--bg);
            color: var(--ink);
            font-family: "Courier New", Courier, monospace;
            line-height: 1.4;
        }

        a { color: inherit; text-decoration: none; }
        button, input, select {
            font: inherit;
            border: 2px solid var(--line);
            background: #fff;
            color: var(--ink);
            padding: 0.65rem 0.75rem;
        }

        button {
            cursor: pointer;
            background: #8fc6ff;
            border-color: #3277c3;
            color: #1f4f8c;
            font-weight: 700;
        }

        .button-link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.65rem 0.75rem;
            border: 2px solid #3277c3;
            background: #8fc6ff;
            color: #1f4f8c;
            font-weight: 700;
        }

        .page {
            max-width: 1320px;
            margin: 0 auto;
            padding: 24px;
        }

        .stack { display: grid; gap: 18px; }
        .two-col { display: grid; gap: 18px; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); }
        .three-col { display: grid; gap: 18px; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); }

        .shell {
            border: 3px solid transparent;
            padding: 12px;
        }

        .shell.super-admin { background: var(--yellow-wash); }
        .shell.admin { background: var(--blue-wash); }
        .shell.member { background: var(--green-wash); }
        .shell.manager, .shell.sales { background: var(--green-wash); }

        .shell-title {
            margin: 0 0 10px;
            color: #ef4444;
            font-size: 1.75rem;
            text-transform: capitalize;
        }

        .shell-kicker {
            margin: 0 0 8px;
            color: #32a35b;
            font-size: 1.6rem;
            text-transform: uppercase;
        }

        .panel {
            background: var(--panel);
            border: 3px solid var(--line);
        }

        .topbar {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            align-items: center;
            padding: 14px 18px;
            border-bottom: 3px solid var(--accent);
        }

        .brand {
            display: flex;
            gap: 12px;
            align-items: center;
            font-weight: 700;
        }

        .brand-mark {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 42px;
            min-height: 28px;
            padding: 0 6px;
            border: 2px solid var(--accent);
            color: var(--accent);
            background: #fff;
            font-size: 0.8rem;
        }

        .topbar-actions {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        .topbar-link { font-weight: 700; }

        .logout-form { margin: 0; }
        .logout-button {
            background: transparent;
            border: 0;
            padding: 0;
            color: var(--ink);
        }

        .panel-body {
            padding: 18px;
            display: grid;
            gap: 18px;
        }

        .block {
            border: 3px solid var(--line);
            background: rgba(255, 255, 255, 0.45);
            padding: 14px;
        }

        .block-title {
            margin: 0 0 12px;
            color: #2f80d0;
            font-size: 1.35rem;
        }

        .meta {
            color: var(--muted);
            font-size: 0.9rem;
        }

        .toolbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }

        .toolbar form,
        .inline-form {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            align-items: center;
        }

        .field-grid {
            display: grid;
            gap: 14px;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        }

        label {
            display: grid;
            gap: 6px;
            font-weight: 700;
            font-size: 0.92rem;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: rgba(255, 255, 255, 0.82);
        }

        th, td {
            border: 2px solid #8b929c;
            padding: 10px 12px;
            text-align: left;
            font-size: 0.9rem;
            vertical-align: top;
        }

        th {
            color: var(--muted);
            font-size: 0.78rem;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }

        .pagination {
            display: flex;
            justify-content: flex-end;
        }

        .pagination nav {
            width: auto;
        }

        .pagination svg {
            width: 16px;
            height: 16px;
        }

        .status {
            padding: 10px 12px;
            border: 2px solid;
        }

        .status.success {
            color: var(--success);
            border-color: #76c08c;
            background: #effcf3;
        }

        .status.error {
            color: var(--danger);
            border-color: #e7a8a8;
            background: #fff3f3;
        }

        .error-text { color: var(--danger); font-size: 0.86rem; }
        .mono { word-break: break-all; }

        @media (max-width: 720px) {
            .page { padding: 16px; }
            .topbar { align-items: flex-start; }
            .toolbar { align-items: flex-start; }
        }
    </style>
</head>
<body>
    <div class="page">
        @if (session('status'))
            <div class="status success" style="margin-bottom: 16px;">{{ session('status') }}</div>
        @endif

        @if ($errors->any())
            <div class="status error" style="margin-bottom: 16px;">
                {{ $errors->first() }}
            </div>
        @endif

        @yield('content')
    </div>
</body>
</html>
